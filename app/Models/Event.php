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

}