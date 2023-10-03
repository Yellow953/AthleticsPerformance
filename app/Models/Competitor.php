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
}