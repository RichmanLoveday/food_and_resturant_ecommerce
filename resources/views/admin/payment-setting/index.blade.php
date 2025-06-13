@extends('admin.layout.master')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Payment Setting</h1>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h4>All Payment Settings</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-2">
                        <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="paypal-tab" data-toggle="tab" href="#paypal-setting"
                                    role="tab" aria-controls="home" aria-selected="true">Paypal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="stripe-tap" data-toggle="tab" href="#stripe-setting" role="tab"
                                    aria-controls="profile" aria-selected="false">Stripe</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="razorpay-tap" data-toggle="tab" href="#razorpay-setting"
                                    role="tab" aria-controls="profile" aria-selected="false">Razorpay</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-sm-12 col-md-10">
                        <div class="tab-content no-padding" id="myTab2Content">
                            @include('admin.payment-setting.sections.paypal')
                            @include('admin.payment-setting.sections.stripe')
                            @include('admin.payment-setting.sections.razorpay')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    {{-- {{ $dataTable->scripts(attributes: ['type' => 'module']) }} --}}
@endpush
