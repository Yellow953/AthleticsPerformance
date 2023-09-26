<?php

namespace App\Http\Controllers;

use App\Models\AgeGroupSecond;

use App\Models\Athlete;
use App\Models\AthleteSecond;
use App\Models\Competitor;
use App\Models\CompetitorSecond;
use App\Models\GenderSecond;
use App\Models\TeamSecond;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Carbon\Carbon;

class CompetitorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->only(['edit', 'update', 'destroy', 'upload', 'upload_all']);
    }

    public function index()
    {
        $search = request()->query('search');

        if ($search) {
            $competitors = Competitor::where('name', 'LIKE', "%{$search}%")->paginate(25);
        } else {
            $competitors = Competitor::orderBy('created_at', 'DESC')->paginate(25);
        }

        return view('competitors.index', compact('competitors'));
    }

    public function new()
    {
        $genders = GenderSecond::all();
        $age_groups = AgeGroupSecond::select('ID', 'name')->orderBy('name')->get();
        $teams = TeamSecond::select('ID', 'name')->get();
        $athletes = AthleteSecond::select('ID', 'firstName', 'lastName', 'middleName', 'gender')->orderBy('ID', 'DESC')->get();

        $data = compact('genders', 'age_groups', 'teams', 'athletes');
        return view('competitors.new', $data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'ageGroupID' => 'required',
            'teamID' => 'required',
            'year' => 'required|numeric|min:1900'
        ]);

        if (Competitor::where('uploaded', false)->count() == 0) {
            $request['id'] = CompetitorSecond::orderBy('ID', 'DESC')->first()->ID + 1;
        } else {
            $request['id'] = Competitor::orderBy('ID', 'DESC')->first()->id + 1;
        }

        Competitor::create(
            $request->all()
        );

        return redirect('/competitors')->with('success', 'Competitor successfully created!');
    }

    public function edit($id)
    {
        $competitor = Competitor::findOrFail($id);
        $genders = GenderSecond::all();
        $age_groups = AgeGroupSecond::select('ID', 'name')->orderBy('name')->get();
        $teams = TeamSecond::select('ID', 'name')->get();
        $athletes = AthleteSecond::select('ID', 'firstName', 'lastName', 'middleName', 'gender')->orderBy('ID', 'DESC')->get();

        if (!$competitor) {
            return redirect('/competitors')->with('danger', 'Competitor not found!');
        }

        $data = compact('genders', 'age_groups', 'teams', 'athletes', 'competitor');
        return view('competitors.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'ageGroupID' => 'required',
            'teamID' => 'required',
            'year' => 'required|numeric|min:1900'
        ]);

        $competitor = Competitor::findOrFail($id);

        if (!$competitor) {
            return redirect('/competitors')->with('danger', 'Competitor not found!');
        }

        $competitor->update(
            $request->all()
        );

        return redirect('/competitors')->with('warning', 'Competitor successfully updated!');
    }

    public function destroy($id)
    {
        $competitor = Competitor::findOrFail($id);

        if (!$competitor) {
            return redirect()->back()->with('danger', 'Competitor not found!');
        }

        $competitor->delete();

        return redirect()->back()->with('danger', 'Competitor successfully deleted!');
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

    public function upload_all()
    {
        $competitors = Competitor::where('uploaded', false)->get();

        if ($competitors->isEmpty()) {
            return redirect()->back()->with('warning', 'All competitors are up-to-date!');
        }

        foreach ($competitors as $competitor) {
            if (!$competitor || $competitor->uploaded) {
                return;
            }

            $athlete = Athlete::find($competitor->athleteID);
            $athlete_second = AthleteSecond::find($competitor->athleteID);

            if (!$athlete->uploaded && !$athlete_second) {
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
            }

            CompetitorSecond::create([
                'ID' => $competitor->id,
                'name' => $competitor->name,
                'athleteID' => $competitor->athleteID,
                'gender' => $competitor->gender,
                'teamID' => $competitor->teamID,
                'year' => $competitor->year,
                'ageGroupID' => $competitor->ageGroupID,
            ]);

            $competitor->update(['uploaded' => true]);
        }

        return redirect()->back()->with('success', 'Competitors uploaded successfully.');
    }

    public function upload($id)
    {
        $competitor = Competitor::findOrFail($id);

        if (!$competitor || $competitor->uploaded) {
            return redirect()->back()->with('warning', 'Competitor already uploaded!');
        }

        $athlete = Athlete::find($competitor->athleteID);
        $athlete_second = AthleteSecond::find($competitor->athleteID);

        if (!$athlete->uploaded && !$athlete_second) {
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
        }

        CompetitorSecond::create([
            'ID' => $competitor->id,
            'name' => $competitor->name,
            'athleteID' => $competitor->athleteID,
            'gender' => $competitor->gender,
            'teamID' => $competitor->teamID,
            'year' => $competitor->year,
            'ageGroupID' => $competitor->ageGroupID,
        ]);

        $competitor->update(['uploaded' => true]);

        return redirect()->back()->with('success', 'Competitor uploaded successfully!');
    }
}