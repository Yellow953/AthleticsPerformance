<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Competitor;
use App\Models\Event;
use App\Models\Meeting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $meeting_count = Meeting::count();
        $event_count = Event::count();
        $athlete_count = Athlete::count();
        $competitor_count = Competitor::count();

        return view('index', compact('meeting_count', 'event_count', 'athlete_count', 'competitor_count'));
    }
}