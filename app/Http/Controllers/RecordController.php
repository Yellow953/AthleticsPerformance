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
use Carbon\Carbon;

class RecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $search = request()->query('search');

        if ($search) {
            $records = Record::where('record', 'LIKE', "%{$search}%")->paginate(25);
        } else {
            $records = Record::orderBy('created_at', 'DESC')->paginate(25);
        }

        return view('records.index', compact('records'));
    }

    public function new()
    {
        $ios = IOSecond::all();
        $age_groups = AgeGroupSecond::select('ID', 'name')->orderBy('name')->get();
        $genders = GenderSecond::all();
        $teams = TeamSecond::select('ID', 'name')->get();
        $athletes = AthleteSecond::select('ID', 'firstName', 'lastName', 'middleName')->orderBy('ID', 'DESC')->get();
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
        $data['id'] = RecordSecond::orderBy('ID', 'DESC')->first()->ID + Record::where('uploaded', 0)->count() + 1;
        $data['current'] = $request->boolean('current');
        $data['extra'] = $request->extra ?? '';

        Record::create(
            $data
        );

        return redirect('/records')->with('success', 'Record successfully created!');
    }

    public function edit($id)
    {
        $record = Record::find($id);
        $ios = IOSecond::all();
        $age_groups = AgeGroupSecond::select('ID', 'name')->orderBy('name')->get();
        $genders = GenderSecond::all();
        $teams = TeamSecond::select('ID', 'name')->get();
        $athletes = AthleteSecond::select('ID', 'firstName', 'lastName', 'middleName')->orderBy('ID', 'DESC')->get();
        $results = Result::select('id')->orderBy('created_at', 'DESC')->get();
        $event_types = EventTypeSecond::select('ID', 'name')->get();

        if (!$record) {
            return redirect('/records')->with('danger', 'Record not found!');
        }

        $data = compact('ios', 'age_groups', 'genders', 'teams', 'athletes', 'results', 'record', 'event_types');
        return view('records.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // 'title' => 'required|max:255',
            // 'date' => 'required|date',
            // 'location' => 'required|max:255',
        ]);

        $record = Record::find($id);

        if (!$record) {
            return redirect('/records')->with('danger', 'Record not found!');
        }

        $data = $request->except('current', 'extra');
        $data['current'] = $request->boolean('current');
        $data['extra'] = $request->extra ?? '';

        $record->update(
            $data
        );

        return redirect('/records')->with('warning', 'Record successfully updated!');
    }

    public function destroy($id)
    {
        $record = Record::find($id);

        if (!$record) {
            return redirect()->back()->with('danger', 'Record not found!');
        }

        $record->delete();

        return redirect()->back()->with('danger', 'Record successfully deleted!');
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

    public function upload()
    {
        $records = Record::where('uploaded', false)->get();

        if ($records->count() == 0) {
            return redirect()->back()->with('warning', 'All Records are uptodate!');
        }

        foreach ($records as $record) {
            $athlete = Athlete::where('id', $record->athleteID)->first();
            $athlete_second = AthleteSecond::where('ID', $record->athleteID)->first();
            if ($athlete->uploaded == false && $athlete_second == null) {
                AthleteSecond::create([
                    'firstName' => $athlete->firstName,
                    'middleName' => $athlete->middleName,
                    'lastName' => $athlete->lastName,
                    'dateOfBirth' => $athlete->dateOfBirth,
                    'gender' => $athlete->gender,
                    'exactDate' => $athlete->exactDate,
                    'showResult' => $athlete->showResult,
                ]);

                $athlete->update(['uploaded' => true]);
            }

            $result = Result::where('id', $record->resultID)->first();
            $result_second = ResultSecond::where('ID', $record->resultID)->first();
            if ($result->uploaded == false && $result_second == null) {
                $event = Event::where('id', $result->eventID)->first();
                $event_second = EventSecond::where('ID', $result->eventID)->first();
                if ($event->uploaded == false && $event_second == null) {
                    $meeting = Meeting::where('IDSecond', $event->meetingID)->first();
                    $meeting_second = MeetingSecond::where('ID', $event->meetingID)->first();
                    if ($meeting->uploaded == false && $meeting_second == null) {
                        MeetingSecond::create([
                            'ID' => $meeting->IDSecond,
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
                            'picture2' => $meeting->picture,
                            'isActive' => $meeting->isActive,
                            'isNew' => $meeting->isNew,
                            'createDate' => $meeting->created_at,
                        ]);

                        $meeting->update(['uploaded' => true]);
                    }

                    EventSecond::create([
                        'name' => $event->name,
                        'typeID' => $event->typeID,
                        'extra' => $event->extra,
                        'round' => $event->round,
                        'ageGroupID' => $event->ageGroupID,
                        'gender' => $event->gender,
                        'meetingID' => $event->meetingID,
                        'wind' => $event->wind,
                        'note' => $event->note,
                        'distance' => $event->distance,
                        'io' => $event->io,
                        'heat' => $event->heat,
                        'createDate' => $event->created_at,
                    ]);

                    $event->update(['uploaded' => true]);
                }

                $competitor = Result::where('id', $result->competitorID)->first();
                $competitor_second = ResultSecond::where('ID', $result->competitorID)->first();
                if ($competitor->uploaded == false && $competitor_second == null) {
                    $athlete = Athlete::where('id', $competitor->athleteID)->first();
                    $athlete_second = AthleteSecond::where('ID', $competitor->athleteID)->first();
                    if ($athlete->uploaded == false && $athlete_second == null) {
                        AthleteSecond::create([
                            'firstName' => $athlete->firstName,
                            'middleName' => $athlete->middleName,
                            'lastName' => $athlete->lastName,
                            'dateOfBirth' => $athlete->dateOfBirth,
                            'gender' => $athlete->gender,
                            'exactDate' => $athlete->exactDate,
                            'showResult' => $athlete->showResult,
                        ]);

                        $athlete->update(['uploaded' => true]);
                    }

                    CompetitorSecond::create([
                        'name' => $competitor->name,
                        'athleteID' => $competitor->athleteID,
                        'gender' => $competitor->gender,
                        'teamID' => $competitor->teamID,
                        'year' => $competitor->year,
                        'ageGroupID' => $competitor->ageGroupID,
                    ]);

                    $competitor->uploaded = true;
                    $competitor->save();
                }

                ResultSecond::create([
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
                    'createDate' => $result->created_at,
                ]);

                $result->update(['uploaded' => true]);
            }

            RecordSecond::create([
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
                'createDate' => $record->created_at,
            ]);

            $record->uploaded = true;
            $record->save();
        }

        return redirect()->back()->with('success', 'Records uploaded successfully...');
    }
}