<?php

namespace App\Http\Livewire\Operational;

use App\Models\MobilRekanan;
use App\Models\Pesanan;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class KalenderPesanan extends Component
{

    public $data;

    public function mount()
    {
        $this->data = Pesanan::with('rekan')
        ->select('id', 'kode', 'tgl_mulai', 'tgl_selesai', 'mobil_rekan', 'idmobil', 'status', 'idrekanan')
        ->where('idmitra', auth()->user()->mitra_id)
        ->where('status', '!=', 'Cancel')->get();

        foreach ($this->data as $value) {
            if ($value->idmobil != null) {
                $value->nopol = DB::connection('mysql2')->table('mobil')->where('idmobil', $value->idmobil)->first()->nopol ?? '';
            } else {
                $value->nopol = MobilRekanan::find($value->mobil_rekan)->nopol ?? '';
            }
            $value->tgl_mulai = date('Y-m-d', strtotime($value->tgl_mulai));
            $value->tgl_selesai = date('Y-m-d', strtotime($value->tgl_selesai));
        }

        // dd($this->data);
    }

    public function render()
    {
        return view('livewire.operational.kalender-pesanan');
    }
}
