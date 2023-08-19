<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordSecond extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';

    protected $table = 'records';

    protected $guarded = [];

    public $timestamps = false;

}