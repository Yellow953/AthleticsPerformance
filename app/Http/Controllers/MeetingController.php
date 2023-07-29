<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $meetings = Meeting::paginate(25);
        return view('meetings.index', compact('meetings'));
    }

    public function new()
    {
        return view('meetings.new');
    }

    public function create(Request $request)
    {
        $request->validate([
            // 'title' => 'required|max:255',
            // 'date' => 'required|date',
            // 'location' => 'required|max:255',
        ]);

        Meeting::create(
            $request->all()
        );

        return redirect('/meetings')->with('success', 'Meeting successfully created!');
    }

    public function edit($id)
    {
        $meeting = Meeting::find($id);

        if (!$meeting) {
            return redirect('/meetings')->with('danger', 'Meeting not found!');
        }

        return view('meetings.edit', compact('meeting'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // 'title' => 'required|max:255',
            // 'date' => 'required|date',
            // 'location' => 'required|max:255',
        ]);

        $meeting = Meeting::find($id);

        if (!$meeting) {
            return redirect('/meetings')->with('danger', 'Meeting not found!');
        }

        $meeting->update([
            $request->all()
        ]);

        return redirect('/meetings')->with('warning', 'Meeting successfully updated!');
    }

    public function destroy($id)
    {
        $meeting = Meeting::find($id);

        if (!$meeting) {
            return redirect()->back()->with('danger', 'Meeting not found!');
        }

        $meeting->delete();

        return redirect()->back()->with('danger', 'Meeting successfully deleted!');
    }
}