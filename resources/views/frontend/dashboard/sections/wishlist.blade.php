 <div class="tab-pane fade" id="v-pills-wishlist" role="tabpanel" aria-labelledby="v-pills-wishlist-tab">
     <div class="fp_dashboard_body">
         <h3>Wishlist</h3>
         <div class="fp_dashboard_order">
             <div class="table-responsive">
                 <table class="table">
                     <tbody>
                         <tr class="t_header">
                             {{-- <th>No</th> --}}
                             <th>Image</th>
                             <th>Product</th>
                             <th>Stock</th>
                             <th>Action</th>
                         </tr>
                         @foreach ($wishlist as $item)
                             <tr>
                                 {{-- <td>
                                     <h5>{{ ++$loop->index }}</h5>
                                 </td> --}}
                                 <td>
                                     <img style="width: 50px; height: 50px;"
                                         src="{{ asset($item->product->thumb_image) }}" alt=""
                                         class="{{ $item->product->quantity > 0 ? '' : 'fp__fade_image' }} fp__wishlist_image">
                                 </td>
                                 <td>
                                     {{ $item->product->name }}
                                 </td>
                                 <td>
                                     @if ($item->product->quantity > 0)
                                         <h6 class="text-success">In Stock</h6>
                                     @else
                                         <h6 class="text-danger">Out of Stock</h6>
                                     @endif
                                 </td>
                                 <td>
                                     <div class="space-x-6">
                                         <a href="{{ route('product.show', $item->product->slug) }}"
                                             class="view_invoice">View
                                             Product</a>

                                         <a class="remove-wishlist-item" data-id="{{ $item->id }}"
                                             href="javascript:void();" class="view_invoice">Remove</a>
                                     </div>
                                 </td>
                             </tr>
                         @endforeach
                     </tbody>
                 </table>
             </div>
         </div>
     </div>
 </div>


 @push('scripts')
     <script>
         $(document).ready(function() {
             $('.remove-wishlist-item').on('click', function(e) {
                 e.preventDefault();
                 removeCartWishlist($(this).data('id'), $(this));
             });


             /** Remove cart product */
             function removeCartWishlist(rowId, button) {
                 console.log('Removing wishlist item with rowId:', rowId);
                 $.ajax({
                     url: '{{ route('wishlist-product-remove', ['rowId' => '__ROW_ID__']) }}'
                         .replace(
                             '__ROW_ID__', rowId),

                     method: 'DELETE',
                     beforeSend: function() {
                         showLoader();
                     },
                     success: function(response) {
                         if (response.status === 'success') {
                             button.closest('tr').remove();
                             toastr.success(response.message);
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
         });
     </script>
 @endpush
