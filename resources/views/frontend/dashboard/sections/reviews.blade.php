 <div class="tab-pane fade" id="v-pills-reviews" role="tabpanel" aria-labelledby="v-pills-reviews-tab">
     <div class="fp_dashboard_body dashboard_review">
         <h3>review</h3>
         <div class="fp__review_area">
             <div class="fp__comment pt-0 mt_20">
                 @forelse ($reviews as $review)
                     <div class="fp__single_comment m-0 border-0">
                         <img src="{{ asset($review->user->avatar) }}" alt="review" class="img-fluid">
                         <div class="fp__single_comm_text">
                             <h3><a href="#">{{ $review->user->name }}</a>
                                 <span>{{ date('d m Y', strtotime($review->created_at)) }}
                                 </span>
                             </h3>
                             <span class="rating">
                                 @php
                                     $fullStars = min($review->rating, 5);
                                     $emptyStars = max(5 - $fullStars, 0);
                                 @endphp

                                 {{-- Full stars --}}
                                 @for ($i = 0; $i < $fullStars; $i++)
                                     <i class="fas fa-star"></i>
                                 @endfor

                                 {{-- Empty stars --}}
                                 @for ($i = 0; $i < $emptyStars; $i++)
                                     <i class="fal fa-star"></i>
                                 @endfor

                                 {{-- <b>(120)</b> --}}
                             </span>
                             <p>{{ $review->review }}</p>
                             <span
                                 class="status {{ $review->status ? 'active' : 'inactive' }}">{{ $review->status ? 'active' : 'pending' }}</span>
                         </div>
                     </div>
                 @empty
                     <h5>No reviews added</h5>
                 @endforelse

                 @if ($reviews->currentPage() < $reviews->lastPage())
                     <a href="javascript:void(0);" class="load_more load-more-btn"
                         data-next-page="{{ $reviews->currentPage() + 1 }}">
                         Load More
                     </a>
                 @endif
             </div>
         </div>
     </div>
 </div>


 @push('scripts')
     <script>
         $(document).ready(function() {
             $(document).on('click', '.load-more-btn', function(e) {
                 e.preventDefault();
                 // alert('working');

                 let button = $(this);
                 let nextPage = button.data('next-page');

                 $.ajax({
                     url: `{{ route('user-reviews.loadMore') }}?page=${nextPage}`,
                     method: 'GET',
                     beforeSend: function() {
                         button.text('Loading...')
                             .css({
                                 'pointer-events': 'none',
                                 'opacity': 0.6,
                             });
                     },
                     success: function(res) {
                         //? add data after the last single comment
                         $('.fp__single_comment').last().after(res);
                         let reviewCount = $('.fp__single_comment').length;
                         $('.fp_comment_count').text(`${reviewCount} comments`)


                         //? add next page to button
                         let newPage = nextPage + 1;
                         button.data('next-page', newPage);

                         //? Check if more pages exist
                         $.get(`{{ route('user-reviews.loadMore') }}?page=${newPage}`,
                             function(res) {
                                 if ($(res).length === 0) {
                                     button.remove();
                                 } else {
                                     button.text('Load More')
                                         .css({
                                             'pointer-events': 'auto',
                                             'opacity': 1,
                                         })
                                 }
                             });

                     },
                     error: function(xhr, status, error) {
                         let errorMsg = xhr.responseJSON.message;
                         toastr.error(errorMsg);

                         //? enable button
                         button.text('Load More')
                             .css({
                                 'pointer-events': 'auto',
                                 'opacity': 1,
                             })
                     }
                 });
             });
         })
     </script>
 @endpush
