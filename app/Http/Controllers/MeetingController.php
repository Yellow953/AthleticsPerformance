<?php

namespace App\Http\Controllers;

use App\Models\AgeGroupSecond;
use App\Models\Event;
use App\Models\MeetingSecond;
use App\Models\Meeting;
use App\Models\MeetingTypeSecond;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

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
            $meetings = Meeting::orderBy('created_at', 'DESC')->paginate(25);
        }

        return view('meetings.index', compact('meetings'));
    }

    public function new()
    {
        $age_groups = AgeGroupSecond::orderBy('name')->get();
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
            'startDate' => 'required|date',
            'venue' => 'required',
            'country' => 'required',
        ]);

        $formattedDate = Carbon::parse($request->startDate)->format('ymd');
        $id = $request->typeID . $formattedDate;

        $data = $request->except('isActive', 'isNew', 'image', 'image2');
        $isActive = $request->boolean('isActive');
        $isNew = $request->boolean('isNew');
        $data['isActive'] = $isActive;
        $data['isNew'] = $isNew;
        $data['IDSecond'] = $id;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('uploads/meetings/', $filename);
            $data['image'] = '/uploads/meetings/' . $filename;
        }

        if ($request->hasFile('image2')) {
            $file = $request->file('image2');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('uploads/meetings/', $filename);
            $data['image2'] = '/uploads/meetings/' . $filename;
        }

        Meeting::create(
            $data
        );

        return redirect('/meetings')->with('success', 'Meeting successfully created!');
    }

    public function edit($id)
    {
        $meeting = Meeting::find($id);
        $age_groups = AgeGroupSecond::orderBy('name')->get();
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
            'startDate' => 'required|date',
            'venue' => 'required',
            'country' => 'required',
        ]);

        $meeting = Meeting::find($id);

        if (!$meeting) {
            return redirect('/meetings')->with('danger', 'Meeting not found!');
        }

        $data = $request->except('isNew', 'isActive', 'image', 'image2');
        $isActive = $request->boolean('isActive');
        $isNew = $request->boolean('isNew');
        $data['isActive'] = $isActive;
        $data['isNew'] = $isNew;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('uploads/meetings/', $filename);
            $data['image'] = '/uploads/meetings/' . $filename;
        }

        if ($request->hasFile('image2')) {
            $file = $request->file('image2');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('uploads/meetings/', $filename);
            $data['image2'] = '/uploads/meetings/' . $filename;
        }

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
        $data = Meeting::select('id', 'IDSecond', 'ageGroupID', 'name', 'shortName', 'startDate', 'endDate', 'venue', 'country', 'typeID', 'subgroup', 'picture', 'picture2', 'isActive', 'isNew', 'created_at')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['ID', 'ID Second', 'Age Group', 'Name', 'Short Name', 'Start Date', 'End Date', 'Venue', 'Country', 'Type ID', 'Sub Group', 'Picture', 'Picture2', 'isActive', 'isNew', 'Created At'], null, 'A1');

        $rows = 2;

        foreach ($data as $d) {
            $sheet->fromArray([
                $d->id,
                $d->IDSecond,
                $d->ageGroupID,
                $d->name,
                $d->shortName,
                $d->startDate,
                $d->endDate,
                $d->venue,
                $d->country,
                $d->typeID,
                $d->subgroup,
                $d->picture,
                $d->picture2,
                $d->isActive,
                $d->isNew,
                $d->created_at ?? Carbon::now(),
            ], null, 'A' . $rows);

            $rows++;
        }

        $fileName = "Meeting.xls";
        $writer = new Xls($spreadsheet);
        $writer->save($fileName);

        return response()->file($fileName, [
            'Content-Type' => 'application/xls',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ]);
    }

    public function events($id)
    {
        $meeting = Meeting::find($id);
        $events = Event::where('meetingID', $meeting->IDSecond)->get();

        $data = compact('meeting', 'events');
        return view('meetings.events', $data);
    }
}