<?php

namespace App\Http\Controllers;

use App\Models\AgeGroupSecond;
use App\Models\MeetingSecond;
use App\Models\Meeting;
use App\Models\MeetingTypeSecond;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $search = request()->query('search');

        if ($search) {
            $meetings = Meeting::where('name', 'LIKE', "%{$search}%")->orWhere('shortName', 'LIKE', "%{$search}%")->paginate(25);
        } else {
            $meetings = Meeting::paginate(25);
        }

        return view('meetings.index', compact('meetings'));
    }

    public function new()
    {
        $age_groups = AgeGroupSecond::all();
        $meeting_types = MeetingTypeSecond::all();

        $data = compact('age_groups', 'meeting_types');
        return view('meetings.new', $data);
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
        $age_groups = AgeGroupSecond::all();
        $meeting_types = MeetingTypeSecond::all();

        if (!$meeting) {
            return redirect('/meetings')->with('danger', 'Meeting not found!');
        }

        $data = compact('meeting', 'age_groups', 'meeting_types');
        return view('meetings.edit', $data);
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

    public function export()
    {

    }
}