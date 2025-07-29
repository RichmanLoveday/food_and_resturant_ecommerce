@extends('frontend.layout.master')
@section('content')
    @include('frontend.common-component.breadcrumb')
    <section class="fp__blog_page fp__blog2 mt_120 xs_mt_65 mb_100 xs_mb_70">
        <div class="container">
            <form class="fp__search_menu_form mb-4" action="{{ route('product.index') }}" method="get">
                <div class="row">
                    <div class="col-xl-6 col-md-5">
                        <input type="text" value="{{ request('search') }}" placeholder="Search..." name="search">
                    </div>
                    <div class="col-xl-4 col-md-4">
                        <select class="nice-select" name="category">
                            <option value="">select category</option>
                            @foreach ($categories as $category)
                                <option @selected(request('category') == $category->slug) value="{{ $category->slug }}">{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xl-2 col-md-3">
                        <button type="submit" class="common_btn">search</button>
                    </div>
                </div>
            </form>
            <div class="row">
                @forelse ($products as $product)
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
                                    <li><a href="javascript:;" onclick="loadProductModal(this, '{{ $product->id }}')"><i
                                                class="fas fa-shopping-basket"></i></a></li>
                                    <li><a href="#"><i class="fal fa-heart"></i></a></li>
                                    <li><a href="#"><i class="far fa-eye"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @empty
                    <h5 class="text-center">No Product Found!</h5>
                @endforelse
            </div>

            {{ $products->links('frontend.common-component.pagination') }}
        </div>
    </section>
@endsection
