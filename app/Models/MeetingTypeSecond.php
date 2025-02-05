<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingTypeSecond extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';

    protected $table = 'meetingtypes';

    protected $guarded = [];

    public $timestamps = false;

}