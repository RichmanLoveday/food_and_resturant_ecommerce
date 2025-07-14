@extends('frontend.layout.master')
@section('content')
    @include('frontend.common-component.breadcrumb')
    <section class="fp__blog_page fp__blog2 mt_120 xs_mt_65 mb_100 xs_mb_70">
        <div class="container">
            <form class="fp__search_menu_form mb-4">
                <div class="row">
                    <div class="col-xl-6 col-md-5">
                        <input type="text" placeholder="Search...">
                    </div>
                    <div class="col-xl-4 col-md-4">
                        <select class="nice-select">
                            <option value="">select country</option>
                            <option value="">bangladesh</option>
                            <option value="">nepal</option>
                            <option value="">japan</option>
                            <option value="">korea</option>
                            <option value="">thailand</option>
                        </select>
                    </div>
                    <div class="col-xl-2 col-md-3">
                        <button type="submit" class="common_btn">search</button>
                    </div>
                </div>
            </form>
            <div class="row">
                @foreach ($blogs as $blog)
                    <div class="col-xl-4 col-sm-6 col-lg-4 wow fadeInUp" data-wow-duration="1s">
                        <div class="fp__single_blog">
                            <a href="#" class="fp__single_blog_img">
                                <img src="{{ $blog->image }}" alt="blog" class="img-fluid w-100">
                            </a>
                            <div class="fp__single_blog_text">
                                <a class="category" href="#">{{ $blog->category->name }}</a>
                                <ul class="d-flex flex-wrap mt_15">
                                    <li><i class="fas fa-user"></i>{{ $blog->user->name }}</li>
                                    <li><i class="fas fa-calendar-alt"></i>
                                        {{ date('d m Y', strtotime($blog->created_at)) }}</li>
                                    <li><i class="fas fa-comments"></i> 25 comment</li>
                                </ul>
                                <a class="title"
                                    href="{{ route('blog-details', $blog->slug) }}">{!! truncate($blog->title, 20) !!}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $blogs->links('frontend.common-component.pagination') }}
        </div>
    </section>
@endsection
