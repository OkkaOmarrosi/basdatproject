<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Transaksi</h5>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                wire:click="$emit('addButton')">Tambah</button>
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
                                <input type="date" wire:model.defer="formData.start_date"class="form-control w-50"
                                    placeholder="2023/01/01">
                                <input type="date" wire:model.defer="formData.end_date"class="form-control w-50"
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
                                    <td class="w-25"><strong>Jumlah</strong></td>
                                    <td class="w-5">:</td>
                                    <td class="w-75">{{ $total }}</td>
                                </tr>
                                <tr style="color: green">
                                    <td class="w-25"><span>Uang Masuk</span></td>
                                    <td class="w-5">:</td>
                                    <td class="w-75">Rp. {{ number_format($uang_masuk, 0, ',', '.') }}</td>
                                </tr>
                                <tr style="color: red">
                                    <td class="w-25"><span>Uang Keluar</span></td>
                                    <td class="w-5">:</td>
                                    <td class="w-75">Rp. {{ number_format($uang_keluar, 0, ',', '.') }}</td>
                                </tr>
                                <tr style="color: {{ $html }}">
                                    <td class="w-25"><span>Total Pendapatan Bersih</span></td>
                                    <td class="w-5">:</td>
                                    <td class="w-75">Rp. {{ number_format($uang_total, 0, ',', '.') }}</td>
                                </tr>
                        </table>
                        <div class="row w-100 text-center mt-3">
                            <div class="column">
                                <button class="btn btn-success" style="width: 100%">
                                    <h6 class="mb-0" style="color: white">Uang Masuk</h6>
                                    <h6 style="color: white">
                                        Transfer
                                    </h6><br>
                                    <h5 style="color: white">
                                        Rp. {{ number_format($total_masuk_transfer, 0, ',', '.') }}
                                    </h5>
                                </button>
                            </div>
                            <div class="column">
                                <button class="btn btn-success" style="width: 100%">
                                    <h6 class="mb-0" style="color: white">Uang Masuk</h6>
                                    <h6 style="color: white">
                                        Cash
                                    </h6><br>
                                    <h5 style="color: white">
                                        Rp. {{ number_format($total_masuk_cash, 0, ',', '.') }}
                                    </h5>
                                </button>
                            </div>
                            <div class="column">
                                <button class="btn btn-danger" style="width: 100%">
                                    <h6 class="mb-0" style="color: white">Uang Keluar</h6>
                                    <br>
                                    <br>
                                    <br>
                                    <h5 style="color: white">
                                        Rp. {{ number_format($total_keluar, 0, ',', '.') }}
                                    </h5>
                                </button>
                            </div>
                        </div>
                        <div class="p-4 row table-responsive">
                            {{-- <div class="d-flex flex-row">
                                <div class="form w-80">
                                    <input type="text" wire:model.defer="search" class="form-control"
                                        placeholder="Search ..">
                                </div>
                                <span class="mx-1"></span>
                                <button class="btn btn-success" wire:click="search()">Filter</button>
                            </div> --}}
                            <div class="w-100">
                                <table class="table w-100" x-data style="background-color: rgb(253, 253, 253)">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Dibuat</th>
                                            <th>Keterangan</th>
                                            <th>Metode</th>
                                            <th>Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($merge as $v)
                                            <tr
                                                style="color : {{ $v['warna'] }}; border-radius: 15px ; background-color: ">
                                                <td>{{ $v['tanggal_dibuat'] ?? ' - ' }}</td>
                                                <td>{{ $v['created_by']['name'] ?? ' - ' }}</td>
                                                <td>{{ $v['keterangan'] ?? ($v['order_pengeluaran']['label'] ?? ' - ') }}
                                                    {{ $v['header'] == null ? '' : ' (Pesanan Kode : ' . $v['header']['kode'] . ')' }}
                                                </td>
                                                <td>{{ $v['methode_pembayaran']['nama'] ?? ' - ' }}</td>
                                                <td>{{ number_format($v['harga'], 0, ',', '.') }}</td>
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
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <form wire:submit.prevent="submitTransaksi">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Tambah Transaksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="type">Tipe Transaksi</label><br>
                            <input type="radio" wire:model="formTransaksi.type" value="1" name="type"
                                id="type"> Pemasukan
                            <input type="radio" wire:model="formTransaksi.type" value="2" name="type"
                                id="type"> Pengeluaran
                        </div>
                        <div class="form-group">
                            <label>Nominal</label>
                            <input type="number" wire:model.defer="formTransaksi.nominal"class="form-control"
                                placeholder="Isi nominal" min='0'>
                        </div>
                        @if ($formTransaksi['type'] == 1)
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea wire:model.defer="formTransaksi.keterangan" class="form-control" placeholder="Isi keterangan"></textarea>
                            </div>
                        @elseif ($formTransaksi['type'] == 2)
                            <div>
                                <div class="form-group" wire:ignore>
                                    <label>Pengeluaran</label>
                                    <select id="selectPengeluaran"
                                        wire:model.defer="formTransaksi.iditem_order_pengeluaran"
                                        class="form-control">
                                        <option value="">Pilih Pengeluaran</option>
                                        @foreach ($list_pengeluaran as $option)
                                            <option value="{{ $option['iditem_order_pengeluaran'] }}">
                                                {{ $option['label'] }}</option>
                                        @endforeach
                                    </select>
                                    <script>
                                        $(document).ready(function() {
                                            $('#selectPengeluaran').select2({
                                                theme: "bootstrap-5",
                                                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                                                    'style',
                                                placeholder: $(this).data('placeholder'),
                                            });
                                            $('#selectPengeluaran').on('change', function(e) {
                                                var data = $('#selectPengeluaran').select2("val");
                                                @this.set('formTransaksi.iditem_order_pengeluaran', data);
                                            });
                                        });

                                        window.addEventListener('reset-form', event => {
                                            @this.set('formTransaksi.iditem_order_pengeluaran', '');
                                        })
                                    </script>
                                </div>
                            </div>
                        @endif
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                id="exit-modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </div>
                </div>
        </form>
    </div>


    <script>
        window.addEventListener('close-modal', event => {
            $('#exit-modal').click();
        })
    </script>

    <style>
        .column {
            float: left;
            width: 25%;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</div>
