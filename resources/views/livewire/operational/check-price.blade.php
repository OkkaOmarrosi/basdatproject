<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Cek Harga</h5>
                        </div>
                        {{-- <a href="{{ route('operational.check-price') }}" class="btn bg-gradient-primary btn-sm mb-0"
                            type="button"><i class="fa fa-undo" aria-hidden="true"></i>
                            &nbsp; Kembali</a> --}}
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="p-3">
                        <form wire:submit.prevent="submit">
                            <div class="form-group" wire:ignore>
                                <label>Jenis Kendaraan</label>
                                <select id="typeVehicleSelect" wire:model.defer="idtipe_mobil" class="form-control">
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
                                <label>Type Layanan</label>
                                <select id="typeLayananSelect" wire:model.defer="layanan_id" class="form-control">
                                    <option value="null">Pilih layanan...</option>
                                    <option value="1">Banyak Tujuan</option>
                                    <option value="2">DropOff</option>
                                </select>
                            </div>
                            <div>
                                @error('layanan_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group" wire:ignore>
                                <label>Waktu Penjemputan</label>
                                <div style="display: flex">
                                    <input type="date" wire:model.defer="date_mulai"class="form-control w-60"
                                        placeholder="2023/01/01">
                                    <span class="mx-2"></span>
                                    <input type="time" wire:model.defer="time_mulai"class="form-control w-35"
                                        placeholder="06:30">
                                </div>
                            </div>
                            <div div style="display: flex">
                                <div>
                                    @error('date_mulai')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <span class="mx-2"></span>
                                <div>
                                    @error('time_mulai')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                @if ($layanan_id == 1)
                                    <div class="form-group" wire:ignore>
                                        <label>Jam selesai</label>
                                        <div style="display: flex">
                                            <input type="date"
                                                wire:model.defer="date_selesai"class="form-control w-60"
                                                placeholder="2023/01/01">
                                            <span class="mx-2"></span>
                                            <input type="time"
                                                wire:model.defer="time_selesai"class="form-control w-35"
                                                placeholder="06:30">
                                        </div>
                                    </div>
                                    <div div style="display: flex">
                                        <div>
                                            @error('date_selesai')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <span class="mx-2"></span>
                                        <div>
                                            @error('time_selesai')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group" wire:ignore>
                                <label>Tempat Penjemputan</label>
                                <input type="text" wire:model.defer="address_from"class="form-control"
                                    placeholder="Pilih Lokasi penjemputan here..." id='address-from'>
                            </div>
                            <div>
                                @error('address_from')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @if ($layanan_id == 1)
                                <div>
                                    <div class="form-group">
                                        <label>Tempat Tujuan</label>
                                        @foreach ($this->destination as $key => $item)
                                            <div style="display: flex; margin-top: 10px">
                                                <input type="text" wire:model.defer="destination.{{ $key }}"
                                                    class="form-control" placeholder="Pilih Lokasi tujuan here..."
                                                    id="destination-to-{{ $key }}"
                                                    wire:key="destination-field-{{ $key }}">
                                                @if ($key > 0)
                                                    <a class="btn bg-gradient-warning btn-sm mb-0 mx-2"
                                                        wire:click="removeDestination({{ $key }})">Delete</a>
                                                @endif
                                            </div>
                                            <script>
                                                var options = {
                                                    componentRestrictions: {
                                                        country: "id"
                                                    },
                                                    types: ['geocode'],
                                                    language: 'id'
                                                };
                                                var inputDes = document.getElementById('destination-to-{{ $key }}');
                                                var autocompleteDes = new google.maps.places.Autocomplete(inputDes, options);
                                                autocompleteDes.addListener('place_changed', function() {
                                                    var place = autocompleteDes.getPlace();
                                                    if (!place.geometry) {
                                                        console.log("Place details not found for the input: '" + place.name + "'");
                                                        return;
                                                    }

                                                    let full_place = "";
                                                    place.address_components.forEach(element => {
                                                        full_place += element.long_name + ", ";
                                                    });

                                                    @this.set('destination.{{ $key }}', full_place);
                                                });
                                            </script>
                                        @endforeach
                                    </div>
                                    <a class="btn bg-gradient-success btn-sm mb-0" wire:click="addDestination">Add
                                        Destination</a>


                                    <div class="form-group">
                                        <label>Tempat Turun</label>
                                        <input type="text" wire:model.defer="address_to"class="form-control"
                                            placeholder="Pilih Lokasi tujuan here..." id='address-to'>
                                        <div>
                                            @error('address_to')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <script>
                                        var options = {
                                            componentRestrictions: {
                                                country: "id"
                                            },
                                            types: ['geocode'],
                                            language: 'id'
                                        };
                                        var inputTo = document.getElementById('address-to');
                                        var autocompleteTo = new google.maps.places.Autocomplete(inputTo, options);

                                        autocompleteTo.addListener('place_changed', function() {
                                            var place = autocompleteTo.getPlace();
                                            if (!place.geometry) {
                                                console.log("Place details not found for the input: '" + place.name + "'");
                                                return;
                                            }

                                            let full_place = "";
                                            place.address_components.forEach(element => {
                                                full_place += element.long_name + ", ";
                                            });

                                            @this.set('address_to', full_place);
                                        });
                                    </script>
                                </div>
                            @endif
                            <div>
                                @if ($layanan_id == 2)
                                    <div class="form-group">
                                        <label>Tempat Tujuan</label>
                                        <input type="text" wire:model.defer="address_to"class="form-control"
                                            placeholder="Pilih Lokasi tujuan here..." id='address-to'>
                                        <div>
                                            @error('address_to')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <script>
                                        var options = {
                                            componentRestrictions: {
                                                country: "id"
                                            },
                                            types: ['geocode'],
                                            language: 'id'
                                        };
                                        var inputTo = document.getElementById('address-to');
                                        var autocompleteTo = new google.maps.places.Autocomplete(inputTo, options);

                                        autocompleteTo.addListener('place_changed', function() {
                                            var place = autocompleteTo.getPlace();
                                            @this.set('address_to', place.formatted_address);
                                        });
                                    </script>
                                @endif
                            </div>

                            <button class="btn bg-gradient-success btn-sm mb-0" type="submit">Submit</button>
                            {{-- <a class="btn bg-gradient-success btn-sm mb-0" type="button"
                                wire:click='submitDummy'>Submit Dummy</a> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('#typeVehicleSelect').select2({
                    theme: "bootstrap-5",
                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                        'style',
                    placeholder: $(this).data('placeholder'),
                });
                $('#typeVehicleSelect').on('change', function(e) {
                    var data = $('#typeVehicleSelect').select2("val");
                    @this.set('idtipe_mobil', data);
                });

                $('#typeLayananSelect').select2({
                    theme: "bootstrap-5",
                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                        'style',
                    placeholder: $(this).data('placeholder'),
                });
                $('#typeLayananSelect').on('change', function(e) {
                    var data = $('#typeLayananSelect').select2("val");
                    @this.set('layanan_id', data);
                });

                var options = {
                    componentRestrictions: {
                        country: "id"
                    },
                    types: ['geocode'],
                    language: 'id'
                };

                var inputFrom = document.getElementById('address-from');
                var autocompleteFrom = new google.maps.places.Autocomplete(inputFrom, options);

                autocompleteFrom.addListener('place_changed', function() {
                    var place = autocompleteFrom.getPlace();
                    @this.set('address_from', place.formatted_address);
                });

            });
        </script>
    </div>
</div>
