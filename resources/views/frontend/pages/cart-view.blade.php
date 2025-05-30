@extends('frontend.layout.master')
@section('content')
    @include('frontend.common-component.breadcrumb')
    <section class="fp__cart_view mt_125 xs_mt_95 mb_100 xs_mb_70">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 wow fadeInUp" data-wow-duration="1s">
                    <div class="fp__cart_list">
                        <div class="table-responsive">
                            <table>
                                <tbody>
                                    <tr>
                                        <th class="fp__pro_img">
                                            Image
                                        </th>

                                        <th class="fp__pro_name">
                                            details
                                        </th>

                                        <th class="fp__pro_status">
                                            price
                                        </th>

                                        <th class="fp__pro_select">
                                            quantity
                                        </th>

                                        <th class="fp__pro_tk">
                                            total
                                        </th>

                                        <th class="fp__pro_icon">
                                            <a class="clear_all" href="{{ route('cart.destroy') }}">clear all</a>
                                        </th>
                                    </tr>
                                    @forelse (Cart::content() as $cartProduct)
                                        <tr>
                                            <td class="fp__pro_img"><img
                                                    src="{{ asset($cartProduct->options->product_info['image']) }}"
                                                    alt="product" class="img-fluid w-100">
                                            </td>

                                            <td class="fp__pro_name">
                                                <a
                                                    href="{{ route('product.show', $cartProduct->options->product_info['slug']) }}">{{ $cartProduct->name }}</a>
                                                <span>
                                                    {{ @$cartProduct->options->product_size['name'] }}
                                                    {{ @$cartProduct->options->product_size['price'] ? '(' . currencyPosition(@$cartProduct->options->product_size['price']) . ')' : '' }}
                                                </span>

                                                @foreach ($cartProduct->options->product_options as $option)
                                                    <p>{{ $option['name'] }} ({{ currencyPosition($option['price']) }})</p>
                                                @endforeach
                                            </td>

                                            <td class="fp__pro_status">
                                                <h6>{{ currencyPosition($cartProduct->price) }}</h6>
                                            </td>

                                            <td class="fp__pro_select">
                                                <div class="quentity_btn">
                                                    <button data-id="{{ $cartProduct->rowId }}"
                                                        class="btn btn-danger decrement"><i
                                                            class="fal fa-minus"></i></button>
                                                    <input class="quantity" type="text" value="{{ $cartProduct->qty }}"
                                                        placeholder="1" readonly>
                                                    <button data-id="{{ $cartProduct->rowId }}"
                                                        class="btn btn-success increment"><i
                                                            class="fal fa-plus"></i></button>
                                                </div>
                                            </td>

                                            <td class="fp__pro_tk">
                                                <h6 class="product_cart_total">
                                                    {{ currencyPosition(productTotal($cartProduct->rowId)) }}</h6>
                                            </td>

                                            <td class="fp__pro_icon">
                                                <a href="#" data-id="{{ $cartProduct->rowId }}"
                                                    class="remove_cart_product"><i class="far fa-times"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="d-inline  w-100">Cart is empty!</td>
                                        </tr>
                                    @endforelse
                                    {{-- @foreach (Cart::content() as $cartProduct)
                                    @endforeach --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 wow fadeInUp" data-wow-duration="1s">
                    <div class="fp__cart_list_footer_button">
                        @php
                            $discount = Session::get('coupon')['discount'] ?? 0;
                            $finalTotal = $discount > 0 ? cartTotal() - $discount : cartTotal();
                        @endphp
                        <h6>total cart</h6>
                        <p>subtotal: <span id="subTotal">{{ currencyPosition(cartTotal()) }}</span></p>
                        <p>delivery: <span id="delivery">$00.00</span></p>
                        <p>discount: <span id="discount">{{ config('settings.site_currency_icon') }}
                                {{ $discount }}</span></p>
                        <p class="total"><span>total:</span> <span
                                id="finalTotal">{{ config('settings.site_currency_icon') }} {{ $finalTotal }}</span></p>
                        <form id="coupon_form">
                            <input type="text" name="code" id="coupon_code" placeholder="Coupon Code">
                            <button type="submit">apply</button>
                        </form>

                        <div class="coupon_card">
                            @if (Session::has('coupon'))
                                <div class="card mt-2">
                                    <div class="d-flex justify-content-between align-items-center p-3">
                                        <span><b class="v_coupon_code">Applied Coupon:
                                                {{ Session::get('coupon')['code'] }}</b></span>
                                        <button type="button" class="remove_coupon" id="destroyCoupon"><i
                                                class="far fa-times"></i></button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <a class="common_btn" href="{{ route('checkout.index') }}">checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              CART VIEW END
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ==============================-->
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            //? increment quantity
            $('.increment').on('click', function(e) {
                let inputField = $(this).siblings(".quantity");
                let currValue = +inputField.val();
                let rowId = $(this).data('id');

                //? incement value 
                inputField.val(currValue + 1);

                // update cart quantity and handle callback 
                cartQtyUpdate(rowId, +inputField.val(), function(response) {
                    if (response.status === 'success') {
                        inputField.val(response.qty);
                        let productTotal = response.product_total;

                        //? change value of product cart total
                        $(e.target).closest('tr').find('.product_cart_total').text(
                            "{{ currencyPosition(':productTotal') }}".replace(":productTotal",
                                productTotal));

                        //? update value of sub total
                        $('#subTotal').text("{{ config('settings.site_currency_icon') }}" +
                            response.subTotal);

                        $('#finalTotal').text("{{ config('settings.site_currency_icon') }}" +
                            response.productTotal);

                        //? clear coupon code

                        //? update discount value
                        // $('#discount').text("{{ config('settings.site_currency_icon') }}" +
                        //     (response.discount ?? '0.00'));

                        //? update finale total
                        $('#finalTotal').text("{{ config('settings.site_currency_icon') }}" +
                            (response.grandTotal));
                        toastr.success(response.message);
                    } else {
                        inputField.val(response.qty);
                        toastr.error(response.message);
                    }
                });
            });

            //? decrement quantity
            $('.decrement').on('click', function(e) {
                let inputField = $(this).siblings(".quantity");
                let currValue = +inputField.val();
                let rowId = $(this).data('id');

                if (currValue > 1) {
                    //? decrement value 
                    inputField.val(currValue - 1);

                    // update cart quantity and handle callback 
                    cartQtyUpdate(rowId, +inputField.val(), function(response) {
                        if (response.status === 'success') {
                            inputField.val(response.qty);
                            let productTotal = response.product_total;

                            //? change value of product cart total
                            $(e.target).closest('tr').find('.product_cart_total').text(
                                "{{ currencyPosition(':productTotal') }}".replace(
                                    ":productTotal",
                                    productTotal));

                            $('#subTotal').text("{{ config('settings.site_currency_icon') }}" +
                                response.subTotal);

                            //? clear coupon code

                            //? update discount value
                            // $('#discount').text("{{ config('settings.site_currency_icon') }}" +
                            //     (response.discount ?? '0.00'));

                            //? update finale total
                            $('#finalTotal').text("{{ config('settings.site_currency_icon') }}" +
                                (response.grandTotal));
                            toastr.success(response.message);
                        } else {
                            inputField.val(response.qty);
                            toastr.error(response.message);
                        }
                    });
                }
            });


            function cartQtyUpdate(rowId, qty, callback) {
                $.ajax({
                    method: 'POST',
                    url: '{{ route('cart.quantity-update') }}',
                    data: {
                        rowId: rowId,
                        qty: qty,
                    },
                    beforeSend: function() {
                        showLoader();
                    },
                    success: function(response) {
                        //? update the side bar cart
                        updateSideBarCart();

                        //? return a call back when cartQty update is called
                        if (callback && typeof callback === 'function') {
                            callback(response);
                        }

                    },
                    error: function(xhr, status, error) {
                        let errorMessage = xhr.responseJSON.message;
                        hideLoader();
                        toastr.error(errorMessage);
                    },
                    complete: function(response) {
                        hideLoader();
                    }
                });
            }


            $('.remove_cart_product').on('click', function(e) {
                e.preventDefault();
                removeCartProduct($(this).data('id'));
                $(this).closest('tr').remove(); //? remove dom row
            });


            /** Remove cart product */
            function removeCartProduct(rowId) {
                $.ajax({
                    url: '{{ route('cart-product-remove', ['rowId' => '__ROW_ID__']) }}'
                        .replace(
                            '__ROW_ID__', rowId),

                    method: 'GET',
                    beforeSend: function() {
                        showLoader();
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            updateSideBarCart(); //? update side bar content

                            $('#subTotal').text("{{ config('settings.site_currency_icon') }}" +
                                response.subTotal);

                            //? clear coupon code

                            // //? update discount value
                            // $('#discount').text("{{ config('settings.site_currency_icon') }}" +
                            //     (response.discount ?? '0.00'));

                            //? update finale total
                            $('#finalTotal').text("{{ config('settings.site_currency_icon') }}" +
                                (response.grandTotal));
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = xhr.responseJSON.message;
                        toastr.error(errorMessage);
                    },
                    complete: function() {
                        hideLoader();
                    }
                })
            }


            /** apply coupon on submit */
            $('#coupon_form').on('submit', function(e) {
                e.preventDefault();

                let couponCode = $('#coupon_code').val();
                let subTotal = getCartTotal();

                if (couponCode) {
                    couponApply(couponCode, subTotal);
                } else {
                    toastr.error('Please enter a coupon code');
                }
            });

            /** apply coupon */
            function couponApply(code, subTotal) {
                $.ajax({
                    method: 'POST',
                    url: '{{ route('apply-coupon') }}',
                    data: {
                        code: code,
                        subTotal: subTotal,
                    },
                    beforeSend: function() {
                        showLoader();
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            //? update the html
                            $('#discount').text("{{ config('settings.site_currency_icon') }} " +
                                response.discount);
                            $('#finalTotal').text("{{ config('settings.site_currency_icon') }} " +
                                response.finalTotal);

                            let couponCardHtml = `<div class="card mt-2">
                                <div class="d-flex justify-content-between align-items-center p-3">
                                    <span><b class="v_coupon_code">Applied Coupon: ${response.coupon_code}</b></span>
                                    <button type="button" class="remove_coupon" id="destroyCoupon"><i class="far fa-times"></i></button>
                                </div>`;

                            $('.coupon_card').html(couponCardHtml);

                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = xhr.responseJSON.message;
                        toastr.error(errorMessage);
                        hideLoader();
                    },
                    complete: function() {
                        hideLoader();
                        $('#coupon_code').val(''); //? clear the coupon code input
                    }
                })
            }


            $(document).on('click', '#destroyCoupon', function(e) {
                e.preventDefault();
                destroyCoupon();
            });

            /** remove coupon */
            function destroyCoupon() {
                $.ajax({
                    method: 'GET',
                    url: '{{ route('destroy-coupon') }}',
                    beforeSend: function() {
                        showLoader();
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            //? update the html
                            $('#discount').text(
                                "{{ config('settings.site_currency_icon') }}" + response.discount);

                            $('#subTotal').text(
                                "{{ config('settings.site_currency_icon') }} " + response.subTotal);

                            $('#finalTotal').text(
                                "{{ config('settings.site_currency_icon') }} " +
                                response.grandTotal);

                            $('.coupon_card').html('');
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = xhr.responseJSON.message;
                        toastr.error(errorMessage);
                    },
                    complete: function() {
                        hideLoader();
                    }
                });
            }
        });
    </script>
@endpush
