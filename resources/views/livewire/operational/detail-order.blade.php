<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5>
                                Detail Pesanan
                            </h5>
                        </div>
                        <div>
                            @if (strtoupper($data['status']) == strtoupper('draft') && $data['harga'] == null)
                                <a type='button' class="btn btn-sm btn-success" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdropPemesanan">Jadwalkan</a>
                            @elseif (strtoupper($data['status']) == strtoupper('draft'))
                                <button type="button" class="btn btn-sm btn-success" wire:click='setSchedule()'
                                    {{ $disableBtn == true ? 'disabled' : '' }}>Jadwalkan</button>
                            @elseif (strtoupper($data['status']) == strtoupper('terjadwal'))
                                <button type="button" class="btn btn-sm btn-success" wire:click='setProses()'>
                                    Proses
                                </button>
                            @elseif (strtoupper($data['status']) == strtoupper('proses'))
                                <button type="button" class="btn btn-sm btn-success" wire:click='setClose()'>
                                    Close
                                </button>
                            @endif

                            @if (strtoupper($data['status']) != strtoupper('close') && strtoupper($data['status']) != strtoupper('cancel'))
                                <button type="button" class="btn btn-sm btn-danger" wire:click='setCancel()'>
                                    Cancel
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="p-3">
                        <div class="row table-responsive">
                            <div class="col-lg-6">
                                <table class="table" wire:ignore>
                                    <tr>
                                        <td class="w-20 fw-bold">No Pesanan</td>
                                        <td class="w-5">:</td>
                                        <td class="w-75" style="color: rgb(218, 72, 72)">{{ $data['kode'] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-20 fw-bold">Tanggal Booking</td>
                                        <td class="w-5">:</td>
                                        <td class="w-75">
                                            {{ \Carbon\Carbon::parse($data['created_at'])->format('d-m-Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-20 fw-bold">Nama Pemesan</td>
                                        <td class="w-5">:</td>
                                        <td class="w-75">{{ $data['nama_pemesan'] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-20 fw-bold">Nomor Pemesan</td>
                                        <td class="w-5">:</td>
                                        <td class="w-75">{{ $data['nomor_pemesan'] }}</td>
                                    </tr>
                                    @if ($data['nama_tamu'] != null)
                                        <tr>
                                            <td class="w-20 fw-bold">Nama Tamu</td>
                                            <td class="w-5">:</td>
                                            <td class="w-75">{{ $data['nama_tamu'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="w-20 fw-bold">Nomor Tamu</td>
                                            <td class="w-5">:</td>
                                            <td class="w-75">{{ $data['nomor_tamu'] }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="w-20 fw-bold">Status Pesanan</td>
                                        <td class="w-5">:</td>
                                        <td class="w-75">
                                            @php
                                                $html = '';
                                                if (strtoupper($data['status']) == strtoupper('draft')) {
                                                    $html = 'bg-warning';
                                                } elseif (strtoupper($data['status']) == strtoupper('terjadwal')) {
                                                    $html = 'bg-success';
                                                } elseif (strtoupper($data['status']) == strtoupper('proses')) {
                                                    $html = 'bg-primary';
                                                } elseif (strtoupper($data['status']) == strtoupper('close')) {
                                                    $html = 'bg-info';
                                                } elseif (strtoupper($data['status']) == strtoupper('cancel')) {
                                                    $html = 'bg-danger';
                                                }
                                            @endphp
                                            <span class="badge {{ $html }}">
                                                {{ $data['status'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-20 fw-bold">Deskripsi</td>
                                        <td class="w-5">:</td>
                                        <td class="w-75">{{ $data['description'] }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-6">
                                <div class="text-center">
                                    <h6>Detail Mobil</h6>
                                </div>
                                <table class="table" wire:ignore>
                                    <tr>
                                        <td class="w-20 fw-bold">Tipe Mobil</td>
                                        <td class="w-5">:</td>
                                        <td class="w-75">{{ $data['tipe_mobil']['nama_mobil'] }}</td>
                                    </tr>
                                    @if ($data['layanan_id'] < 3)
                                        <tr>
                                            <td class="w-20 fw-bold">Tarif</td>
                                            <td class="w-5">:</td>
                                            <td class="w-75">Rp. {{ number_format($data['harga'], 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($data['rekan'] != null)
                                        <tr>
                                            <td class="w-20 fw-bold">Rekan</td>
                                            <td class="w-5">:</td>
                                            <td class="w-75"><strong>{{ $data['rekan']->nama_rekanan }}</strong></td>
                                        </tr>
                                    @endif
                                    {{-- @if ($data['layanan_id'] < 3) --}}
                                    <tr>
                                        <td class="w-20 fw-bold">Mobil</td>
                                        <td class="w-5">:</td>
                                        <td class="w-75">
                                            @if ($data['mobil'] != null)
                                                <strong>{{ $data['mobil']->nopol }}</strong>
                                            @elseif ($data['rekan'] != null)
                                                <strong>{{ $data['mobil_rekanan']->nopol ?? '' }}</strong>
                                            @else
                                                @if (strtoupper($data['status']) == strtoupper('draft'))
                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                        data-bs-target="#staticBackdrop">Pilih Nopol</button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-20 fw-bold">Driver</td>
                                        <td class="w-5">:</td>
                                        <td class="w-75">
                                            @if ($data['iddriver'] != null)
                                                <strong>{{ $data['driver']['nama_driver'] }}</strong>
                                                {{-- <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#staticBackdropDriver"> --}}
                                                @if (strtoupper($data['status']) == strtoupper('draft'))
                                                    <a href="#">
                                                        <i class="fa fa-edit" data-bs-toggle="modal"
                                                            data-bs-target="#staticBackdropDriver"
                                                            wire:click='detailDriver({{ $data['iddriver'] }})'></i>
                                                    </a>
                                                @endif
                                                {{-- </button> --}}
                                            @else
                                                @if (strtoupper($data['status']) == strtoupper('draft'))
                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                        data-bs-target="#staticBackdropDriver">Pilih Driver</button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    {{-- @endif --}}
                                </table>
                            </div>
                        </div>
                        <div class="row table-responsive" wire:ignore>
                            <div class="col-lg-6">
                                <div class="text-left">
                                    {{-- <h6>Detail Mobil</h6> --}}
                                </div>
                                <table class="table">
                                    <tr>
                                        <td class="w-20 fw-bold">Paket</td>
                                        <td class="w-5">:</td>
                                        <td class="w-75">{{ $data['paket'] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-20 fw-bold">Layanan</td>
                                        <td class="w-5">:</td>
                                        <td class="w-75">{{ $data['layanan'] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-20 fw-bold">Tanggal Mulai</td>
                                        <td class="w-5">:</td>
                                        <td class="w-75">
                                            {{ \Carbon\Carbon::parse($data['tgl_mulai'])->format('d-m-Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-20 fw-bold">Tanggal Selesai</td>
                                        <td class="w-5">:</td>
                                        <td class="w-75">
                                            {{ \Carbon\Carbon::parse($data['tgl_selesai'])->format('d-m-Y H:i:s') }}
                                            <a href="#">
                                                <i class="fa fa-edit" data-bs-toggle="modal"
                                                    data-bs-target="#staticBackdropTglSelesai"
                                                    wire:click='detailTglSelesai({{ $data['tgl_selesai'] }})'></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-20 fw-bold">Estimasi Perjalanan</td>
                                        <td class="w-5">:</td>
                                        <td class="w-75">{{ $data['estimasi_km'] }} km</td>
                                    </tr>
                                    <tr>
                                        <td class="w-20 fw-bold">Estimasi BBM</td>
                                        <td class="w-5">:</td>
                                        <td class="w-75">
                                            Rp. {{ number_format($data['estimasi_bbm'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-20 fw-bold">Estimasi Tol Parkir</td>
                                        <td class="w-5">:</td>
                                        <td class="w-75">{{ $data['estimasi_tol_parkir'] }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-6">
                                {{-- <h6 class="text-center">Latest News</h6> --}}
                                <ul class="timeline-cs mt-3">
                                    @foreach (json_decode($data['track_mobil']) as $index => $val)
                                        <li class="timeline-cs-item mb-5 bg-{{ $index }}">
                                            <h5 class="fw-bold">{{ $val->deskripsi }}</h5>
                                            <p class="text-muted mb-2 fw-bold">{{ $val->waktu ?? '' }}</p>
                                            <p class="text-muted">
                                                {{ $val->lokasi }}
                                            </p>
                                            <style>
                                                .bg-{{ $index }}:after {
                                                    background-color: {{ $val->warna }};
                                                }
                                            </style>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="border-top border-bottom"></div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mt-3 mb-1">
                                    <div class="d-flex flex-row justify-content-between">
                                        <div>
                                            <h5 class="">Pengeluaran</h5>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdropPengeluaran"
                                                wire:click="$emit('addButton')">Tambah</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th class="fw-bold">Nama</th>
                                            <th class="">Dibuat</th>
                                            <th class="">Nominal</th>
                                            <th class="">Action</th>
                                        </tr>
                                        @foreach ($data_list_pengeluaran as $val)
                                            <tr>
                                                <td>{{ $val->order_pengeluaran->label }}</td>
                                                <td>{{ \Carbon\Carbon::parse($val->created_at)->format('d-m-Y H:i:s') }}
                                                </td>
                                                <td>Rp. {{ number_format($val->jumlah, 0, ',', '.') }}</td>
                                                <td>
                                                    <a type='button' class="btn btn-sm btn-success"
                                                        wire:click="detailPengeluaran({{ $val->id }})"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#staticBackdropPengeluaran">Edit</a>
                                                    <a type='button' class="btn btn-sm btn-danger"
                                                        wire:click="deletePengeluaran({{ $val->id }})">Hapus</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if (count($data_list_pengeluaran) == 0)
                                            <tr>
                                                <td colspan="4" class="text-center">Tidak ada data</td>
                                            </tr>
                                        @endif

                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="border-top border-bottom"></div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mt-3 mb-1">
                                    <div class="d-flex flex-row justify-content-between">
                                        <div>
                                            <h5 class="">Pembayaran</h5>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdropPembayaran"
                                                wire:click='resetPembayaran'
                                                wire:click="$emit('addButton')">Tambah</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th class="fw-bold">Keterangan</th>
                                            <th class="">Dibuat</th>
                                            <th class="">Methode Pembayaran</th>
                                            <th class="">Nominal</th>
                                            <th class="">Status</th>
                                            <th class="">Action</th>
                                        </tr>
                                        @php
                                            $total = 0;
                                        @endphp
                                        @foreach ($data_list_pembayaran as $val)
                                            @php
                                                $total +=
                                                    $val->status_pembayaran->iditem_status_pembayaran == 1
                                                        ? $val->nominal
                                                        : 0;
                                            @endphp
                                            <tr>
                                                <td wire:key="item-{{ $val->id }}">
                                                    {{ $val->keterangan }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($val->created_at)->format('d-m-Y H:i:s') }}
                                                <td>{{ $val->methode_pembayaran->label }}</td>
                                                <td>Rp. {{ number_format($val->nominal, 0, ',', '.') }}</td>
                                                <td>{{ $val->status_pembayaran->label }}</td>
                                                <td>
                                                    <a type='button' class="btn btn-sm btn-success"
                                                        wire:click="detailPembayaran({{ $val->id }})"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#staticBackdropPembayaran">Edit</a>
                                                    <a type='button' class="btn btn-sm btn-danger"
                                                        wire:click="deletePembayaran({{ $val->id }})">Hapus</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if (count($data_list_pembayaran) == 0)
                                            <tr>
                                                <td colspan="6" class="text-center">Tidak ada data</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td><span class="text-right fw-bold">Grand Total</span></td>
                                            <td colspan="2" class="fw-bold">Rp.
                                                {{ number_format($total, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td><span class="text-right fw-bold">Kurang</span></td>
                                            <td colspan="2" class="fw-bold">Rp.
                                                {{ number_format($data['harga'] - $total, 0, ',', '.') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="border-top border-bottom"></div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mt-3 mb-1">
                                    <div class="d-flex flex-row justify-content-between">
                                        <div>
                                            <h5 class="">Checklist</h5>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdropChecklist"
                                                wire:click="$emit('addButton')">Tambah</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th class="fw-bold">Keterangan</th>
                                            <th class="">Dibuat</th>
                                            <th class="">Nominal</th>
                                            <th class="">Action</th>
                                        </tr>
                                        @foreach ($data_list_checklist as $val)
                                            <tr>
                                                <td wire:key="item-{{ $val->id }}">
                                                    {{ $val->checklist->label }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($val->created_at)->format('d-m-Y H:i:s') }}
                                                <td>{{ number_format($val->value, 0, ',', '.') }}</td>
                                                <td>
                                                    <a type='button' class="btn btn-sm btn-success"
                                                        wire:click="detailChecklist({{ $val->id }})"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#staticBackdropChecklist">Edit</a>
                                                    <a type='button' class="btn btn-sm btn-danger"
                                                        wire:click="deleteChecklist({{ $val->id }})">Hapus</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if (count($data_list_checklist) == 0)
                                            <tr>
                                                <td colspan="4" class="text-center">Tidak ada data</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="border-top border-bottom"></div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mt-3 mb-1">
                                    <div class="d-flex flex-row justify-content-between">
                                        <div>
                                            <h5 class="">History Status Pesanan</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th class="fw-bold">Dibuat</th>
                                            <th>Tanggal</th>
                                            <th class="">Status</th>
                                        </tr>
                                        @foreach ($data['pesanan_history'] as $val)
                                            <tr>
                                                <td>{{ $val['created_by_']['name'] }}</td>
                                                <td>{{ \Carbon\Carbon::parse($val['created_at'])->format('d-m-Y H:i:s') }}
                                                <td>{{ $val['status'] }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-row justify-content-between">
                    <div>
                    </div>
                    <div>
                        <small style="margin-right: 15px">Order by <span
                                class="fw-bold">{{ $data['created_by']['name'] }}</span></small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div wire:ignore.self class="modal fade" id="staticBackdrop" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <form wire:submit.prevent="submitVehicle">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">List Mobil</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    @if ($formData['type_form'] == 0)
                                        <label>Gunakan Mobil Luar</label>
                                    @else
                                        <label>Gunakan Mobil Dalam</label>
                                    @endif
                                </div>
                                <div class="form-check form-switch ps-0">
                                    <input class="form-check-input mt-1 ms-auto" type="checkbox"
                                        wire:model="formData.type_form">
                                </div>
                            </div>
                            <div>
                                @if ($formData['type_form'] == 0)
                                    <div class="form-group" wire:ignore>
                                        <label>Mobil Anda</label>
                                        <select id="selectMobil" wire:model.self="formData.idmobil"
                                            class="form-control">
                                            <option value="">Pilih Mobil anda</option>
                                            @foreach ($list_mobil as $option)
                                                <option value="{{ $option['idmobil'] }}">
                                                    {{ $option['nama_mobil'] }}
                                                    -
                                                    {{ $option['nopol'] }}</option>
                                            @endforeach
                                        </select>
                                        <script>
                                            $(document).ready(function() {
                                                $('#selectMobil').select2({
                                                    theme: "bootstrap-5",
                                                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                                                        'style',
                                                    placeholder: $(this).data('placeholder'),
                                                });
                                                $('#selectMobil').on('change', function(e) {
                                                    var data = $('#selectMobil').select2("val");
                                                    @this.set('formData.idmobil', data);
                                                });

                                            });
                                        </script>
                                    </div>
                                @endif
                            </div>
                            <div>
                                @if ($formData['type_form'] == 1)
                                    <div class="form-group" wire:ignore>
                                        <label>Rekan Anda</label>
                                        <select id="selectRekan" wire:model.self="formData.idrekanan_mitra"
                                            class="form-control">
                                            <option value="">Pilih Rekan anda</option>
                                            @foreach ($list_rekan as $option)
                                                <option value="{{ $option['idrekanan_mitra'] }}">
                                                    {{ $option['nama_rekanan'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('formData.idrekanan_mitra')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <label>Kendaraan Rekan Anda</label>
                                        <select id="selectMobilRekan" wire:model.self="formData.mobil_rekan"
                                            class="form-control">
                                        </select>
                                        @error('formData.mobil_rekan')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <hr>
                                        <label>Opsional* <small>Jika kendaraan tidak ada di list</small></label>
                                        <div class="form-group">
                                            <label>Nopol</label>
                                            <input type="text" wire:model="formData.nopol" class="form-control"
                                                placeholder="Type here...">
                                            @error('formData.nopol')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        {{-- <div class="form-group">
                                            <label>Nopol Mobil</label>
                                            <input type="text" wire:model="formData.mobil_rekan"
                                                class="form-control" placeholder="Type here...">
                                            @error('formData.mobil_rekan')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div> --}}
                                        <script>
                                            $(document).ready(function() {
                                                $('#selectRekan').select2({
                                                    theme: "bootstrap-5",
                                                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                                                        'style',
                                                    placeholder: $(this).data('placeholder'),
                                                });

                                                $('#selectRekan').on('change', function(e) {
                                                    var data_ = $('#selectRekan').select2("val");
                                                    @this.set('formData.idrekanan_mitra', data_);

                                                    var array = @json($list_mobil_rekan);
                                                    var html = '';
                                                    html += '<option value="">Pilih Nopol anda</option>';
                                                    array.forEach(element => {
                                                        if (element.idrekanan_mitra == data_)
                                                            html += '<option value="' + element.id + '">' + element.nopol + '</option>';
                                                    });
                                                    $('#selectMobilRekan').html(html);
                                                });



                                                $('#selectMobilRekan').select2({
                                                    theme: "bootstrap-5",
                                                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                                                        'style',
                                                    placeholder: $(this).data('placeholder'),
                                                });
                                                $('#selectMobilRekan').on('change', function(e) {
                                                    var data = $('#selectMobilRekan').select2("val");
                                                    @this.set('formData.mobil_rekan', data);
                                                });
                                            });
                                        </script>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Modal Pemesanan --}}
        <div wire:ignore.self class="modal fade" id="staticBackdropPemesanan" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <form wire:submit.prevent="submitPemesanan">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Isi Form Pemesan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Harga</label>
                                <input type="number" wire:model="formPemesanan.harga" class="form-control"
                                    placeholder="Type here..." min='0'>
                                @error('formPengeluaran.harga')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Nama Customer</label>
                                <input type="text" wire:model="formPemesanan.nama_pemesan" class="form-control"
                                    placeholder="Type here...">
                                @error('formPengeluaran.nama_pemesan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Nomor Customer</label>
                                <input type="number" wire:model="formPemesanan.nomor_pemesan" class="form-control"
                                    placeholder="Type here...">
                                @error('formPengeluaran.nomor_pemesan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Alamat Customer</label>
                                <input type="text" wire:model="formPemesanan.alamat_pemesan" class="form-control"
                                    placeholder="Type here...">
                                @error('formPengeluaran.alamat_pemesan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-check form-switch ps-0 d-flex flex-row justify-content-between">
                                <div>
                                    <span>Pesan Untuk Tamu ?</span>
                                </div>
                                <div>
                                    <input class="form-check-input mt-1 ms-auto" type="checkbox" id="useDriver"
                                        wire:model="formPemesanan.is_pesanan_tamu">
                                </div>
                            </div>
                            @if ($formPemesanan['is_pesanan_tamu'])
                                <div class="form-group">
                                    <label>Nama Tamu</label>
                                    <input type="text" wire:model="formPemesanan.nama_tamu" class="form-control"
                                        placeholder="Type here...">
                                    @error('formPemesanan.nama_tamu')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Nomor Tamu</label>
                                    <input type="number" wire:model="formPemesanan.nomor_tamu" class="form-control"
                                        placeholder="Type here...">
                                    @error('formPemesanan.nomor_tamu')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                            <div class="form-group">
                                <label>Catatan</label>
                                <textarea wire:model="formPemesanan.description" class="form-control" placeholder="Type here..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Modal Driver --}}
        <div wire:ignore.self class="modal fade" id="staticBackdropDriver" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <form wire:submit.prevent={{ $formDriver['action'] }}>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">List Pembayaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div>
                                <div class="form-group" wire:ignore>
                                    <label>Driver</label>
                                    <select id="selectDriver" wire:model="formDriver.iddriver" class="form-control">
                                        <option value="">Pilih Driver</option>
                                        @foreach ($list_driver as $option)
                                            <option value="{{ $option['iddriver'] }}">
                                                {{ $option['nama'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('formDriver.iddriver')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <script>
                                    // only running when called on detail modal
                                    window.addEventListener('select-detail-driver', event => {
                                        $('#selectDriver').select2({
                                            theme: "bootstrap-5",
                                            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass(
                                                'w-100') ? '100%' : 'style',
                                            placeholder: $(this).data('placeholder'),
                                        });
                                        $('#selectDriver').on('change', function(e) {
                                            var data = event.detail.iddriver;
                                            data = $(this).val();
                                            @this.set('formDriver.iddriver', data);
                                        });
                                    })

                                    $(document).ready(function() {
                                        $('#selectDriver').select2({
                                            theme: "bootstrap-5",
                                            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                                                'style',
                                            placeholder: $(this).data('placeholder'),
                                        });
                                        $('#selectDriver').on('change', function(e) {
                                            var data = $('#selectDriver').select2("val");
                                            @this.set('formDriver.iddriver', data);
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Modal Tgl Selesai --}}
        <div wire:ignore.self class="modal fade" id="staticBackdropTglSelesai" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <form wire:submit.prevent={{ $formTanggalSelesai['action'] }}>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Tgl Selesai</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div>
                                <div class="form-group" wire:ignore>
                                    <label>Jam selesai</label>
                                    <div style="display: flex">
                                        <input type="date"
                                            wire:model.defer="formTanggalSelesai.date_selesai"class="form-control w-60"
                                            placeholder="Masukkan Tanggal">
                                        <span class="mx-2"></span>
                                        <input type="time"
                                            wire:model.defer="formTanggalSelesai.jam_selesai"class="form-control w-35"
                                            placeholder="Jam">
                                    </div>
                                </div>
                                <div div style="display: flex">
                                    <div>
                                        @error('formTanggalSelesai.date_selesai')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <span class="mx-2"></span>
                                    <div>
                                        @error('formTanggalSelesai.time_selesai')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Pengeluaran -->
        <div wire:ignore.self class="modal fade" id="staticBackdropPengeluaran" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <form wire:submit.prevent="{{ $formPengeluaran['action'] }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">List Pengeluaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div>
                                <div class="form-group" wire:ignore>
                                    <label>Pengeluaran</label>
                                    <select id="selectPengeluaran"
                                        wire:model.self="formPengeluaran.iditem_order_pengeluaran"
                                        class="form-control">
                                        <option value="">Pilih Pengeluaran</option>
                                        @foreach ($list_pengeluaran as $option)
                                            <option value="{{ $option['iditem_order_pengeluaran'] }}">
                                                {{ $option['nama'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('formPengeluaran.iditem_order_pengeluaran')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <div class="form-group">
                                        <label>Jumlah</label>
                                        <input type="number" wire:model="formPengeluaran.jumlah"
                                            class="form-control" placeholder="Type here..." min='0'>
                                        @error('formPengeluaran.jumlah')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <script>
                                        window.addEventListener('select-detail-pengeluaran', event => {
                                            $('#selectPengeluaran').select2({
                                                theme: "bootstrap-5",
                                                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass(
                                                    'w-100') ? '100%' : 'style',
                                                placeholder: $(this).data('placeholder'),
                                            });
                                            $('#selectPengeluaran').on('change', function(e) {
                                                var data = event.detail.iditem_order_pengeluaran;
                                                data = $(this).val();
                                                @this.set('formPengeluaran.iditem_order_pengeluaran', data);
                                            });
                                        })

                                        $(document).ready(function() {
                                            $('#selectPengeluaran').select2({
                                                theme: "bootstrap-5",
                                                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                                                    'style',
                                                placeholder: $(this).data('placeholder'),
                                            });
                                            $('#selectPengeluaran').on('change', function(e) {
                                                var data = $('#selectPengeluaran').select2("val");
                                                @this.set('formPengeluaran.iditem_order_pengeluaran', data);
                                            });
                                        });

                                        window.addEventListener('reset-form', event => {
                                            @this.set('formPengeluaran.iditem_order_pengeluaran', '');
                                        })
                                        // @this.set('formPengeluaran.jumlah', null);
                                        // @this.set('formPembayaran.keterangan', null);
                                        // @this.set('formPembayaran.iditem_methode_pembayaran', '');
                                        // @this.set('formPembayaran.iditem_status_pembayaran', '');
                                        // @this.set('formPembayaran.nominal', null);
                                        // @this.set('formChecklist.iditem_checklist', null);
                                        // @this.set('formChecklist.value', null);
                                    </script>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                id="exit-modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Modal Pembayaran --}}
        <div wire:ignore.self class="modal fade" id="staticBackdropPembayaran" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <form wire:submit.prevent={{ $formPembayaran['action'] }}>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">List Pembayaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div>
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <input type="text" wire:model="formPembayaran.keterangan" class="form-control"
                                        placeholder="Type here..." min='0'>
                                    @error('formPembayaran.keterangan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group" wire:ignore>
                                    <label>Methode Pembayaran</label>
                                    <select id="selectMethodePembayaran"
                                        wire:model="formPembayaran.iditem_methode_pembayaran" class="form-control">
                                        <option value="">Pilih Methode Pembayaran</option>
                                        @foreach ($list_methode_pembayaran as $option)
                                            <option value="{{ $option['iditem_methode_pembayaran'] }}">
                                                {{ $option['label'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('formPembayaran.iditem_methode_pembayaran')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group" wire:ignore>
                                    <label>Status Pembayaran</label>
                                    <select id="selectPembayaran" wire:model="formPembayaran.iditem_status_pembayaran"
                                        class="form-control">
                                        <option value="">Pilih Status Pembayaran</option>
                                        @foreach ($list_pembayaran as $option)
                                            <option value="{{ $option['iditem_status_pembayaran'] }}">
                                                {{ $option['label'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('formPembayaran.iditem_status_pembayaran')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Nominal</label>
                                    <input type="number" wire:model="formPembayaran.nominal" class="form-control"
                                        placeholder="Type here..." min='0'>
                                    @error('formPembayaran.nominal')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <script>
                                    // only running when called on detail modal
                                    window.addEventListener('select-detail', event => {
                                        $('#selectPembayaran').select2({
                                            theme: "bootstrap-5",
                                            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass(
                                                'w-100') ? '100%' : 'style',
                                            placeholder: $(this).data('placeholder'),
                                        });
                                        $('#selectPembayaran').on('change', function(e) {
                                            var data = event.detail.iditem_status_pembayaran;
                                            data = $(this).val();
                                            @this.set('formPembayaran.iditem_status_pembayaran', data);
                                        });

                                        $('#selectMethodePembayaran').select2({
                                            theme: "bootstrap-5",
                                            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass(
                                                'w-100') ? '100%' : 'style',
                                            placeholder: $(this).data('placeholder'),
                                        });
                                        $('#selectMethodePembayaran').on('change', function(e) {
                                            var data = event.detail.iditem_methode_pembayaran;
                                            data = $(this).val();
                                            @this.set('formPembayaran.iditem_methode_pembayaran', data);
                                        });
                                    })

                                    $(document).ready(function() {
                                        $('#selectPembayaran').select2({
                                            theme: "bootstrap-5",
                                            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                                                'style',
                                            placeholder: $(this).data('placeholder'),
                                        });
                                        $('#selectPembayaran').on('change', function(e) {
                                            var data = $('#selectPembayaran').select2("val");
                                            @this.set('formPembayaran.iditem_status_pembayaran', data);
                                        });
                                        $('#selectMethodePembayaran').select2({
                                            theme: "bootstrap-5",
                                            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                                                'style',
                                            placeholder: $(this).data('placeholder'),
                                        });
                                        $('#selectMethodePembayaran').on('change', function(e) {
                                            var data = $('#selectMethodePembayaran').select2("val");
                                            @this.set('formPembayaran.iditem_methode_pembayaran', data);
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                id="exit-modal-pembayaran">Tutup</button>
                            <button type="submit" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Modal Checklist --}}
        <div wire:ignore.self class="modal fade" id="staticBackdropChecklist" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <form wire:submit.prevent={{ $formChecklist['action'] }}>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">List Pembayaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                wire:click='resetChecklist'></button>
                        </div>
                        <div class="modal-body">
                            <div>
                                <div class="form-group" wire:ignore>
                                    <label>Checklist</label>
                                    <select id="selectChecklist" wire:model="formChecklist.iditem_checklist"
                                        class="form-control">
                                        <option value="">Pilih Checklist</option>
                                        @foreach ($list_checklist as $option)
                                            <option value="{{ $option['iditem_checklist'] }}">
                                                {{ $option['label'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('formChecklist.iditem_checklist')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Nilai</label>
                                    <input type="number" wire:model="formChecklist.value" class="form-control"
                                        placeholder="Type here..." min='0'>
                                    @error('formChecklist.value')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <script>
                                    // only running when called on detail modal
                                    window.addEventListener('select-detail-checklist', event => {
                                        $('#selectChecklist').select2({
                                            theme: "bootstrap-5",
                                            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass(
                                                'w-100') ? '100%' : 'style',
                                            placeholder: $(this).data('placeholder'),
                                        });
                                        $('#selectChecklist').on('change', function(e) {
                                            var data = event.detail.iditem_checklist;
                                            data = $(this).val();
                                            @this.set('formChecklist.iditem_checklist', data);
                                        });
                                    })

                                    $(document).ready(function() {
                                        $('#selectChecklist').select2({
                                            theme: "bootstrap-5",
                                            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                                                'style',
                                            placeholder: $(this).data('placeholder'),
                                        });
                                        $('#selectChecklist').on('change', function(e) {
                                            var data = $('#selectChecklist').select2("val");
                                            @this.set('formChecklist.iditem_checklist', data);
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                id="exit-modal-checklist">Tutup</button>
                            <button type="submit" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        window.addEventListener('close-modal', event => {
            $('#exit-modal').click();
        })
        window.addEventListener('close-modal-pembayaran', event => {
            $('#exit-modal-pembayaran').click();
        })
        window.addEventListener('close-modal-checklist', event => {
            $('#exit-modal-checklist').click();
        })

        window.addEventListener('open-modal-pemesanan', event => {
            $('#staticBackdrop').modal('show');
        })

        // reset-select 2
        window.addEventListener('reset-select2', event => {
            $('#selectPengeluaran').val('').trigger('change');
            $('#selectMethodePembayaran').val('').trigger('change');
            $('#selectPembayaran').val('').trigger('change');
            $('#selectChecklist').val('').trigger('change');
        })
    </script>
    <style>
        .timeline-cs {
            border-left: 1px solid hsl(0, 0%, 90%);
            position: relative;
            list-style: none;
        }

        .timeline-cs .timeline-cs-item {
            position: relative;
        }

        .timeline-cs .timeline-cs-item:after {
            position: absolute;
            display: block;
            top: 0;
        }

        .timeline-cs .timeline-cs-item:after {
            /* background-color: hsl(0, 0%, 90%); */
            left: -38px;
            border-radius: 50%;
            height: 11px;
            width: 11px;
            content: "";
        }
    </style>
</div>
