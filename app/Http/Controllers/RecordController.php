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
use App\Models\Competitor;
use App\Models\CompetitorSecond;
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

    public function upload_individual($id)
    {
        $record = Record::find($id);

        if (!$record || $record->uploaded) {
            return redirect()->back()->with('warning', 'Record not found or already uploaded.');
        }

        $athlete = $this->uploadAthlete($record->athleteID);
        $result = $this->uploadResult($record->resultID);
        $event = $this->uploadEvent($result->eventID);
        $meeting = $this->uploadMeeting($event->meetingID);

        RecordSecond::create($record->only([
            'date',
            'venue',
            'io',
            'ageGroupID',
            'gender',
            'typeID',
            'name',
            'extra',
            'competitor',
            'teamID',
            'result',
            'note',
            'wind',
            'date2',
            'current',
            'distance',
            'athleteID',
            'points',
            'resultValue',
            'resultID',
            'created_at'
        ]));

        $record->update(['uploaded' => true]);

        return redirect()->back()->with('success', 'Record uploaded successfully.');
    }

    public function upload_all()
    {
        $records = Record::where('uploaded', false)->get();

        if ($records->isEmpty()) {
            return redirect()->back()->with('warning', 'All records are up-to-date!');
        }

        foreach ($records as $record) {
            $athlete = $this->uploadAthlete($record->athleteID);
            $result = $this->uploadResult($record->resultID);
            $event = $this->uploadEvent($result->eventID);
            $meeting = $this->uploadMeeting($event->meetingID);

            RecordSecond::create($record->only([
                'date',
                'venue',
                'io',
                'ageGroupID',
                'gender',
                'typeID',
                'name',
                'extra',
                'competitor',
                'teamID',
                'result',
                'note',
                'wind',
                'date2',
                'current',
                'distance',
                'athleteID',
                'points',
                'resultValue',
                'resultID',
                'created_at'
            ]));

            $record->update(['uploaded' => true]);
        }

        return redirect()->back()->with('success', 'Records uploaded successfully...');
    }

    protected function uploadAthlete($athleteID)
    {
        $athlete = Athlete::find($athleteID);

        if (!$athlete || $athlete->uploaded || AthleteSecond::find($athleteID)) {
            return null;
        }

        AthleteSecond::create($athlete->only([
            'firstName',
            'middleName',
            'lastName',
            'dateOfBirth',
            'gender',
            'exactDate',
            'showResult'
        ]));

        $athlete->update(['uploaded' => true]);
        return $athlete;
    }

    protected function uploadResult($resultID)
    {
        $result = Result::find($resultID);

        if (!$result || $result->uploaded || ResultSecond::find($resultID)) {
            return null;
        }

        $event = $this->uploadEvent($result->eventID);

        ResultSecond::create($result->only([
            'eventID',
            'competitorID',
            'result',
            'isHand',
            'position',
            'note',
            'wind',
            'points',
            'resultValue',
            'recordStatus',
            'heat',
            'isActive',
            'created_at'
        ]));

        $result->update(['uploaded' => true]);
        return $result;
    }

    protected function uploadEvent($eventID)
    {
        $event = Event::find($eventID);

        if (!$event || $event->uploaded || EventSecond::find($eventID)) {
            return null;
        }

        $meeting = $this->uploadMeeting($event->meetingID);

        EventSecond::create($event->only([
            'name',
            'typeID',
            'extra',
            'round',
            'ageGroupID',
            'gender',
            'meetingID',
            'wind',
            'note',
            'distance',
            'io',
            'heat',
            'created_at'
        ]));

        $event->update(['uploaded' => true]);
        return $event;
    }

    protected function uploadMeeting($meetingID)
    {
        $meeting = Meeting::find($meetingID);

        if (!$meeting || $meeting->uploaded || MeetingSecond::find($meetingID)) {
            return null;
        }

        MeetingSecond::create($meeting->only([
            'IDSecond',
            'ageGroupID',
            'name',
            'shortName',
            'startDate',
            'endDate',
            'venue',
            'country',
            'typeID',
            'subgroup',
            'picture',
            'isActive',
            'isNew',
            'created_at'
        ]));

        $meeting->update(['uploaded' => true]);
        return $meeting;
    }

}