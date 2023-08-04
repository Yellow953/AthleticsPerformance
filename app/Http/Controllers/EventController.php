<?php

namespace App\Http\Controllers;

use App\Models\AgeGroupSecond;
use App\Models\Event;
use App\Models\EventSecond;
use App\Models\EventTypeSecond;
use App\Models\GenderSecond;
use App\Models\IOSecond;
use App\Models\Meeting;
use App\Models\RoundSecond;
use Illuminate\Http\Request;

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

        $data = $request->except('heat', 'extra');
        $heat = $request->boolean('heat');
        $data['heat'] = $heat;
        $data['extra'] = $request->extra ?? '';

        Event::create(
            $data
        );

        return redirect('/events')->with('success', 'Event successfully created!');
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

        $data = $request->except('heat');
        $heat = $request->boolean('heat');
        $data['heat'] = $heat;

        $event->update(
            $data
        );

        return redirect('/events')->with('warning', 'Event successfully updated!');
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

    }
}