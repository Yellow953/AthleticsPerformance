<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoundSecond extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';

    protected $table = 'rounds';

    protected $guarded = [];

    public $timestamps = false;

}