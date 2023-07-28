<?php

namespace App\Http\Controllers;

use App\Models\AthleteSecond;
use App\Models\CompetitorSecond;
use App\Models\EventSecond;
use App\Models\MeetingSecond;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $meeting_count = MeetingSecond::count();
        $event_count = EventSecond::count();
        $athlete_count = AthleteSecond::count();
        $competitor_count = CompetitorSecond::count();

        return view('index', compact('meeting_count', 'event_count', 'athlete_count', 'competitor_count'));
    }
}