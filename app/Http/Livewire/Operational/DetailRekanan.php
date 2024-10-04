<?php

namespace App\Http\Livewire\Operational;

use App\Models\MobilRekanan;
use App\Models\Thrubus\Rekanan;
use Illuminate\Support\Facades\DB;

use Livewire\Component;

class DetailRekanan extends Component
{

    public $data;

    public $idrekanan_mitra;

    public $years = 1985;

    public $list_mobil = [];

    public $formData = [
        'idrekanan_mitra' => null,
        'nopol' => null,
        'merk' => null,
        'tipe' => null,
        'tahun' => null,
        'warna' => null,
        'no_rangka' => null,
        'no_mesin' => null,
        'action' => 'submitMobil'
    ];

    protected $listeners = ['addButton' => 'resetForm'];

    public function mount($id = null)
    {
        $data = Rekanan::where('idrekanan_mitra', $id ?? request('id'))->first();
        $this->years = range(1985, strftime('%Y', time()));
        $this->data = $data;
        $this->idrekanan_mitra = $data->idrekanan_mitra;
        // dd($this->data);
        $this->getListMobil();

    }

    public function getListMobil()
    {
        $this->list_mobil = MobilRekanan::with('tipe_mobil')->where('idrekanan_mitra', $this->idrekanan_mitra)->get();
    }

    public function submitMobil()
    {
        $this->formData['idrekanan_mitra'] = $this->idrekanan_mitra;

        $this->validate([
            'formData.nopol' => 'required',
            'formData.merk' => 'required',
            'formData.tipe' => 'required',
        ]);

        DB::beginTransaction();
        try {
            // $this->formData['idrekanan_mitra'] = request('id');
            MobilRekanan::create($this->formData);
            DB::commit();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil disimpan']);
            $this->dispatchBrowserEvent('close-modal');
            $this->getListMobil();
            // return redirect()->route('operational.detail-order', [$id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $th->getMessage()]);
        }
    }

    public function resetForm()
    {
        $this->formData = [
            'idrekanan_mitra' => $this->idrekanan_mitra,
            'nopol' => null,
            'merk' => null,
            'tipe' => null,
            'tahun' => null,
            'warna' => null,
            'no_rangka' => null,
            'no_mesin' => null,
            'action' => 'submitMobil'
        ];
    }

    public function detailMobil($id)
    {
        $this->formData = MobilRekanan::find($id)->toArray();
        $this->formData['action'] = 'updateMobil';
        $this->dispatchBrowserEvent('open-modal');
    }

    public function updateMobil()
    {
        $this->validate([
            'formData.nopol' => 'required',
            'formData.merk' => 'required',
            'formData.tipe' => 'required',
        ]);

        DB::beginTransaction();
        try {
            MobilRekanan::find($this->formData['id'])->update($this->formData);
            DB::commit();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil Di Ubah!']);
            $this->dispatchBrowserEvent('close-modal');
            $this->getListMobil();
            // return redirect()->route('operational.detail-order', [$id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $th->getMessage()]);
        }
    }

    public function deleteMobil($id)
    {
        DB::beginTransaction();
        try {
            MobilRekanan::find($id)->delete();
            DB::commit();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Berhasil', 'message' => 'Data berhasil dihapus']);
            $this->getListMobil();
            // return redirect()->route('operational.detail-order', [$id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatchBrowserEvent('show-toast', ['type' => 'error', 'title' => 'Gagal', 'message' => $th->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.operational.detail-rekanan');
    }
}
