@extends('frontend.layout.master')
@section('content')
    @include('frontend.common-component.breadcrumb')
    <section class="fp__team_page pt_95 xs_pt_65 pb_100 xs_pb_70">
        <div class="container">
            <div class="row">
                @foreach ($chefs as $chef)
                    <div class="col-xl-3 col-sm-6 col-lg-4 wow fadeInUp" data-wow-duration="1s">
                        <div class="fp__single_team">
                            <div class="fp__single_team_img">
                                <img src="{{ asset($chef->image) }}" alt="team" class="img-fluid w-100">
                            </div>
                            <div class="fp__single_team_text">
                                <h4>{{ $chef->name }}</h4>
                                <p>{{ $chef->title }}</p>
                                <ul class="d-flex flex-wrap justify-content-center">
                                    @if ($chef->fb)
                                        <li><a target="_blank" href="{{ $chef->fb }}"><i
                                                    class="fab fa-facebook-f"></i></a></li>
                                    @endif

                                    @if ($chef->in)
                                        <li><a target="_blank" href="{{ $chef->in }}"><i
                                                    class="fab fa-linkedin-in"></i></a></li>
                                    @endif

                                    @if ($chef->x)
                                        <li><a target="_blank" href="{{ $chef->x }}"><i class="fab fa-twitter"></i></a>
                                        </li>
                                    @endif

                                    @if ($chef->web)
                                        <li><a target="_blank" href="{{ $chef->web }}"><i class="fas fa-link"></i></a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $chefs->links('frontend.common-component.pagination') }}
        </div>
    </section>
@endsection
