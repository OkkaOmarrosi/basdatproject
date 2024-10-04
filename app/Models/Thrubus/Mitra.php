<?php

namespace App\Models\Thrubus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    protected $table = 'mitra';

    protected $connection = 'mysql2';
    use HasFactory;
}
