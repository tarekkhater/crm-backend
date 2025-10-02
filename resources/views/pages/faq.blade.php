@extends('layouts.front')

@section('content')

    <!-- start intro -->
    <div id="intro" class="jarallax" data-speed="0.5" style="background-image: url(/img/intro_img/1.jpg);">
        <div class="grid grid--container">
            <div class="row row--xs-middle">
                <div class="col col--lg-5 text--center">
                    <h1 class="__title">FAQ’s</h1>
                    <p>Explore a range of guides, topics and commonly-asked questions to help you find what you're looking for. </p>
                </div>
            </div>
        </div>
    </div>
    <!-- end intro -->

    <!-- start main -->
    <main role="main">
        <!-- start section -->
        <section class="section section--no-pb">
            <div class="grid grid--container">
                <div class="row row--xs-middle">
                    <div class="col col--md-9 col--lg-8">
                        <form class="form--horizontal" method="get" action="{{ route('faqs') }}">
                            <div class="b-table">
                                <div class="cell v-middle">
                                    <div class="input-wrp">
                                        <input name="search" class="textfield" type="text" value="{{ request()->get('search') }}" placeholder="Enter your question" />
                                    </div>
                                </div>

                                <div class="cell v-middle">
                                    <button class="custom-btn custom-btn--medium custom-btn--style-2" type="submit" role="button">Find</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- end section -->



        <!-- start section -->
        <section class="section">
            <div class="grid grid--container">
                <div class="section-heading section-heading--center  col-MB-60">
                    <h2 class="__title">Frequently Asked Questions</h2>
                </div>

                <div class="row row--xs-middle">
                    <div class="col col--md-10">
                        <!-- start FAQ -->
                        <div class="faq">
                            <div class="accordion-container">
                                <!-- start item -->
                                @foreach($faqs as $item)
                                <div class="accordion-item">
                                    <div class="accordion-toggler">
                                        <h4 class="__title h5">{{ $item->title }}</h4>

                                        <i class="circled"></i>
                                    </div>
                                    <article>
                                        <div class="__inner">
                                            <p>
                                                {{ $item->details }}
                                            </p>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                                <!-- end item -->

                                @if (count($faqs) < 1)
                                    <h3 class="text-center">No Faqs found</h3>
                                @endif

                            </div>

                            <div class="text--center">
                                <a class="custom-btn custom-btn--medium custom-btn--style-1" href="{{ route('contact') }}">Contact Us</a>
                            </div>
                        </div>
                        <!-- end FAQ -->
                    </div>
                </div>
            </div>
        </section>
        <!-- end section -->



        <!-- start section -->
        <section class="section section--light-blue-bg section--custom-15">
            <style type="text/css">
                .section--custom-15
                {
                    background-image: url(img/sectoin_bg_2.png);
                    background-position: 50% 50%;
                    background-repeat: no-repeat;
                }
            </style>

            <div class="grid grid--container">
                <div class="row row--xs-middle row--xs-center  text--center">
                    <div class="col col--lg-auto">
                        <h2>Start Trading Today</h2>
                    </div>

                    <div class="col col--lg-auto">
                        <br class="hide--lg">
                        <a class="custom-btn custom-btn--medium custom-btn--style-2" href="{{ route('backend.dashboard') }}">Get Started</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- end section -->

    </main>
    <!-- end main -->

@endsection
