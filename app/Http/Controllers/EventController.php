<?php

namespace App\Http\Controllers;

use App\Models\AgeGroupSecond;
use App\Models\Competitor;
use App\Models\Event;
use App\Models\EventSecond;
use App\Models\EventTypeSecond;
use App\Models\GenderSecond;
use App\Models\IOSecond;
use App\Models\Meeting;
use App\Models\Result;
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
        $meetings = Meeting::orderBy('created_at', 'DESC')->get();
        $rounds = RoundSecond::all();
        $event_types = EventTypeSecond::all();
        $age_groups = AgeGroupSecond::orderBy('name')->get();
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
        $data['id'] = EventSecond::orderBy('ID', 'DESC')->first()->ID + Event::where('uploaded', 0)->count() + 1;
        $data['extra'] = $request->extra ?? '';

        Event::create(
            $data
        );

        return redirect()->back()->with('success', 'Event successfully created!');
    }

    public function edit($id)
    {
        $event = Event::find($id);
        $meetings = Meeting::orderBy('created_at', 'DESC')->get();
        $rounds = RoundSecond::all();
        $event_types = EventTypeSecond::all();
        $age_groups = AgeGroupSecond::orderBy('name')->get();
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

    public function upload()
    {
        $events = Event::where('uploaded', false)->get();

        foreach ($events as $event) {
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

            $event->uploaded = true;
            $event->save();
        }

        return redirect()->back()->with('success', 'Events uploaded successfully...');
    }

    public function results($id)
    {
        $event = Event::findOrFail($id);
        $results = Result::where('eventID', $event->id)->get();
        $competitors = Competitor::all();

        $data = compact('event', 'results', 'competitors');
        return view('events.results', $data);
    }
}