<?php

namespace App\Http\Livewire\Report;

use App\Models\PesananPembayaran;
use App\Models\PesananPengeluaran;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Keuangan extends Component
{

    public $data = [];
    public $data_search = [
        [
            'kode' => 24
        ]
    ];
    public $data_status = [
        'Draft' => [],
        'Booking' => [],
        'Terjadwal' => [],
        'Proses' => [],
        'Close' => [],
        'Expired' => [],
        'Off' => [],
        'Cancel' => [],
    ];

    public $filterForm = [
        'start_date' => '',
        'end_date' => '',
    ];

    public $search;

    public $total_harga = 0;
    public $total_pembayaran = 0;
    public $total_belum_lunas = 0;
    public $total_pengeluaran = 0;
    public $total_laba_bersih = 0;

    public $count_draft = 0;
    public $count_booking = 0;
    public $count_terjadwal = 0;
    public $count_proses = 0;
    public $count_close = 0;
    public $count_expired = 0;
    public $count_off = 0;
    public $count_cancel = 0;

    public $total_draft = 0;
    public $total_booking = 0;
    public $total_terjadwal = 0;
    public $total_proses = 0;
    public $total_close = 0;
    public $total_expired = 0;
    public $total_off = 0;
    public $total_cancel = 0;

    public function mount()
    {
        // if (request()->filled('start_date')) {
        //     $this->filterForm['start_date'] = request('start_date');
        // }
        // if (request()->filled('end_date')) {
        //     $this->filterForm['end_date'] = request('end_date');
        // }

        $this->getPesananByUser($this->filterForm);
        $this->search($this->filterForm);
    }

    public function filter()
    {
        try {
            $this->validate([
                'filterForm.start_date' => 'required',
                'filterForm.end_date' => 'required',
            ], [
                'filterForm.start_date.required' => 'Tanggal
            Mulai tidak boleh kosong',
                'filterForm.end_date.required' => 'Tanggal
            Akhir tidak boleh kosong',
            ]);

            $this->getPesananByUser($this->filterForm);
            $this->search($this->filterForm);
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil difilter']);
        } catch (\Throwable $th) {
            $this->search($this->filterForm);
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $th->getMessage()]);
            // throw $th;
        }
    }

    public function resetFilter()
    {
        $this->filterForm = [
            'start_date' => '',
            'end_date' => '',
        ];
        $this->search($this->filterForm);
    }

    public function search($filterForm = null)
    {

        $wr = '1=1';

        if ($filterForm) {
            if ($filterForm['start_date'] != '' && $filterForm['end_date'] != '') {
                $wr .= " AND (created_at >= '" . $filterForm['start_date'] . "' AND created_at <= '" . $filterForm['end_date'] . "')";
            }
        }

        if (auth()->user()->id == 1) {
            $this->data_search = DB::table('pesanan')->whereRaw($wr)
            ->where('nama_pemesan', 'like', '%' . $this->search . '%')
            ->orWhere('nomor_pemesan', 'like', '%' . $this->search . '%')
            ->orWhere('created_at', 'like', '%' . $this->search . '%')
            ->orWhere('harga', 'like', '%' . $this->search . '%')
            ->orWhere('status', 'like', '%' . $this->search . '%')
            ->get()->toArray();
        } else {
            $this->data_search = DB::table('pesanan')->whereRaw($wr)->where('create_by', auth()->user()->id)
            ->where('nama_pemesan', 'like', '%' . $this->search . '%')
            ->orWhere('nomor_pemesan', 'like', '%' . $this->search . '%')
            ->orWhere('created_at', 'like', '%' . $this->search . '%')
            ->orWhere('harga', 'like', '%' . $this->search . '%')
            ->orWhere('status', 'like', '%' . $this->search . '%')
            ->get()->toArray();
        }

        // $this->data_search = array_filter($this->data_search, function ($item) use ($this->search) {
        //     return false !== stripos($item->nama, $this->search);
        // });
    }

    public function getPesananByUser($filterForm = null)
    {
        $wr = '1=1';
        // dd($filterForm);
        $this->data = [];
        $this->data_search = [];
        $this->data_status = [
            'Draft' => [],
            'Booking' => [],
            'Terjadwal' => [],
            'Proses' => [],
            'Close' => [],
            'Expired' => [],
            'Off' => [],
            'Cancel' => [],
        ];

        if ($filterForm) {
            if ($filterForm['start_date'] != '' && $filterForm['end_date'] != '') {
                $wr .= " AND (created_at >= '" . $filterForm['start_date'] . "' AND created_at <= '" . $filterForm['end_date'] . "')";
            }
        }

        if (auth()->user()->id == 1) {
            $this->data = DB::table('pesanan')->whereRaw($wr)->get()->toArray();
            // $this->data_search = DB::table('pesanan')->whereRaw($wr)->get()->toArray();
        } else {
            $this->data = DB::table('pesanan')->whereRaw($wr)->where('create_by', auth()->user()->id)->get()->toArray();
            // $this->data_search = DB::table('pesanan')->whereRaw($wr)->where('create_by', auth()->user()->id)->get()->toArray();
        }

        $total_pembayaran = 0;
        $total_pengeluaran = 0;
        $total_laba_bersih = 0;

        foreach ($this->data as $key => $value) {
            $this->data[$key]->total_pengeluaran = PesananPengeluaran::whereHeaderId($value->id)->sum('jumlah');
            $this->data[$key]->total_bayar = PesananPembayaran::whereHeaderId($value->id)->where('iditem_status_pembayaran', 1)->sum('nominal');
            $this->data[$key]->kurang_bayar = $this->data[$key]->harga - $this->data[$key]->total_bayar;
            $this->data[$key]->laba_bersih = $this->data[$key]->total_bayar - ($this->data[$key]->total_pengeluaran + $this->data[$key]->kurang_bayar);

            if ($this->data[$key]->kurang_bayar < 1) {
                $this->data[$key]->status_pesanan = 'Lunas';
                $total_pembayaran += $this->data[$key]->total_bayar;
            } else {
                $this->data[$key]->status_pesanan = 'Belum Lunas';
                $total_pembayaran += 0;
            }

            $total_pengeluaran += $this->data[$key]->total_pengeluaran;
            $total_laba_bersih += $this->data[$key]->laba_bersih;

            if (strtoupper($value->status) == strtoupper('Draft')) {
                $value->warna = 'bg-danger';
                $this->data_status['Draft'][] = $value;
            }

            if (strtoupper($value->status) == strtoupper('Booking')) {
                $value->warna = 'bg-warning';
                $this->data_status['Booking'][] = $value;
            }

            if (strtoupper($value->status) == strtoupper('Terjadwal')) {
                $value->warna = 'bg-success';
                $this->data_status['Terjadwal'][] = $value;
            }

            if (strtoupper($value->status) == strtoupper('Proses')) {
                $value->warna = 'bg-primary';
                $this->data_status['Proses'][] = $value;
            }

            if (strtoupper($value->status) == strtoupper('Close')) {
                $value->warna = 'bg-info';
                $this->data_status['Close'][] = $value;
            }

            if (strtoupper($value->status) == strtoupper('Expired')) {
                $value->warna = 'bg-secondary';
                $this->data_status['Expired'][] = $value;
            }

            if (strtoupper($value->status) == strtoupper('Off')) {
                $value->warna = 'bg-secondary';
                $this->data_status['Off'][] = $value;
            }

            if (strtoupper($value->status) == strtoupper('Cancel')) {
                $value->warna = 'bg-warning';
                $this->data_status['Cancel'][] = $value;
            }
        }


        $this->count_draft = count($this->data_status['Draft']);
        $this->count_booking = count($this->data_status['Booking']);
        $this->count_terjadwal = count($this->data_status['Terjadwal']);
        $this->count_proses = count($this->data_status['Proses']);
        $this->count_close = count($this->data_status['Close']);
        $this->count_expired = count($this->data_status['Expired']);
        $this->count_off = count($this->data_status['Off']);
        $this->count_cancel = count($this->data_status['Cancel']);

        $this->total_draft = 0;
        foreach ($this->data_status['Draft'] as $key => $value) {
            $this->total_draft += $value->harga ?? 0;
        }

        $this->total_booking = 0;
        foreach ($this->data_status['Booking'] as $key => $value) {
            $this->total_booking += $value->harga ?? 0;
        }

        $this->total_terjadwal = 0;
        foreach ($this->data_status['Terjadwal'] as $key => $value) {
            $this->total_terjadwal += $value->harga ?? 0;
        }

        $this->total_proses = 0;
        foreach ($this->data_status['Proses'] as $key => $value) {
            $this->total_proses += $value->harga ?? 0;
        }

        $this->total_close = 0;
        foreach ($this->data_status['Close'] as $key => $value) {
            $this->total_close += $value->harga ?? 0;
        }

        $this->total_expired = 0;
        foreach ($this->data_status['Expired'] as $key => $value) {
            $this->total_expired += $value->harga ?? 0;
        }

        $this->total_off = 0;
        foreach ($this->data_status['Off'] as $key => $value) {
            $this->total_off += $value->harga ?? 0;
        }

        $this->total_cancel = 0;
        foreach ($this->data_status['Cancel'] as $key => $value) {
            $this->total_cancel += $value->harga ?? 0;
        }

        $this->total_laba_bersih = $total_laba_bersih;
        $this->total_pembayaran = $total_pembayaran;
        $this->total_harga = DB::table('pesanan')->whereRaw($wr)->sum('harga');
        $this->total_belum_lunas = $this->total_harga - $this->total_pembayaran;
        $this->total_pengeluaran = $total_pengeluaran;
    }

    public function render()
    {
        return view('livewire.report.keuangan');
    }
}
