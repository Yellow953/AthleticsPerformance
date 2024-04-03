<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Athlete extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $guarded = [];

    // Filter
    public function scopeFilter($q)
    {
        if (request('firstName')) {
            $firstName = request('firstName');
            $q->where('firstName', 'LIKE', "%{$firstName}%");
        }
        if (request('lastName')) {
            $lastName = request('lastName');
            $q->where('lastName', 'LIKE', "%{$lastName}%");
        }
        if (request('gender')) {
            $gender = request('gender');
            $q->where('gender', $gender);
        }
        if (request('dob')) {
            $dob = request('dob');
            $q->where('dob', $dob);
        }

        return $q;
    }
}
