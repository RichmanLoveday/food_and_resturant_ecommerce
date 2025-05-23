@extends('frontend.layout.master')
@section('content')
    @include('frontend.common-component.breadcrumb')

    <section class="fp__menu_details mt_115 xs_mt_85 mb_95 xs_mb_65">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-9 wow fadeInUp" data-wow-duration="1s">
                    <div class="exzoom hidden" id="exzoom">
                        <div class="exzoom_img_box fp__menu_details_images">
                            <ul class='exzoom_img_ul'>
                                <li><img class="zoom ing-fluid w-100" src="{{ asset($product->thumb_image) }}"
                                        alt="{{ $product->name }}"></li>

                                @foreach ($product->productImages as $image)
                                    <li><img class="zoom ing-fluid w-100" src="{{ asset($image->image) }}"
                                            alt="{{ $product->name }}"></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="exzoom_nav"></div>
                        <p class="exzoom_btn">
                            <a href="javascript:void(0);" class="exzoom_prev_btn"> <i class="far fa-chevron-left"></i>
                            </a>
                            <a href="javascript:void(0);" class="exzoom_next_btn"> <i class="far fa-chevron-right"></i>
                            </a>
                        </p>
                    </div>
                </div>
                <div class="col-lg-7 wow fadeInUp" data-wow-duration="1s">
                    <div class="fp__menu_details_text">
                        <h2>{!! $product->name !!}</h2>
                        <p class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                            <span>(201)</span>
                        </p>
                        <form action="" id="v_add_to_cart_form">
                            @csrf
                            <input type="hidden" name="base_price" class="v_base_price"
                                value="{{ $product->offer_price > 0 ? $product->offer_price : $product->price }}">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <h3 class="price">
                                @if ($product->offer_price > 0)
                                    {{ currencyPosition($product->offer_price) }}
                                    <del>{{ currencyPosition($product->price) }}</del>
                                @else
                                    {{ currencyPosition($product->price) }}
                                @endif
                            </h3>
                            <p class="short_description">{!! $product->short_description !!}</p>

                            <div class="details_size">
                                @if ($product->productSizes()->exists())
                                    <h5>select size</h5>
                                    @foreach ($product->productSizes as $size)
                                        <div class="form-check">
                                            <input data-price="{{ $size->price }}" value="{{ $size->id }}"
                                                name="product_size" class="form-check-input v_product_size" type="radio"
                                                name="flexRadioDefault" id="size-{{ $size->id }}">
                                            <label class="form-check-label" for="size-{{ $size->id }}">
                                                {{ $size->name }} <span>+ {{ currencyPosition($size->price) }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="details_extra_item">
                                @if ($product->productOptions()->exists())
                                    <h5>select option <span>(optional)</span></h5>
                                    @foreach ($product->productOptions as $option)
                                        <div class="form-check">
                                            <input data-price="{{ $option->price }}"
                                                class="form-check-input v_product_option" name="product_option[]"
                                                type="checkbox" value="{{ $option->id }}"
                                                id="option-{{ $option->id }}">
                                            <label class="form-check-label" for="option-{{ $option->id }}">
                                                {{ $option->name }} <span>+ {{ currencyPosition($option->price) }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                @endif

                            </div>

                            <div class="details_quentity">
                                <h5>select quentity</h5>
                                <div class="quentity_btn_area d-flex flex-wrapa align-items-center">
                                    <div class="quentity_btn">
                                        <button class="btn btn-danger v_decrement"><i class="fal fa-minus"></i></button>
                                        <input type="text" name="quantity" placeholder="1" value="1" id="v_quantity"
                                            readonly>
                                        <button class="btn btn-success v_increment"><i class="fal fa-plus"></i></button>
                                    </div>
                                    <h3 id="v_total_price">
                                        {{ currencyPosition($product->offer_price > 0 ? $product->offer_price : $product->price) }}
                                    </h3>
                                </div>
                            </div>
                        </form>

                        <ul class="details_button_area d-flex flex-wrap">
                            @if ($product->quantity === 0)
                                <li><a href="javascript:;" class="common_btn bg-danger">Out of
                                        stock</a></li>
                            @else
                                <li><a href="#" class="common_btn v_submit_button">add to cart</a></li>
                            @endif
                            <li><a class="wishlist" href="#"><i class="far fa-heart"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-12 wow fadeInUp" data-wow-duration="1s">
                    <div class="fp__menu_description_area mt_100 xs_mt_70">
                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                    aria-selected="true">Description</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-contact" type="button" role="tab"
                                    aria-controls="pills-contact" aria-selected="false">Reviews</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                aria-labelledby="pills-home-tab" tabindex="0">
                                <div class="menu_det_description">
                                    {!! $product->long_description !!}
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                aria-labelledby="pills-contact-tab" tabindex="0">
                                <div class="fp__review_area">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <h4>04 reviews</h4>
                                            <div class="fp__comment pt-0 mt_20">
                                                <div class="fp__single_comment m-0 border-0">
                                                    <img src="{{ asset('frontend/images/comment_img_1.png') }}"
                                                        alt="review" class="img-fluid">
                                                    <div class="fp__single_comm_text">
                                                        <h3>Michel Holder <span>29 oct 2022 </span></h3>
                                                        <span class="rating">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fad fa-star-half-alt"></i>
                                                            <i class="fal fa-star"></i>
                                                            <b>(120)</b>
                                                        </span>
                                                        <p>Sure there isn't anything embarrassing hiidden in the
                                                            middles of text. All erators on the Internet
                                                            tend to repeat predefined chunks</p>
                                                    </div>
                                                </div>
                                                <div class="fp__single_comment">
                                                    <img src="{{ asset('frontend/images/chef_1.jpg') }}" alt="review"
                                                        class="img-fluid">
                                                    <div class="fp__single_comm_text">
                                                        <h3>salina khan <span>29 oct 2022 </span></h3>
                                                        <span class="rating">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fad fa-star-half-alt"></i>
                                                            <i class="fal fa-star"></i>
                                                            <b>(120)</b>
                                                        </span>
                                                        <p>Sure there isn't anything embarrassing hiidden in the
                                                            middles of text. All erators on the Internet
                                                            tend to repeat predefined chunks</p>
                                                    </div>
                                                </div>
                                                <div class="fp__single_comment">
                                                    <img src="{{ asset('frontend/images/comment_img_2.png') }}"
                                                        alt="review" class="img-fluid">
                                                    <div class="fp__single_comm_text">
                                                        <h3>Mouna Sthesia <span>29 oct 2022 </span></h3>
                                                        <span class="rating">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fad fa-star-half-alt"></i>
                                                            <i class="fal fa-star"></i>
                                                            <b>(120)</b>
                                                        </span>
                                                        <p>Sure there isn't anything embarrassing hiidden in the
                                                            middles of text. All erators on the Internet
                                                            tend to repeat predefined chunks</p>
                                                    </div>
                                                </div>
                                                <div class="fp__single_comment">
                                                    <img src="{{ asset('frontend/images/chef_3.jpg') }}" alt="review"
                                                        class="img-fluid">
                                                    <div class="fp__single_comm_text">
                                                        <h3>marjan janifar <span>29 oct 2022 </span></h3>
                                                        <span class="rating">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fad fa-star-half-alt"></i>
                                                            <i class="fal fa-star"></i>
                                                            <b>(120)</b>
                                                        </span>
                                                        <p>Sure there isn't anything embarrassing hiidden in the
                                                            middles of text. All erators on the Internet
                                                            tend to repeat predefined chunks</p>
                                                    </div>
                                                </div>
                                                <a href="#" class="load_more">load More</a>
                                            </div>

                                        </div>
                                        <div class="col-lg-4">
                                            <div class="fp__post_review">
                                                <h4>write a Review</h4>
                                                <form>
                                                    <p class="rating">
                                                        <span>select your rating : </span>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                    </p>
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <input type="text" placeholder="Name">
                                                        </div>
                                                        <div class="col-xl-12">
                                                            <input type="email" placeholder="Email">
                                                        </div>
                                                        <div class="col-xl-12">
                                                            <textarea rows="3" placeholder="Write your review"></textarea>
                                                        </div>
                                                        <div class="col-12">
                                                            <button class="common_btn" type="submit">submit
                                                                review</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (count($relatedProducts) > 0)
                <div class="fp__related_menu mt_90 xs_mt_60">
                    <h2>related item</h2>
                    <div class="row related_product_slider">
                        @foreach ($relatedProducts as $product)
                            <div class="col-xl-3 wow fadeInUp" data-wow-duration="1s">
                                <div class="fp__menu_item">
                                    <div class="fp__menu_item_img">
                                        <img src="{{ asset($product->thumb_image) }}" alt="{{ $product->name }}"
                                            class="img-fluid w-100">
                                        <a class="category" href="#">{{ $product->category->name }}</a>
                                    </div>
                                    <div class="fp__menu_item_text">
                                        <p class="rating">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                            <i class="far fa-star"></i>
                                            <span>74</span>
                                        </p>
                                        <a class="title"
                                            href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
                                        <h5 class="price">
                                            @if ($product->offer_price > 0)
                                                {{ currencyPosition($product->offer_price) }}
                                                <del>{{ currencyPosition($product->price) }}</del>
                                            @else
                                                {{ currencyPosition($product->price) }}
                                            @endif
                                        </h5>
                                        <ul class="d-flex flex-wrap justify-content-center">
                                            <li><a href="#" onclick="loadProductModal(this, '{{ $product->id }}')"
                                                    data-bs-toggle="modal" data-bs-target="#cartModal"><i
                                                        class="fas fa-shopping-basket"></i></a></li>
                                            <li><a href="#"><i class="fal fa-heart"></i></a></li>
                                            <li><a href="#"><i class="far fa-eye"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            @endif
        </div>
    </section>

@endsection


@push('scripts')
    <script>
        $('.v_product_option').prop('checked', false);
        $('.v_product_size').prop('checked', false);
        $('#v_quantity').val(1);

        //? when a product size is seleted
        $('.v_product_size').on('change', function() {
            v_updateTotalPrice()
        });


        //? when an option is changed
        $('.v_product_option').on('change', function() {
            v_updateTotalPrice()
        });


        //? when increment button is clicked
        $('.v_increment').on('click', function(e) {
            e.preventDefault();

            let quantity = $('#v_quantity');
            let currentQty = parseFloat(quantity.val());
            quantity.val(currentQty + 1);
            v_updateTotalPrice()
        });


        //? when decrement button is clicked
        $('.v_decrement').on('click', function(e) {
            e.preventDefault();

            let quantity = $('#v_quantity');
            let currentQty = parseFloat(quantity.val());

            if (currentQty > 1) {
                quantity.val(currentQty - 1);
                v_updateTotalPrice()
            }
        });



        // Function to update the total price based on seleted options
        function v_updateTotalPrice() {
            let basePrice = parseFloat($('.v_base_price').val()).toFixed(2) * 1;
            let seletedSizePrice = 0;
            let selectedOptionsPrice = 0;
            let quantity = parseFloat($('#v_quantity').val());

            // calculate the selected size price
            let seletedSize = $('.v_product_size:checked');
            //  console.log(seletedSize);
            if (seletedSize.length > 0) {
                seletedSizePrice = parseFloat(seletedSize.data("price")).toFixed(2) * 1;
            }


            // calculate seleted options price
            let selectedOptions = $('.v_product_option:checked');
            //  console.log(selectedOptions);
            $(selectedOptions).each(function() {
                selectedOptionsPrice += parseFloat($(this).data('price')).toFixed(2) * 1;
            });

            // console.log(selectedOptionsPrice, seletedSizePrice);

            // calculate the total price
            let totalPrice = basePrice + seletedSizePrice + selectedOptionsPrice;
            $('#v_total_price').text("{{ config('settings.site_currency_icon') }}" + (totalPrice * quantity)
                .toFixed(2) *
                1);
        }


        //? prevent default behaviour of button, and submit form
        $('.v_submit_button').on('click', function(e) {
            e.preventDefault();
            $('#v_add_to_cart_form').submit();
        });

        //? Add to cart function
        $("#v_add_to_cart_form").on('submit', function(e) {
            e.preventDefault();

            //? validation
            let selectedSize = $(".v_product_size");

            if (selectedSize.length > 0) {
                if ($(".v_product_size:checked").val() === undefined) {
                    toastr.error("Please select a size");
                    console.error('Please select a size');
                    return;
                }
            }

            let formData = $(this).serialize();

            $.ajax({
                method: 'POST',
                url: '{{ route('add-to-cart') }}',
                data: formData,
                beforeSend: function() {
                    $('.v_submit_button').prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span>Loading...'
                    );
                },
                success: function(response) {
                    console.log(response);
                    if (response.status === 'success') {
                        updateSideBarCart();
                        toastr.success(response.message);
                        $('#v_add_to_cart_form')[0].reset();
                        $('.v_product_option').prop('checked', false);
                        $('.v_product_size').prop('checked', false);
                        $('#v_quantity').val(1);

                        //? update total price
                        v_updateTotalPrice();
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = xhr.responseJSON.message;
                    toastr.error(errorMessage);
                },
                complete: function() {
                    $('.v_submit_button').prop('disabled', false).html('add to cart');
                    $('#cartModal').modal('hide');
                }
            })

        });
    </script>
@endpush
