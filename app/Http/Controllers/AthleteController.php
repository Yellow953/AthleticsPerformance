<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\AthleteSecond;
use Illuminate\Http\Request;

class AthleteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $search = request()->query('search');

        if ($search) {
            $athletes = Athlete::where('firstName', 'LIKE', "%{$search}%")->orWhere('lastName', 'LIKE', "%{$search}%")->paginate(25);
        } else {
            $athletes = Athlete::orderBy('created_at', 'DESC')->paginate(25);
        }

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

        Athlete::create(
            $request->all()
        );

        return redirect('/athletes')->with('success', 'Athlete successfully created!');
    }

    public function edit($id)
    {
        $athlete = Athlete::find($id);

        if (!$athlete) {
            return redirect('/athletes')->with('danger', 'Athlete not found!');
        }

        return view('athletes.edit', compact('athlete'));
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

        $athlete->update(
            $request->all()
        );

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

    public function export()
    {

    }
}