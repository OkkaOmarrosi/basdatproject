<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5>
                                Detail Rekan
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="p-3">
                        <div class="row table-responsive" wire:ignore>
                            <div class="col-lg-12">
                                <table class="table" style="width: 100%">
                                    <tbody>
                                        <tr>
                                            <td style="width: 20%">Nama Rekanan</td>
                                            <td style="width: 2%">:</td>
                                            <td>{{ $data->nama_rekanan }}</td>
                                        </tr>
                                        <tr>
                                            <td>Alamat</td>
                                            <td>:</td>
                                            <td>{{ $data->alamat_rekanan }}</td>
                                        </tr>
                                        <tr>
                                            <td>No. Telepon</td>
                                            <td>:</td>
                                            <td>{{ $data->no_hp_rekanan }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="border-top border-bottom"></div>
                        <div class="row table-responsive">
                            <div class="col-lg-12">
                                <div class="mt-3 mb-1">
                                    <div class="d-flex flex-row justify-content-between">
                                        <div>
                                            <h5 class="">Daftar Kendaraan</h5>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                data-bs-target="#staticModal" wire:click='resetForm'
                                                wire:click="$emit('addButton')">Tambah</button>
                                            {{-- <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                        data-bs-target="#staticModal">Tambah</button> --}}
                                        </div>
                                    </div>
                                </div>
                                <table class="table" style="width: 100%;" id='table2'>
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No. Polisi</th>
                                            <th>Merk</th>
                                            <th>Tipe</th>
                                            <th>Tahun</th>
                                            <th>Warna</th>
                                            <th>Action</th>
                                        </tr>
                                    <tbody>
                                        @if (count($list_mobil) == 0)
                                            <tr>
                                                <td colspan="7" class="text-center">Tidak ada data</td>
                                            </tr>
                                        @else
                                            @foreach ($list_mobil as $key => $v)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $v->nopol }}</td>
                                                    <td>{{ $v->merk }}</td>
                                                    <td>{{ $v->tipe_mobil->nama_mobil ?? '' }}</td>
                                                    <td>{{ $v->tahun }}</td>
                                                    <td>{{ $v->warna }}</td>
                                                    <td>
                                                        <a type='button' class="btn btn-sm btn-success"
                                                            wire:click="detailMobil({{ $v->id }})"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#staticModal">Edit</a>
                                                        <a type='button' class="btn btn-sm btn-danger"
                                                            wire:click="deleteMobil({{ $v->id }})">Hapus</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Kendaraan -->
    <div wire:ignore.self class="modal fade" id="staticModal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticModal" aria-hidden="true">
        <form wire:submit.prevent="{{ $formData['action'] }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Tambah Kendaraan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nopol</label>
                            <input type="text" wire:model="formData.nopol" class="form-control"
                                placeholder="Type here...">
                            @error('formData.nopol')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Merk Mobil</label>
                            <input type="text" wire:model="formData.merk" class="form-control"
                                placeholder="Type here...">
                            @error('formData.merk')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- <div class="form-group">
                            <label>Tipe Mobil</label>
                            <input type="text" wire:model="formData.tipe" class="form-control"
                                placeholder="Type here...">
                            @error('formData.tipe')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div> --}}
                        <div class="form-group" wire:ignore>
                            <label>Jenis Kendaraan</label>
                            <select id="typeVehicleSelect" wire:model.defer="formData.tipe" class="form-control">
                                <option value="null">Pilih Jenis kendaraan...</option>
                                @foreach (DB::connection('mysql2')->table('tipe_mobil')->select('tipe_mobil.idtipe_mobil as id', 'tipe_mobil.nama_mobil as nama')->get() as $option)
                                    <option value="{{ $option->id }}">{{ $option->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            @error('idtipe_mobil')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" wire:ignore>
                            <label>Tahun Mobil</label>
                            <select id="selectYear" wire:model.self="formData.tahun" class="form-control">
                                <option value="">Tahun Mobil anda</option>
                                @foreach ($years as $option)
                                    <option value="{{ $option }}">
                                        {{ $option }}</option>
                                @endforeach
                            </select>
                            <script>
                                $(document).ready(function() {
                                    $('#selectYear').select2({
                                        theme: "bootstrap-5",
                                        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                                            'style',
                                        placeholder: $(this).data('placeholder'),
                                    });
                                    $('#selectYear').on('change', function(e) {
                                        var data = $('#selectYear').select2("val");
                                        // console.log(data, 'ini a[a]')
                                        @this.set('formData.tahun', data);
                                    });

                                    $('#typeVehicleSelect').select2({
                                        theme: "bootstrap-5",
                                        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                                            'style',
                                        placeholder: $(this).data('placeholder'),
                                    });
                                    $('#typeVehicleSelect').on('change', function(e) {
                                        var data = $('#typeVehicleSelect').select2("val");
                                        @this.set('formData.tipe', data);
                                    });
                                });
                            </script>
                        </div>
                        <div class="form-group">
                            <label>Warna Mobil</label>
                            <input type="text" wire:model="formData.warna" class="form-control"
                                placeholder="Type here...">
                            @error('formData.warna')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>No. Rangka Mobil</label>
                            <input type="text" wire:model="formData.no_rangka" class="form-control"
                                placeholder="Type here...">
                            @error('formData.no_rangka')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>No. Mesin Mobil</label>
                            <input type="text" wire:model="formData.no_mesin" class="form-control"
                                placeholder="Type here...">
                            @error('formData.no_mesin')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            id="exit-modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        window.addEventListener('close-modal', event => {
            $('#exit-modal').click();
        });

        var url = window.location.pathname;
        var id = url.substring(url.lastIndexOf('/') + 1);
        @this.set('formData.idrekanan_mitra', id);
    </script>

</div>
