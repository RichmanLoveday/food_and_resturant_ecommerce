@extends('frontend.layout.master')
@section('content')
    @include('frontend.common-component.breadcrumb')
    <section class="fp__testimonial_page mt_95 xs_mt_65 mb_100 xs_mb_70">
        <div class="container">
            <div class="row">
                @foreach ($testimonials as $testimonial)
                    <div class="col-xl-4 wow fadeInUp" data-wow-duration="1s">
                        <div class="fp__single_testimonial">
                            <div class="fp__testimonial_header d-flex flex-wrap align-items-center">
                                <div class="img">
                                    <img src="{{ asset($testimonial->image) }}" alt="clients" class="img-fluid w-100">
                                </div>
                                <div class="text">
                                    <h4>{{ $testimonial->name }}</h4>
                                    <p>{{ $testimonial->title }}</p>
                                </div>
                            </div>
                            <div class="fp__single_testimonial_body">
                                <p class="feedback">{{ $testimonial->review }}</p>
                                <span class="rating">
                                    @for ($i = 1; $i <= $testimonial->rating; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                    {{-- <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i> --}}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $testimonials->links('frontend.common-component.pagination') }}
        </div>
    </section>
@endsection
