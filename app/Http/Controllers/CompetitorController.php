<?php

namespace App\Http\Controllers;

use App\Models\Competitor;
use Illuminate\Http\Request;

class CompetitorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $competitors = Competitor::paginate(25);
        return view('competitors.index', compact('competitors'));
    }

    public function new()
    {
        return view('competitors.new');
    }

    public function create(Request $request)
    {
        $request->validate([
            // 'title' => 'required|max:255',
            // 'date' => 'required|date',
            // 'location' => 'required|max:255',
        ]);

        Competitor::create([
            $request->all()
        ]);

        return redirect('/competitors')->with('success', 'Competitor successfully created!');
    }

    public function edit($id)
    {
        $competitor = Competitor::find($id);

        if (!$competitor) {
            return redirect('/competitors')->with('danger', 'Competitor not found!');
        }

        return view('competitors.edit', compact('Competitor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // 'title' => 'required|max:255',
            // 'date' => 'required|date',
            // 'location' => 'required|max:255',
        ]);

        $competitor = Competitor::find($id);

        if (!$competitor) {
            return redirect('/competitors')->with('danger', 'Competitor not found!');
        }

        $competitor->update([
            $request->all()
        ]);

        return redirect('/competitors')->with('warning', 'Competitor successfully updated!');
    }

    public function destroy($id)
    {
        $competitor = Competitor::find($id);

        if (!$competitor) {
            return redirect()->back()->with('danger', 'Competitor not found!');
        }

        $competitor->delete();

        return redirect()->back()->with('danger', 'Competitor successfully deleted!');
    }
}