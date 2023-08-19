<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConvertEventExtraSecond extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';

    protected $table = 'converteventextra';

    protected $guarded = [];

    public $timestamps = false;

}