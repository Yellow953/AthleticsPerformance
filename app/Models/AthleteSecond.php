<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AthleteSecond extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';

    protected $table = 'athletes';

    protected $guarded = [];

    public $timestamps = false;

}