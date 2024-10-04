<?php

namespace App\Http\Livewire\Operational;

use App\Models\Pesanan;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Order extends Component
{
    public $data;

    public function mount()
    {
        $query = Pesanan::query();
        $query->leftJoin('mobil_rekanan', 'pesanan.mobil_rekan', '=', 'mobil_rekanan.id');
        $externalTable = DB::raw(DB::connection('mysql2')->getDatabaseName() . '.mobil as external_mobil');
        $query->leftJoin($externalTable, 'pesanan.idmobil', '=', 'external_mobil.idmobil');

        $query->select('pesanan.*',
        DB::raw('CASE WHEN pesanan.idmobil IS NULL THEN mobil_rekanan.nopol ELSE external_mobil.nopol END as nopol')
        );

        $query->where('pesanan.idmitra', auth()->user()->mitra_id);

        $this->data = $query->get()->toArray();
    }

    public function render()
    {
        return view('livewire.operational.order');
    }
}
