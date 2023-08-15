<?php

namespace App\Http\Controllers;

use App\Models\Competitor;
use App\Models\Result;
use App\Models\Event;
use App\Models\ResultSecond;
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
        $events = Event::orderBy('created_at', 'DESC')->get();
        $competitors = Competitor::orderBy('created_at', 'DESC')->get();

        $data = compact('events', 'competitors');
        return view('results.new', $data);
    }

    public function create(Request $request)
    {
        $request->validate([
            // 'title' => 'required|max:255',
            // 'date' => 'required|date',
            // 'location' => 'required|max:255',
        ]);

        $data = $request->except('isHand', 'isActive');
        $data['id'] = ResultSecond::orderBy('ID', 'DESC')->first()->ID + Result::where('uploaded', 0)->count() + 1;
        $data['isHand'] = $request->boolean('isHand');
        $data['isActive'] = $request->boolean('isActive');

        Result::create(
            $data
        );

        return redirect()->back()->with('success', 'Result successfully created!');
    }

    public function edit($id)
    {
        $result = Result::find($id);
        $events = Event::orderBy('created_at', 'DESC')->get();
        $competitors = Competitor::orderBy('created_at', 'DESC')->get();

        if (!$result) {
            return redirect('/results')->with('danger', 'Result not found!');
        }

        $data = compact('result', 'events', 'competitors');
        return view('results.edit', $data);
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

        $data = $request->except('isHand', 'isActive');
        $data['isHand'] = $request->boolean('isHand');
        $data['isActive'] = $request->boolean('isActive');

        $result->update(
            $data
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
        $data = Result::select('id', 'eventID', 'competitorID', 'result', 'isHand', 'position', 'wind', 'note', 'points', 'resultValue', 'recordStatus', 'heat', 'isActive', 'created_at')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['ID', 'Event ID', 'Competitor ID', 'Result', 'isHand', 'Position', 'Wind', 'Note', 'Points', 'Result Value', 'Record Status', 'Heat', 'isActive', 'Created At'], null, 'A1');

        $rows = 2;

        foreach ($data as $d) {
            $sheet->fromArray([
                $d->id,
                $d->eventID,
                $d->competitorID,
                $d->result,
                $d->isHand,
                $d->position,
                $d->wind,
                $d->note,
                $d->points,
                $d->resultValue,
                $d->recordStatus,
                $d->heat,
                $d->isActive,
                $d->created_at ?? Carbon::now(),
            ], null, 'A' . $rows);

            $rows++;
        }

        $fileName = "Results.xls";
        $writer = new Xls($spreadsheet);
        $writer->save($fileName);

        return response()->file($fileName, [
            'Content-Type' => 'application/xls',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ]);
    }

    public function upload()
    {
        $results = Result::where('uploaded', false)->get();

        foreach ($results as $result) {
            ResultSecond::create([
                'eventID' => $result->eventID,
                'competitorID' => $result->competitorID,
                'result' => $result->result,
                'isHand' => $result->isHand,
                'position' => $result->position,
                'note' => $result->note,
                'wind' => $result->wind,
                'points' => $result->points,
                'resultValue' => $result->resultValue,
                'recordStatus' => $result->recordStatus,
                'heat' => $result->heat,
                'isActive' => $result->isActive,
                'createDate' => $result->created_at,
            ]);

            $result->uploaded = true;
            $result->save();
        }

        return redirect()->back()->with('success', 'Results uploaded successfully...');
    }
}