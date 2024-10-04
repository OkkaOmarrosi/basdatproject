<?php

namespace App\Models;

use App\Models\Thrubus\Rekanan;
use App\Models\Thrubus\TipeMobil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobilRekanan extends Model
{
    use HasFactory;

    protected $table = 'mobil_rekanan';

    protected $fillable = [
        'idrekanan_mitra',
        'nopol',
        'merk',
        'tipe',
        'tahun',
        'warna',
        'no_rangka',
        'no_mesin',
    ];

    public function rekanan()
    {
        return $this->belongsTo(Rekanan::class, 'idrekanan_mitra', 'idrekanan_mitra');
    }

    public function tipe_mobil()
    {
        return $this->belongsTo(TipeMobil::class, 'tipe', 'idtipe_mobil');
    }

}
