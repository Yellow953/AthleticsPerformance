<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $events = Event::paginate(25);
        return view('events.index', compact('events'));
    }

    public function new()
    {
        return view('events.new');
    }

    public function create(Request $request)
    {
        $request->validate([
            // 'title' => 'required|max:255',
            // 'date' => 'required|date',
            // 'location' => 'required|max:255',
        ]);

        Event::create(
            $request->all()
        );

        return redirect('/events')->with('success', 'Event successfully created!');
    }

    public function edit($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return redirect('/events')->with('danger', 'Event not found!');
        }

        return view('events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // 'title' => 'required|max:255',
            // 'date' => 'required|date',
            // 'location' => 'required|max:255',
        ]);

        $event = Event::find($id);

        if (!$event) {
            return redirect('/events')->with('danger', 'Event not found!');
        }

        $event->update([
            $request->all()
        ]);

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
}