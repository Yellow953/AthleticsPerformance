<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $records = Record::paginate(25);
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

        return view('records.edit', compact('Record'));
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

        $record->update([
            $request->all()
        ]);

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
}