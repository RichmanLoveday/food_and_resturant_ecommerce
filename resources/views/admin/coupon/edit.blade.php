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
                <form action="{{ route('admin.coupon.update', $coupon->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" value="{{ $coupon->name }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Coupon Code</label>
                        <input type="text" name="code" value="{{ $coupon->code }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Coupon Quantity</label>
                        <input type="number" name="quantity" value="{{ $coupon->quantity }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Minimum Purchase Amount</label>
                        <input type="number" name="min_purchase_amount" value="{{ $coupon->min_purchase_amount }}"
                            class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Expire Date</label>
                        <input type="date" name="expire_date" value="{{ $coupon->expire_date }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Discount Type</label>
                        <select name="discount_type" id="" class="form-control">
                            <option @selected($coupon->discount_type === 'percent') value="percent">Percent</option>
                            <option @selected($coupon->discount_type === 'amount') value="amount">Amount
                                ({{ config('settings.site_currency_icon') }})</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Discount Amount</label>
                        <input type="number" name="discount" value="{{ $coupon->discount }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="" class="form-control">
                            <option @selected($coupon->status) value="1">Active</option>
                            <option @selected(!$coupon->status) value="0">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </section>
@endsection
