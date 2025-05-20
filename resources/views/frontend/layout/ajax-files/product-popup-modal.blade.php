 <div class="modal-body">
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
             class="fal fa-times"></i></button>
     <form action="" id="modal_add_to_cart_form">
         <input type="hidden" name="product_id" value="{{ $product->id }}">
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
                                 name="product_size" value="{{ $size->id }}" id="size-{{ $size->id }}">
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
                                 value="{{ $option->id }}" name="product_option[]" id="option-{{ $option->id }}">
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
                         <button type="button" class="btn btn-danger decrement"><i class="fal fa-minus"></i></button>
                         <input type="text" id="quantity" name="quantity" placeholder="1" value="1" readonly>
                         <button type="button" class="btn btn-success increment"><i class="fal fa-plus"></i></button>
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
                 <li><button type="submit" class="common_btn modal_cart_button">add to cart</button></li>
             </ul>
         </div>
     </form>
 </div>


 <script>
     $(document).ready(function() {
         //? when a product size is seleted
         $('input[name="product_size"]').on('change', function() {
             //  alert('working');
             updateTotalPrice();
         });


         //? when an option is changed
         $('input[name="product_option[]"]').on('change', function() {
             //  alert('working');
             updateTotalPrice();
         });

         //? when increment button is clicked
         $('.increment').on('click', function(e) {
             e.preventDefault();

             let quantity = $('#quantity');
             let currentQty = parseFloat(quantity.val());
             quantity.val(currentQty + 1);
             updateTotalPrice();

         });


         //? when decrement button is clicked
         $('.decrement').on('click', function(e) {
             e.preventDefault();

             let quantity = $('#quantity');
             let currentQty = parseFloat(quantity.val());

             if (currentQty > 1) {
                 quantity.val(currentQty - 1);
                 updateTotalPrice();
             }
         });


         // Function to update the total price based on seleted options
         function updateTotalPrice() {
             let basePrice = parseFloat($('input[name="base_price"]').val()).toFixed(2) * 1;
             let seletedSizePrice = 0;
             let selectedOptionsPrice = 0;
             let quantity = parseFloat($('#quantity').val());

             // calculate the selected size price
             let seletedSize = $('input[name="product_size"]:checked');
             //  console.log(seletedSize);
             if (seletedSize.length > 0) {
                 seletedSizePrice = parseFloat(seletedSize.data("price")).toFixed(2) * 1;
             }

             // calculate seleted options price
             let selectedOptions = $('input[name="product_option[]"]:checked');
             //  console.log(selectedOptions);
             $(selectedOptions).each(function() {
                 selectedOptionsPrice += parseFloat($(this).data('price')).toFixed(2) * 1;
             });

             // calculate the total price
             let totalPrice = basePrice + seletedSizePrice + selectedOptionsPrice;
             $('#total_price').text("{{ config('settings.site_currency_icon') }}" + (totalPrice * quantity)
                 .toFixed(2) *
                 1);
         }


         //? Add to cart function
         $("#modal_add_to_cart_form").on('submit', function(e) {
             e.preventDefault();

             //? validation
             let selectedSize = $("input[name='product_size']");

             if (selectedSize.length > 0) {
                 if ($("input[name='product_size']:checked").val() === undefined) {
                     toastr.error("Please select a size");
                     console.error('Please select a size')
                     return;
                 }
             }

             let formData = $(this).serialize();

             $.ajax({
                 method: 'POST',
                 url: '{{ route('add-to-cart') }}',
                 data: formData,
                 beforeSend: function() {
                     $('.modal_cart_button').prop('disabled', true).html(
                         '<span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span>Loading...'
                     );
                 },
                 success: function(response) {
                     console.log(response);
                     if (response.status === 'success') {
                         updateSideBarCart();
                         toastr.success(response.message);
                     }
                 },
                 error: function(xhr, status, error) {
                     let errorMessage = xhr.responseJSON.message;
                     toastr.error(errorMessage);
                 },
                 complete: function() {
                     $('.modal_cart_button').prop('disabled', false).html('add to cart');
                 }
             })

         });
     })
 </script>
