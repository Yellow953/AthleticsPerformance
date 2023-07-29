<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $results = Result::paginate(25);
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

        return view('results.edit', compact('Result'));
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

        $result->update([
            $request->all()
        ]);

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
}