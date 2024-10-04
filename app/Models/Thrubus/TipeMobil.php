<?php

namespace App\Models\Thrubus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeMobil extends Model
{

    protected $table = 'tipe_mobil';

    protected $connection = 'mysql2';
    use HasFactory;


}
