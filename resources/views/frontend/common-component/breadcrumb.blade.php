<!--=============================
                        BREADCRUMB START
                    ==============================-->
<section class="fp__breadcrumb" style="background: url({{ asset('frontend/images/counter_bg.jpg') }});">
    <div class="fp__breadcrumb_overlay">
        <div class="container">
            <div class="fp__breadcrumb_text">
                <h1>{{ $breadCrumb['title'] }}</h1>
                <ul>
                    <li><a href="{{ route('home') }}">home</a></li>
                    <li><a href="{{ $breadCrumb['link'] }}">{{ $breadCrumb['title'] }}</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--=============================
                        BREADCRUMB END
                    ==============================-->
