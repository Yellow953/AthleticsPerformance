<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use Illuminate\Http\Request;

class AthleteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $athletes = Athlete::paginate(25);
        return view('athletes.index', compact('athletes'));
    }

    public function new()
    {
        return view('athletes.new');
    }

    public function create(Request $request)
    {
        $request->validate([
            // 'title' => 'required|max:255',
            // 'date' => 'required|date',
            // 'location' => 'required|max:255',
        ]);

        Athlete::create([
            $request->all()
        ]);

        return redirect('/athletes')->with('success', 'Athlete successfully created!');
    }

    public function edit($id)
    {
        $athlete = Athlete::find($id);

        if (!$athlete) {
            return redirect('/athletes')->with('danger', 'Athlete not found!');
        }

        return view('athletes.edit', compact('Athlete'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // 'title' => 'required|max:255',
            // 'date' => 'required|date',
            // 'location' => 'required|max:255',
        ]);

        $athlete = Athlete::find($id);

        if (!$athlete) {
            return redirect('/athletes')->with('danger', 'Athlete not found!');
        }

        $athlete->update([
            $request->all()
        ]);

        return redirect('/athletes')->with('warning', 'Athlete successfully updated!');
    }

    public function destroy($id)
    {
        $athlete = Athlete::find($id);

        if (!$athlete) {
            return redirect()->back()->with('danger', 'Athlete not found!');
        }

        $athlete->delete();

        return redirect()->back()->with('danger', 'Athlete successfully deleted!');
    }
}