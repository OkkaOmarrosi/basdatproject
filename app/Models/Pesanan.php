<?php

namespace App\Models;

use App\Models\Thrubus\Driver;
use App\Models\Thrubus\Rekanan;
use App\Models\Thrubus\TipeMobil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'kode',
        'nama_pemesan',
        'nomor_pemesan',
        'nama_tamu',
        'nomor_tamu',
        'paket',
        'is_pesanan_tamu',
        'idtipe_mobil',
        'idmobil',
        'iddriver',
        'layanan_id',
        'idmitra',
        'idpool',
        'tgl_mulai',
        'tgl_selesai',
        'penjemputan',
        'tujuan',
        'track_mobil',
        'estimasi_km',
        'estimasi_bbm',
        'estimasi_tol_parkir',
        'harga',
        'idrekanan',
        'mobil_rekan',
        'status',
        'create_by',
        'banyak_tujuan',
        'alamat_pemesan',
        'description'
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'iddriver', 'iddriver');
    }
    public function created_by()
    {
        return $this->belongsTo(User::class, 'create_by', 'id');
    }
    public function tipe_mobil()
    {
        return $this->belongsTo(TipeMobil::class, 'idtipe_mobil', 'idtipe_mobil');
    }
    public function pesanan_pengeluaran()
    {
        return $this->hasMany(PesananPengeluaran::class, 'header_id', 'id');
    }
    public function pesanan_pembayaran()
    {
        return $this->hasMany(PesananPembayaran::class, 'header_id', 'id');
    }
    public function pesanan_checklist()
    {
        return $this->hasMany(PesananChecklist::class, 'header_id', 'id');
    }
    public function pesanan_history()
    {
        return $this->hasMany(PesananHistory::class, 'pesanan_id', 'id');
    }
    public function mobil_rekanan()
    {
        return $this->belongsTo(MobilRekanan::class, 'mobil_rekan', 'id');
    }
    public function rekan()
    {
        return $this->belongsTo(Rekanan::class, 'idrekanan', 'idrekanan_mitra');
    }
}
