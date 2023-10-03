<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSecond extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';

    protected $table = 'events';

    protected $guarded = [];

    public $timestamps = false;

    public function meeting()
    {
        return $this->belongsTo(MeetingSecond::class, 'meetingID');
    }

}