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
use Illuminate\Support\Facades\DB;
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
        $competitors = Competitor::filter()->orderBy('created_at', 'DESC')->paginate(25);

        return view('competitors.index', compact('competitors'));
    }

    public function new()
    {
        $genders = GenderSecond::all();
        $age_groups = AgeGroupSecond::select('ID', 'name')->orderBy('name')->get();
        $teams = TeamSecond::select('ID', 'name')->get();
        $athletes = Athlete::select('ID', 'firstName', 'lastName', 'middleName', 'gender')->orderBy('ID', 'DESC')->get();

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

        if ($request->athleteID) {
            $athlete = Athlete::find($request->athleteID);
            $ageGroup = AgeGroupSecond::find($request->ageGroupID);

            if ($athlete->gender != $request->gender) {
                return redirect()->back()->with('warning', 'Athlete Gender different than entered gender!');
            }

            $athleteYear = $athlete->dateOfBirth ? Carbon::createFromFormat('Y-m-d', $athlete->dateOfBirth)->format('Y') : date('Y') - 20;
            $date = date('Y') - $athleteYear;

            if ($ageGroup->lowerLimit > $date || $date > $ageGroup->upperLimit) {
                return redirect()->back()->with('warning', 'Athlete Not in the correct Age Group!');
            }
        }

        if (Competitor::where('uploaded', false)->count() == 0) {
            $request['id'] = CompetitorSecond::orderBy('ID', 'DESC')->first()->ID + 1;
        } else {
            $request['id'] = Competitor::orderBy('ID', 'DESC')->first()->id + 1;
        }

        Competitor::create($request->all());

        return redirect()->route('competitors')->with('success', 'Competitor successfully created!');
    }

    public function edit(Competitor $competitor)
    {
        $genders = GenderSecond::all();
        $age_groups = AgeGroupSecond::select('ID', 'name')->orderBy('name')->get();
        $teams = TeamSecond::select('ID', 'name')->get();
        $athletes = Athlete::select('ID', 'firstName', 'lastName', 'middleName', 'gender')->orderBy('ID', 'DESC')->get();

        $data = compact('genders', 'age_groups', 'teams', 'athletes', 'competitor');
        return view('competitors.edit', $data);
    }

    public function update(Request $request, Competitor $competitor)
    {
        $request->validate([
            'name' => 'required',
            'ageGroupID' => 'required',
            'teamID' => 'required',
            'year' => 'required|numeric|min:1900'
        ]);

        if ($request->athleteID) {
            $athlete = Athlete::find($request->athleteID);
            $ageGroup = AgeGroupSecond::find($request->ageGroupID);

            if ($athlete->gender != $request->gender) {
                return redirect()->back()->with('warning', 'Athlete Gender different than entered gender!');
            }

            $athleteYear = $athlete->dateOfBirth ? Carbon::createFromFormat('Y-m-d', $athlete->dateOfBirth)->format('Y') : date('Y') - 20;
            $date = date('Y') - $athleteYear;

            if ($ageGroup->lowerLimit > $date || $date > $ageGroup->upperLimit) {
                return redirect()->back()->with('warning', 'Athlete Not in the correct Age Group!');
            }
        }

        $data = $request->all();
        $data['uploaded'] = false;

        $competitor->update(
            $data
        );

        return redirect()->route('competitors')->with('warning', 'Competitor successfully updated!');
    }

    public function destroy(Competitor $competitor)
    {
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

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($competitors as $competitor) {
            $athlete = Athlete::find($competitor->athleteID);

            if (!$athlete->uploaded) {
                AthleteSecond::updateOrInsert(
                    ['ID' => $athlete->id],
                    [
                        'ID' => $athlete->id,
                        'firstName' => $athlete->firstName,
                        'middleName' => $athlete->middleName,
                        'lastName' => $athlete->lastName,
                        'dateOfBirth' => $athlete->dateOfBirth,
                        'gender' => $athlete->gender,
                        'exactDate' => $athlete->exactDate,
                        'showResult' => $athlete->showResult
                    ]
                );

                $athlete->update(['uploaded' => true]);
            }

            CompetitorSecond::updateOrInsert(
                ['ID' => $competitor->id],
                [
                    'ID' => $competitor->id,
                    'name' => $competitor->name,
                    'athleteID' => $competitor->athleteID,
                    'gender' => $competitor->gender,
                    'teamID' => $competitor->teamID,
                    'year' => $competitor->year,
                    'ageGroupID' => $competitor->ageGroupID,
                ]
            );

            $competitor->update(['uploaded' => true]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->back()->with('success', 'Competitors uploaded successfully.');
    }

    public function upload(Competitor $competitor)
    {
        if ($competitor->uploaded) {
            return redirect()->back()->with('warning', 'Competitor already uploaded!');
        }

        $athlete = Athlete::find($competitor->athleteID);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        if (!$athlete->uploaded) {
            AthleteSecond::updateOrInsert(
                ['ID' => $athlete->id],
                [
                    'ID' => $athlete->id,
                    'firstName' => $athlete->firstName,
                    'middleName' => $athlete->middleName,
                    'lastName' => $athlete->lastName,
                    'dateOfBirth' => $athlete->dateOfBirth,
                    'gender' => $athlete->gender,
                    'exactDate' => $athlete->exactDate,
                    'showResult' => $athlete->showResult
                ]
            );

            $athlete->update(['uploaded' => true]);
        }

        CompetitorSecond::updateOrInsert(
            ['ID' => $competitor->id],
            [
                'ID' => $competitor->id,
                'name' => $competitor->name,
                'athleteID' => $competitor->athleteID,
                'gender' => $competitor->gender,
                'teamID' => $competitor->teamID,
                'year' => $competitor->year,
                'ageGroupID' => $competitor->ageGroupID,
            ]
        );

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $competitor->update(['uploaded' => true]);

        return redirect()->back()->with('success', 'Competitor uploaded successfully!');
    }
}
