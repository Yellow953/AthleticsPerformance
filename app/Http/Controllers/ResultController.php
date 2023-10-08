<?php

namespace App\Http\Controllers;

use App\Models\AgeGroupSecond;
use App\Models\Competitor;
use App\Models\CompetitorSecond;
use App\Models\Result;
use App\Models\ResultSecond;
use App\Models\Meeting;
use App\Models\MeetingSecond;
use App\Models\Event;
use App\Models\EventSecond;
use App\Models\Athlete;
use App\Models\AthleteSecond;
use App\Models\Record;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->only(['edit', 'update', 'destroy', 'upload', 'upload_all']);
    }

    public function index()
    {
        $search = request()->query('search');

        if ($search) {
            $results = Result::orderBy('created_at', 'DESC')->paginate(25);
        } else {
            $results = Result::orderBy('created_at', 'DESC')->paginate(25);
        }

        return view('results.index', compact('results'));
    }

    public function new()
    {
        $events = Event::select('id', 'name')->orderBy('created_at', 'DESC')->get();
        $competitors = CompetitorSecond::select('ID', 'name')->orderBy('ID', 'DESC')->get();

        $data = compact('events', 'competitors');
        return view('results.new', $data);
    }

    public function create(Request $request)
    {
        $request->validate([
            // 'title' => 'required|max:255',
            // 'date' => 'required|date',
            // 'location' => 'required|max:255',
        ]);

        $data = $request->except('isHand', 'isActive');
        $data['isHand'] = $request->boolean('isHand');
        $data['isActive'] = $request->boolean('isActive');
        if (Result::where('uploaded', false)->count() == 0) {
            $data['id'] = ResultSecond::orderBy('ID', 'DESC')->first()->ID + 1;
        } else {
            $data['id'] = Result::orderBy('ID', 'DESC')->first()->id + 1;
        }

        Result::create(
            $data
        );

        return redirect()->back()->with('success', 'Result successfully created!');
    }

    public function edit($id)
    {
        $result = Result::findOrFail($id);
        $events = Event::select('id', 'name')->orderBy('created_at', 'DESC')->get();
        $competitors = CompetitorSecond::select('ID', 'name')->orderBy('ID', 'DESC')->get();

        if (!$result) {
            return redirect('/results')->with('danger', 'Result not found!');
        }

        $data = compact('result', 'events', 'competitors');
        return view('results.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // 'title' => 'required|max:255',
            // 'date' => 'required|date',
            // 'location' => 'required|max:255',
        ]);

        $result = Result::findOrFail($id);

        if (!$result) {
            return redirect('/results')->with('danger', 'Result not found!');
        }

        $data = $request->except('isHand', 'isActive');
        $data['isHand'] = $request->boolean('isHand');
        $data['isActive'] = $request->boolean('isActive');

        $result->update(
            $data
        );

        return redirect('/results')->with('warning', 'Result successfully updated!');
    }

    public function destroy($id)
    {
        try{
            Result::findOrFail($id)->delete();

            return redirect()->back()->with('danger', 'Result successfully deleted!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('danger', 'Result found in other Models!');
        }
    }

    public function new_record($id)
    {
        $result = Result::findOrFail($id);
        $age_groups = AgeGroupSecond::select('ID', 'name')->orderBy('name')->get();
        $event = Event::find($result->eventID);
        $competitor = Competitor::find($result->competitorID);
        $meeting = Meeting::find($event->meetingID)->first();

        $data = compact('result', 'age_groups', 'event', 'competitor', 'meeting');
        return view('results.new_record', $data);
    }

    public function create_record($id, Request $request)
    {   
        $result = Result::findOrFail($id);
        $event = Event::find($result->eventID);
        $competitor = Competitor::find($result->competitorID);
        $meeting = Meeting::find($event->meetingID);

        Record::create([
            'resultID' => $result->id,
            'result' => $result->result,
            'points' => $result->points,
            'resultValue' => $result->resultValue,

            'competitor' => $competitor->name,
            'teamID' => $competitor->teamID,
            'athleteID' => $competitor->athleteID,

            'name' => $event->name,
            'typeID' => $event->typeID,
            'extra' => $event->extra,
            'gender' => $event->gender,
            'distance' => $event->distance,
            'io' => $event->io,

            'venue' => $meeting->venue,

            'date' => $request->date,
            'date2' => $request->date2,
            'ageGroupID' => $request->ageGroupID,
            'note' => $request->note,
            'current' => $request->boolean('current'),
            'wind' => ($request->boolean('wind') ? $event->wind . '(i)' : ''),

            'uploaded' => false,
        ]);

        return redirect()->back()->with('success', 'Record successfully created!');
    }

    public function export()
    {
        $data = Result::select('id', 'eventID', 'competitorID', 'result', 'isHand', 'position', 'wind', 'note', 'points', 'resultValue', 'recordStatus', 'heat', 'isActive', 'created_at')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['ID', 'Event ID', 'Competitor ID', 'Result', 'isHand', 'Position', 'Wind', 'Note', 'Points', 'Result Value', 'Record Status', 'Heat', 'isActive', 'Created At'], null, 'A1');

        $rows = 2;

        foreach ($data as $d) {
            $sheet->fromArray([
                $d->id,
                $d->eventID,
                $d->competitorID,
                $d->result,
                $d->isHand,
                $d->position,
                $d->wind,
                $d->note,
                $d->points,
                $d->resultValue,
                $d->recordStatus,
                $d->heat,
                $d->isActive,
                $d->created_at ?? Carbon::now(),
            ], null, 'A' . $rows);

            $rows++;
        }

        $fileName = "Results.xls";
        $writer = new Xls($spreadsheet);
        $writer->save($fileName);

        return response()->file($fileName, [
            'Content-Type' => 'application/xls',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ]);
    }

    public function upload($id)
    {
        $result = Result::findOrFail($id);

        if (!$result || $result->uploaded || ResultSecond::find($id)) {
            return redirect()->back()->with('warning', 'Result already uploaded!');
        }

        $event = $this->uploadEvent($result->eventID);
        $competitor = $this->uploadCompetitor($result->competitorID);
        $athlete = $this->uploadAthlete($competitor->athleteID);

        if (!$event || !$competitor || !$athlete) {
            return redirect()->back()->with('warning', 'Dependencies could not be uploaded.');
        }

        ResultSecond::create([
            'ID' => $result->id,
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
        ]);


        $result->update(['uploaded' => true]);

        return redirect()->back()->with('success', 'Result uploaded successfully!');
    }


    public function upload_all()
    {
        $results = Result::where('uploaded', false)->get();

        if ($results->isEmpty()) {
            return redirect()->back()->with('warning', 'All results are up-to-date!');
        }

        foreach ($results as $result) {
            $event = $this->uploadEvent($result->eventID);
            $competitor = $this->uploadCompetitor($result->competitorID);
            $athlete = $this->uploadAthlete($competitor->athleteID);

            ResultSecond::create([
                'ID' => $result->id,
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
            ]);

            $result->update(['uploaded' => true]);
        }

        return redirect()->back()->with('success', 'Results uploaded successfully...');
    }

    public function scoring(){
        return redirect()->back()->with('success', 'Scoring script successfully executed!');
    }

    // -------------------------------------------------------------------------------- 
    // private 
    // --------------------------------------------------------------------------------
    protected function uploadEvent($eventID)
    {
        $event = Event::find($eventID);

        if (!$event || $event->uploaded || EventSecond::find($eventID)) {
            return null;
        }

        $meeting = $this->uploadMeeting($event->meetingID);

        EventSecond::create([
            'ID' => $event->id,
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
            'createDate' => $event->created_at
        ]);

        $event->update(['uploaded' => true]);
        return $event;
    }

    protected function uploadCompetitor($competitorID)
    {
        $competitor = Competitor::find($competitorID);

        if (!$competitor || $competitor->uploaded || CompetitorSecond::find($competitorID)) {
            return null;
        }

        $athlete = $this->uploadAthlete($competitor->athleteID);

        CompetitorSecond::create([
            'ID' => $competitor->id,
            'name' => $competitor->name,
            'athleteID' => $competitor->athleteID,
            'gender' => $competitor->gender,
            'teamID' => $competitor->teamID,
            'year' => $competitor->year,
            'ageGroupID' => $competitor->ageGroupID,
        ]);

        $competitor->update(['uploaded' => true]);
        return $competitor;
    }

    protected function uploadAthlete($athleteID)
    {
        $athlete = Athlete::find($athleteID);

        if (!$athlete || $athlete->uploaded || AthleteSecond::find($athleteID)) {
            return null;
        }

        AthleteSecond::create([
            'ID' => $athlete->id,
            'firstName' => $athlete->firstName,
            'middleName' => $athlete->middleName,
            'lastName' => $athlete->lastName,
            'dateOfBirth' => $athlete->dateOfBirth,
            'gender' => $athlete->gender,
            'exactDate' => $athlete->exactDate,
            'showResult' => $athlete->showResult
        ]);

        $athlete->update(['uploaded' => true]);
        return $athlete;
    }

    protected function uploadMeeting($meetingID)
    {
        $meeting = Meeting::find($meetingID);

        if (!$meeting || $meeting->uploaded || MeetingSecond::find($meetingID)) {
            return null;
        }

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
            'isActive' => $meeting->isActive,
            'isNew' => $meeting->isNew,
            'createDate' => $meeting->created_at
        ]);

        $meeting->update(['uploaded' => true]);
        return $meeting;
    }

}