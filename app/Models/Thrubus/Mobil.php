<?php

namespace App\Models\Thrubus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{

    protected $table = 'mobil';

    protected $primaryKey = 'idmobil';

    protected $connection = 'mysql2';
    use HasFactory;


    public function tipe_mobil()
    {
        return $this->belongsTo(TipeMobil::class,'tipe_mobil_idtipe_mobil' , 'idtipe_mobil');
    }

}
