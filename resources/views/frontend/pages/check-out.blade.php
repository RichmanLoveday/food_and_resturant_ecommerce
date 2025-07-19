@extends('frontend.layout.master')
@section('content')
    @include('frontend.common-component.breadcrumb')
    <section class="fp__cart_view mt_125 xs_mt_95 mb_100 xs_mb_70">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-7 wow fadeInUp" data-wow-duration="1s">
                    <div class="fp__checkout_form">
                        <div class="fp__check_form">
                            <h5>select address <a href="#" data-bs-toggle="modal" data-bs-target="#address_modal"><i
                                        class="far fa-plus"></i> add address</a></h5>

                            <div class="fp__address_modal">
                                <div class="modal fade" id="address_modal" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="address_modalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="address_modalLabel">add new address
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="fp_dashboard_new_address d-block">
                                                    <form action="{{ route('address.store') }}" method="POST">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <h4>add new address</h4>
                                                            </div>
                                                            <div class="col-md-12 col-lg-12 col-xl-12">
                                                                <div class="fp__check_single_form">
                                                                    <select class="nice-select" name="area">
                                                                        <option value="">Select Area</option>
                                                                        @foreach ($deliveryAreas as $area)
                                                                            <option value="{{ $area->id }}">
                                                                                {{ $area->area_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-lg-12 col-xl-6">
                                                                <div class="fp__check_single_form">
                                                                    <input type="text" name="first_name"
                                                                        placeholder="First Name">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-lg-12 col-xl-6">
                                                                <div class="fp__check_single_form">
                                                                    <input type="text" name="last_name"
                                                                        placeholder="Last Name">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6 col-lg-12 col-xl-6">
                                                                <div class="fp__check_single_form">
                                                                    <input type="text" name="phone"
                                                                        placeholder="Phone">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6 col-lg-12 col-xl-6">
                                                                <div class="fp__check_single_form">
                                                                    <input type="email" name="email"
                                                                        placeholder="Email">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12 col-lg-12 col-xl-12">
                                                                <div class="fp__check_single_form">
                                                                    <textarea cols="3" rows="4" name="address" placeholder="Address"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="fp__check_single_form check_area">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" name="type"
                                                                            value="home" type="radio"
                                                                            name="flexRadioDefault" id="flexRadioDefault1">
                                                                        <label class="form-check-label"
                                                                            for="flexRadioDefault1">
                                                                            home
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" name="type"
                                                                            value="office" type="radio"
                                                                            name="flexRadioDefault" id="flexRadioDefault2">
                                                                        <label class="form-check-label"
                                                                            for="flexRadioDefault2">
                                                                            office
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex justify-content-center gap-4">
                                                                <button type="button"
                                                                    class="common_btn cancel_new_address">cancel</button>
                                                                <button type="submit" class="common_btn">save
                                                                    address</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                @foreach ($addresses as $address)
                                    @php
                                        $icon_type = 'fa-home';
                                        $checked = '';
                                        if ($address->type === 'office') {
                                            $icon_type = 'fa-car-building';
                                        }

                                        if ($address->id === Session::get('address')) {
                                            $checked = 'checked';
                                        }
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="fp__checkout_single_address">
                                            <div class="form-check">
                                                <input class="form-check-input v_address" value="{{ $address->id }}"
                                                    type="radio" name="flexRadioDefault"
                                                    id="home_{{ $address->id }}"{{ $checked }}>
                                                <label class="form-check-label" for="home_{{ $address->id }}">
                                                    <span class="icon"><i class="fas {{ $icon_type }}"></i>
                                                        home</span>
                                                    <span class="address">{{ $address->address }},
                                                        {{ $address->deliveryArea?->area_name }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 wow fadeInUp" data-wow-duration="1s">
                    <div id="sticky_sidebar" class="fp__cart_list_footer_button">
                        @php
                            $discount = Session::get('coupon')['discount'] ?? 0;
                            $finalTotal = $discount > 0 ? cartTotal() - $discount : cartTotal();
                            $deliveryCharge = Session::get('delivery_charge') ?? 00.0;
                        @endphp
                        <h6>total cart</h6>
                        <p>subtotal: <span>{{ currencyPosition(cartTotal()) }}</span></p>
                        <p>delivery: <span id="delivery_fee"> {{ config('settings.site_currency_icon') }}
                                {{ $deliveryCharge }}</span></p>

                        <p>discount: <span id="discount">{{ config('settings.site_currency_icon') }}
                                {{ $discount }}</span></p>
                        <p class="total"><span>total:</span> <span
                                id="grand_total">{{ currencyPosition(grandCartTotal($deliveryCharge)) }}</span></p>
                        <a class="common_btn" href="#" id="proceed_pmt_button">Proceed To Payment</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.v_address').prop('checked', false);

            $('.v_address').on('click', function() {
                let address = $(this).val();
                let deliveryFee = $('#delivery_fee');
                let grandTotal = $('#grand_total');

                $.ajax({
                    method: 'GET',
                    url: '{{ route('checkout.delivery.cal', ':id') }}'.replace(":id", address),
                    beforeSend: function() {
                        showLoader();
                    },
                    success: function(response) {
                        deliveryFee.text("{{ currencyPosition(':amount') }}".replace(
                            ":amount", response.delivery_fee.toFixed(2)));
                        grandTotal.text("{{ currencyPosition(':amount') }}".replace(
                            ":amount", response.grand_total.toFixed(2)))
                    },
                    error: function(xhr, status, error) {
                        let errorMsg = xhr.responseJSON.message;
                        toastr.error(errorMsg);
                    },
                    complete: function() {
                        hideLoader();
                    }
                });
            });

            $('#proceed_pmt_button').on('click', function(e) {
                e.preventDefault();

                let address = $('.v_address:checked').val();

                if (!address || address.length === 0) {
                    toastr.error('Please select an Address!');
                    return;
                }

                $.ajax({
                    method: 'POST',
                    url: '{{ route('checkout.redirect') }}',
                    data: {
                        id: address
                    },
                    beforeSend: function() {
                        showLoader();
                    },
                    success: function(response) {
                        window.location.href = response.redirect_url;
                    },
                    error: function(xhr, status, error) {
                        let errorMsg = xhr.responseJSON.message;
                        toastr.success(errorMsg);
                    },
                    complete: function() {
                        hideLoader();
                    }
                });
            });
        });
    </script>
@endpush
