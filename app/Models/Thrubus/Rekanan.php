<?php

namespace App\Models\Thrubus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekanan extends Model
{
    use HasFactory;

    protected $table = 'rekanan_mitra';
    protected $connection = 'mysql2';

    protected $fillable = [
        'idrekanan_mitra',
        'nama_rekanan',
        'alamat_rekanan',
        'no_hp_rekanan',
        'create_at',
        'mitra_idmitra'
    ];

}
