<?php

namespace App\Http\Controllers;

use App\Models\AgeGroupSecond;
use App\Models\CompetitorSecond;
use App\Models\Event;
use App\Models\EventSecond;
use App\Models\EventTypeSecond;
use App\Models\GenderSecond;
use App\Models\IOSecond;
use App\Models\Meeting;
use App\Models\MeetingSecond;
use App\Models\Result;
use App\Models\ResultSecond;
use App\Models\RoundSecond;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Carbon\Carbon;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $search = request()->query('search');

        if ($search) {
            $events = Event::where('name', 'LIKE', "%{$search}%")->paginate(25);
        } else {
            $events = Event::orderBy('created_at', 'DESC')->paginate(25);
        }

        return view('events.index', compact('events'));
    }

    public function new()
    {
        $meetings = Meeting::select('IDSecond', 'name')->orderBy('created_at', 'DESC')->get();
        $rounds = RoundSecond::select('ID', 'name')->get();
        $event_types = EventTypeSecond::select('ID', 'name')->get();
        $age_groups = AgeGroupSecond::select('ID', 'name')->orderBy('name')->get();
        $ios = IOSecond::all();
        $genders = GenderSecond::all();

        $data = compact('meetings', 'rounds', 'event_types', 'age_groups', 'ios', 'genders');
        return view('events.new', $data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'typeID' => 'required',
            'ageGroupID' => 'required',
            'round' => 'required',
            'gender' => 'required',
            'io' => 'required',
        ]);

        $data = $request->except('extra');
        $data['extra'] = $request->extra ?? '';
        if (Event::where('uploaded', false)->count() == 0) {
            $data['id'] = EventSecond::orderBy('ID', 'DESC')->first()->ID + 1;
        } else {
            $data['id'] = Event::orderBy('ID', 'DESC')->first()->id + 1;
        }

        Event::create(
            $data
        );

        return redirect()->back()->with('success', 'Event successfully created!');
        // return response()->json(['event' => $event]);
    }

    public function edit($id)
    {
        $event = Event::find($id);
        $meetings = Meeting::select('IDSecond', 'name')->orderBy('created_at', 'DESC')->get();
        $rounds = RoundSecond::select('ID', 'name')->get();
        $event_types = EventTypeSecond::select('ID', 'name')->get();
        $age_groups = AgeGroupSecond::select('ID', 'name')->orderBy('name')->get();
        $ios = IOSecond::all();
        $genders = GenderSecond::all();

        if (!$event) {
            return redirect('/events')->with('danger', 'Event not found!');
        }

        $data = compact('meetings', 'rounds', 'event_types', 'age_groups', 'ios', 'genders', 'event');
        return view('events.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'typeID' => 'required',
            'ageGroupID' => 'required',
            'round' => 'required',
            'gender' => 'required',
            'io' => 'required',
        ]);

        $event = Event::find($id);

        if (!$event) {
            return redirect('/events')->with('danger', 'Event not found!');
        }

        $data = $request->except('extra');
        $data['extra'] = $request->extra ?? '';

        $event->update(
            $data
        );

        return redirect()->back()->with('warning', 'Event successfully updated!');
    }

    public function destroy($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return redirect()->back()->with('danger', 'Event not found!');
        }

        $event->delete();

        return redirect()->back()->with('danger', 'Event successfully deleted!');
    }

    public function export()
    {
        $data = Event::select('id', 'name', 'typeID', 'extra', 'round', 'ageGroupID', 'gender', 'meetingID', 'wind', 'note', 'distance', 'io', 'heat', 'created_at')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['ID', 'Name', 'Type ID', 'Extra', 'Round', 'Age Group', 'Gender', 'Meeting ID', 'Wind', 'Note', 'distance', 'io', 'heat', 'Created At'], null, 'A1');

        $rows = 2;

        foreach ($data as $d) {
            $sheet->fromArray([
                $d->id,
                $d->name,
                $d->typeID,
                $d->extra,
                $d->round,
                $d->ageGroupID,
                $d->gender,
                $d->meetingID,
                $d->wind,
                $d->note,
                $d->distance,
                $d->io,
                $d->heat,
                $d->created_at ?? Carbon::now(),
            ], null, 'A' . $rows);

            $rows++;
        }

        $fileName = "Events.xls";
        $writer = new Xls($spreadsheet);
        $writer->save($fileName);

        return response()->file($fileName, [
            'Content-Type' => 'application/xls',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ]);
    }

    public function results($id)
    {
        $event = Event::findOrFail($id);
        $results = Result::where('eventID', $event->id)->get();
        $competitors = CompetitorSecond::select('ID', 'name')->get();

        $data = compact('event', 'results', 'competitors');
        return view('events.results', $data);
    }

    public function upload_all()
    {
        $events = Event::where('uploaded', false)->get();

        if ($events->isEmpty()) {
            return redirect()->back()->with('warning', 'All events are up-to-date!');
        }

        foreach ($events as $event) {
            $this->upload_individual($event->id);
        }

        return redirect()->back()->with('success', 'Events uploaded successfully.');
    }

    public function upload_individual($eventID)
    {
        $event = Event::find($eventID);

        if (!$event || $event->uploaded) {
            return;
        }

        $meeting = Meeting::find($event->meetingID);
        $meeting_second = MeetingSecond::find($event->meetingID);

        if (!$meeting->uploaded && !$meeting_second) {
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
        }

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
    }

    public function get_results($id)
    {
        $event = Event::findOrFail($id);
        $results = Result::where('eventID', $event->id)->get();
        $competitors = CompetitorSecond::select('ID', 'name')->get();

        return response()->json(['results' => $results, 'event' => $event, 'competitors' => $competitors]);
    }

    public function result_create(Request $request)
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

        $result = Result::create(
            $data
        );

        return response()->json(['result' => $result]);
    }
}