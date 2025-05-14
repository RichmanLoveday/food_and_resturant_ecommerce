@extends('frontend.layout.master')
@section('content')
    <!--=============================
                                                                        BANNER START
                                                                    ==============================-->
    @include('frontend.home.component.banner')
    <!--=============================
                                                                        BANNER END
                                                                    ==============================-->


    <!--=============================
                                                                        WHY CHOOSE START
                                                                    ==============================-->
    @include('frontend.home.component.why-choose-us')
    <!--=============================
                                                                        WHY CHOOSE END
                                                                    ==============================-->


    <!--=============================
                                                                        OFFER ITEM START
                                                                    ==============================-->
    @include('frontend.home.component.offer-item')

    <!-- CART POPUT START -->
    @include('frontend.home.component.card-popup')
    <!-- CART POPUT END -->
    <!--=============================
                                                                        OFFER ITEM END
                                                                    ==============================-->


    <!--=============================
                                                                        MENU ITEM START
                                                                    ==============================-->
    @include('frontend.home.component.menu-item')
    <!--=============================
                                                                        MENU ITEM END
                                                                    ==============================-->


    <!--=============================
                                                                        ADD SLIDER START
                                                                    ==============================-->
    @include('frontend.home.component.slider')
    <!--=============================
                                                                        ADD SLIDER END
                                                                    ==============================-->


    <!--=============================
                                                                        TEAM START
                                                                    ==============================-->
    @include('frontend.home.component.team')
    <!--=============================
                                                                        TEAM END
                                                                    ==============================-->


    <!--=============================
                                                                        DOWNLOAD APP START
                                                                    ==============================-->
    @include('frontend.home.component.app-download')
    <!--=============================
                                                                        DOWNLOAD APP END
                                                                    ==============================-->


    <!--=============================
                                                                       TESTIMONIAL  START
                                                                    ==============================-->
    @include('frontend.home.component.testimonial')
    <!--=============================
                                                                        TESTIMONIAL END
                                                                    ==============================-->


    <!--=============================
                                                                        COUNTER START
                                                                    ==============================-->
    @include('frontend.home.component.counter')
    <!--=============================
                                                                        COUNTER END
                                                                    ==============================-->


    <!--=============================
                                                                        BLOG 2 START
                                                                    ==============================-->
    @include('frontend.home.component.blog')
    <!--=============================
                                                                        BLOG 2 END
                                                                    ==============================-->
@endsection
