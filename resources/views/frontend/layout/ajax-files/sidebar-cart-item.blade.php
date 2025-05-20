@foreach (Cart::content() as $cartProduct)
    <li>
        <div class="menu_cart_img">
            <img src="{{ asset($cartProduct->options->product_info['image']) }}" alt="menu" class="img-fluid w-100">
        </div>
        <div class="menu_cart_text">
            <a class="title"
                href="{{ route('product.show', $cartProduct->options->product_info['slug']) }}">{!! $cartProduct->name !!}
            </a>
            <p class="size">Qty: {{ $cartProduct->qty }}</p>
            <p class="size">{{ @$cartProduct->options->product_size['name'] }}</p>
            @foreach ($cartProduct->options->product_options as $cartProductOption)
                <span class="extra">{{ $cartProductOption['name'] }}</span>
            @endforeach
            {{-- <span class="extra">7up</span> --}}
            {{-- <p class="price">$99.00 <del>$110.00</del></p> --}}
            <p class="price">{{ currencyPosition($cartProduct->price) }}</p>
        </div>
        <span class="del_icon"><i class="fal fa-times"></i></span>
    </li>
@endforeach

{{-- 
 <li>
     <div class="menu_cart_img">
         <img src="{{ asset('frontend/images/menu4.png') }}" alt="menu" class="img-fluid w-100">
     </div>
     <div class="menu_cart_text">
         <a class="title" href="#">Chicken Masalas</a>
         <p class="size">medium</p>
         <span class="extra">7up</span>
         <p class="price">$70.00</p>
     </div>
     <span class="del_icon"><i class="fal fa-times"></i></span>
 </li>
 <li>
     <div class="menu_cart_img">
         <img src="{{ asset('frontend/images/menu5.png') }}" alt="menu" class="img-fluid w-100">
     </div>
     <div class="menu_cart_text">
         <a class="title" href="#">Competently Supply Customized Initiatives</a>
         <p class="size">large</p>
         <span class="extra">coca-cola</span>
         <span class="extra">7up</span>
         <p class="price">$120.00 <del>$150.00</del></p>
     </div>
     <span class="del_icon"><i class="fal fa-times"></i></span>
 </li>
 <li>
     <div class="menu_cart_img">
         <img src="{{ asset('frontend/images/menu6.png') }}" alt="menu" class="img-fluid w-100">
     </div>
     <div class="menu_cart_text">
         <a class="title" href="#">Hyderabadi Biryani</a>
         <p class="size">small</p>
         <span class="extra">7up</span>
         <p class="price">$59.00</p>
     </div>
     <span class="del_icon"><i class="fal fa-times"></i></span>
 </li>
 <li>
     <div class="menu_cart_img">
         <img src="{{ asset('frontend/images/menu1.png') }}" alt="menu" class="img-fluid w-100">
     </div>
     <div class="menu_cart_text">
         <a class="title" href="#">Competently Supply</a>
         <p class="size">medium</p>
         <span class="extra">coca-cola</span>
         <span class="extra">7up</span>
         <p class="price">$99.00 <del>$110.00</del></p>
     </div>
     <span class="del_icon"><i class="fal fa-times"></i></span>
 </li> --}}
