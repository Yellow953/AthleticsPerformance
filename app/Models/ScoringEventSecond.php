<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoringEventSecond extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';

    protected $table = 'scoringevents';

    protected $guarded = [];

}