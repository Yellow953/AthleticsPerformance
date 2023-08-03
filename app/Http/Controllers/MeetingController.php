<?php

namespace App\Http\Controllers;

use App\Models\AgeGroupSecond;
use App\Models\MeetingSecond;
use App\Models\Meeting;
use App\Models\MeetingTypeSecond;
use Carbon\Carbon;
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
            'ageGroupID' => 'required',
            'shortName' => 'required',
            'subgroup' => 'required',
            'typeID' => 'required',
            'startDate' => 'required|date'
        ]);

        $formattedDate = Carbon::parse($request->startDate)->format('ymd');
        $id = $request->typeID . $formattedDate;

        $data = $request->except('isActive', 'isNew');
        $isActive = $request->boolean('isActive');
        $isNew = $request->boolean('isNew');
        $data['isActive'] = $isActive;
        $data['isNew'] = $isNew;
        $data['IDSecond'] = $id;

        Meeting::create(
            $data
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

        $data = compact('age_groups', 'meeting_types', 'meeting');
        return view('meetings.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ageGroupID' => 'required',
            'shortName' => 'required',
            'subgroup' => 'required',
            'typeID' => 'required',
            'startDate' => 'required|date'
        ]);

        $meeting = Meeting::find($id);

        if (!$meeting) {
            return redirect('/meetings')->with('danger', 'Meeting not found!');
        }

        $data = $request->except('isNew', 'isActive');
        $isActive = $request->boolean('isActive');
        $isNew = $request->boolean('isNew');
        $data['isActive'] = $isActive;
        $data['isNew'] = $isNew;

        if ($request->typeID != $meeting->typeID) {
            $formattedDate = Carbon::parse($request->startDate)->format('ymd');
            $id = $request->typeID . $formattedDate;

            $data['IDSecond'] = $id;
        }

        $meeting->update(
            $data
        );

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