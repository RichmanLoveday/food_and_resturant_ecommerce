@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Clear Database</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Clear Database</h4>
                <div class="card-header-action">

                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-warning alert-has-icon">
                    <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                    <div class="alert-body">
                        <div class="alert-title">Warning</div>
                        If you fire this action it will wipe your entire database.
                    </div>

                    <form method="post" class="mt-2 clear-db">
                        <button class="btn btn-danger btn-sm fw-bolder submit_button"><b>Clear Database</b></button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('body').on('submit', '.clear-db', function(e) {
                e.preventDefault();
                let url = $(this).attr('href');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('admin.clear-database.destroy') }}',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            type: 'POST',
                            beforeSend: function() {
                                $('.submit_button').prop("disabled", true);
                                $('.submit_button').html(
                                    `<span class="spinner-border text-primary" role="status"></span><b>Clearing..</b>`
                                );
                            },
                            success: function(res) {
                                if (res.status == 'success') {
                                    toastr.success(res.message);
                                    // //$('.table').DataTable().draw();
                                    // window.location.reload();

                                    $('.submit_button').prop("disabled", false);
                                    $('.submit_button').html(
                                        `<b>Clear Database</b>`
                                    );
                                } else if (res.status == 'error') {
                                    toastr.error(res.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                let errorMessage = xhr.responseJSON.message;
                                toastr.error(errorMessage);
                            },
                        });
                    }
                });
            });
        });
    </script>
@endpush
