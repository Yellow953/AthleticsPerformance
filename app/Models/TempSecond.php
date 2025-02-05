<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempSecond extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';

    protected $table = 'temp';

    protected $guarded = [];

    public $timestamps = false;

}