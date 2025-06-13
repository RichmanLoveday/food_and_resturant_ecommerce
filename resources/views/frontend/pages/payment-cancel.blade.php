@extends('frontend.layout.master')
@section('content')
    @include('frontend.common-component.breadcrumb')
    <section class="fp__payment_page mt_100 xs_mt_70 mb_100 xs_mb_70">
        <div class="container">
            <div class="row">
                <div class="text-center col-lg-12">
                    <div>
                        <i class="fas fa-times-circle fs-1 rounded-circle p-4 bg-danger text-white"></i>
                    </div>
                    <h4 class="mt-4">Transaction Failed!</h4>
                    <p>
                        {{ session()->has('errors') ? session('errors')->first('error') : '' }}
                    </p>
                    <a href="" class="common_btn mt-4">Go To Payment Page</a>
                </div>
            </div>
        </div>
    </section>
@endsection
