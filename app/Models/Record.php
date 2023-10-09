<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $guarded = [];

    public function athlete()
    {
        return $this->belongsTo(Athlete::class, 'athleteID');
    }

    public function result()
    {
        return $this->belongsTo(Result::class, 'resultID');
    }

    // Filter
    public function scopeFilter($q)
    {
        if (request('competitor')) {
            $competitor = request('competitor');
            $q->where('competitor', 'LIKE', "%{$competitor}%");
        }
        if (request('extra')) {
            $extra = request('extra');
            $q->where('extra', 'LIKE', "%{$extra}%");
        }
        if (request('name')) {
            $name = request('name');
            $q->where('name', 'LIKE', "%{$name}%");
        }
        if (request('date')) {
            $date = request('date');
            $q->where('date', $date);
        }
        if (request('ageGroupID')) {
            $ageGroupID = request('ageGroupID');
            $q->where('ageGroupID', $ageGroupID);
        }
        if (request('gender')) {
            $gender = request('gender');
            $q->where('gender', $gender);
        }
        if (request('typeID')) {
            $typeID = request('typeID');
            $q->where('typeID', $typeID);
        }
        if (request('teamID')) {
            $teamID = request('teamID');
            $q->where('teamID', $teamID);
        }
 
        return $q;
    }

}