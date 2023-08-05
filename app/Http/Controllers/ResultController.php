<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Carbon\Carbon;

class ResultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $search = request()->query('search');

        if ($search) {
            $results = Result::orderBy('created_at', 'DESC')->paginate(25);
        } else {
            $results = Result::orderBy('created_at', 'DESC')->paginate(25);
        }

        return view('results.index', compact('results'));
    }

    public function new()
    {
        return view('results.new');
    }

    public function create(Request $request)
    {
        $request->validate([
            // 'title' => 'required|max:255',
            // 'date' => 'required|date',
            // 'location' => 'required|max:255',
        ]);

        Result::create(
            $request->all()
        );

        return redirect('/results')->with('success', 'Result successfully created!');
    }

    public function edit($id)
    {
        $result = Result::find($id);

        if (!$result) {
            return redirect('/results')->with('danger', 'Result not found!');
        }

        return view('results.edit', compact('result'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // 'title' => 'required|max:255',
            // 'date' => 'required|date',
            // 'location' => 'required|max:255',
        ]);

        $result = Result::find($id);

        if (!$result) {
            return redirect('/results')->with('danger', 'Result not found!');
        }

        $result->update(
            $request->all()
        );

        return redirect('/results')->with('warning', 'Result successfully updated!');
    }

    public function destroy($id)
    {
        $result = Result::find($id);

        if (!$result) {
            return redirect()->back()->with('danger', 'Result not found!');
        }

        $result->delete();

        return redirect()->back()->with('danger', 'Result successfully deleted!');
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