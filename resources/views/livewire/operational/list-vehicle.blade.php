<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div class="w-70" wire:ignore>
                            <h5 class="mb-1 ">Paket Layanan
                                {{-- {{ $data['data']['paket']['nama'] == 'DROPOFF' ? 'Satu Tujuan' : 'Banyak Tujuan' }} --}}
                            </h5>
                            <table>
                                <tr>
                                    <td style="width: 20%; white-space: nowrap;"><i class="fa fa-map-marker"
                                            aria-hidden="true"></i> Tujuan</td>
                                    <td style="width: 2%">:</td>
                                    @if ($layanan_id == 1)
                                        <td style="width: 78%;">
                                            @foreach ($data['data']['route']['tujuan'] as $v)
                                                {{ $v }} <br>
                                            @endforeach
                                        </td>
                                    @else
                                        <td style="width: 78%;">
                                            {{ $data['data']['route']['tujuan'] }}
                                        </td>
                                    @endif
                                </tr>
                            </table>
                        </div>
                        <div>
                            <a type="a" class="btn btn-danger btn-sm mb-0"
                                href="{{ route('operational.check-price') }}">Ubah Rute</a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="p-3">
                        <div class="form-group" wire:ignore>
                            <label>Pool</label>
                            <select id="selectPool" wire:model.defer="idpool" class="form-control">
                                <option value="">Pilih Pool</option>
                                @foreach ($data['data']['pool'] as $option)
                                    <option value="{{ $option['idpool'] }}">{{ $option['lokasi'] }}</option>
                                @endforeach
                            </select>
                            @error('idpool')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- List Vehicle --}}
        <div class="col-12 mt-2">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-1">List Kendaraan</h6>
                    <p class="text-sm">Siap untuk di sewa</p>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        @foreach ($list_mobil_recomendation as $key => $val)
                            <div class="col-xl-3 col-md-6 mb-xl-0 mb-5">
                                <div class="card card-blog card-plain">
                                    <div class="position-relative">
                                        <a class="d-block shadow-xl border-radius-xl">
                                            <img src="{{ $val['images'] }}" alt="img-blur-shadow"
                                                class="img-fluid border-radius-xl mt-2" style= height: 22px; width: 100px;>
                                        </a>
                                    </div>
                                    <div class="card-body px-1 pb-0">
                                        <h6>
                                            {{ $val['nama_pool'] }} <small style="color: red">Recomendation</small>
                                        </h6>
                                        <p class="text-gradient text-dark mb-2 text-sm">
                                            {{ $val['nama_kategori_mobil'] }} {{ $val['nama_mobil'] }} #2023</p>
                                        <a href="javascript:;">
                                            @if ($layanan_id == 1)
                                                @if ($val['is_paket'])
                                                    <h5>
                                                        Rp. {{ number_format($val['harga_persentase'], 0, ',', '.') }}
                                                        <small class="font-weight-light" style="font-size: 12px">/
                                                            Paket</small>
                                                    </h5>
                                                @else
                                                    <h5>
                                                        Rp. {{ number_format($val['harga_fix'], 0, ',', '.') }} <small
                                                            class="font-weight-light" style="font-size: 12px">/
                                                            Paket</small>
                                                    </h5>
                                                @endif
                                            @else
                                                <h5>
                                                    Rp. {{ number_format($val['harga'], 0, ',', '.') }} <small
                                                        class="font-weight-light" style="font-size: 12px">/
                                                        Paket</small>
                                                </h5>
                                            @endif
                                        </a>
                                        <p class="mb-2 text-sm">
                                            {{ $val['description_mobil'] }}
                                        </p>
                                        <div class="d-flex align-items-center justify-content-between">
                                            @if ($layanan_id == 1)
                                                <span class="mb-2 text-sm"><i class='fas fa-gas-pump'></i>
                                                    {{ $val['nama_bahan_bakar'] }}</span>
                                                <span class="mb-2 text-sm"><i class="fa fa-male"></i> Paket Driver &
                                                    BBM</span>
                                            @endif

                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <button type="button" class="btn btn-warning btn-sm mb-0"
                                                data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                                wire:click='getVehicle({{ $val['idmobil'] }} ,{{ $val['is_paket'] ? 'true' : 'false' }})'>Pesan</button>
                                            @if ($layanan_id == 1)
                                                <div class="form-check form-switch ps-0">
                                                    <input class="form-check-input mt-1 ms-auto" type="checkbox"
                                                        wire:model="list_mobil.{{ $key }}.is_paket">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @foreach ($list_mobil as $key => $val)
                            <div class="col-xl-3 col-md-6 mb-xl-0 mb-5">
                                <div class="card card-blog card-plain">
                                    <div class="position-relative">
                                        <a class="d-block shadow-xl border-radius-xl">
                                            <img src="{{ $val['images'] }}" alt="img-blur-shadow"
                                                class="img-fluid border-radius-xl mt-2" style= height: 22px; width: 100px;>
                                        </a>
                                    </div>
                                    <div class="card-body px-1 pb-0">
                                        <h6>
                                            {{ $val['nama_pool'] }}
                                        </h6>
                                        <p class="text-gradient text-dark mb-2 text-sm">
                                            {{ $val['nama_kategori_mobil'] }} {{ $val['nama_mobil'] }} #2023</p>
                                        <a href="javascript:;">
                                            @if ($layanan_id == 1)
                                                @if ($val['is_paket'])
                                                    <h5>
                                                        Rp. {{ number_format($val['harga_persentase'], 0, ',', '.') }}
                                                        <small class="font-weight-light" style="font-size: 12px">/
                                                            Paket</small>
                                                    </h5>
                                                @else
                                                    <h5>
                                                        Rp. {{ number_format($val['harga_fix'], 0, ',', '.') }} <small
                                                            class="font-weight-light" style="font-size: 12px">/
                                                            Paket</small>
                                                    </h5>
                                                @endif
                                            @else
                                                <h5>
                                                    Rp. {{ number_format($val['harga'], 0, ',', '.') }} <small
                                                        class="font-weight-light" style="font-size: 12px">/
                                                        Paket</small>
                                                </h5>
                                            @endif
                                        </a>
                                        <p class="mb-2 text-sm">
                                            {{ $val['description_mobil'] }}
                                        </p>
                                        <div class="d-flex align-items-center justify-content-between">
                                            @if ($layanan_id == 1)
                                                <span class="mb-2 text-sm"><i class='fas fa-gas-pump'></i>
                                                    {{ $val['nama_bahan_bakar'] }}</span>
                                                <span class="mb-2 text-sm"><i class="fa fa-male"></i> Paket Driver &
                                                    BBM</span>
                                            @endif

                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <button type="button" class="btn btn-warning btn-sm mb-0"
                                                data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                                wire:click='getVehicle({{ $val['idmobil'] }} ,{{ $val['is_paket'] ? 'true' : 'false' }})'>Pesan</button>
                                            @if ($layanan_id == 1)
                                                <div class="form-check form-switch ps-0">
                                                    <input class="form-check-input mt-1 ms-auto" type="checkbox"
                                                        wire:model="list_mobil.{{ $key }}.is_paket">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <form wire:submit.prevent="submit">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Detail Pesanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Pemesan</label>
                            <input type="text" wire:model.self="nama_pemesan" class="form-control"
                                placeholder="Type here...">
                            @error('nama_pemesan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Nomor Pemesan</label>
                            <input type="number" wire:model="nomor_pemesan" class="form-control"
                                placeholder="Type here...">
                            @error('nomor_pemesan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-check form-switch ps-0 d-flex flex-row justify-content-between">
                            <div>
                                <span>Saya memesan untuk orang lain</span>
                            </div>
                            <div>
                                <input class="form-check-input mt-1 ms-auto" type="checkbox" id="useDriver"
                                    wire:model="is_pesanan_tamu">
                            </div>
                        </div>
                        @if ($is_pesanan_tamu)
                            <div class="form-group">
                                <label>Nama Tamu</label>
                                <input type="text" wire:model="nama_tamu" class="form-control"
                                    placeholder="Type here...">
                                @error('nama_tamu')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Nomor Tamu</label>
                                <input type="number" wire:model="nomor_tamu" class="form-control"
                                    placeholder="Type here...">
                                @error('nomor_tamu')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Lanjutkan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#selectPool').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                'style',
            placeholder: $(this).data('placeholder'),
        });
        $('#selectPool').on('change', function(e) {
            var data = $('#selectPool').select2("val");
            @this.set('idpool', data);
        });

    });
</script>
