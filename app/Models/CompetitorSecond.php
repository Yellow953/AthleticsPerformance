<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitorSecond extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';

    protected $table = 'competitors';

    protected $guarded = [];

    public $timestamps = false;

}