<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamSecond extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';

    protected $table = 'teams';

    protected $guarded = [];

    public $timestamps = false;

}