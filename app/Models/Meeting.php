<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $guarded = [];

    public function ageGroup()
    {
        return $this->belongsTo(AgeGroupSecond::class, 'ageGroupID', 'ID');
    }

    public function type()
    {
        return $this->belongsTo(MeetingTypeSecond::class, 'typeID', 'ID');
    }

    // Filter
    public function scopeFilter($q)
    {
        if (request('name')) {
            $name = request('name');
            $q->where('name', 'LIKE', "%{$name}%")->orWhere('shortName', 'LIKE', "%{$name}%");
        }
        if (request('venue')) {
            $venue = request('venue');
            $q->where('venue', $venue);
        }
        if (request('ageGroupID')) {
            $ageGroupID = request('ageGroupID');
            $q->where('ageGroupID', $ageGroupID);
        }
        if (request('country')) {
            $country = request('country');
            $q->where('country', $country);
        }
        if (request('typeID')) {
            $typeID = request('typeID');
            $q->where('typeID', $typeID);
        }
        if (request('io')) {
            $io = request('io');
            $q->where('io', $io);
        }
        if (request('startDate') || request('endDate')) {
            $startDate = request()->query('startDate') ?? Carbon::today();
            $endDate = request()->query('endDate') ?? Carbon::today()->addYears(100);
            $q->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $q;
    }
}
