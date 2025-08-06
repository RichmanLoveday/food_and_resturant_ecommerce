@extends('frontend.layout.master')
@section('content')
    @include('frontend.common-component.breadcrumb')
    <section class="fp__terms_condition mt_120 xs_mt_90 mb_100 xs_mb_70">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 wow fadeInUp" data-wow-duration="1s">
                    <div class="fp__career_det_text">
                        {!! @$termsAndCondition->content !!}
                        <a href="{{ route('home') }}" class="common_btn">go home</a>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
