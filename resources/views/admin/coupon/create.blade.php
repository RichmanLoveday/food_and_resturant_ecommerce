@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Coupon</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>Create Coupon</h4>

            </div>
            <div class="card-body">
                <form action="{{ route('admin.coupon.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Coupon Code</label>
                        <input type="text" name="code" value="{{ old('code') }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Coupon Quantity</label>
                        <input type="number" name="quantity" value="{{ old('quantity') }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Minimum Purchase Amount</label>
                        <input type="number" name="min_purchase_amount" value="{{ old('min_purchase_amount') }}"
                            class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Expire Date</label>
                        <input type="date" name="expire_date" value="{{ old('expire_date') }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Discount Type</label>
                        <select name="discount_type" id="" class="form-control">
                            <option value="percent">Percent</option>
                            <option value="amount">Amount ({{ config('settings.site_currency_icon') }})</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Discount Amount</label>
                        <input type="number" name="discount" value="{{ old('discount') }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </section>
@endsection
