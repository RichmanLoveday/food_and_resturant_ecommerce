<section class="fp__menu mt_95 xs_mt_65">
    <div class="container">
        <div class="row wow fadeInUp" data-wow-duration="1s">
            <div class="col-md-8 col-lg-7 col-xl-6 m-auto text-center">
                <div class="fp__section_heading mb_45">
                    <h4>food Menu</h4>
                    <h2>Our Popular Delicious Foods</h2>
                    <span>
                        <img src="{{ asset('frontend/images/heading_shapes.png') }}" alt="shapes"
                            class="img-fluid w-100">
                    </span>
                    <p>Objectively pontificate quality models before intuitive information. Dramatically
                        recaptiualize multifunctional materials.</p>
                </div>
            </div>
        </div>

        <div class="row wow fadeInUp" data-wow-duration="1s">
            <div class="col-12">
                <div class="menu_filter d-flex flex-wrap justify-content-center">
                    <button class=" active" data-filter="*">all menu</button>
                    @foreach ($categories as $category)
                        <button data-filter=".{{ $category->slug }}">{{ $category->name }}</button>
                    @endforeach
                    {{-- <button data-filter=".chicken">chicken</button>
                    <button data-filter=".pizza">pizza</button>
                    <button data-filter=".dresserts">dresserts</button> --}}
                </div>
            </div>
        </div>

        <div class="row grid">
            @foreach ($menuItems as $item)
                @foreach ($item as $product)
                    <div class="col-xl-3 col-sm-6 col-lg-4 {{ $product->category->slug }} pizza wow fadeInUp"
                        data-wow-duration="1s">
                        <div class="fp__menu_item">
                            <div class="fp__menu_item_img">
                                <img src="{{ asset($product->thumb_image) }}" alt="{{ $product->image }}"
                                    class="img-fluid w-100">
                                <a class="category" href="#">{{ @$product->category->name }}</a>
                            </div>
                            <div class="fp__menu_item_text">
                                <p class="rating">
                                    @php
                                        $avgRating = min($product->reviews_avg_rating, 5);

                                        $fullStars = floor($avgRating); // full stars (integer part)
                                        $halfStar = $avgRating - $fullStars >= 0.5 ? 1 : 0; // half star if decimal >= 0.5
                                        $emptyStars = 5 - $fullStars - $halfStar; // rest are empty stars
                                    @endphp

                                    {{-- Full stars --}}
                                    @for ($i = 0; $i < $fullStars; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor

                                    {{-- Half star --}}
                                    @if ($halfStar)
                                        <i class="fas fa-star-half-alt"></i>
                                    @endif

                                    {{-- Empty stars --}}
                                    @for ($i = 0; $i < $emptyStars; $i++)
                                        <i class="fal fa-star"></i>
                                    @endfor

                                    @if ($product->reviews_count > 0)
                                        <span>({{ $product->reviews_count }})</span>
                                    @endif
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
                                    <li><a href="javascript:;"
                                            onclick="loadProductModal(this, '{{ $product->id }}')"><i
                                                class="fas fa-shopping-basket"></i></a></li>
                                    <li><a onclick="addToWishList(this, '{{ $product->id }}')"
                                            href="javascript:void();"><i class="fal fa-heart"></i></a></li>
                                    <li><a href="#"><i class="far fa-eye"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</section>
