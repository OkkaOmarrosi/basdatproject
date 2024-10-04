<?php

namespace App\Http\Livewire\Operational;

use App\Models\Thrubus\Rekanan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Livewire\Component;

class CreateRekanan extends Component
{
    public ?Rekanan $rekanan;
    public $nama_rekanan;
    public $alamat_rekanan;
    public $no_hp_rekanan;

    protected $rules = [
        'nama_rekanan' => 'required',
        'alamat_rekanan' => 'required',
        'no_hp_rekanan' => 'required',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $this->rekanan = Rekanan::where('idrekanan_mitra', $id)->first();
            $this->nama_rekanan = $this->rekanan->nama_rekanan;
            $this->alamat_rekanan = $this->rekanan->alamat_rekanan;
            $this->no_hp_rekanan = $this->rekanan->no_hp_rekanan;
        }
    }

    public function saveOrUpdate()
    {
        DB::beginTransaction();
        if (isset($this->rekanan)) {
            dd($this->rekanan);
            if ($this->rekanan->nama_rekanan == $this->nama_rekanan) {
                // do nothing
            } else {
                Validator::make([
                    'nama_rekanan' => $this->nama_rekanan,
                ], [
                    'nama_rekanan' => 'required|string|unique:rekanan_mitra|max:255',
                ])->validate();
            }


            DB::connection('mysql2')->table('rekanan_mitra')->where('idrekanan_mitra', $this->rekanan->idrekanan_mitra)->update([
                'nama_rekanan' => $this->nama_rekanan,
                'alamat_rekanan' => $this->alamat_rekanan,
                'no_hp_rekanan' => $this->no_hp_rekanan,
            ]);

            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Updated' , 'message' => 'Rekanan Berhasil Di Ubah!']);
        } else {
            $this->validate();
            DB::connection('mysql2')->table('rekanan_mitra')->insert([
                'mitra_idmitra' => auth()->user()->mitra_id,
                'nama_rekanan' => $this->nama_rekanan,
                'alamat_rekanan' => $this->alamat_rekanan,
                'no_hp_rekanan' => $this->no_hp_rekanan,
                'create_at' => now(),
            ]);

            $this->dispatchBrowserEvent('show-toast', ['type' => 'success', 'title' => 'Created' , 'message' => 'Rekanan Berhasil Dibuat']);
        }

        DB::commit();
        return redirect()->route('operational.rekanan');
    }

    public function render()
    {
        return view('livewire.operational.create-rekanan');
    }
}
