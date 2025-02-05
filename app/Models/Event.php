<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $guarded = [];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class, 'meetingID');
    }

    public function type()
    {
        return $this->belongsTo(EventTypeSecond::class, 'typeID', 'ID');
    }

    public function ageGroup()
    {
        return $this->belongsTo(AgeGroupSecond::class, 'ageGroupID', 'ID');
    }

    public function results()
    {
        return $this->hasMany(Result::class, 'eventID');
    }

    // Permissions
    public function can_delete()
    {
        return $this->results->count() == 0;
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
        if (request('ageGroupID')) {
            $ageGroupID = request('ageGroupID');
            $q->where('ageGroupID', $ageGroupID);
        }
        if (request('typeID')) {
            $typeID = request('typeID');
            $q->where('typeID', $typeID);
        }

        return $q;
    }
}
