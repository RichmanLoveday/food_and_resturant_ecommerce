@foreach ($reviews as $review)
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
@endforeach
