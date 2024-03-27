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
use App\Models\MeetingSecond;
use App\Models\Result;
use App\Models\ResultSecond;
use App\Models\RoundSecond;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->only(['edit', 'update', 'destroy', 'upload', 'upload_all']);
    }

    public function index()
    {
        $events = Event::filter()->orderBy('created_at', 'DESC')->paginate(25);

        return view('events.index', compact('events'));
    }

    public function new()
    {
        $meetings = Meeting::select('id', 'shortName')->orderBy('created_at', 'DESC')->get();
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

        Event::create(
            $data
        );

        return redirect()->back()->with('success', 'Event successfully created!');
        // return response()->json(['event' => $event]);
    }

    public function edit(Event $event)
    {
        $meetings = Meeting::select('id', 'shortName')->orderBy('created_at', 'DESC')->get();
        $rounds = RoundSecond::select('ID', 'name')->get();
        $event_types = EventTypeSecond::select('ID', 'name')->get();
        $age_groups = AgeGroupSecond::select('ID', 'name')->orderBy('name')->get();
        $ios = IOSecond::all();
        $genders = GenderSecond::all();

        $data = compact('meetings', 'rounds', 'event_types', 'age_groups', 'ios', 'genders', 'event');
        return view('events.edit', $data);
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'typeID' => 'required',
            'ageGroupID' => 'required',
            'round' => 'required',
            'gender' => 'required',
        ]);

        $data = $request->except('extra');
        $data['extra'] = $request->extra ?? '';
        $data['uploaded'] = false;

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

        $event->update(
            $data
        );

        return redirect()->back()->with('warning', 'Event successfully updated!');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->back()->with('danger', 'Event successfully deleted!');
    }

    public function export()
    {
        $data = Event::select('id', 'name', 'typeID', 'extra', 'round', 'ageGroupID', 'gender', 'meetingID', 'wind', 'note', 'distance', 'io', 'heat', 'created_at')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['ID', 'Name', 'Type ID', 'Extra', 'Round', 'Age Group', 'Gender', 'Meeting ID', 'Wind', 'Note', 'distance', 'heat', 'Created At'], null, 'A1');

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

    public function results(Event $event)
    {
        $results = Result::where('eventID', $event->id)->get();
        $competitors = Competitor::select('id', 'name')->get();

        $data = compact('event', 'results', 'competitors');
        return view('events.results', $data);
    }

    public function upload_all()
    {
        $events = Event::where('uploaded', false)->get();

        if ($events->isEmpty()) {
            return redirect()->back()->with('warning', 'All events are up-to-date!');
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($events as $event) {
            $meeting = Meeting::find($event->meetingID);

            if (!$meeting->uploaded) {
                MeetingSecond::updateOrInsert(
                    ['ID' => $event->meetingID],
                    [
                        'ID' => $event->meetingID,
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

                $meeting->update(['uploaded' => true]);
            }

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

        return redirect()->back()->with('success', 'Events uploaded successfully.');
    }

    public function upload(Event $event)
    {
        if ($event->uploaded) {
            return redirect()->back()->with('warning', 'Event already Uploaded!');
        }

        $meeting = Meeting::find($event->meetingID);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        if (!$meeting->uploaded) {
            MeetingSecond::updateOrInsert(
                ['ID' => $event->meetingID],
                [
                    'ID' => $event->meetingID,
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

            $meeting->update(['uploaded' => true]);
        }

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

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $event->update(['uploaded' => true]);
        return redirect()->back()->with('success', 'Event uploaded successfully!');
    }

    public function get_results(Event $event)
    {
        $results = Result::where('eventID', $event->id)->get();
        $competitors = Competitor::select('id', 'name')->get();

        return response()->json(['results' => $results, 'event' => $event, 'competitors' => $competitors]);
    }

    public function result_create(Request $request)
    {
        $request->validate([
            'competitorID' => 'required',
            'position' => 'required',
            'result' => 'required',
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
