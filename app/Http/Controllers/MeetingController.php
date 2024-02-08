<?php

namespace App\Http\Controllers;

use App\Models\AgeGroupSecond;
use App\Models\Event;
use App\Models\EventSecond;
use App\Models\IOSecond;
use App\Models\MeetingSecond;
use App\Models\Meeting;
use App\Models\MeetingTypeSecond;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Illuminate\Support\Facades\DB;

class MeetingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->only(['edit', 'update', 'destroy', 'upload', 'upload_all']);
    }

    public function index()
    {
        $meetings = Meeting::filter()->orderBy('created_at', 'DESC')->paginate(25);

        return view('meetings.index', compact('meetings'));
    }

    public function new()
    {
        $age_groups = AgeGroupSecond::select('ID', 'name')->orderBy('name')->get();
        $meeting_types = MeetingTypeSecond::select('ID', 'name')->get();
        $ios = IOSecond::select('io')->get();

        $data = compact('age_groups', 'meeting_types', 'ios');
        return view('meetings.new', $data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'ageGroupID' => 'required',
            'shortName' => 'required',
            'typeID' => 'required',
            'startDate' => 'required|date',
            'venue' => 'required',
            'country' => 'required',
        ]);

        $formattedDate = Carbon::parse($request->startDate)->format('ymd');
        $id = $request->typeID . $formattedDate;

        $data = $request->except('isActive', 'isNew', 'image', 'image2', 'subgroup');
        $isActive = $request->boolean('isActive');
        $isNew = $request->boolean('isNew');
        $data['isActive'] = $isActive;
        $data['isNew'] = $isNew;
        $data['IDSecond'] = $id;
        $data['subgroup'] = $request->subgroup ?? '';

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
        $meeting = Meeting::findOrFail($id);
        $age_groups = AgeGroupSecond::select('ID', 'name')->orderBy('name')->get();
        $meeting_types = MeetingTypeSecond::select('ID', 'name')->get();
        $ios = IOSecond::select('io')->get();

        if (!$meeting) {
            return redirect('/meetings')->with('danger', 'Meeting not found!');
        }

        $data = compact('age_groups', 'meeting_types', 'meeting', 'ios');
        return view('meetings.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ageGroupID' => 'required',
            'shortName' => 'required',
            'typeID' => 'required',
            'startDate' => 'required|date',
            'venue' => 'required',
            'country' => 'required',
        ]);

        $meeting = Meeting::findOrFail($id);

        if (!$meeting) {
            return redirect('/meetings')->with('danger', 'Meeting not found!');
        }

        $data = $request->except('isNew', 'isActive', 'image', 'image2', 'subgroup');
        $isActive = $request->boolean('isActive');
        $isNew = $request->boolean('isNew');
        $data['isActive'] = $isActive;
        $data['isNew'] = $isNew;
        $data['uploaded'] = false;
        $data['subgroup'] = $request->subgroup ?? '';

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
        try {
            Meeting::findOrFail($id)->delete();

            return redirect()->back()->with('danger', 'Meeting successfully deleted!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('danger', 'Meeting found in other Models!');
        }
    }

    public function export()
    {
        $data = Meeting::select('id', 'IDSecond', 'ageGroupID', 'name', 'shortName', 'startDate', 'endDate', 'venue', 'country', 'typeID', 'subgroup', 'picture', 'picture2', 'isActive', 'isNew', 'created_at')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['ID', 'ID Second', 'Age Group', 'Name', 'Short Name', 'Start Date', 'End Date', 'Venue', 'Country', 'Type ID', 'Sub Group', 'Picture', 'Picture2', 'isActive', 'isNew', 'IO', 'Created At'], null, 'A1');

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
                $d->io,
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
        $events = Event::where('meetingID', $meeting->id)->get();

        $data = compact('meeting', 'events');
        return view('meetings.events', $data);
    }

    public function upload_all()
    {
        $meetings = Meeting::where('uploaded', false)->get();

        if ($meetings->isEmpty()) {
            return redirect()->back()->with('warning', 'All Meetings are up-to-date!');
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($meetings as $meeting) {
            MeetingSecond::updateOrInsert(
                ['ID' => $meeting->IDSecond],
                [
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
                    'isActive' => $meeting->isActive,
                    'isNew' => $meeting->isNew,
                    'createDate' => $meeting->created_at,
                    'io' => $meeting->io,
                ]
            );

            $events = Event::where('uploaded', false)->where('meetingID', $meeting->id)->get();
            foreach ($events as $event) {
                EventSecond::updateOrInsert(
                    ['ID' => $event->id],
                    [
                        'ID' => $event->id,
                        'name' => $event->name,
                        'typeID' => $event->typeID,
                        'extra' => $event->extra,
                        'round' => $event->round,
                        'ageGroupID' => $event->ageGroupID,
                        'gender' => $event->gender,
                        'meetingID' => $meeting->IDSecond,
                        'wind' => $event->wind,
                        'note' => $event->note,
                        'distance' => $event->distance,
                        'heat' => $event->heat,
                        'createDate' => $event->created_at
                    ]
                );

                $event->update(['uploaded' => true]);
            }

            $meeting->update(['uploaded' => true]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->back()->with('success', 'Meetings uploaded successfully.');
    }

    public function upload($id)
    {
        $meeting = Meeting::findOrFail($id);

        if (!$meeting || $meeting->uploaded) {
            return redirect()->back()->with('warning', 'Meeting already uploaded!');
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        MeetingSecond::updateOrInsert(
            ['ID' => $meeting->IDSecond],
            [
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
                'isActive' => $meeting->isActive,
                'isNew' => $meeting->isNew,
                'createDate' => $meeting->created_at,
                'io' => $meeting->io,
            ]
        );

        $events = Event::where('uploaded', false)->where('meetingID', $meeting->id)->get();
        foreach ($events as $event) {
            EventSecond::updateOrInsert(
                ['ID' => $event->id],
                [
                    'ID' => $event->id,
                    'name' => $event->name,
                    'typeID' => $event->typeID,
                    'extra' => $event->extra,
                    'round' => $event->round,
                    'ageGroupID' => $event->ageGroupID,
                    'gender' => $event->gender,
                    'meetingID' => $meeting->IDSecond,
                    'wind' => $event->wind,
                    'note' => $event->note,
                    'distance' => $event->distance,
                    'heat' => $event->heat,
                    'createDate' => $event->created_at
                ]
            );

            $event->update(['uploaded' => true]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $meeting->update(['uploaded' => true]);
        return redirect()->back()->with('success', 'Meeting successfully uploaded!');
    }

    public function event_create(Request $request)
    {
        $request->validate([
            'typeID' => 'required',
            'ageGroupID' => 'required',
            'round' => 'required',
            'gender' => 'required',
        ]);

        $data = $request->except('extra');
        $data['extra'] = $request->extra ?? '';

        switch ($data['typeID']) {
            case 'HM':
            case 'M':
            case 'UM':
                $data['distance'] = $data['typeID'] == 'HM' ? 21000 : ($data['typeID'] == 'M' ? 42000 : 100000);
                $data['name'] = null;
                break;
            case '03':
            case '04':
            case '05':
            case '07':
            case '08':
            case '10':
            case '1H':
            case 'BT':
            case 'DT':
            case 'HJ':
            case 'HT':
            case 'JT':
            case 'LJ':
            case 'PV':
            case 'SP':
            case 'TJ':
            case 'WT':
            case 'YB':
                $data['name'] = null;
                break;
            case '4R':
                $data['name'] = '4x' . $data['distance'] / 4 . 'm';
                break;
            default:
                if ($data['distance'] == 1600) {
                    $data['name'] = 'Mile';
                } elseif ($data['distance'] == 6400) {
                    $data['name'] = '4 Miles';
                } elseif ($data['distance'] == 8000) {
                    $data['name'] = '5 Miles';
                } elseif ($data['distance'] == 16000) {
                    $data['name'] = '10 Miles';
                } elseif ($data['io'] == 'R') {
                    $data['name'] = $data['distance'] / 1000 . 'km';
                } else {
                    $data['name'] = $data['distance'] . 'm';
                }
                break;
        }

        if (Event::where('uploaded', false)->count() == 0) {
            $data['id'] = EventSecond::orderBy('ID', 'DESC')->first()->ID + 1;
        } else {
            $data['id'] = Event::orderBy('ID', 'DESC')->first()->id + 1;
        }

        $event = Event::create($data);

        return response()->json(['event' => $event]);
    }
}
