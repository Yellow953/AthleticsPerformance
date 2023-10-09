<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $guarded = [];

    public function event()
    {
        return $this->belongsTo(Event::class, 'eventID');
    }

    public function competitor()
    {
        return $this->belongsTo(Competitor::class, 'competitorID');
    }

    // Filter
    public function scopeFilter($q)
    {
        if (request('eventID')) {
            $eventID = request('eventID');
            $q->where('eventID', $eventID);
        }
        if (request('competitorID')) {
            $competitorID = request('competitorID');
            $q->where('competitorID', $competitorID);
        }
 
        return $q;
    }

}