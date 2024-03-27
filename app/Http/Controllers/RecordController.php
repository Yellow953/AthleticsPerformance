<?php

namespace App\Http\Controllers;

use App\Models\AgeGroupSecond;
use App\Models\Athlete;
use App\Models\AthleteSecond;
use App\Models\EventTypeSecond;
use App\Models\GenderSecond;
use App\Models\IOSecond;
use App\Models\Record;
use App\Models\RecordSecond;
use App\Models\Result;
use App\Models\ResultSecond;
use App\Models\Meeting;
use App\Models\MeetingSecond;
use App\Models\Event;
use App\Models\EventSecond;
use App\Models\TeamSecond;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->only(['edit', 'update', 'destroy', 'upload', 'upload_all']);
    }

    public function index()
    {
        $records = Record::filter()->orderBy('created_at', 'DESC')->paginate(25);

        return view('records.index', compact('records'));
    }

    public function new()
    {
        $ios = IOSecond::all();
        $age_groups = AgeGroupSecond::select('ID', 'name')->orderBy('name')->get();
        $genders = GenderSecond::all();
        $teams = TeamSecond::select('ID', 'name')->get();
        $athletes = Athlete::select('ID', 'firstName', 'lastName', 'middleName')->orderBy('ID', 'DESC')->get();
        $results = Result::select('id')->orderBy('created_at', 'DESC')->get();
        $event_types = EventTypeSecond::select('ID', 'name')->get();

        $data = compact('ios', 'age_groups', 'genders', 'teams', 'athletes', 'results', 'event_types');
        return view('records.new', $data);
    }

    public function create(Request $request)
    {
        $request->validate([
            // 'title' => 'required|max:255',
            // 'date' => 'required|date',
            // 'location' => 'required|max:255',
        ]);

        $data = $request->except('current');
        $data['current'] = $request->boolean('current');
        $data['extra'] = $request->extra ?? '';
        if (Record::where('uploaded', false)->count() == 0) {
            $data['id'] = RecordSecond::orderBy('ID', 'DESC')->first()->ID + 1;
        } else {
            $data['id'] = Record::orderBy('ID', 'DESC')->first()->id + 1;
        }

        Record::create(
            $data
        );

        return redirect()->route('records')->with('success', 'Record successfully created!');
    }

    public function edit(Record $record)
    {
        $ios = IOSecond::all();
        $age_groups = AgeGroupSecond::select('ID', 'name')->orderBy('name')->get();
        $genders = GenderSecond::all();
        $teams = TeamSecond::select('ID', 'name')->get();
        $athletes = Athlete::select('ID', 'firstName', 'lastName', 'middleName')->orderBy('ID', 'DESC')->get();
        $results = Result::select('id')->orderBy('created_at', 'DESC')->get();
        $event_types = EventTypeSecond::select('ID', 'name')->get();

        $data = compact('ios', 'age_groups', 'genders', 'teams', 'athletes', 'results', 'record', 'event_types');
        return view('records.edit', $data);
    }

    public function update(Request $request, Record $record)
    {
        $request->validate([
            // 'title' => 'required|max:255',
            // 'date' => 'required|date',
            // 'location' => 'required|max:255',
        ]);

        $data = $request->except('current', 'extra');
        $data['current'] = $request->boolean('current');
        $data['extra'] = $request->extra ?? '';
        $data['uploaded'] = false;

        $record->update(
            $data
        );

        return redirect()->route('records')->with('warning', 'Record successfully updated!');
    }

    public function destroy(Record $record)
    {
        $record->delete();
        return redirect()->back()->with('danger', 'Record successfully deleted!');
    }

    public function copy(Record $record)
    {
        $record_attributes = $record->getAttributes();
        unset($record_attributes['id']);
        Record::create($record_attributes);

        return redirect()->back()->with('success', 'Record duplicated successfully!');
    }

    public function export()
    {
        $data = Record::select('id', 'date', 'venue', 'io', 'ageGroupID', 'gender', 'typeID', 'name', 'extra', 'record', 'teamID', 'result', 'note', 'wind', 'date2', 'current', 'distance', 'athleteID', 'points', 'resultValue', 'resultID', 'created_at')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['ID', 'Date', 'Venue', 'IO', 'Age Group ID', 'Gender', 'Type ID', 'Name', 'Extra', 'Competitor', 'Team ID', 'Result', 'Note', 'Wind', 'Date2', 'Current', 'Distance', 'Athlete ID', 'Points', 'Result Value', 'Result ID', 'Created At'], null, 'A1');

        $rows = 2;

        foreach ($data as $d) {
            $sheet->fromArray([
                $d->id,
                $d->date,
                $d->venue,
                $d->io,
                $d->ageGroupID,
                $d->gender,
                $d->typeID,
                $d->name,
                $d->extra,
                $d->record,
                $d->teamID,
                $d->result,
                $d->note,
                $d->wind,
                $d->date2,
                $d->current,
                $d->distance,
                $d->athleteID,
                $d->points,
                $d->resultValue,
                $d->resultID,
                $d->created_at ?? Carbon::now(),
            ], null, 'A' . $rows);

            $rows++;
        }

        $fileName = "Records.xls";
        $writer = new Xls($spreadsheet);
        $writer->save($fileName);

        return response()->file($fileName, [
            'Content-Type' => 'application/xls',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ]);
    }

    public function upload(Record $record)
    {
        if ($record->uploaded) {
            return redirect()->back()->with('warning', 'Record already uploaded!');
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $athlete = $this->uploadAthlete($record->athleteID);
        $result = $this->uploadResult($record->resultID);
        if ($result) {
            $event = $this->uploadEvent($result->eventID);
            if ($event) {
                $meeting = $this->uploadMeeting($event->meetingID);
            }
        }

        RecordSecond::updateOrInsert(
            ['ID' => $record->id],
            [
                'ID' => $record->id,
                'date' => $record->date,
                'venue' => $record->venue,
                'io' => $record->io,
                'ageGroupID' => $record->ageGroupID,
                'gender' => $record->gender,
                'typeID' => $record->typeID,
                'name' => $record->name,
                'extra' => $record->extra,
                'competitor' => $record->competitor,
                'teamID' => $record->teamID,
                'result' => $record->result,
                'note' => $record->note,
                'wind' => $record->wind,
                'date2' => $record->date2,
                'current' => $record->current,
                'distance' => $record->distance,
                'athleteID' => $record->athleteID,
                'points' => $record->points,
                'resultValue' => $record->resultValue,
                'resultID' => $record->resultID,
                'createDate' => $record->created_at
            ]
        );

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $record->update(['uploaded' => true]);

        return redirect()->back()->with('success', 'Record uploaded successfully.');
    }

    public function upload_all()
    {
        $records = Record::where('uploaded', false)->get();

        if ($records->isEmpty()) {
            return redirect()->back()->with('warning', 'All records are up-to-date!');
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($records as $record) {
            $athlete = $this->uploadAthlete($record->athleteID);
            $result = $this->uploadResult($record->resultID);
            if ($result) {
                $event = $this->uploadEvent($result->eventID);
                if ($event) {
                    $meeting = $this->uploadMeeting($event->meetingID);
                }
            }

            RecordSecond::updateOrInsert(
                ['ID' => $record->id],
                [
                    'ID' => $record->id,
                    'date' => $record->date,
                    'venue' => $record->venue,
                    'io' => $record->io,
                    'ageGroupID' => $record->ageGroupID,
                    'gender' => $record->gender,
                    'typeID' => $record->typeID,
                    'name' => $record->name,
                    'extra' => $record->extra,
                    'competitor' => $record->competitor,
                    'teamID' => $record->teamID,
                    'result' => $record->result,
                    'note' => $record->note,
                    'wind' => $record->wind,
                    'date2' => $record->date2,
                    'current' => $record->current,
                    'distance' => $record->distance,
                    'athleteID' => $record->athleteID,
                    'points' => $record->points,
                    'resultValue' => $record->resultValue,
                    'resultID' => $record->resultID,
                    'createDate' => $record->created_at
                ]
            );

            $record->update(['uploaded' => true]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->back()->with('success', 'Records uploaded successfully...');
    }

    // -------------------------------------------------------------------------------- 
    // private 
    // --------------------------------------------------------------------------------

    protected function uploadAthlete($athleteID)
    {
        $athlete = Athlete::find($athleteID);

        if (!$athlete || $athlete->uploaded) {
            return null;
        }

        AthleteSecond::updateOrInsert(
            ['ID' => $athleteID],
            [
                'ID' => $athleteID,
                'firstName' => $athlete->firstName,
                'middleName' => $athlete->middleName,
                'lastName' => $athlete->lastName,
                'dateOfBirth' => $athlete->dateOfBirth,
                'gender' => $athlete->gender,
                'exactDate' => $athlete->exactDate,
                'showResult' => $athlete->showResult
            ]
        );

        $athlete->update(['uploaded' => true]);
        return $athlete;
    }

    protected function uploadResult($resultID)
    {
        $result = Result::find($resultID);

        if (!$result || $result->uploaded) {
            return null;
        }

        $event = $this->uploadEvent($result->eventID);

        ResultSecond::updateOrInsert(
            ['ID' => $resultID],
            [
                'ID' => $resultID,
                'eventID' => $result->eventID,
                'competitorID' => $result->competitorID,
                'result' => $result->result,
                'isHand' => $result->isHand,
                'position' => $result->position,
                'note' => $result->note,
                'wind' => $result->wind,
                'points' => $result->points,
                'resultValue' => $result->resultValue,
                'recordStatus' => $result->recordStatus,
                'heat' => $result->heat,
                'isActive' => $result->isActive,
                'createDate' => $result->created_at
            ]
        );

        $result->update(['uploaded' => true]);
        return $result;
    }

    protected function uploadEvent($eventID)
    {
        $event = Event::find($eventID);

        if (!$event || $event->uploaded) {
            return null;
        }

        $meeting = $this->uploadMeeting($event->meetingID);

        EventSecond::updateOrInsert(
            ['ID' => $eventID],
            [
                'ID' => $eventID,
                'name' => $event->name,
                'typeID' => $event->typeID,
                'extra' => $event->extra,
                'round' => $event->round,
                'ageGroupID' => $event->ageGroupID,
                'gender' => $event->gender,
                'meetingID' => $meeting->IDSecond,
                'wind' => $event->wind,
                'note' => $event->note,
                'distance' => $event->distance,
                'heat' => $event->heat,
                'createDate' => $event->created_at
            ]
        );

        $event->update(['uploaded' => true]);
        return $event;
    }

    protected function uploadMeeting($meetingID)
    {
        $meeting = Meeting::find($meetingID);

        if (!$meeting || $meeting->uploaded) {
            return null;
        }

        MeetingSecond::updateOrInsert(
            ['ID' => $meetingID],
            [
                'ID' => $meetingID,
                'ageGroupID' => $meeting->ageGroupID,
                'name' => $meeting->name,
                'shortName' => $meeting->shortName,
                'startDate' => $meeting->startDate,
                'endDate' => $meeting->endDate,
                'venue' => $meeting->venue,
                'country' => $meeting->country,
                'typeID' => $meeting->typeID,
                'subgroup' => $meeting->subgroup,
                'picture' => $meeting->picture,
                'isActive' => $meeting->isActive,
                'isNew' => $meeting->isNew,
                'createDate' => $meeting->created_at,
                'io' => $meeting->io,
            ]
        );

        $meeting->update(['uploaded' => true]);
        return $meeting;
    }
}
