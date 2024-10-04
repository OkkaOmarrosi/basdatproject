<?php

namespace App\Http\Livewire\Operational;

use App\Models\Pesanan;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CreateOrder extends Component
{
    public $idmobil;
    public $idtipe_mobil;
    public $layanan_id;
    public $date_mulai;
    public $time_mulai;
    public $date_selesai;
    public $time_selesai;
    public $address_from;
    public $address_to;
    public $destination = [''];


    public function mount()
    {
        if(request()->filled('date_mulai')) {
            $this->date_mulai = request('date_mulai');
        }

        if(request()->filled('idtipe_mobil')) {
            $this->idtipe_mobil = request('idtipe_mobil');
        }

        if(request()->filled('idmobil')) {
            $this->idmobil = request('idmobil');
        }
    }

    public function updated($val)
    {
        if ($val == 'layanan_id') {
            $this->reset(['date_selesai', 'time_selesai', 'address_to', 'destination', 'address_from']);
            $this->destination = [''];
        }
    }

    public function addDestination()
    {
        $this->destination[] = '';
    }

    public function removeDestination($key)
    {
        unset($this->destination[$key]);
        $this->destination = array_values($this->destination);
    }

    public function submit()
    {

        $this->validate([
            'idtipe_mobil' => 'required',
            'layanan_id' => 'required',
            'date_mulai' => 'required',
            'time_mulai' => 'required',
            'date_selesai' => 'required_if:layanan_id,1,3,4',
            'time_selesai' => 'required_if:layanan_id,1,3,4',
            'address_to' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $now = Carbon::now();
            $date_mulai = date_create($this->date_mulai . ' ' . $this->time_mulai . ':00');
            $date_selesai = date_create($this->date_selesai . ' ' . $this->time_selesai . ':00');

            if ($this->date_mulai != null) {
                if ($date_mulai < $now) {
                    return $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Error', 'message' => 'Waktu tidak boleh kurang dari sekarang']);
                }
            }

            if ($this->date_selesai != null && $this->date_mulai) {
                if ($date_selesai < $date_mulai) {
                    return $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Error', 'message' => 'Tanggal Selesai tidak boleh kurang dari sekarang']);
                }
            }
            // if ($this->layanan_id != 2) {
            //     if ($date_mulai > $date_selesai) {
            //         $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Error', 'message' => 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai']);
            //         return;
            //     }
            // }

            $pool_address = DB::connection('mysql2')->table('pool')->where('idpool', auth()->user()->pool_id)->first();
            $this->address_from = $pool_address->lokasi ?? "-";
            $name_pool = $pool_address->nama ?? "-";

            $track_mobil = [
                [
                    'lokasi' => $name_pool,
                    'waktu' => $this->date_mulai . ' ' . $this->time_mulai . ':00',
                    'deskripsi' => 'Start Pool',
                    'warna' => 'red'
                ],
                [
                    'lokasi' => $this->address_from,
                    'waktu' => $this->date_mulai . ' ' . $this->time_mulai . ':00',
                    'deskripsi' => 'Lokasi Penjemputan',
                    'warna' => 'yellow'
                ],
                [
                    'lokasi' => $this->address_to,
                    'waktu' => $this->date_selesai . ' ' . $this->time_selesai . ':00',
                    'deskripsi' => 'Lokasi Turun',
                    'warna' => 'yellow'
                ],
                [
                    'lokasi' => $name_pool,
                    'waktu' => $this->date_selesai . ' ' . $this->time_selesai . ':00',
                    'deskripsi' => 'End Pool',
                    'warna' => 'red'
                ],
            ];

            if ($this->layanan_id == 1) {
                $newElements = [];
                foreach ($this->destination as $key => $value) {
                    $newElements[] = [
                        'lokasi' => $value,
                        'deskripsi' => 'Lokasi Tujuan',
                        'warna' => 'green'
                    ];
                }
                array_splice($track_mobil, 2, 0, $newElements);
            }


            $responseMaps = Http::withHeaders([
                'app_id' => 'A.M.V1.0',
                'auth_key' => 'YvcR--78-cwFBw6SW8uCegs7SP__gYa_',
            ])->post('https://api-order.thrubus.co.id/google-map/directions', [
                'origin' => $this->address_from,
                'destination' => $this->address_to,
                'waypoints' => $this->destination
            ]);

            $responseEstimasi = $responseMaps->json();

            // dd($responseEstimasi, $this->all());

            if ($responseMaps->successful()) {

                $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Success', 'message' => 'Berhasil membuat pesanan']);

                if ($this->date_selesai == null) {
                    $tgl_selesai = $this->date_mulai . ' ' . $this->time_mulai . ':00';
                } else {
                    $tgl_selesai = $this->date_selesai . ' ' . $this->time_selesai . ':00';
                }

                $pesanan = Pesanan::insertGetId([
                    'kode' => Str::random(10),
                    'idtipe_mobil' => $this->idtipe_mobil,
                    'idmobil' => $this->idmobil ?? null,
                    'layanan_id' => $this->layanan_id,
                    'idmitra' => auth()->user()->mitra_id,
                    'idpool' => auth()->user()->pool_id ?? 5,
                    "tgl_mulai" => $this->date_mulai . ' ' . $this->time_mulai . ':00',
                    "tgl_selesai" => $tgl_selesai,
                    'penjemputan' => $this->address_from,
                    'tujuan' => $this->address_to,
                    'track_mobil' => json_encode($track_mobil),
                    'estimasi_km' => $this->total_km ?? 0,
                    'estimasi_bbm' => $responseEstimasi['data']['total_km'] ?? 0,
                    'estimasi_tol_parkir' => $this->estimasi_tol_parkir ?? 0,
                    'create_by' => auth()->id(),
                ]);
                DB::commit();
                return to_route('operational.detail-order', ['id' => $pesanan]);
            } else {
                DB::rollBack();
                $error = $responseEstimasi['error'];
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Error', 'message' => $error['message']]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Error', 'message' => $e->getMessage()]);
        }
    }

    public function submitDummy()
    {
        return to_route('operational.list-vehicle', [
            'idtipe_mobil' => 5,
            'idmitra' => 3,
            "tgl_mulai" => "2024-11-05 05:33:00",
            "tgl_selesai" => "2024-11-07 12:33:00",
            "penjemputan" => "Jakarta Selatan, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta, Indonesia",
            "tujuan" => "Malang, Kota Malang, Jawa Timur, Indonesia",
            "layanan_id" => 2,
            "idpool" => auth()->user()->pool_id ?? 5,
            'total_km' => 512
        ]);
    }
    public function render()
    {
        return view('livewire.operational.create-order');
    }
}
