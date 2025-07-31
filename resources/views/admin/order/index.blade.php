@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Orders</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>All Orders</h4>
            </div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </section>


    <!-- Modal -->
    <div class="modal fade" id="order_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <form class="order_status_form" action="" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="">Payment Status</label>
                                    <select class="form-control payment_status" name="payment_status" id="">
                                        <option name="" id="" value="pending">Pending</option>
                                        <option name="" id="" value="completed">Completed
                                        </option>
                                        <option name="" id="" value="failed">Failed
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="">Order Status</label>
                                    <select class="form-control order_status" name="order_status" id="">
                                        <option name="" id="" value="pending">Pending
                                        </option>
                                        <option name="" id="" value="in_process">In Process
                                        </option>
                                        <option name="" id="" value="delivered">Delivered
                                        </option>
                                        <option name="" id="" value="declined">Declined
                                        </option>
                                    </select>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary submit_btn">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script>
        $(document).ready(function() {
            var orderId = '';

            $(document).on('click', '#order_status', function() {
                let id = $(this).data('id');
                orderId = id;

                let payment_status = $('.payment_status option');
                let order_status = $('.order_status option');

                $.ajax({
                    method: 'GET',
                    url: '{{ route('admin.orders.status', ':id') }}'.replace(":id", id),
                    beforeSend: function() {
                        $('.submit_btn').prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            payment_status.each(function() {
                                if ($(this).val() === response.order.payment_status) {
                                    $(this).attr('selected', 'selected');
                                } else {
                                    $(this).removeAttr('selected');
                                }
                            });

                            order_status.each(function() {
                                if ($(this).val() === response.order.order_status) {
                                    $(this).attr('selected', 'selected');
                                } else {
                                    $(this).removeAttr('selected');
                                }
                            });
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('An error occurred while fetching order status.');
                    },
                    complete: function() {
                        $('.submit_btn').prop('disabled', false);
                    }
                });
            });

            $('.order_status_form').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let actionUrl = form.attr('action');

                $.ajax({
                    url: '{{ route('admin.orders.status-update', ':id') }}'.replace(":id", orderId),
                    type: 'POST',
                    data: form.serialize(),
                    beforeSend: function() {},
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#order_modal').modal('hide');
                            toastr.success(response.message);
                            $('#order-table').DataTable().draw();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('An error occurred while updating order status.');
                    },
                    complete: function() {}
                });
            });
        });
    </script>
@endpush
