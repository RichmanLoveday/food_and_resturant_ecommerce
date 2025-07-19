@foreach ($comments as $comment)
    <div class="fp__single_comment m-0 border-0">
        <img src="{{ asset($comment->user->avatar) }}" alt="review" class="img-fluid">
        <div class="fp__single_comm_text">
            <h3>{{ \Str::ucfirst($comment->user->name) }}
                <span>{{ \Carbon\Carbon::parse($comment->created_at)->format('j F Y') }}</span>
            </h3>
            <p>{{ $comment->comment }}</p>
            {{-- <a href="#">Reply <i class="fas fa-reply-all"></i></a> --}}
        </div>
    </div>
@endforeach
