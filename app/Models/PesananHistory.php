<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananHistory extends Model
{
    use HasFactory;

    protected $table = 'pesanan_history';

    protected $fillable = [
        'pesanan_id',
        'status',
        'created_at',
        'updated_at',
        'created_by',
    ];

    public function created_by_()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id', 'id');
    }
}
