<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoringprevSecond extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';

    protected $table = 'scoringprev';

    protected $guarded = [];

}