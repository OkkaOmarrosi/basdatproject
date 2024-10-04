<?php

namespace App\Http\Livewire\Operational;

use Livewire\Component;
use Illuminate\Support\Facades\Http;


class CheckPrice extends Component
{
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
    }

    public function updated($val)
    {
        if ($val == 'layanan_id') {
            $this->reset(['date_mulai', 'time_mulai', 'date_selesai', 'time_selesai', 'address_from', 'address_to', 'destination']);
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
            'date_selesai' => 'required_if:layanan_id,1',
            'time_selesai' => 'required_if:layanan_id,1',
            'address_from' => 'required',
            'address_to' => 'required',
        ]);
        if ($this->layanan_id == 1) {
            $response = Http::withHeaders([
                'app_id' => 'A.M.V1.0',
                'auth_key' => 'YvcR--78-cwFBw6SW8uCegs7SP__gYa_',
            ])->post('https://api-order.thrubus.co.id/admin-mitra-order/layanan', [
                'idtipe_mobil' => $this->idtipe_mobil,
                'idmitra' => auth()->user()->mitra_id,
                "tgl_mulai" => $this->date_mulai . ' ' . $this->time_mulai . ':00',
                "tgl_selesai" => $this->date_selesai . ' ' . $this->time_selesai . ':00',
                "penjemputan" => $this->address_from,
                "lokasi_turun" => $this->address_to,
                "tujuan" => $this->destination
            ]);

            $responseMaps = Http::withHeaders([
                'app_id' => 'A.M.V1.0',
                'auth_key' => 'YvcR--78-cwFBw6SW8uCegs7SP__gYa_',
            ])->post('https://api-order.thrubus.co.id/google-map/directions', [
                'origin' => $this->address_from,
                'destination' => $this->address_to,
                'waypoints' => $this->destination
            ]);

            $responseEstimasi = $responseMaps->json();
            $responseData = $response->json();

            if ($response->successful() && $responseMaps->successful()) {

                $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Success', 'message' => 'Berhasil mendapatkan estimasi harga']);
                return to_route('operational.list-vehicle', [
                    'idtipe_mobil' => $this->idtipe_mobil,
                    'idmitra' => auth()->user()->mitra_id,
                    "tgl_mulai" => $this->date_mulai . ' ' . $this->time_mulai . ':00',
                    "tgl_selesai" => $this->date_selesai . ' ' . $this->time_selesai . ':00',
                    "penjemputan" => $this->address_from,
                    "lokasi_turun" => $this->address_to,
                    "tujuan" => $this->destination,
                    "layanan_id" => $this->layanan_id,
                    "idpool" => auth()->user()->pool_id ?? 5,
                    'total_km' => $responseEstimasi['data']['total_km']
                ]);
            } else {
                $error = $responseData['error'];
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Error', 'message' => $error['message']]);
                // dd($error['message']);
            }
        }

        if ($this->layanan_id == 2) {
            // for dropoff cek if the vehicle is available nad the request is success
            $response = Http::withHeaders([
                'app_id' => 'A.M.V1.0',
                'auth_key' => 'YvcR--78-cwFBw6SW8uCegs7SP__gYa_',
            ])->post('https://api-order.thrubus.co.id/admin-mitra-order/dropoff', [
                'idtipe_mobil' => $this->idtipe_mobil,
                'idmitra' => auth()->user()->mitra_id,
                "tgl_mulai" => $this->date_mulai . ' ' . $this->time_mulai . ':00',
                "penjemputan" => $this->address_from,
                "tujuan" => $this->address_to
            ]);

            // cek for get the distance
            $responseMaps = Http::withHeaders([
                'app_id' => 'A.M.V1.0',
                'auth_key' => 'YvcR--78-cwFBw6SW8uCegs7SP__gYa_',
            ])->post('https://api-order.thrubus.co.id/google-map/directions', [
                'origin' => $this->address_from,
                'destination' => $this->address_to,
                'waypoints' => [
                    ''
                ]
            ]);

            $responseEstimasi = $responseMaps->json();
            $responseData = $response->json();

            if ($response->successful() && $responseMaps->successful()) {

                $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Success', 'message' => 'Berhasil mendapatkan estimasi harga']);

                return to_route('operational.list-vehicle', [
                    'idtipe_mobil' => $this->idtipe_mobil,
                    'idmitra' => auth()->user()->mitra_id,
                    "tgl_mulai" => $this->date_mulai . ' ' . $this->time_mulai . ':00',
                    "tgl_selesai" => $this->date_selesai . ' ' . $this->time_selesai . ':00',
                    "penjemputan" => $this->address_from,
                    "tujuan" => $this->address_to,
                    "layanan_id" => $this->layanan_id,
                    "idpool" => auth()->user()->pool_id ?? 5,
                    'total_km' => $responseEstimasi['data']['total_km']
                ]);
            } else {
                $error = $responseData['error'];
                $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Error', 'message' => $error['message']]);
                // dd($error['message']);
            }
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
        return view('livewire.operational.check-price');
    }
}
