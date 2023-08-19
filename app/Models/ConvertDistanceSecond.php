<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConvertDistanceSecond extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';

    protected $table = 'convertdistances';

    protected $guarded = [];

    public $timestamps = false;

}