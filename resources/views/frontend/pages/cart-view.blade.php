@extends('frontend.layout.master')
@section('content')
    @include('frontend.common-component.breadcrumb')
    <!--============================
                                                                                                                                                                                                                                                                                                                            CART VIEW START
                                                                                                                                                                                                                                                                                                                        ==============================-->
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
                                            <a class="clear_all" href="#">clear all</a>
                                        </th>
                                    </tr>
                                    @foreach (Cart::content() as $cartProduct)
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
                                                <h6>$180,00</h6>
                                            </td>

                                            <td class="fp__pro_icon">
                                                <a href="#"><i class="far fa-times"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 wow fadeInUp" data-wow-duration="1s">
                    <div class="fp__cart_list_footer_button">
                        <h6>total cart</h6>
                        <p>subtotal: <span>$124.00</span></p>
                        <p>delivery: <span>$00.00</span></p>
                        <p>discount: <span>$10.00</span></p>
                        <p class="total"><span>total:</span> <span>$134.00</span></p>
                        <form>
                            <input type="text" placeholder="Coupon Code">
                            <button type="submit">apply</button>
                        </form>
                        <a class="common_btn" href=" #">checkout</a>
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

                inputField.val(currValue + 1);
                cartQtyUpdate(rowId, +inputField.val());
            });

            //? decrement quantity
            $('.decrement').on('click', function(e) {
                let inputField = $(this).siblings(".quantity");
                let currValue = +inputField.val();
                let rowId = $(this).data('id');

                if (currValue > 1) {
                    inputField.val(currValue - 1);
                    cartQtyUpdate(rowId, +inputField.val());
                }
            });


            function cartQtyUpdate(rowId, qty) {
                $.ajax({
                    method: 'POST',
                    url: '{{ route('cart.quantity-update') }}',
                    data: {
                        rowId: rowId,
                        qty: qty,
                    },
                    beforeSend: function() {
                        showOrHideLoader();
                    },
                    success: function(response) {
                        toastr.success(response.message);
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = xhr.responseJSON.message;
                        showOrHideLoader();
                        toastr.error(errorMessage);
                    },
                    complete: function(response) {
                        showOrHideLoader();
                    }
                });
            }
        });
    </script>
@endpush
