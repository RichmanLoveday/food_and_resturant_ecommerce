 <div class="modal-body">
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
             class="fal fa-times"></i></button>
     <form action="">
         <div class="fp__cart_popup_img">
             <img src="{{ asset($product->thumb_image) }}" alt="{{ $product->name }}" class="img-fluid w-100">
         </div>
         <div class="fp__cart_popup_text">
             <a href="{{ route('product.show', $product->slug) }}" class="title">{!! $product->name !!}</a>
             <p class="rating">
                 <i class="fas fa-star"></i>
                 <i class="fas fa-star"></i>
                 <i class="fas fa-star"></i>
                 <i class="fas fa-star-half-alt"></i>
                 <i class="far fa-star"></i>
                 <span>(201)</span>
             </p>
             <h4 class="price">

                 @if ($product->offer_price > 0)
                     <input type="hidden" name="base_price" value="{{ $product->offer_price }}">
                     {{ currencyPosition($product->offer_price) }}
                     <del>{{ currencyPosition($product->price) }}</del>
                 @else
                     <input type="hidden" name="base_price" value="{{ $product->price }}">
                     {{ currencyPosition($product->price) }}
                 @endif
             </h4>

             <div class="details_size">
                 @if ($product->productSizes()->exists())
                     <h5>select size</h5>
                     @foreach ($product->productSizes as $size)
                         <div class="form-check">
                             <input class="form-check-input" data-price="{{ $size->price }}" type="radio"
                                 name="product_size" id="size-{{ $size->id }}" checked>
                             <label class="form-check-label" for="size-{{ $size->id }}">
                                 {{ $size->name }} <span>+ ${{ currencyPosition($size->price) }}</span>
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
                             <input class="form-check-input" data-price="{{ $option->price }}" type="checkbox"
                                 value="" name="product_option" id="option-{{ $option->id }}">
                             <label class="form-check-label" for="option-{{ $option->id }}">
                                 {{ $option->name }} <span>+ ${{ currencyPosition($option->price) }}</span>
                             </label>
                         </div>
                     @endforeach
                 @endif
             </div>

             <div class="details_quentity">
                 <h5>select quentity</h5>
                 <div class="quentity_btn_area d-flex flex-wrapa align-items-center">
                     <div class="quentity_btn">
                         <button class="btn btn-danger"><i class="fal fa-minus"></i></button>
                         <input type="text" placeholder="1">
                         <button class="btn btn-success"><i class="fal fa-plus"></i></button>
                     </div>
                     <h3 id="total_price">
                         @if ($product->offer_price > 0)
                             {{ currencyPosition($product->offer_price) }}
                         @else
                             {{ currencyPosition($product->price) }}
                         @endif
                     </h3>
                 </div>
             </div>
             <ul class="details_button_area d-flex flex-wrap">
                 <li><a class="common_btn" href="#">add to cart</a></li>
             </ul>
         </div>
     </form>
 </div>


 <script>
     $(document).ready(function() {
         $('input[name="product_size"]').on('change', function() {
             //  alert('working');
             updateTotalPrice();
         });


         $('input[name="product_option"]').on('change', function() {
             //  alert('working');
             updateTotalPrice();
         });

         // Function to update the total price based on seleted options
         function updateTotalPrice() {
             let basePrice = parseFloat($('input[name="base_price"]').val());
             let seletedSizePrice = 0;
             let selectedOptionsPrice = 0;

             // calculate the selected size price
             let seletedSize = $('input[name="product_size"]:checked');
             if (seletedSize.length > 0) {
                 seletedSizePrice = parseFloat(seletedSize.data("price"));
             }

             // calculate seleted options price
             let selectedOptions = $('input[name="product_option"]:checked');
             $(selectedOptions).each(function() {
                 selectedOptionsPrice += parseFloat($(this).data('price'));
             });


             // calculate the total price
             let totalPrice = basePrice + seletedSizePrice + selectedOptionsPrice;
             $('#total_price').text("{{ config('settings.site_currency_icon') }}" + totalPrice);

         }
     })
 </script>
