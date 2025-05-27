@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Delivery Area</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Create Delivery Area</h4>

            </div>
            <div class="card-body">
                <form action="{{ route('admin.delivery-area.update', $deliveryArea->id) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Area Name</label>
                        <input type="text" name="area_name" value="{{ $deliveryArea->area_name }}" class="form-control">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Min Delivery Time</label>
                                <input type="text" name="min_delivery_time"
                                    value="{{ $deliveryArea->min_delivery_time }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Max Delivery Time</label>
                                <input type="text" name="max_delivery_time"
                                    value="{{ $deliveryArea->max_delivery_time }}" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Delivery Fee</label>
                                <input type="number" name="delivery_fee" value="{{ $deliveryArea->delivery_fee }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" id="" class="form-control">
                                    <option @selected($deliveryArea->status) value="1">Active</option>
                                    <option @selected(!$deliveryArea->status) value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </section>
@endsection
