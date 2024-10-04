<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">List Pesanan</h5>
                        </div>
                        <a href="{{ route('operational.create-order') }}" class="btn bg-gradient-primary btn-sm mb-0"
                            type="button">+&nbsp; Buat Pesanan</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="p-3">
                        <table id="datatables" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nopol</th>
                                    <th>Nama Pesanan</th>
                                    <th>Nomor Pesanan</th>
                                    <th>Layanan</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    var passedArray = <?php echo json_encode($data); ?>;

    $(document).ready(function() {
        $('#datatables').DataTable({
            data: passedArray,
            columns: [{
                    data: 'kode'
                },
                {
                    data: 'nopol'
                },
                {
                    data: 'nama_pemesan'
                },
                {
                    data: 'nomor_pemesan'
                },
                {
                    data: 'layanan_id',
                    render: function(data, type, row) {
                        if (data == 1)
                            $html = '<span style="background-color: red;color: white;padding: 4px 8px; text-align: center;border-radius: 5px;">Banyak Tujuan</span>';
                        else if (data == 2)
                            $html = '<span style="background-color: green;color: white;padding: 4px 8px; text-align: center;border-radius: 5px;">DropOff</span>';
                        else if (data == 3)
                            $html = '<span style="background-color: blue;color: white;padding: 4px 8px; text-align: center;border-radius: 5px;">Lepas Kunci</span>';
                        else if (data == 4)
                            $html = '<span style="background-color: yellow;color: white;padding: 4px 8px; text-align: center;border-radius: 5px;">Bulanan</span>';
                        return $html;
                    }
                },
                {
                    data: 'status'
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return `
                            <a href="/operational/detail-order/${row.id}" class="btn btn-sm btn-success">Detail</a>
                        `;
                    }
                }
            ]
        });
    });

    // document.addEventListener('DOMContentLoaded', function() {
    //     document.querySelectorAll('.delete-user').forEach(function(deleteButton) {
    //         deleteButton.addEventListener('click', function(event) {
    //             event.preventDefault();

    //             var userId = this.getAttribute('data-user-id');
    //             var confirmation = confirm('Are you sure you want to delete this user?');

    //             if (confirmation) {
    //                 Livewire.emit('deleteUser', userId);
    //             }
    //         });
    //     });
    // });
</script>
