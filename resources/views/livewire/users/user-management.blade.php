<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">All Users</h5>
                        </div>
                        <a href="{{ route('user.create') }}" class="btn bg-gradient-primary btn-sm mb-0"
                            type="button">+&nbsp; New User</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="p-3">
                        <livewire:table.user-table theme="bootstrap-5" />
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-user').forEach(function(deleteButton) {
            deleteButton.addEventListener('click', function(event) {
                event.preventDefault();

                var userId = this.getAttribute('data-user-id');
                var confirmation = confirm('Are you sure you want to delete this user?');

                if (confirmation) {
                    Livewire.emit('deleteUser', userId);
                }
            });
        });
    });
</script>
