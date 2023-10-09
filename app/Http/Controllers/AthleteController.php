<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\AthleteSecond;
use App\Models\GenderSecond;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Carbon\Carbon;

class AthleteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->only(['edit', 'update', 'destroy', 'upload', 'upload_all']);
    }

    public function index()
    {
        $athletes = Athlete::filter()->orderBy('created_at', 'DESC')->paginate(25);

        return view('athletes.index', compact('athletes'));
    }

    public function new()
    {
        $genders = GenderSecond::all();

        return view('athletes.new', compact('genders'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'gender' => 'required',
        ]);

        $data = $request->except('showResult', 'exactDate');
        $data['showResult'] = $request->boolean('showResult');
        $data['exactDate'] = $request->boolean('exactDate');
        if (Athlete::where('uploaded', false)->count() == 0) {
            $data['id'] = AthleteSecond::orderBy('ID', 'DESC')->first()->ID + 1;
        } else {
            $data['id'] = Athlete::orderBy('ID', 'DESC')->first()->id + 1;
        }

        Athlete::create(
            $data
        );

        return redirect('/athletes')->with('success', 'Athlete successfully created!');
    }

    public function edit($id)
    {
        $athlete = Athlete::findOrFail($id);
        $genders = GenderSecond::all();

        if (!$athlete) {
            return redirect('/athletes')->with('danger', 'Athlete not found!');
        }

        $data = compact('athlete', 'genders');
        return view('athletes.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'gender' => 'required',
        ]);

        $data = $request->except('showResult', 'exactDate');
        $data['showResult'] = $request->boolean('showResult');
        $data['exactDate'] = $request->boolean('exactDate');

        $athlete = Athlete::findOrFail($id);

        if (!$athlete) {
            return redirect('/athletes')->with('danger', 'Athlete not found!');
        }

        $athlete->update(
            $data
        );

        return redirect('/athletes')->with('warning', 'Athlete successfully updated!');
    }

    public function destroy($id)
    {
        try {
            Athlete::findOrFail($id)->delete();

            return redirect()->back()->with('danger', 'Athlete successfully deleted!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('danger', 'Athlete found in other Models!');
        }
    }

    public function export()
    {
        $data = Athlete::select('id', 'firstName', 'middleName', 'lastName', 'gender', 'dateOfBirth', 'showResult', 'exactDate', 'created_at')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['ID', 'firstName', 'middleName', 'lastName', 'Gender', 'Date of Birth', 'Show Result', 'Exact Date', 'Created At'], null, 'A1');

        $rows = 2;

        foreach ($data as $d) {
            $sheet->fromArray([
                $d->id,
                $d->firstName,
                $d->middleName,
                $d->lastName,
                $d->gender,
                $d->dateOfBirth,
                $d->showResult,
                $d->exactDate,
                $d->created_at ?? Carbon::now(),
            ], null, 'A' . $rows);

            $rows++;
        }

        $fileName = "Athletes.xls";
        $writer = new Xls($spreadsheet);
        $writer->save($fileName);

        return response()->file($fileName, [
            'Content-Type' => 'application/xls',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ]);
    }

    public function upload_all()
    {
        $athletes = Athlete::where('uploaded', false)->get();

        if ($athletes->isEmpty()) {
            return redirect()->back()->with('warning', 'All athletes are up-to-date!');
        }

        foreach ($athletes as $athlete) {
            AthleteSecond::create([
                'ID' => $athlete->id,
                'firstName' => $athlete->firstName,
                'middleName' => $athlete->middleName,
                'lastName' => $athlete->lastName,
                'dateOfBirth' => $athlete->dateOfBirth,
                'gender' => $athlete->gender,
                'exactDate' => $athlete->exactDate,
                'showResult' => $athlete->showResult
            ]);

            $athlete->uploaded = true;
            $athlete->save();
        }

        return redirect()->back()->with('success', 'Athletes uploaded successfully.');
    }

    public function upload($id)
    {
        $athlete = Athlete::findOrFail($id);

        if (!$athlete || $athlete->uploaded || AthleteSecond::find($id)) {
            return redirect()->back()->with('warning', 'Athlete is already up-to-date or not found.');
        }

        AthleteSecond::create([
            'ID' => $athlete->id,
            'firstName' => $athlete->firstName,
            'middleName' => $athlete->middleName,
            'lastName' => $athlete->lastName,
            'dateOfBirth' => $athlete->dateOfBirth,
            'gender' => $athlete->gender,
            'exactDate' => $athlete->exactDate,
            'showResult' => $athlete->showResult
        ]);

        $athlete->update(['uploaded' => true]);
        return redirect()->back()->with('success', 'Athlete uploaded successfully.');
    }

}