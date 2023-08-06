<?php

namespace App\Http\Controllers;

use App\Models\AgeGroupSecond;
use App\Models\Athlete;
use App\Models\EventTypeSecond;
use App\Models\GenderSecond;
use App\Models\IOSecond;
use App\Models\Record;
use App\Models\Result;
use App\Models\TeamSecond;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Carbon\Carbon;

class RecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $search = request()->query('search');

        if ($search) {
            $records = Record::where('competitor', 'LIKE', "%{$search}%")->paginate(25);
        } else {
            $records = Record::orderBy('created_at', 'DESC')->paginate(25);
        }

        return view('records.index', compact('records'));
    }

    public function new()
    {
        $ios = IOSecond::all();
        $age_groups = AgeGroupSecond::orderBy('name')->get();
        $genders = GenderSecond::all();
        $teams = TeamSecond::all();
        $athletes = Athlete::orderBy('created_at', 'DESC')->get();
        $results = Result::orderBy('created_at', 'DESC')->get();
        $event_types = EventTypeSecond::all();

        $data = compact('ios', 'age_groups', 'genders', 'teams', 'athletes', 'results', 'event_types');
        return view('records.new', $data);
    }

    public function create(Request $request)
    {
        $request->validate([
            // 'title' => 'required|max:255',
            // 'date' => 'required|date',
            // 'location' => 'required|max:255',
        ]);

        $data = $request->except('current');
        $data['current'] = $request->boolean('current');
        $data['extra'] = $request->extra ?? '';

        Record::create(
            $data
        );

        return redirect('/records')->with('success', 'Record successfully created!');
    }

    public function edit($id)
    {
        $record = Record::find($id);
        $ios = IOSecond::all();
        $age_groups = AgeGroupSecond::orderBy('name')->get();
        $genders = GenderSecond::all();
        $teams = TeamSecond::all();
        $athletes = Athlete::orderBy('created_at', 'DESC')->get();
        $results = Result::orderBy('created_at', 'DESC')->get();
        $event_types = EventTypeSecond::all();

        if (!$record) {
            return redirect('/records')->with('danger', 'Record not found!');
        }

        $data = compact('ios', 'age_groups', 'genders', 'teams', 'athletes', 'results', 'record', 'event_types');
        return view('records.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // 'title' => 'required|max:255',
            // 'date' => 'required|date',
            // 'location' => 'required|max:255',
        ]);

        $record = Record::find($id);

        if (!$record) {
            return redirect('/records')->with('danger', 'Record not found!');
        }

        $data = $request->except('current', 'extra');
        $data['current'] = $request->boolean('current');
        $data['extra'] = $request->extra ?? '';

        $record->update(
            $data
        );

        return redirect('/records')->with('warning', 'Record successfully updated!');
    }

    public function destroy($id)
    {
        $record = Record::find($id);

        if (!$record) {
            return redirect()->back()->with('danger', 'Record not found!');
        }

        $record->delete();

        return redirect()->back()->with('danger', 'Record successfully deleted!');
    }

    public function export()
    {
        $data = Record::select('id', 'date', 'venue', 'io', 'ageGroupID', 'gender', 'typeID', 'name', 'extra', 'competitor', 'teamID', 'result', 'note', 'wind', 'date2', 'current', 'distance', 'athleteID', 'points', 'resultValue', 'resultID', 'created_at')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['ID', 'Date', 'Venue', 'IO', 'Age Group ID', 'Gender', 'Type ID', 'Name', 'Extra', 'Competitor', 'Team ID', 'Result', 'Note', 'Wind', 'Date2', 'Current', 'Distance', 'Athlete ID', 'Points', 'Result Value', 'Result ID', 'Created At'], null, 'A1');

        $rows = 2;

        foreach ($data as $d) {
            $sheet->fromArray([
                $d->id,
                $d->date,
                $d->venue,
                $d->io,
                $d->ageGroupID,
                $d->gender,
                $d->typeID,
                $d->name,
                $d->extra,
                $d->competitor,
                $d->teamID,
                $d->result,
                $d->note,
                $d->wind,
                $d->date2,
                $d->current,
                $d->distance,
                $d->athleteID,
                $d->points,
                $d->resultValue,
                $d->resultID,
                $d->created_at ?? Carbon::now(),
            ], null, 'A' . $rows);

            $rows++;
        }

        $fileName = "Records.xls";
        $writer = new Xls($spreadsheet);
        $writer->save($fileName);

        return response()->file($fileName, [
            'Content-Type' => 'application/xls',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ]);
    }
}