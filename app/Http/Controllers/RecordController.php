<?php

namespace App\Http\Controllers;

use App\Models\Record;
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
            $records = Record::orderBy('created_at', 'DESC')->paginate(25);
        } else {
            $records = Record::orderBy('created_at', 'DESC')->paginate(25);
        }

        return view('records.index', compact('records'));
    }

    public function new()
    {
        return view('records.new');
    }

    public function create(Request $request)
    {
        $request->validate([
            // 'title' => 'required|max:255',
            // 'date' => 'required|date',
            // 'location' => 'required|max:255',
        ]);

        Record::create(
            $request->all()
        );

        return redirect('/records')->with('success', 'Record successfully created!');
    }

    public function edit($id)
    {
        $record = Record::find($id);

        if (!$record) {
            return redirect('/records')->with('danger', 'Record not found!');
        }

        return view('records.edit', compact('record'));
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

        $record->update(
            $request->all()
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
        $data = Competitor::select('id', 'athleteID', 'name', 'gender', 'teamID', 'ageGroupID', 'year', 'created_at')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['ID', 'Athlete', 'name', 'Gender', 'Team', 'Age Group', 'Year', 'Created At'], null, 'A1');

        $rows = 2;

        foreach ($data as $d) {
            $sheet->fromArray([
                $d->id,
                $d->athleteID,
                $d->name,
                $d->gender,
                $d->teamID,
                $d->year,
                $d->ageGroupID,
                $d->created_at ?? Carbon::now(),
            ], null, 'A' . $rows);

            $rows++;
        }

        $fileName = "Competitors.xls";
        $writer = new Xls($spreadsheet);
        $writer->save($fileName);

        return response()->file($fileName, [
            'Content-Type' => 'application/xls',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ]);
    }
}