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

}