<?php

namespace App\Models;

use App\Models\Thrubus\ItemMethodePembayaran;
use App\Models\Thrubus\ItemOrderPengeluaran;
use App\Models\Thrubus\ItemStatusPembayaran;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananPembayaran extends Model
{
    use HasFactory;

    protected $table = 'pesanan_pembayaran';

    protected $fillable = [
        'header_id',
        'iditem_status_pembayaran',
        'iditem_methode_pembayaran',
        'nominal',
        'created_at',
        'updated_at',
        'created_by',
        'keterangan',
    ];

    public function header()
    {
        return $this->belongsTo(Pesanan::class, 'header_id', 'id');
    }
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function status_pembayaran()
    {
        return $this->belongsTo(ItemStatusPembayaran::class, 'iditem_status_pembayaran', 'iditem_status_pembayaran');
    }
    public function methode_pembayaran()
    {
        return $this->belongsTo(ItemMethodePembayaran::class, 'iditem_methode_pembayaran', 'iditem_methode_pembayaran');
    }
}
