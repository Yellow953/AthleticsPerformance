<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competitor extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $guarded = [];

    public function athlete()
    {
        return $this->belongsTo(Athlete::class, 'athleteID');
    }

    public function ageGroup()
    {
        return $this->belongsTo(AgeGroupSecond::class, 'ageGroupID', 'ID');
    }

    public function team()
    {
        return $this->belongsTo(TeamSecond::class, 'teamID', 'ID');
    }

    // Filter
    public function scopeFilter($q)
    {
        if (request('name')) {
            $name = request('name');
            $q->where('name', 'LIKE', "%{$name}%");
        }
        if (request('gender')) {
            $gender = request('gender');
            $q->where('gender', $gender);
        }
        if (request('teamID')) {
            $teamID = request('teamID');
            $q->where('teamID', $teamID);
        }
        if (request('year')) {
            $year = request('year');
            $q->where('year', $year);
        }
        if (request('ageGroupID')) {
            $ageGroupID = request('ageGroupID');
            $q->where('ageGroupID', $ageGroupID);
        }

        return $q;
    }
}
