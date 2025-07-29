@extends('frontend.layout.master')
@section('og_metatag_section')
    <meta property="og:title" content="{{ $blog->seo_title }}">
    <meta property="og:description" content="{{ $blog->seo_description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset($blog->image) }}">
    <meta property="og:site_name" content="{{ config('settings.site_name') }}">
    <meta property="og:type" content="website">
@endsection

@section('content')
    @include('frontend.common-component.breadcrumb')
    <section class="fp__blog_details mt_120 xs_mt_90 mb_100 xs_mb_70">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8">
                    <div class="fp__blog_det_area">
                        <div class="fp__blog_details_img wow fadeInUp" data-wow-duration="1s">
                            <img src="{{ $blog->image }}" alt="blog details" class="imf-fluid w-100">
                        </div>
                        <div class="fp__blog_details_text wow fadeInUp" data-wow-duration="1s">
                            <ul class="details_bloger d-flex flex-wrap">
                                <li><i class="far fa-user"></i> By {{ $blog->user->name }}</li>
                                <li><i class="far fa-comment-alt-lines"></i> 12 Comments</li>
                                <li><i class="far fa-calendar-alt"></i> {{ date('d m Y', strtotime($blog->created_at)) }}
                                </li>
                            </ul>
                            <h2>{!! $blog->title !!}</h2>
                            <p>{!! $blog->description !!}</p>
                            {!! $blog->description !!}


                            <div class="blog_tags_share d-flex flex-wrap justify-content-between align-items-center">
                                {{-- <div class="tags d-flex flex-wrap align-items-center">
                                    <span>tags:</span>
                                    <ul class="d-flex flex-wrap">
                                        <li><a href="#">Cleaning</a></li>
                                        <li><a href="#">AC Repair</a></li>
                                        <li><a href="#">Home Move</a></li>
                                    </ul>
                                </div> --}}
                                <div class="share d-flex flex-wrap align-items-center">
                                    <span>share:</span>
                                    <ul class="d-flex flex-wrap">
                                        <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"><i
                                                    class="fab fa-facebook-f"></i></a></li>
                                        <li><a
                                                href="https://www.linkedin.com/shareArticle?mini=true&url={{ url()->current() }}&title={{ $blog->title }}"><i
                                                    class="fab fa-linkedin-in"></i></a></li>
                                        <li><a
                                                href="http://twitter.com/share?text={{ $blog->title }}&url={{ url()->current() }}"><i
                                                    class="fab fa-twitter"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <ul class="blog_det_button mt_100 xs_mt_70 wow fadeInUp" data-wow-duration="1s">
                        @if (!is_null($prevBlog))
                            <li>
                                <a href="{{ route('blog-details', $prevBlog->slug) }}">
                                    <img src="{{ asset($prevBlog->image) }}" alt="button img" class="img-fluid w-100">
                                    <p>{{ truncate($prevBlog->title) }}
                                        <span> <i class="far fa-long-arrow-left"></i> Previous</span>
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if (!is_null($nextBlog))
                            <li>
                                <a href="{{ route('blog-details', $nextBlog->slug) }}">
                                    <p>{{ truncate($nextBlog->title) }}
                                        <span>next <i class="far fa-long-arrow-right"></i></span>
                                    </p>
                                    <img src="{{ asset($nextBlog->image) }}" alt="button img" class="img-fluid w-100">
                                </a>
                            </li>
                        @endif
                    </ul>

                    <div class="fp__comment mt_100 xs_mt_70 wow fadeInUp" data-wow-duration="1s">
                        <h4 class="fp_comment_count">{{ count($comments) }}
                            {{ \Str::plural('Comment', $comments->count()) }}</h4>
                        @if (count($comments) > 0)
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

                            @if ($comments->currentPage() < $comments->lastPage())
                                <a href="javascript:void(0);" class="load_more" id="load-more-btn"
                                    data-next-page="{{ $comments->currentPage() + 1 }}" data-blog-id="{{ $blog->id }}"
                                    tabindex="-1" aria-disabled="true">Load More</a>
                            @endif
                        @endif
                    </div>

                    <div class="comment_input mt_100 xs_mt_70 wow fadeInUp" data-wow-duration="1s">
                        <h4>Leave A Comment</h4>
                        <p>Required field are marked *</p>
                        <form action="{{ route('blogs.comment.store', $blog->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-xl-12">
                                    <label>comment</label>
                                    <div class="fp__contact_form_input textarea">
                                        <span><i class="fal fa-user-alt"></i></span>
                                        <textarea name="comment" rows="5" placeholder="Your Comment"></textarea>
                                    </div>
                                    <button type="submit" class="common_btn mt_20">Submit comment</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4">
                    <div id="sticky_sidebar">
                        <div class="fp__blog_search blog_sidebar m-0 wow fadeInUp" data-wow-duration="1s">
                            <h3>Search</h3>
                            <form action="{{ route('blogs') }}" method="get">
                                <input type="text" placeholder="Search" name="search">
                                <button type="submit"><i class="fas fa-search"></i></button>
                            </form>
                        </div>
                        <div class="fp__related_blog blog_sidebar wow fadeInUp" data-wow-duration="1s">
                            <h3>Latest Post</h3>
                            <ul>
                                @foreach ($latestBlogs as $blog)
                                    <li>
                                        <img src="{{ $blog->image }}" alt="blog" class="img-fluid w-100">
                                        <div class="text">
                                            <a href="{{ route('blog-details', $blog->slug) }}">{!! truncate($blog->title, 40) !!}</a>
                                            <p><i class="far fa-calendar-alt"></i>
                                                {{ date('d m Y', strtotime($blog->created_at)) }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="fp__blog_categori blog_sidebar wow fadeInUp" data-wow-duration="1s">
                            <h3>Categories</h3>
                            <ul>
                                @foreach ($categories as $category)
                                    <li><a href="{{ route('blogs', ['category' => $category->slug]) }}">{{ $category->name }}
                                            <span>{{ $category->blogs_count }}</span></a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="fp__blog_tags blog_sidebar wow fadeInUp" data-wow-duration="1s">
                            <h3>Popular Tags</h3>
                            <ul>
                                <li><a href="#">Cleaning </a></li>
                                <li><a href="#">Car Repair</a></li>
                                <li><a href="#">Plumbing</a></li>
                                <li><a href="#">Painting</a></li>
                                <li><a href="#">Past Control</a></li>
                                <li><a href="#">AC Repair</a></li>
                                <li><a href="#">Home Move</a></li>
                                <li><a href="#">Disinfection</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '#load-more-btn', function(e) {
                e.preventDefault();

                let button = $(this);
                let nextPage = button.data('next-page');
                let blogId = button.data('blog-id');

                $.ajax({
                    url: `{{ route('blogs.comment.loadmore', ':id') }}?page=${nextPage}`.replace(
                        ":id",
                        blogId),
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
                        let commentCount = $('.fp__single_comment').length;
                        $('.fp_comment_count').text(`${commentCount} comments`)


                        //? add next page to button
                        let newPage = nextPage + 1;
                        button.data('next-page', newPage);

                        //? Check if more pages exist
                        $.get(`{{ route('blogs.comment.loadmore', ':id') }}?page=${newPage}`
                            .replace(":id", blogId),
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
            })
        });
    </script>
@endpush
