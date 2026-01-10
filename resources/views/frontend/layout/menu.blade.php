@php
    $mainMenu = \Menu::getByName('main_menu');
@endphp

<nav class="navbar navbar-expand-lg main_menu">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset(config('settings.logo')) }}" alt="FoodPark" class="img-fluid">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="far fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav m-auto">
                <li class="nav-item"><a class="nav-link" aria-current="page" href="/">Home</a></li>
                @if ($mainMenu)
                    @foreach ($mainMenu as $menu)
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ $menu['link'] }}">{{ $menu['label'] }}
                                @if ($menu['child'])
                                    <i class="far fa-angle-down"></i>
                                @endif
                            </a>

                            @if ($menu['child'])
                                <ul class="droap_menu">
                                    @foreach ($menu['child'] as $item)
                                        <li><a href="{{ $item['link'] }}">{{ $item['label'] }}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                @endif
            </ul>
            <ul class="menu_icon d-flex flex-wrap">
                <li>
                    <a href="#" class="menu_search"><i class="far fa-search"></i></a>
                    <div class="fp__search_form">
                        <form action="{{ route('product.index') }}">
                            <span class="close_search"><i class="far fa-times"></i></span>
                            <input name="search" type="text" placeholder="Search . . .">
                            <button type="submit">search</button>
                        </form>
                    </div>
                </li>
                <li>
                    <a class="cart_icon"><i class="fas fa-shopping-basket"></i> <span
                            class="cart_count">{{ count(Cart::content()) }}</span></a>
                </li>
                @php
                    $unseenMessages = App\Models\Chat::where('receiver_id', auth()->id())
                        ->where('seen', false)
                        ->where('sender_id', '!=', auth()->id())
                        ->count();
                @endphp

                <li>
                    <a href="{{ route('dashboard') }}" class="message_icon">
                        <i class="fas fa-comment-alt-dots"></i>
                        <span class="unseen-messages-count">{{ $unseenMessages > 0 ? $unseenMessages : 0 }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('login') }}"><i class="fas fa-user"></i></a>
                </li>
                <li>
                    <a class="common_btn" href="#" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop">reservation</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="fp__menu_cart_area">
    <div class="fp__menu_cart_boody">
        <div class="fp__menu_cart_header">
            <h5>total item (<span class="cart_count fs-5">{{ count(Cart::content()) }}</span>)</h5>
            <span class="close_cart"><i class="fal fa-times"></i></span>
        </div>
        <ul class="cart_content"></ul>
        <p class="subtotal">sub total <span class="cart_subtotal">{{ currencyPosition(cartTotal()) }}</span></p>
        <a class="cart_view" href="{{ route('cart.index') }}"> view cart</a>
        <a class="checkout" href="{{ route('checkout.index') }}">checkout</a>
    </div>
</div>

@php
    $reservationTime = \App\Models\ReservationTime::where('status', true)->latest()->get();
@endphp
<div class="fp__reservation">
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Book a Table</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="fp__reservation_form" action="{{ route('reservation.store') }}" method="POST">
                        @csrf

                        <input class="reservation_input" type="text" name="name" placeholder="Name">
                        <input class="reservation_input" type="number" name="phone" placeholder="Phone">
                        <input class="reservation_input" name="date" type="date"
                            min="{{ \Carbon\Carbon::today()->toDateString() }}">
                        <select class="reservation_input nice-select" name="time">
                            <option value="">select time</option>
                            @foreach ($reservationTime as $time)
                                <option value="{{ $time->start_time }}-{{ $time->end_time }}">
                                    {{ $time->start_time }} to {{ $time->end_time }}</option>
                            @endforeach
                        </select>
                        <input type="text" class="reservation_input" placeholder="Persons" name="persons">
                        <button type="submit" class="btn_submit">book table</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.fp__reservation_form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    method: 'POST',
                    url: '{{ route('reservation.store') }}',
                    data: formData,
                    beforeSend: function() {
                        $('.btn_submit').html(`
                        <span class="spinner-border text-white"></span>
                        `);
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        $('.fp__reservation_form').trigger("reset");
                        $('#staticBackdrop').modal('hide');

                    },
                    error: function(xhr, status, error) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(index, value) {
                            toastr.error(value);
                        });
                    },
                    complete: function() {
                        $('.btn_submit').html("book table");
                    },
                })
            })
        });
    </script>
@endpush
