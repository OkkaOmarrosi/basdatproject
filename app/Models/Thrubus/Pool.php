<?php

namespace App\Models\Thrubus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pool extends Model
{

    protected $table = 'pool';

    protected $connection = 'mysql2';
    use HasFactory;


}
