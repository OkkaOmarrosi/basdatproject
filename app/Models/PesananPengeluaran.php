<?php

namespace App\Models;

use App\Models\Thrubus\ItemOrderPengeluaran;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananPengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pesanan_pengeluaran';

    protected $fillable = [
        'header_id',
        'iditem_order_pengeluaran',
        'jumlah',
        'created_at',
        'updated_at',
        'created_by'
    ];

    public function header()
    {
        return $this->belongsTo(Pesanan::class, 'header_id', 'id');
    }
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function order_pengeluaran()
    {
        return $this->belongsTo(ItemOrderPengeluaran::class, 'iditem_order_pengeluaran', 'iditem_order_pengeluaran');
    }
}
