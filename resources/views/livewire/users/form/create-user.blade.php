<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Form</h5>
                        </div>
                        <a href="{{ route('user.management') }}" class="btn bg-gradient-primary btn-sm mb-0"
                            type="button"><i class="fa fa-undo" aria-hidden="true"></i>
                            &nbsp; Kembali</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="p-3">
                        <form wire:submit.prevent="saveOrUpdate">
                            <div class="form-group" wire:ignore>
                                <label>Mitra</label>
                                <select id="mitraSelect" wire:model="mitra_id" class="form-control">
                                    <option value="null">Pilih Mitra...</option>
                                    @foreach (DB::connection('mysql2')->table('mitra')->select('mitra.idmitra as id', 'mitra.nama_mitra as nama')->get() as $option)
                                        <option value="{{ $option->id }}">{{ $option->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                @error('mitra_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group" wire:ignore>
                                <label>Pool</label>
                                <select id="poolSelect" wire:model="pool_id" class="form-control">
                                    <option value="null">Pilih Pool...</option>
                                    @foreach (DB::connection('mysql2')->table('pool')->select('pool.idpool as id', 'pool.nama')->get() as $option)
                                        <option value="{{ $option->id }}">{{ $option->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                @error('pool_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" wire:model="name" class="form-control"
                                    placeholder="Type name here...">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" wire:model="email" class="form-control"
                                    placeholder="Type email here...">
                                <div>
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input type="text" wire:model="password" class="form-control"
                                    placeholder="Type password here...">
                                <div>
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label>No. Handphone</label>
                                <input type="number" wire:model="phone"class="form-control"
                                    placeholder="Type phone here...">
                                <div>
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Alamat</label>
                                <input type="text" wire:model="location"class="form-control"
                                    placeholder="Type location here...">
                                <div>
                                    @error('location')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Deskripsi</label>
                                <input type="text" wire:model="about"class="form-control"
                                    placeholder="Type about here...">
                                <div>
                                    @error('about')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <button class="btn bg-gradient-success btn-sm mb-0" type="submit">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    // Initialize Select2
    $(document).ready(function() {
        $('#poolSelect').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                'style',
            placeholder: $(this).data('placeholder'),
        });
        $('#poolSelect').on('change', function(e) {
            var data = $('#poolSelect').select2("val");
            @this.set('pool_id', data);
        });

        $('#mitraSelect').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                'style',
            placeholder: $(this).data('placeholder'),
        });
        $('#mitraSelect').on('change', function(e) {
            var data = $('#mitraSelect').select2("val");
            @this.set('mitra_id', data);
        });
    });
</script>
