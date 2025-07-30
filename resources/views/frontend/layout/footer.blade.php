@php
    $footerInfo = \App\Models\FooterInfo::first();
    $footerMenuOne = \Menu::getByName('footer_menu_one');
    $footerMenuTwo = \Menu::getByName('footer_menu_two');
    $footerMenuThree = \Menu::getByName('footer_menu_three');

@endphp

<footer>
    <div class="footer_overlay pt_100 xs_pt_70 pb_100 xs_pb_70">
        <div class="container wow fadeInUp" data-wow-duration="1s">
            <div class="row justify-content-between">
                <div class="col-lg-4 col-sm-8 col-md-6">
                    <div class="fp__footer_content">
                        <a class="footer_logo" href="index.html">
                            <img src="{{ asset(config('settings.footer_logo')) }}" alt="FoodPark" class="img-fluid w-100">
                        </a>
                        <span>{!! @$footerInfo->short_info !!}</span>
                        <p class="info"><i class="far fa-map-marker-alt"></i> {!! @$footerInfo->address !!}</p>
                        <a class="info" href="callto:1234567890123"><i class="fas fa-phone-alt"></i>
                            {!! @$footerInfo->phone !!}</a>
                        <a class="info" href="mailto:{{ @$footerInfo->email }}"><i class="fas fa-envelope"></i>
                            {!! @$footerInfo->email !!}</a>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-4 col-md-6">
                    <div class="fp__footer_content">
                        <h3>Short Link</h3>
                        <ul>
                            @foreach ($footerMenuOne as $menu)
                                <li><a href="{{ $menu['link'] }}">{{ $menu['label'] }}</a></li>
                            @endforeach

                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-4 col-md-6 order-sm-4 order-lg-3">
                    <div class="fp__footer_content">
                        <h3>Help Link</h3>
                        <ul>
                            @foreach ($footerMenuTwo as $menu)
                                <li><a href="{{ $menu['link'] }}">{{ $menu['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-8 col-md-6 order-lg-4">
                    <div class="fp__footer_content">
                        <h3>subscribe</h3>
                        <form class="subcribe_form" action="POST">
                            @csrf
                            <input type="text" placeholder="Subscribe" name="email">
                            <button type="submit" id="subscribe_btn">Subscribe</button>
                        </form>
                        <div class="fp__footer_social_link">
                            <h5>follow us:</h5>
                            @php
                                $socialLinks = \App\Models\SocialLink::where('status', 1)->get();
                            @endphp
                            <ul class="d-flex flex-wrap">
                                @foreach ($socialLinks as $link)
                                    <li><a href="{{ $link->link }}"><i class="{{ $link->icon }}"></i></a> </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="fp__footer_bottom d-flex flex-wrap">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="fp__footer_bottom_text d-flex flex-wrap justify-content-between">
                        <p>Copyright {{ date('Y') }} <b>FoodPark</b> All Rights Reserved.</p>
                        <ul class="d-flex flex-wrap">
                            @foreach ($footerMenuThree as $menu)
                                <li><a href="{{ $menu['link'] }}">{{ $menu['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.subcribe_form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();

                $.ajax({
                    url: '{{ route('subscribe-newsletter') }}',
                    method: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#subscribe_btn')
                            .attr('disabled', true)
                            .html(
                                `<span class="spinner-border spinner-border-sm"></span>`);
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        $('.subcribe_form').trigger('reset');
                    },
                    error: function(xhr, status, error) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(index, value) {
                            toastr.error(value);
                        });

                        //? enable button
                        $('#subscribe_btn')
                            .attr('disabled', false)
                            .html("Subscribe");
                    },
                    complete: function() {
                        $('#subscribe_btn')
                            .attr('disabled', false)
                            .html("Subscribe");
                    },
                })
            })
        });
    </script>
@endpush
