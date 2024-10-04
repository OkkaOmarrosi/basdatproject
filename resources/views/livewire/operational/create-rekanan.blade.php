<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Form</h5>
                        </div>
                        <a href="{{ route('operational.rekanan') }}" class="btn bg-gradient-primary btn-sm mb-0"
                            type="button"><i class="fa fa-undo" aria-hidden="true"></i>
                            &nbsp; Kembali</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="p-3">
                        <form wire:submit.prevent="saveOrUpdate">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" wire:model.defer="nama_rekanan" class="form-control"
                                    placeholder="Type Nama here...">
                                @error('nama_rekanan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>No. Handphone</label>
                                <input type="number" wire:model.defer="no_hp_rekanan"class="form-control"
                                    placeholder="Type phone here...">
                                <div>
                                    @error('no_hp_rekanan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Alamat</label>
                                <input type="text" wire:model.defer="alamat_rekanan"class="form-control"
                                    placeholder="Alamat rekan" id='alamat'>
                                <div>
                                    @error('alamat_rekanan')
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
                                var inputTo = document.getElementById('alamat');
                                var autocompleteTo = new google.maps.places.Autocomplete(inputTo, options);

                                autocompleteTo.addListener('place_changed', function() {
                                    var place = autocompleteTo.getPlace();
                                    @this.set('alamat_rekanan', place.formatted_address);
                                });
                            </script>

                            <button class="btn bg-gradient-success btn-sm mb-0" type="submit">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
