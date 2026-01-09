<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densityDpi=device-dpi" />
    <meta name="description" content="{{ config('settings.seo_description') }}">
    <meta name="keywords" content="{{ config('settings.seo_keywords') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('og_metatag_section')
    <title>{{ config('settings.seo_title') }}</title>
    <link rel="icon" type="image/png" href="{{ asset(config('settings.favicon')) }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/spacing.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/venobox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/jquery.exzoom.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/toastr.min.css') }}">

    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/responsive.css') }}">
    <!-- <link rel="stylesheet" href="('frontend/css/rtl.css')"> -->

    <script>
        var pusherKey = "{{ config('settings.pusher_key') }}";
        var pusherCluster = "{{ config('settings.pusher_cluster') }}";
        var loggedInUserId = "{{ auth()->check() ? auth()->user()->id : '' }}";
    </script>
    @vite(['resources/js/app.js', 'resources/js/frontend.js'])

</head>
<style>
    :root {
        --colorPrimary: {{ config('settings.site_color') ?? '#F86F03' }};
    }
</style>

<body>
    <!--=============================
        TOPBAR START
    ==============================-->
    <section class="fp__topbar">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-md-8">
                    <ul class="fp__topbar_info d-flex flex-wrap">
                        @if (config('settings.site_email'))
                            <li><a href="mailto:{{ config('settings.site_email') }}"><i class="fas fa-envelope"></i>
                                    {{ config('settings.site_email') }}</a>
                            </li>
                        @endif

                        @if (config('settings.site_phone'))
                            <li><a href="callto:{{ config('settings.site_phone') }}"><i class="fas fa-phone-alt"></i>
                                    {{ config('settings.site_phone') }}</a></li>
                        @endif
                    </ul>
                </div>

                @php
                    $socialLinks = \App\Models\SocialLink::where('status', 1)->get();
                @endphp
                <div class="col-xl-6 col-md-4 d-none d-md-block">
                    <ul class="topbar_icon d-flex flex-wrap">
                        @foreach ($socialLinks as $link)
                            <li><a href="{{ $link->link }}"><i class="{{ $link->icon }}"></i></a> </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        TOPBAR END
    ==============================-->


    <!--=============================
        MENU START
    ==============================-->
    @include('frontend.layout.menu')
    <!--=============================
        MENU END
    ==============================-->


    @yield('content')
    {{-- {{ Cart::destroy() }} --}}

    @include('frontend.layout.footer')


    <!--=============================
        SCROLL BUTTON START
    ==============================-->
    <div class="fp__scroll_btn">
        go to top
    </div>
    <!--=============================
        SCROLL BUTTON END
    ==============================-->

    <!-- CART POPUT START -->
    <div class="fp__cart_popup">
        <div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content load_product_modal_body">

                </div>
            </div>
        </div>
    </div>
    <!-- CART POPUT END -->

    <div class="overlay-container">
        <div class="overlay">
            <span class="loader"></span>
        </div>
    </div>


    {{-- {{ session()->flush() }} --}}


    <!--jquery library js-->
    <script src="{{ asset('frontend/js/jquery-3.6.0.min.js') }}"></script>
    <!--bootstrap js-->
    <script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
    <!--font-awesome js-->
    <script src="{{ asset('frontend/js/Font-Awesome.js') }}"></script>
    <!-- slick slider -->
    <script src="{{ asset('frontend/js/slick.min.js') }}"></script>
    <!-- isotop js -->
    <script src="{{ asset('frontend/js/isotope.pkgd.min.js') }}"></script>
    <!-- simplyCountdownjs -->
    <script src="{{ asset('frontend/js/simplyCountdown.js') }}"></script>
    <!-- counter up js -->
    <script src="{{ asset('frontend/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.countup.min.js') }}"></script>
    <!-- nice select js -->
    <script src="{{ asset('frontend/js/jquery.nice-select.min.js') }}"></script>
    <!-- venobox js -->
    <script src="{{ asset('frontend/js/venobox.min.js') }}"></script>
    <!-- sticky sidebar js -->
    <script src="{{ asset('frontend/js/sticky_sidebar.js') }}"></script>
    <!-- wow js -->
    <script src="{{ asset('frontend/js/wow.min.js') }}"></script>
    <!-- ex zoom js -->
    <script src="{{ asset('frontend/js/jquery.exzoom.js') }}"></script>

    <script src="{{ asset('frontend/js/toastr.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--main/custom js-->
    <script src="{{ asset('frontend/js/main.js') }}"></script>

    <script>
        // set csrf token for ajx request
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        $(document).ready(function() {
            $('.button-click').click();
        });
    </script>

    <!--- show dynamic error messages  --->
    <script>
        toastr.options.progressBar = true;
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>

    @include('frontend.layout.global-scripts')

    <script>
        $(document).ready(function() {
            //? update side bar cart when page loads
            updateSideBarCart();
        });
    </script>

    @stack('scripts')
</body>

</html>
