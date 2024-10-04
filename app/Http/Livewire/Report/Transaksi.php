<?php

namespace App\Http\Livewire\Report;

use App\Models\PesananPembayaran;
use App\Models\PesananPengeluaran;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Transaksi extends Component
{
    public $list_pengeluaran = [];

    public $transaksi_keluar;
    public $transaksi_masuk;

    public $merge = [];

    public $total = 0;

    public $uang_masuk = 0;
    public $uang_keluar = 0;
    public $uang_total = 0;
    public $html = '';


    public $total_masuk_transfer = 0;
    public $total_masuk_cash = 0;
    public $total_keluar = 0;

    public $formData = [
        'start_date' => null,
        'end_date' => null,
        'search' => ''
    ];

    public $formTransaksi = [
        'type' => 1,
        'nominal' => 0,
        'keterangan' => null,
        'iditem_order_pengeluaran' => null,
    ];

    protected $listeners = [
        'addButton' => 'resetForm',
    ];

    public function mount()
    {
        $this->getData($this->formData);

        $listPengeluaran = DB::connection('mysql2')->table('item_order_pengeluaran')->get();
        $this->list_pengeluaran = $listPengeluaran->map(function ($item) {
            return (array) $item;
        })->toArray();
    }

    public function resetForm()
    {
        $this->formTransaksi = [
            'type' => 1,
            'nominal' => 0,
            'keterangan' => null,
            'iditem_order_pengeluaran' => null,
        ];
    }

    public function getData($formData)
    {
        $rw = '1=1';
        $rw2 = '1=1';

        if ($formData) {
            if ($formData['start_date'] != '' && $formData['end_date'] != '') {
                $rw .= " AND (DATE(created_at) >= '" . $formData['start_date'] . "' AND DATE(created_at) <= '" . $formData['end_date'] . "')";
                $rw2 .= " AND (DATE(created_at) >= '" . $formData['start_date'] . "' AND DATE(created_at) <= '" . $formData['end_date'] . "')";
            }
        }

        $this->transaksi_masuk = PesananPembayaran::with('header', 'created_by', 'status_pembayaran', 'methode_pembayaran')
            ->leftJoin('pesanan', 'pesanan.id', 'pesanan_pembayaran.header_id')
            ->where('pesanan.idmitra', auth()->user()->mitra_id)
            ->whereRaw($rw)
            ->get();

            $this->transaksi_keluar = PesananPengeluaran::with('header', 'created_by', 'order_pengeluaran')
            ->leftJoin('pesanan', 'pesanan.id', 'pesanan_pengeluaran.header_id')
            ->where('pesanan.idmitra', auth()->user()->mitra_id)
            ->whereRaw($rw2)
            ->get();

        $data = collect(array_merge($this->transaksi_masuk->toArray(), $this->transaksi_keluar->toArray()))
            ->sortByDesc('created_at')
            ->values()
            ->toArray();


        foreach ($data as $key => $value) {

            if (isset($value['nominal'])) {
                $data[$key]['warna'] = '#82d616';
                $data[$key]['harga'] = $value['nominal'];
            } else {
                $data[$key]['harga'] = $value['jumlah'];
                $data[$key]['warna'] = '#ea0606';
            }
            // if($value['nominal'])
            $data[$key]['tanggal_dibuat'] = Carbon::parse($value['created_at'])->format('d-m-Y H:i:s');
        }

        $this->merge = $data;
        // dd($this->merge);

        $this->total = count($this->transaksi_masuk) + count($this->transaksi_keluar);

        $this->uang_masuk = PesananPembayaran::whereRaw($rw)->sum('nominal');
        $this->uang_keluar = PesananPengeluaran::whereRaw($rw2)->sum('jumlah');
        $this->uang_total = $this->uang_masuk - $this->uang_keluar;

        if ($this->uang_total < 0) {
            $this->html = 'red';
        } else {
            $this->html = 'green';
        }

        $this->total_masuk_cash = PesananPembayaran::whereRaw($rw)->where('iditem_methode_pembayaran', 1)->sum('nominal');
        $this->total_masuk_transfer = PesananPembayaran::whereRaw($rw)->where('iditem_methode_pembayaran', 2)->sum('nominal');

        $this->total_keluar = PesananPengeluaran::whereRaw($rw2)->sum('jumlah');
        // dd($this->merge);
    }

    public function filter()
    {
        try {
            $this->validate([
                'formData.start_date' => 'required',
                'formData.end_date' => 'required',
            ], [
                'formData.start_date.required' => 'Tanggal
            Mulai tidak boleh kosong',
                'formData.end_date.required' => 'Tanggal
            Akhir tidak boleh kosong',
            ]);

            $this->getData($this->formData);
            // $this->search($this->formData);
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil difilter']);
        } catch (\Throwable $th) {
            // $this->search($this->formData);
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $th->getMessage()]);
            // throw $th;
        }
    }

    public function resetFilter()
    {
        $this->formData = [
            'start_date' => '',
            'end_date' => '',
        ];
        $this->getData($this->formData);
    }

    public function search($data)
    {
        $this->formData['search'] = $data;
        $this->getData($this->formData);
    }

    public function submitTransaksi()
    {
        try {
            $this->validate([
                'formTransaksi.type' => 'required',
                'formTransaksi.nominal' => 'required',
                'formTransaksi.keterangan' => 'required_-if:formTransaksi.type,1',
                'formTransaksi.iditem_order_pengeluaran' => 'required_-if:formTransaksi.type,2',
            ], [
                'formTransaksi.type' => 'Tipe transaksi tidak boleh kosong',
                'formTransaksi.nominal' => 'Nominal tidak boleh kosong',
                'formTransaksi.keterangan' => 'Keterangan tidak boleh kosong',
                'formTransaksi.iditem_order_pengeluaran' => 'Keterangan Pengeluaran tidak boleh kosong',
            ]);

            if ($this->formTransaksi['type'] == 1) {
                PesananPembayaran::create([
                    'iditem_status_pembayaran' => 1,
                    'iditem_methode_pembayaran' => 1,
                    'nominal' => $this->formTransaksi['nominal'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'created_by' => auth()->user()->id,
                    'keterangan' => $this->formTransaksi['keterangan'],
                ]);
            } else {
                PesananPengeluaran::create([
                    'iditem_order_pengeluaran' => $this->formTransaksi['iditem_order_pengeluaran'],
                    'jumlah' => $this->formTransaksi['nominal'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'created_by' => auth()->user()->id,
                ]);
            }

            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil Dibuat']);
            $this->resetForm();
            $this->getData($this->formData);
            $this->dispatchBrowserEvent('close-modal');
        } catch (\Throwable $th) {
            // $this->search($this->formData);
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $th->getMessage()]);
            // throw $th;
        }
    }



    public function render()
    {
        return view('livewire.report.transaksi');
    }
}
