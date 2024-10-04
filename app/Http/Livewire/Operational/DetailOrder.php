<?php

namespace App\Http\Livewire\Operational;

use App\Models\MobilRekanan;
use App\Models\Pesanan;
use App\Models\PesananChecklist;
use App\Models\PesananHistory;
use App\Models\PesananPembayaran;
use App\Models\PesananPengeluaran;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class DetailOrder extends Component
{
    public $data;
    public $list_mobil = [];
    public $list_rekan = [];
    public $list_pengeluaran = [];
    public $list_pembayaran = [];
    public $list_methode_pembayaran = [];
    public $list_checklist = [];
    public $list_driver = [];
    public $list_mobil_rekan = [];

    public $data_list_pengeluaran = [];
    public $data_list_pembayaran = [];
    public $data_list_checklist = [];

    public $disableBtn = false;

    public $confirmation;

    protected $listeners = [
        'addButton' => 'resetForm',
        'getMobils' => 'getMobil',
    ];

    public $formData = [
        'idmobil' => null,
        'type_form' => 0,
        'idrekanan_mitra' => null,
        'mobil_rekan' => null,
        'nopol' => null,
    ];

    public $formPengeluaran = [
        'iditem_order_pengeluaran' => null,
        'jumlah' => null,
        'action' => 'submitPengeluaran',
    ];

    public $formPembayaran = [
        'iditem_status_pembayaran' => null,
        'iditem_methode_pembayaran' => null,
        'nominal' => 0,
        'keterangan' => null,
        'action' => 'submitPembayaran',
    ];

    public $formChecklist = [
        'iditem_checklist' => null,
        'value' => 0,
        'action' => 'submitChecklist',
    ];

    public $formDriver = [
        'iddriver' => null,
        'action' => 'submitDriver',
    ];

    public $formTanggalSelesai = [
        'date_selesai' => null,
        'jam_selesai' => null,
        'action' => 'submitTanggalSelesai',
    ];

    public $formPemesanan = [
        'nama_pemesan' => null,
        'nomor_pemesan' => null,
        'alamat_pemesan' => null,
        'nama_tamu' => null,
        'nomor_tamu' => null,
        'harga' => 0,
        'is_pesanan_tamu' => 0,
        'description' => null,
    ];

    public function updated($val)
    {
        if ($val == 'formData.type_form') {
            $this->formData['idmobil'] = null;
            $this->formData['idrekanan_mitra'] = null;
            $this->formData['mobil_rekan'] = null;
            $this->formData['nopol'] = null;
        }

        // if ($val == 'formData.idrekanan_mitra') {
        //     if ($this->formData['idrekanan_mitra'] != "") {
        //         $this->list_mobil_rekan = MobilRekanan::where('idrekanan_mitra', $this->formData['idrekanan_mitra'])->get()->toArray();
        //     } else {
        //         $this->list_mobil_rekan = [
        //             [
        //                 'id' => 1,
        //                 'nopol' => 'kentang',
        //             ]
        //         ];
        //     }
        // }
    }

    public function mount($id = null)
    {

        $data = Pesanan::with(
            'created_by:id,name',
            'tipe_mobil:idtipe_mobil,nama_mobil',
            // 'pesanan_pengeluaran',
            // 'pesanan_pengeluaran.order_pengeluaran',
            // 'pesanan_pembayaran',
            // 'pesanan_pembayaran.status_pembayaran',
            // 'pesanan_pembayaran.methode_pembayaran',
            // 'pesanan_checklist',
            // 'pesanan_checklist.checklist',
            'driver:iddriver,nama_driver',
            'pesanan_history',
            'pesanan_history.created_by_:id,name',
            'mobil_rekanan:id,nopol,merk,tipe',
        )->findOrFail($id ?? request('id'));
        $this->data = $data;
        if ($data->layanan_id == 1) {
            $this->data->layanan = 'Banyak Tujuan';
        } else if ($data->layanan_id == 2) {
            $this->data->layanan = 'Drop Off';
        } else if ($data->layanan_id == 3) {
            $this->data->layanan = 'Lepas Kunci';
        } else {
            $this->data->layanan = 'Bulanan';
        }
        $response = Http::withHeaders([
            'app_id' => 'A.M.V1.0',
            'auth_key' => 'YvcR--78-cwFBw6SW8uCegs7SP__gYa_',
        ])->post('https://api-order.thrubus.co.id/admin-mitra-order/dropoff', [
            'idtipe_mobil' => $data->idtipe_mobil,
            'idmitra' => $data->idmitra,
            "tgl_mulai" => $data->tgl_mulai,
            "penjemputan" => $data->penjemputan,
            "tujuan" => $data->tujuan
        ]);

        $responseData = $response->json();
        // dd($responseData['data']['mobil'], $data);
        if (@$responseData['code'] != null) {
            foreach ($responseData['data']['mobil'] as $key => $value) {
                if ($value['idmitra'] == $data->idmitra && $value['idpool'] == $data->idpool && $value['idtipe_mobil'] == $data->idtipe_mobil) {
                    $this->list_mobil[] = $value;
                }
            }
        } else {
            $this->list_mobil = [];
        }

        if ($data->idmobil != null) {
            $this->data->mobil = DB::connection('mysql2')->table('mobil')->where('idmobil', $data->idmobil)->first();
        } else {
            $this->data->mobil = null;
        }

        if ($data->idrekanan != null) {
            $this->data->rekan = DB::connection('mysql2')->table('rekanan_mitra')->where('idrekanan_mitra', $data->idrekanan)->first();
        } else {
            $this->data->rekan = null;
        }

        // data master
        $listPengeluaran = DB::connection('mysql2')->table('item_order_pengeluaran')->get();
        $this->list_pengeluaran = $listPengeluaran->map(function ($item) {
            return (array) $item;
        })->toArray();

        $rekananMitra = DB::connection('mysql2')->table('rekanan_mitra')->where('mitra_idmitra', $this->data->idmitra)->get();
        $this->list_rekan = $rekananMitra->map(function ($item) {
            return (array) $item;
        })->toArray();

        $listMethodePembayaran = DB::connection('mysql2')->table('item_methode_pembayaran')->get();
        $this->list_methode_pembayaran = $listMethodePembayaran->map(function ($item) {
            return (array) $item;
        })->toArray();

        $listChecklist = DB::connection('mysql2')->table('item_checklist')->orderBy('sorts', 'desc')->get();
        $this->list_checklist = $listChecklist->map(function ($item) {
            return (array) $item;
        })->toArray();

        $listDriver = DB::connection('mysql2')->table('driver')->select('iddriver', 'nama_driver as nama')->get();
        $this->list_driver = $listDriver->map(function ($item) {
            return (array) $item;
        })->toArray();

        $this->listPembayaran();
        // master end

        // list pesanan pengeluaran
        $this->listPesananPengeluaran();

        // list pesanan pembayaran
        $this->listPesananPembayaran();

        // list pesanan checklist
        $this->listPesananChecklist();

        $this->list_mobil_rekan = MobilRekanan::where('tipe', $this->data->idtipe_mobil)->get()->toArray();
        $dateSelesai = new DateTime($this->data->tgl_selesai);
        $this->formTanggalSelesai['date_selesai'] = $dateSelesai->format('Y-m-d');
        $this->formTanggalSelesai['jam_selesai'] = $dateSelesai->format('H:i');
    }

    public function listPesananPengeluaran()
    {
        $this->data_list_pengeluaran = PesananPengeluaran::with('order_pengeluaran')->where('header_id', $this->data->id)->get();
    }

    public function listPesananPembayaran()
    {
        $this->data_list_pembayaran = PesananPembayaran::with('status_pembayaran', 'methode_pembayaran')->where('header_id', $this->data->id)->get();
    }

    public function listPesananChecklist()
    {
        $this->data_list_checklist = PesananChecklist::with('checklist')->where('header_id', $this->data->id)->get();
    }

    public function listPembayaran()
    {
        $listPembayaran = DB::connection('mysql2')->table('item_status_pembayaran')->get();
        $this->list_pembayaran = $listPembayaran->map(function ($item) {
            return (array) $item;
        })->toArray();
    }

    // reset form

    public function resetForm()
    {
        $this->formPengeluaran = [
            // 'iditem_order_pengeluaran' => '',
            'jumlah' => null,
            'action' => 'submitPengeluaran',
        ];

        $this->formPembayaran = [
            // 'iditem_status_pembayaran' => '',
            // 'iditem_methode_pembayaran' => '',
            'nominal' => 0,
            'keterangan' => null,
            'action' => 'submitPembayaran',
        ];

        $this->formChecklist = [
            // 'iditem_checklist' => '',
            'value' => 0,
            'action' => 'submitChecklist',
        ];

        $this->dispatchBrowserEvent('reset-select2');
    }

    // reset form end

    public function submitTanggalSelesai()
    {
        $this->validate([
            'formTanggalSelesai.date_selesai' => 'required',
            'formTanggalSelesai.jam_selesai' => 'required',
        ]);

        $date = new DateTime($this->formTanggalSelesai['date_selesai'] . ' ' . $this->formTanggalSelesai['jam_selesai']);
        $data = Pesanan::find($this->data->id);
        $data->tgl_selesai = $date->format('Y-m-d H:i:s');
        $data->save();

        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil disimpan']);

        return redirect()->route('operational.detail-order', [$this->data->id]);
    }


    public function submitVehicle()
    {

        $data = Pesanan::find($this->data->id);
        DB::beginTransaction();
        try {
            if ($this->formData['type_form'] == 0) {
                $this->validate([
                    'formData.idmobil' => 'required',
                ], [
                    'formData.idmobil.required' => 'Mobil harus diisi',
                ]);

                $data->idmobil = $this->formData['idmobil'];
                $mobil = DB::connection('mysql2')->table('mobil')->where('idmobil', $data->idmobil)->first();
                $data->estimasi_bbm  = ($data->estimasi_km / ($mobil->rasio_bahan_bakar ?? 1)) * ($mobil->harga_bbm ?? 1000);
            } else {
                $this->validate([
                    'formData.idrekanan_mitra' => 'required',
                    'formData.mobil_rekan' => 'required_if:formData.nopol,null',
                    'formData.nopol' => 'required_if:formData.mobil_rekan,null',
                ], [
                    'formData.idrekanan_mitra.required' => 'Rekanan harus diisi',
                    'formData.mobil_rekan.required_if' => 'Mobil harus diisi jika Nopol tidak diisi',
                    'formData.nopol.required_if' => 'Nopol harus diisi jika Mobil tidak diisi',
                ]);

                if ($this->formData['nopol']) {
                    $mobil_rekan_id = MobilRekanan::insertGetId([
                        'idrekanan_mitra' => $this->formData['idrekanan_mitra'],
                        'nopol' => $this->formData['nopol'],
                        'tipe' => $data->idtipe_mobil
                    ]);
                    $data->mobil_rekan = $mobil_rekan_id;
                } else {
                    $data->mobil_rekan = $this->formData['mobil_rekan'];
                }

                $data->idrekanan = $this->formData['idrekanan_mitra'];
            }

            $data->save();

            DB::commit();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil disimpan']);

            return redirect()->route('operational.detail-order', [$this->data->id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $th->getMessage()]);
        }
    }

    public function submitDriver()
    {
        DB::beginTransaction();

        $data = Pesanan::find($this->data->id);

        $this->validate([
            'formDriver.iddriver' => 'required',
        ]);
        $data->iddriver = $this->formDriver['iddriver'];

        $data->save();

        DB::commit();

        return redirect()->route('operational.detail-order', [$this->data->id]);
    }

    public function detailDriver($id)
    {
        $this->dispatchBrowserEvent('select-detail-driver', [
            'iddriver' => $id
        ]);
        $this->formDriver['iddriver'] = $id;
    }

    public function updateDriver($id)
    {
        DB::beginTransaction();
        $data = Pesanan::find($id)->update([
            'iddriver' => $this->formDriver['iddriver'],
        ]);
        DB::commit();

        return redirect()->route('operational.detail-order', [$this->data->id]);
    }

    // pengeluaran
    public function submitPengeluaran()
    {

        DB::beginTransaction();

        try {

            $this->validate([
                'formPengeluaran.iditem_order_pengeluaran' => 'required',
                'formPengeluaran.jumlah' => 'required',
            ], [
                'formPengeluaran.iditem_order_pengeluaran.required' => 'Pengeluaran harus diisi',
                'formPengeluaran.jumlah.required' => 'Jumlah harus diisi',

            ]);

            PesananPengeluaran::create([
                'header_id' => $this->data->id,
                'iditem_order_pengeluaran' => $this->formPengeluaran['iditem_order_pengeluaran'],
                'jumlah' => $this->formPengeluaran['jumlah'],
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            $this->listPesananPengeluaran();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil disimpan']);
            $this->dispatchBrowserEvent('close-modal');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $th->getMessage()]);
        }


        // return redirect()->route('operational.detail-order', [$this->data->id]);
    }

    public function detailPengeluaran($id)
    {
        $data = PesananPengeluaran::find($id);
        $this->dispatchBrowserEvent('select-detail-pengeluaran', [
            'iditem_order_pengeluaran' => $data->iditem_order_pengeluaran
        ]);
        $this->formPengeluaran['jumlah'] = $data->jumlah;
        $this->formPengeluaran['iditem_order_pengeluaran'] = intVal($data->iditem_order_pengeluaran);
        $this->formPengeluaran['action'] = 'updatePengeluaran(' . $id . ')';
    }

    public function updatePengeluaran($id)
    {
        DB::beginTransaction();
        try {
            $this->validate([
                'formPengeluaran.iditem_order_pengeluaran' => 'required',
                'formPengeluaran.jumlah' => 'required',
            ]);

            PesananPengeluaran::find($id)->update([
                'iditem_order_pengeluaran' => $this->formPengeluaran['iditem_order_pengeluaran'],
                'jumlah' => $this->formPengeluaran['jumlah'],
            ]);
            DB::commit();

            $this->listPesananPengeluaran();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil disimpan']);
            $this->dispatchBrowserEvent('close-modal');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $th->getMessage()]);
        }

        // return redirect()->route('operational.detail-order', [$this->data->id]);
    }

    public function deletePengeluaran($id)
    {
        DB::beginTransaction();
        PesananPengeluaran::find($id)->delete();
        DB::commit();
        // return redirect()->route('operational.detail-order', [$this->data->id]);
        $this->listPesananPengeluaran();
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil dihapus']);
        $this->dispatchBrowserEvent('close-modal');
    }
    // end pengeluaran

    // pembayaran

    public function resetPembayaran()
    {
        $this->formPembayaran = [
            'iditem_status_pembayaran' => null,
            'iditem_methode_pembayaran' => null,
            'nominal' => 0,
            'keterangan' => null,
            'action' => 'submitPembayaran',
        ];
    }

    public function submitPembayaran()
    {
        DB::beginTransaction();

        try {
            $this->validate([
                'formPembayaran.iditem_status_pembayaran' => 'required',
                'formPembayaran.iditem_methode_pembayaran' => 'required',
                'formPembayaran.nominal' => 'required',
            ], [
                'formPembayaran.iditem_status_pembayaran.required' => 'Status Pembayaran harus diisi',
                'formPembayaran.iditem_methode_pembayaran.required' => 'Methode Pembayaran harus diisi',
                'formPembayaran.nominal.required' => 'Nominal harus diisi',
            ]);

            PesananPembayaran::create([
                'header_id' => $this->data->id,
                'iditem_status_pembayaran' => $this->formPembayaran['iditem_status_pembayaran'],
                'iditem_methode_pembayaran' => $this->formPembayaran['iditem_methode_pembayaran'],
                'nominal' => $this->formPembayaran['nominal'],
                'keterangan' => $this->formPembayaran['keterangan'],
                'created_by' => auth()->id(),
            ]);
            DB::commit();

            $this->listPesananPembayaran();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil disimpan']);
            $this->dispatchBrowserEvent('close-modal-pembayaran');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $th->getMessage()]);
        }


        // return redirect()->route('operational.detail-order', [$this->data->id]);
    }

    public function detailPembayaran($id)
    {
        $data = PesananPembayaran::find($id);
        $this->dispatchBrowserEvent('select-detail', [
            'iditem_status_pembayaran' => $data->iditem_status_pembayaran,
            'iditem_methode_pembayaran' => $data->iditem_methode_pembayaran,
        ]);
        $this->formPembayaran['keterangan'] = $data->keterangan;
        $this->formPembayaran['nominal'] = $data->nominal;
        $this->formPembayaran['iditem_status_pembayaran'] = intVal($data->iditem_status_pembayaran);
        $this->formPembayaran['iditem_methode_pembayaran'] = intVal($data->iditem_methode_pembayaran);
        $this->formPembayaran['action'] = 'updatePembayaran(' . $id . ')';
    }

    public function updatePembayaran($id)
    {
        DB::beginTransaction();
        try {
            $this->validate([
                'formPembayaran.iditem_status_pembayaran' => 'required',
                'formPembayaran.iditem_methode_pembayaran' => 'required',
                'formPembayaran.nominal' => 'required',
            ], [
                'formPembayaran.iditem_status_pembayaran.required' => 'Status Pembayaran harus diisi',
                'formPembayaran.iditem_methode_pembayaran.required' => 'Methode Pembayaran harus diisi',
                'formPembayaran.nominal.required' => 'Nominal harus diisi',
            ]);

            PesananPembayaran::find($id)->update([
                'iditem_status_pembayaran' => $this->formPembayaran['iditem_status_pembayaran'],
                'iditem_methode_pembayaran' => $this->formPembayaran['iditem_methode_pembayaran'],
                'nominal' => $this->formPembayaran['nominal'],
                'keterangan' => $this->formPembayaran['keterangan'],
            ]);
            DB::commit();

            $this->listPesananPembayaran();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil disimpan']);
            $this->dispatchBrowserEvent('close-modal-pembayaran');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $th->getMessage()]);
            // throw $th;
        }

        // return redirect()->route('operational.detail-order', [$this->data->id]);
    }

    public function deletePembayaran($id)
    {
        DB::beginTransaction();
        PesananPembayaran::find($id)->delete();
        DB::commit();
        $this->listPesananPembayaran();
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil dihapus']);
        // return redirect()->route('operational.detail-order', [$this->data->id]);
    }

    // end pembayaran

    // checklist

    public function resetChecklist()
    {
        $this->formChecklist = [
            'iditem_checklist' => null,
            'value' => 0,
            'action' => 'submitChecklist',
        ];
    }

    public function submitChecklist()
    {
        DB::beginTransaction();

        try {
            $this->validate([
                'formChecklist.iditem_checklist' => 'required',
                'formChecklist.value' => 'required',
            ], [
                'formChecklist.iditem_checklist.required' => 'Checklist harus diisi',
                'formChecklist.value.required' => 'Value harus diisi',
            ]);

            PesananChecklist::create([
                'header_id' => $this->data->id,
                'iditem_checklist' => $this->formChecklist['iditem_checklist'],
                'value' => $this->formChecklist['value'],
                'created_by' => auth()->id(),
            ]);
            DB::commit();

            $this->listPesananChecklist();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil disimpan']);
            $this->dispatchBrowserEvent('close-modal-checklist');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $th->getMessage()]);
        }
    }

    public function detailChecklist($id)
    {
        $data = PesananChecklist::find($id);
        $this->dispatchBrowserEvent('select-detail-checklist', [
            'iditem_checklist' => $data->iditem_checklist
        ]);
        $this->formChecklist['value'] = $data->value;
        $this->formChecklist['iditem_checklist'] = intVal($data->iditem_checklist);
        $this->formChecklist['action'] = 'updateChecklist(' . $id . ')';
    }

    public function updateChecklist($id)
    {
        DB::beginTransaction();
        try {
            $this->validate([
                'formChecklist.iditem_checklist' => 'required',
                'formChecklist.value' => 'required',
            ], [
                'formChecklist.iditem_checklist.required' => 'Checklist harus diisi',
                'formChecklist.value.required' => 'Value harus diisi',
            ]);

            PesananChecklist::find($id)->update([
                'iditem_checklist' => $this->formChecklist['iditem_checklist'],
                'value' => $this->formChecklist['value'],
            ]);
            DB::commit();

            $this->listPesananChecklist();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil disimpan']);
            $this->dispatchBrowserEvent('close-modal-checklist');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $th->getMessage()]);
        }
    }

    public function deleteChecklist($id)
    {
        DB::beginTransaction();
        PesananChecklist::find($id)->delete();
        DB::commit();
        $this->listPesananChecklist();
        $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil dihapus']);
    }

    // end checklist

    public function confirmAction()
    {
        $this->emit('actionConfirmed');
    }

    public function setSchedule()
    {

        $pesanan = Pesanan::find($this->data->id);

        if ($pesanan->nama_pemesan == null) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => 'Nama Pemesan harus diisi']);
            return;
        }

        if ($pesanan->nomor_pemesan == null) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => 'Nomor Pemesan harus diisi']);
            return;
        }

        if ($pesanan->idmobil == null && $pesanan->idrekanan == null) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => 'Mobil harus diisi']);
            return;
        }

        if ($pesanan->iddriver == null && $pesanan->layanan_id < 3) {
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => 'Driver harus diisi']);
            return;
        }

        DB::beginTransaction();

        try {
            $pesanan->update([
                'status' => 'Terjadwal',
            ]);

            PesananHistory::create([
                'pesanan_id' => $this->data->id,
                'status' => 'Terjadwal',
                'created_by' => auth()->id(),
            ]);

            if ($pesanan->idmobil != null) {
                $available = DB::table('pesanan_mobil')
                    ->leftJoin('pesanan', 'pesanan.id', 'pesanan_mobil.pesanan_id')
                    ->where('mobil_id', $pesanan->idmobil)->count();
                if ($available == 0) {
                    DB::table('pesanan_mobil')->insert([
                        'pesanan_id' => $pesanan->id,
                        'mobil_id' => $pesanan->idmobil
                    ]);
                    DB::table('pesanan_mobil_histories')->insert([
                        'pesanan_id' => $pesanan->id,
                        'mobil_id' => $pesanan->idmobil,
                    ]);
                } else {
                    DB::rollBack();
                    $pesanan_mobil = DB::table('pesanan_mobil')
                        ->leftJoin('pesanan', 'pesanan.id', 'pesanan_mobil.pesanan_id')
                        ->where('mobil_id', $pesanan->idmobil)
                        ->select('pesanan.kode')
                        ->first();
                    return $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => 'Mobil sudah di jadwalkan di Pesanan Nomor ' . $pesanan_mobil->kode . ' ']);
                }
            }

            DB::commit();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Dijadwalkan', 'message' => 'Pesanan berhasil dijadwalkan']);
            return redirect()->route('operational.detail-order', [$this->data->id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $th->getMessage()]);
        }
    }

    public function setProses()
    {
        $pesanan = Pesanan::find($this->data->id);

        DB::beginTransaction();
        try {
            $pesanan->update([
                'status' => 'Proses',
            ]);

            PesananHistory::create([
                'pesanan_id' => $this->data->id,
                'status' => 'Proses',
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'DiProses', 'message' => 'Pesanan berhasil diproses']);
            return redirect()->route('operational.detail-order', [$this->data->id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $th->getMessage()]);
        }
    }

    public function setClose()
    {
        $pesanan = Pesanan::find($this->data->id);

        DB::beginTransaction();
        try {
            $pesanan->update([
                'status' => 'Close',
            ]);

            PesananHistory::create([
                'pesanan_id' => $this->data->id,
                'status' => 'Close',
                'created_by' => auth()->id(),
            ]);

            if ($pesanan->idmobil != null) {
                DB::table('pesanan_mobil')->where('pesanan_id', $pesanan->id)->delete();
            }

            DB::commit();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Close', 'message' => 'Pesanan berhasil Di Tutup']);
            return redirect()->route('operational.detail-order', [$this->data->id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $th->getMessage()]);
        }
    }

    public function setCancel()
    {
        $pesanan = Pesanan::find($this->data->id);

        DB::beginTransaction();
        try {
            $pesanan->update([
                'status' => 'Cancel',
            ]);

            PesananHistory::create([
                'pesanan_id' => $this->data->id,
                'status' => 'Cancel',
                'created_by' => auth()->id(),
            ]);

            if ($pesanan->idmobil != null) {
                DB::table('pesanan_mobil')->where('pesanan_id', $pesanan->id)->delete();
            }

            DB::commit();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Cancel', 'message' => 'Pesanan berhasil Di Cancel']);
            return redirect()->route('operational.detail-order', [$this->data->id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $th->getMessage()]);
        }
    }

    public function submitPemesanan()
    {

        DB::beginTransaction();
        try {

            $this->validate([
                'formPemesanan.nama_pemesan' => 'required',
                'formPemesanan.nomor_pemesan' => 'required',
                'formPemesanan.nama_tamu' => 'required_if:formPemesanan.is_pesanan_tamu,1',
                'formPemesanan.nomor_tamu' => 'required_if:formPemesanan.is_pesanan_tamu,1',
                'formPemesanan.harga' => 'required',
                'formPemesanan.is_pesanan_tamu' => 'required',
            ]);

            Pesanan::find($this->data->id)->update([
                'nama_pemesan' => $this->formPemesanan['nama_pemesan'],
                'nomor_pemesan' => $this->formPemesanan['nomor_pemesan'],
                'alamat_pemesan' => $this->formPemesanan['alamat_pemesan'],
                'nama_tamu' => $this->formPemesanan['nama_tamu'],
                'nomor_tamu' => $this->formPemesanan['nomor_tamu'],
                'harga' => $this->formPemesanan['harga'],
                'is_pesanan_tamu' => $this->formPemesanan['is_pesanan_tamu'],
                'description' => $this->formPemesanan['description'],
                'status' => 'Terjadwal'
            ]);

            $pesanan = Pesanan::find($this->data->id);

            if ($pesanan->idmobil != null) {
                $available = DB::table('pesanan_mobil')->where('mobil_id', $pesanan->idmobil)->count();
                if ($available == 0) {
                    DB::table('pesanan_mobil')->insert([
                        'pesanan_id' => $pesanan->id,
                        'mobil_id' => $pesanan->idmobil
                    ]);
                    DB::table('pesanan_mobil_histories')->insert([
                        'pesanan_id' => $pesanan->id,
                        'mobil_id' => $pesanan->idmobil,
                    ]);
                } else {
                    DB::rollBack();
                    $pesanan_mobil = DB::table('pesanan_mobil')
                        ->leftJoin('pesanan', 'pesanan.id', 'pesanan_mobil.pesanan_id')
                        ->where('mobil_id', $pesanan->idmobil)
                        ->select('pesanan.kode')
                        ->first();
                    return $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => 'Mobil sudah di jadwalkan di Pesanan Nomor ' . $pesanan_mobil->kode . ' ']);
                }
            }

            DB::commit();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil disimpan']);
            return redirect()->route('operational.detail-order', [$this->data->id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $th->getMessage()]);
        }
    }


    public function render()
    {
        return view('livewire.operational.detail-order');
    }
}
