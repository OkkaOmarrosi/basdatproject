<?php

namespace App\Models\Thrubus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{

    protected $table = 'driver';

    protected $connection = 'mysql2';
    use HasFactory;


}
