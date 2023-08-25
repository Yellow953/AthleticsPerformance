<?php

namespace App\Http\Controllers;

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
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Carbon\Carbon;

class ResultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
        $data['id'] = ResultSecond::orderBy('ID', 'DESC')->first()->ID + Result::where('uploaded', 0)->count() + 1;
        $data['isHand'] = $request->boolean('isHand');
        $data['isActive'] = $request->boolean('isActive');

        Result::create(
            $data
        );

        return redirect()->back()->with('success', 'Result successfully created!');
    }

    public function edit($id)
    {
        $result = Result::find($id);
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

        $result = Result::find($id);

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
        $result = Result::find($id);

        if (!$result) {
            return redirect()->back()->with('danger', 'Result not found!');
        }

        $result->delete();

        return redirect()->back()->with('danger', 'Result successfully deleted!');
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

    public function upload_individual($id)
    {
        $result = Result::find($id);

        if (!$result || $result->uploaded || ResultSecond::find($id)) {
            return redirect()->back()->with('warning', 'Result is already up-to-date or does not exist.');
        }

        $event = $this->uploadEvent($result->eventID);
        $competitor = $this->uploadCompetitor($result->competitorID);
        $athlete = $this->uploadAthlete($competitor->athleteID);

        if (!$event || !$competitor || !$athlete) {
            return redirect()->back()->with('warning', 'Dependencies could not be uploaded.');
        }

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

        return redirect()->back()->with('success', 'Result and its dependencies uploaded successfully.');
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
        }

        return redirect()->back()->with('success', 'Results uploaded successfully...');
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

    protected function uploadCompetitor($competitorID)
    {
        $competitor = Competitor::find($competitorID);

        if (!$competitor || $competitor->uploaded || CompetitorSecond::find($competitorID)) {
            return null;
        }

        $athlete = $this->uploadAthlete($competitor->athleteID);

        CompetitorSecond::create($competitor->only([
            'name',
            'athleteID',
            'gender',
            'teamID',
            'year',
            'ageGroupID'
        ]));

        $competitor->update(['uploaded' => true]);
        return $competitor;
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