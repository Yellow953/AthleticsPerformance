<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IOSecond extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';

    protected $table = 'ios';

    protected $guarded = [];

    public $timestamps = false;

}