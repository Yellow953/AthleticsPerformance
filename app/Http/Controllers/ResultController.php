<?php

namespace App\Http\Controllers;

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

    public function upload()
    {
        $results = Result::where('uploaded', false)->get();

        if ($results->count() == 0) {
            return redirect()->back()->with('warning', 'All Results are uptodate!');
        }

        foreach ($results as $result) {
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

        return redirect()->back()->with('success', 'Results uploaded successfully...');
    }
}