<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-50">
                            <h8 class="mb-0">List Mobil</h8>
                        </div>
                        <div class="col-50">
                            <div class="row">
                            <div class="col-50" wire:ignore>
                                <div class="form-group" >
                                    <label>Bulan</label>
                                    <select id="selectMonth"
                                        wire:model.defer="formMonth"
                                        class="form-control">
                                        <option value="">Semua Bulan</option>
                                        @foreach ($months as $key => $option)
                                            <option value="{{ $option->month ?? '' }}">
                                                {{ $option->bulan ?? '' }}</option>
                                        @endforeach
                                    </select>
                                    <script>
                                        $(document).ready(function() {
                                            $('#selectMonth').select2({
                                                theme: "bootstrap-5",
                                                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                                                    'style',
                                                placeholder: $(this).data('placeholder'),
                                            });
                                            $('#selectMonth').on('change', function(e) {
                                                var data = $('#selectMonth').select2("val");
                                                @this.set('formMonth', data);
                                            });
                                        });

                                        window.addEventListener('reset-form', event => {
                                            @this.set('formMonth', '');
                                        })
                                    </script>
                                </div>
                            </div>
                            <div class="col-50">
                                <div class="form-group" wire:ignore>
                                    <label>Tahun</label>
                                    <select id="selectYears"
                                        wire:model.defer="formYear"
                                        class="form-control">
                                        <option value="">Tahun</option>
                                        @foreach ($years as $option)
                                            <option value="{{ $option }}">
                                                {{ $option }}</option>
                                        @endforeach
                                    </select>
                                    <script>
                                        $(document).ready(function() {
                                            $('#selectYears').select2({
                                                theme: "bootstrap-5",
                                                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                                                    'style',
                                                placeholder: $(this).data('placeholder'),
                                            });
                                            $('#selectYears').on('change', function(e) {
                                                var data = $('#selectYears').select2("val");
                                                @this.set('formYear', data);
                                            });
                                        });

                                        window.addEventListener('reset-form', event => {
                                            @this.set('formYear', '');
                                        })
                                    </script>
                                </div>
                            </div>
                            {{-- <button wire:click='generateDate(2024)'>Refresh Year</button> --}}
                        </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="p-4">
                        <div class="row" style="margin-top: 5px">
                            <div class="table-container">
                                <table>
                                    <thead>
                                        <tr>
                                            @php
                                                $counts = 1;
                                            @endphp
                                            @foreach ($data as $item)
                                            @if ($counts == 1)
                                                @foreach ($item->calendar as $val)
                                                    <th>
                                                        <h8>{{ $val->hari }}</h8>
                                                        <br>
                                                        <h8>{{ \Carbon\Carbon::parse($val->full_date)->format('d M Y') }}</h8>
                                                    </th>
                                                @endforeach
                                                @php
                                                    $counts++;
                                                @endphp
                                            @endif
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                @foreach ($item->calendar as $val)
                                                @if ($val->status == 'booked')

                                                    @php
                                                        $count = 1;
                                                    @endphp
                                                    <td style="padding: 0; border: 1px solid black; height: 50px;" wire:click='createPesanan({{ $val->day }}, {{ $val->month }}, {{ $val->year }}, {{ $item->idmobil }})'>
                                                    @if (count($val->pesananList) > 0)
                                                        @foreach ($val->pesananList as $vv)
                                                        @php
                                                            $color = '';
                                                            if(strtoupper($vv->status) == strtoupper('draft')){
                                                                $colors = '#ffbd00';
                                                                $color_font = 'black';
                                                            } else if(strtoupper($vv->status) == strtoupper('Terjadwal')){
                                                                $colors = '#44b544';
                                                                $color_font = 'black';
                                                            } else if(strtoupper($vv->status) == strtoupper('Proses')){
                                                                $colors = '#365de8';
                                                                $color_font = 'black';
                                                            } else if(strtoupper($vv->status) == strtoupper('Close')){
                                                                $colors = '#c88400';
                                                                $color_font = 'black';
                                                            } else if(strtoupper($vv->status) == strtoupper('Expired')){
                                                                $colors = 'black';
                                                                $color_font = 'white';
                                                            } elseif (strtoupper($vv->status) == strtoupper('Off')) {
                                                                $colors = 'black';
                                                                $color_font = 'white';
                                                            } elseif (strtoupper($vv->status) == strtoupper('Cancel')) {
                                                                $colors = 'red';
                                                                $color_font = 'white';
                                                            }
                                                        @endphp
                                                        {{-- {{ \Carbon\Carbon::parse($vv->tgl_selesai)->format('d-m-Y H:i:s') }} --}}
                                                            <div style='background-color: {{ $colors }}; color: {{ $color_font }};  font-size: 10px'> {{ $vv->kode }} <br> {{ \Carbon\Carbon::parse($vv->tgl_selesai)->format('d M Y H:i:s') }}
                                                        @endforeach
                                                    @else
                                                        <div class="btnr" style="background-color: red; color: black;">
                                                    @endif
                                                @else
                                                    <td style="padding: 0; border: 1px solid black; height: 50px;">
                                                    @if(date('Y-m-d') > $val->full_date)
                                                    <div class="btnr" style="background-color: white; color: black;">
                                                    @else
                                                    <div class="btnr" style="background-color: white; color: black;" wire:click='createPesanan({{ $val->day }}, {{ $val->month }}, {{ $val->year }}, {{ $item->idmobil }})'>
                                                    @endif
                                                @endif
                                                    <br>
                                                    <h8>
                                                        {{ $item->tipe_mobil->nama_mobil }}
                                                    </h8>
                                                    <br>
                                                    <h8 style="white-space: nowrap;">
                                                        [ {{ $item->nopol }} ]
                                                    </h8>
                                                </div>
                                                </td>
                                                @endforeach
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
</div>

<style>
      * {
            /* box-sizing: border-box; */
        }

        .col-20 {
            float: left;
            width: 20%;
        }

        .col-50 {
            float: left;
            width: 50%;
        }

        .col-80 {
            float: left;
            width: 80%;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .btnr {
            width: 100%;
            padding: 2px
        }
        .table-container {
            overflow-x: auto;
            overflow-y: auto;
            max-height: 800px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 100;
            box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
        }

        th, td {
            padding: 0;
            text-align: center;
            border: 1px solid black;
        }
</style>
