<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingSecond extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';

    protected $table = 'meetings';

    protected $guarded = [];

    public $timestamps = false;
}
