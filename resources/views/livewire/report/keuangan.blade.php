<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Keuangan</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="p-4 row table-responsive" wire:ignore>
                        <div class="d-flex flex-row justify-content-between">
                            <div>Periode Awal</div>
                            <div>Periode Akhir</div>
                        </div>
                        <div class="form-group" wire:ignore>
                            <div style="display: flex">
                                <input type="date" wire:model.defer="filterForm.start_date"class="form-control w-50"
                                    placeholder="2023/01/01">
                                <input type="date" wire:model.defer="filterForm.end_date"class="form-control w-50"
                                    placeholder="2023/01/01">
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-success" wire:click='filter'>Filter</button>
                            <button class="btn btn-warning" wire:click='resetFilter'>reset</button>
                        </div>
                    </div>
                    <div class="border"></div>
                    <div class="p-4 w-100">
                        <table class="w-100 mb-3">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <td class="w-25"><strong>Jumlah Invoice</strong></td>
                                    <td class="w-5">:</td>
                                    <td class="w-75">{{ count($data) }}</td>
                                </tr>
                                <tr>
                                    <td class="w-25"><span>Total Harga</span></td>
                                    <td class="w-5">:</td>
                                    <td class="w-75">Rp. {{ number_format($total_harga, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="w-25"><span>Lunas</span></td>
                                    <td class="w-5">:</td>
                                    <td class="w-75">Rp. {{ number_format($total_pembayaran, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="w-25"><span>Belum Lunas</span></td>
                                    <td class="w-5">:</td>
                                    <td class="w-75">Rp. {{ number_format($total_belum_lunas, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="w-25"><span>Pengeluaran</span></td>
                                    <td class="w-5">:</td>
                                    <td class="w-75">Rp. {{ number_format($total_pengeluaran, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="w-25"><span>Total Pendapatan Bersih</span></td>
                                    <td class="w-5">:</td>
                                    <td class="w-75">Rp. {{ number_format($total_laba_bersih, 0, ',', '.') }}</td>
                                </tr>
                        </table>
                        <div class="border"></div>
                        <div class="row w-100 text-center mt-3">
                            <div class="column">
                                <button class="btn btn-danger" style="width: 100%">
                                    <h6 class="mb-0" style="color: white">Draft</h6>
                                    <h6 style="color: white">
                                        {{ $count_draft }}
                                    </h6>
                                    <span>
                                        Rp. {{ number_format($total_draft, 0, ',', '.') }}
                                    </span>
                                </button>
                            </div>
                            <div class="column">
                                <button class="btn btn-warning" style="width: 100%">
                                    <h6 class="mb-0" style="color: white">Booking</h6>
                                    <h6 style="color: white">
                                        {{ $count_booking }}
                                    </h6>
                                    <span>
                                        Rp. {{ number_format($total_booking, 0, ',', '.') }}
                                    </span>
                                </button>
                            </div>
                            <div class="column">
                                <button class="btn btn-success" style="width: 100%">
                                    <h6 class="mb-0" style="color: white">Terjadwal</h6>
                                    <h6 style="color: white">
                                        {{ $count_terjadwal }}
                                    </h6>
                                    <span>
                                        Rp. {{ number_format($total_terjadwal, 0, ',', '.') }}
                                    </span>
                                </button>
                            </div>
                            <div class="column">
                                <button class="btn btn-info" style="width: 100%">
                                    <h6 class="mb-0" style="color: white">Proses</h6>
                                    <h6 style="color: white">
                                        {{ $count_proses }}
                                    </h6>
                                    <span>
                                        Rp. {{ number_format($total_proses, 0, ',', '.') }}
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="row w-100 text-center mt-3">\
                            <div class="column">
                                <button class="btn btn-secondary" style="width: 100%">
                                    <h6 class="mb-0" style="color: white">Close</h6>
                                    <h6 style="color: white">
                                        {{ $count_close }}
                                    </h6>
                                    <span>
                                        Rp. {{ number_format($total_close, 0, ',', '.') }}
                                    </span>
                                </button>
                            </div>
                            <div class="column">
                                <button class="btn btn-dark" style="width: 100%">
                                    <h6 class="mb-0" style="color: white">Expired</h6>
                                    <h6 style="color: white">
                                        {{ $count_expired }}
                                    </h6>
                                    <span>
                                        Rp. {{ number_format($total_expired, 0, ',', '.') }}
                                    </span>
                                </button>
                            </div>
                            <div class="column">
                                <button class="btn btn-light" style="width: 100%">
                                    <h6 class="mb-0" style="color: black">Off</h6>
                                    <h6 style="color: black">
                                        {{ $count_off }}
                                    </h6>
                                    <span>
                                        Rp. {{ number_format($total_off, 0, ',', '.') }}
                                    </span>
                                </button>
                            </div>
                            <div class="column">
                                <button class="btn btn-danger" style="width: 100%">
                                    <h6 class="mb-0" style="color: white">Cancel</h6>
                                    <h6 style="color: white">
                                        {{ $count_cancel }}
                                    </h6>
                                    <span>
                                        Rp. {{ number_format($total_cancel, 0, ',', '.') }}
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="border"></div>
                    <div class="p-4 row table-responsive">
                        <div class="d-flex flex-row">
                            <div class="form w-80">
                                <input type="text" wire:model.defer="search" class="form-control"
                                    placeholder="Search ..">
                            </div>
                            <span class="mx-1"></span>
                            <button class="btn btn-success" wire:click="search()">Filter</button>
                        </div>
                        <div class="w-100">
                            <table class="table w-100" x-data>
                                <thead>
                                    <tr>
                                        <th>Nama Pemesan</th>
                                        <th>Nomor Pemesan</th>
                                        <th>Tanggal</th>
                                        <th>Total Harga</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_search as $v)
                                        {{-- @php
                                        dd($v);
                                    @endphp --}}
                                        <tr>
                                            <td>{{ $v->nama_pemesan ?? ' - ' }}</td>
                                            <td>{{ $v->nomor_pemesan ?? ' - ' }}</td>
                                            <td>{{ $v->created_at ?? ' - ' }}</td>
                                            <td>Rp. {{ number_format($v->harga, 0, ',', '.') }}</td>
                                            <td>{{ $v->status ?? ' - ' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var data = @this.data_search;
        console.log(data, 'ini apa');
    </script>
    <style>
        * {
            /* box-sizing: border-box; */
        }

        .column {
            float: left;
            width: 23%;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</div>
