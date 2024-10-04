<?php

namespace App\Http\Livewire\Operational;

use App\Models\Pesanan;
use App\Models\PesananHistory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ListVehicle extends Component
{
    public $dummy;
    public $data;
    public $name;
    public $idpool;
    public $list_mobil = [];
    public $list_mobil_recomendation = [];

    // Form
    public $nama_pemesan;
    public $nomor_pemesan;
    public $nama_tamu;
    public $nomor_tamu;
    public $paket;
    public $is_pesanan_tamu;
    public $idtipe_mobil;
    public $idmobil;
    public $layanan_id;
    public $total_km;
    public $idmitra;
    public $tgl_mulai;
    public $tgl_selesai;
    public $penjemputan;
    public $tujuan;
    public $lokasi_turun;
    public $estimasi_km;
    public $estimasi_bbm;
    public $estimasi_tol_parkir;
    public $is_paket;
    public $detail_mobil = [];

    protected $rules = [
        'nama_pemesan' => 'required',
        'nomor_pemesan' => 'required',
        'nama_tamu' => 'required_if:is_pesanan_tamu,true',
        'nomor_tamu' => 'required_if:is_pesanan_tamu,true',
    ];

    public function mount()
    {
        if (request()->filled('idtipe_mobil')) {
            $this->idtipe_mobil = request('idtipe_mobil');
        }
        if (request()->filled('idmitra')) {
            $this->idmitra = request('idmitra');
        }
        if (request()->filled('tgl_mulai')) {
            $this->tgl_mulai = request('tgl_mulai');
        }
        if (request()->filled('tgl_selesai')) {
            $this->tgl_selesai = request('tgl_selesai');
        }
        if (request()->filled('penjemputan')) {
            $this->penjemputan = request('penjemputan');
        }
        if (request()->filled('tujuan')) {
            $this->tujuan = request('tujuan');
        }
        if (request()->filled('idpool')) {
            $this->idpool = request('idpool');
        }
        if (request()->filled('layanan_id')) {
            $this->layanan_id = request('layanan_id');
        }
        if (request()->filled('total_km')) {
            $this->total_km = request('total_km');
        }
        if (request()->filled('lokasi_turun')) {
            $this->lokasi_turun = request('lokasi_turun');
        }

        // dd($this->all());
        if ($this->layanan_id == 1) {
            $response = Http::withHeaders([
                'app_id' => 'A.M.V1.0',
                'auth_key' => 'YvcR--78-cwFBw6SW8uCegs7SP__gYa_',
            ])->post('https://api-order.thrubus.co.id/admin-mitra-order/layanan', [
                'idtipe_mobil' => $this->idtipe_mobil,
                'idmitra' => auth()->user()->mitra_id,
                "tgl_mulai" => $this->tgl_mulai,
                "tgl_selesai" => $this->tgl_selesai,
                "penjemputan" => $this->penjemputan,
                "lokasi_turun" => $this->lokasi_turun,
                "tujuan" => $this->tujuan
            ]);

            $responseData = $response->json();

            if ($response->successful()) {
                $this->data = $responseData;
                // dd($responseData); // Success return
            } else {
                $error = $responseData['error'];
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Error', 'message' => $error['message']]);
                return redirect()->route('operational.list-vehicle');
                // dd($error['message']);
            }

            foreach ($this->data['data']['mobil'] as $key => $value) {
                if ($value['idmitra'] == $this->idmitra && $value['idpool'] == $this->idpool) {
                    $value['is_paket'] = false;
                    $this->list_mobil_recomendation[] = $value;
                } else {
                    if ($value['idmitra'] == $this->idmitra) {
                        $value['is_paket'] = false;
                        $this->list_mobil[] = $value;
                    }
                }
            }
        }

        if ($this->layanan_id == 2) {
            $response = Http::withHeaders([
                'app_id' => 'A.M.V1.0',
                'auth_key' => 'YvcR--78-cwFBw6SW8uCegs7SP__gYa_',
            ])->post('https://api-order.thrubus.co.id/admin-mitra-order/dropoff', [
                'idtipe_mobil' => $this->idtipe_mobil,
                'idmitra' => $this->idmitra,
                "tgl_mulai" => $this->tgl_mulai,
                "tgl_selesai" => $this->tgl_selesai,
                "penjemputan" => $this->penjemputan,
                "tujuan" => $this->tujuan
            ]);

            $responseData = $response->json();

            if ($response->successful()) {
                $this->data = $responseData;
                // dd($responseData); // Success return
            } else {
                $error = $responseData['error'];
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Error', 'message' => $error['message']]);
                return redirect()->route('operational.list-vehicle');
                // dd($error['message']);
            }

            foreach ($this->data['data']['mobil'] as $key => $value) {
                if ($value['idmitra'] == $this->idmitra && $value['idpool'] == $this->idpool) {
                    $value['is_paket'] = false;
                    $this->list_mobil_recomendation[] = $value;
                } else {
                    if ($value['idmitra'] == $this->idmitra) {
                        $value['is_paket'] = false;
                        $this->list_mobil[] = $value;
                    }
                }
            }
        }
    }

    public function updated($val)
    {
        if ($val == 'idpool') {
            $this->list_mobil = [];
            $this->list_mobil_recomendation = [];
            foreach ($this->data['data']['mobil'] as $key => $value) {
                if ($value['idmitra'] == $this->idmitra && $value['idpool'] == $this->idpool) {
                    $value['is_paket'] = false;
                    $this->list_mobil_recomendation[] = $value;
                }
                // else {
                //     if ($value['idmitra'] == $this->idmitra) {
                //         $value['is_paket'] = false;
                //         $this->list_mobil[] = $value;
                //     }
                // }
            }
            // dd($this->list_mobil, $this->idpool, $this->data['data'], $this->list_mobil_recomendation);
        }

        if ($val == 'is_pesanan_tamu') {
            $this->reset(['nama_tamu', 'nomor_tamu']);
        }
    }

    public function getVehicle($id, $paket)
    {
        foreach ($this->list_mobil as $key => $value) {
            if ($value['idmobil'] == $id) {
                $value['is_paket'] = $paket;
                $this->detail_mobil = $value;
            }
        }
        foreach ($this->list_mobil_recomendation as $key => $value) {
            if ($value['idmobil'] == $id) {
                $value['is_paket'] = $paket;
                $this->detail_mobil = $value;
            }
        }
    }

    public function submit()
    {
        DB::beginTransaction();

        try {

            if ($this->layanan_id == 1) {
                $tujuan = $this->lokasi_turun;
                $banyak_tujuan = json_encode($this->tujuan);
                $harga = $this->detail_mobil['is_paket'] == true ? (int)$this->detail_mobil['harga_persentase'] : (int)$this->detail_mobil['harga_fix'];
            } else {
                $tujuan = $this->tujuan;
                $banyak_tujuan = null;
                $harga = (int)$this->detail_mobil['harga'];
            }

            $track_mobil = [
                [
                    'lokasi' => $this->detail_mobil['nama_pool'],
                    'waktu' => $this->tgl_mulai,
                    'deskripsi' => 'Start Pool',
                    'warna' => 'red'
                ],
                [
                    'lokasi' => $this->penjemputan,
                    'waktu' => $this->tgl_mulai,
                    'deskripsi' => 'Lokasi Penjemputan',
                    'warna' => 'yellow'
                ],
                [
                    'lokasi' => $tujuan,
                    'waktu' => $this->tgl_selesai,
                    'deskripsi' => 'Lokasi Turun',
                    'warna' => 'yellow'
                ],
                [
                    'lokasi' => $this->detail_mobil['nama_pool'],
                    'waktu' => $this->tgl_selesai,
                    'deskripsi' => 'End Pool',
                    'warna' => 'red'
                ],
            ];

            if ($this->layanan_id == 1) {
                $newElements = [];
                foreach ($this->tujuan as $key => $value) {
                    $newElements[] = [
                        'lokasi' => $value,
                        'deskripsi' => 'Lokasi Tujuan',
                        'warna' => 'green'
                    ];
                }
                array_splice($track_mobil, 2, 0, $newElements);
            }

            $validatedData = $this->validate();

            if ($this->is_pesanan_tamu == true) {
                $nama_tamu = $this->nama_tamu;
                $nomor_tamu = $this->nomor_tamu;
            } else {
                $this->is_pesanan_tamu = false;
                $nama_tamu = null;
                $nomor_tamu = null;
            }
            // dd($this->all());
            $paket = $this->detail_mobil['is_paket'] == true ? 'Paket Driver & BBM' : null;
            // $this->reset(['nama_pemesan', 'nomor_pemesan', 'is_pesanan_tamu', 'nama_tamu', 'nomor_tamu']);
            $pesan = Pesanan::create([
                'kode' => Str::random(10),
                'nama_pemesan' => $this->nama_pemesan,
                'nomor_pemesan' => $this->nomor_pemesan,
                'nama_tamu' => $nama_tamu,
                'nomor_tamu' => $nomor_tamu,
                'is_pesanan_tamu' => $this->is_pesanan_tamu,
                'idtipe_mobil' => $this->detail_mobil['idtipe_mobil'] ?? $this->idtipe_mobil,
                'harga' => $harga,
                'paket' => $paket,
                // 'idmobil' => $this->idmobil,
                'layanan_id' => $this->layanan_id,
                'idmitra' => $this->idmitra,
                'idpool' => $this->idpool,
                'tgl_mulai' => date('Y-m-d H:i:s', strtotime($this->tgl_mulai)),
                'tgl_selesai' => date('Y-m-d H:i:s', strtotime($this->tgl_selesai)),
                'penjemputan' => $this->penjemputan,
                'tujuan' => $tujuan,
                'banyak_tujuan' => $banyak_tujuan,
                'track_mobil' => json_encode($track_mobil),
                'estimasi_km' => $this->total_km ?? 0,
                'estimasi_bbm' => $this->estimasi_bbm ?? 0,
                'estimasi_tol_parkir' => $this->estimasi_tol_parkir ?? 0,
                'create_by' => auth()->id(),
            ]);

            PesananHistory::create([
                'pesanan_id' => $pesan->id,
                'status' => 'Draft',
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Success', 'message' => 'Berhasil membuat pesanan']);
            return redirect()->route('operational.detail-order', [$pesan->id]);
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Error', 'message' => $e->getMessage()]);
            // return redirect()->route('operational.list-vehicle');
        }
    }

    public function render()
    {
        return view('livewire.operational.list-vehicle');
    }
}
