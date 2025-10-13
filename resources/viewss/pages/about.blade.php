@extends('layouts.front')

@section('content')

    <!-- start intro -->
    <div id="intro" class="jarallax" data-speed="0.5" style="background-image: url(img/intro_img/1.jpg);">
        <div class="grid grid--container">
            <div class="row row--xs-middle">
                <div class="col col--lg-6 text--center">
                    <h2 class="__title">About {{ setting('site_name') }}</h2>
                    <p>{{ setting('site_name') }} is the world’s most popular way to buy and sell bitcoin, ethereum,  litecoin and other crypto currencies</p>
                </div>
            </div>
        </div>
    </div>
    <!-- end intro -->

    <!-- start main -->
    <main role="main">
        <!-- start section -->
        <section class="section">
            <div class="grid ">
                <div class="section-heading section-heading--center  col-MB-60">
                    <h5 class="__subtitle">Why {{ setting('site_name') }}?</h5>

                    <h2 class="__title">Why choose us!</h2>
                </div>

                <div class="row row--xs-middle">
                    <div class="col col--lg-10 col--xl-8">
                        <p>
                            We’re by your side for every trade, combining fast execution speeds,
                            raw spreads and low commissions with a genuine commitment to helping you achieve your trading goals.
                        </p>

                        <h2 class="__title">Our Company</h2>
                        <p>
                           {{ setting('site_name') }} was founded in 2018 and from the very first day, we have grown exponentially and currently
                            serve clients in more than 150 countries. We provide our clients with access to top-tier liquidity and wide range
                            of trading tools, while maintaining security,
                            liquidity, enabling a safe and efficient trading environment for everyone.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- end section -->

        <!-- start section -->
        <section class="section section--light-blue-bg section--custom-13">
            <style type="text/css">
                .section--custom-13
                {
                    background-image: url(img/sectoin_bg_2.png);
                    background-position: 50% 50%;
                    background-repeat: no-repeat;
                }
            </style>

            <div class="grid grid--container">
                <div class="row row--md-middle">
                    <div class="col col--lg-12 col--xl-12">
                        <!-- start feature -->
                        <div class="facts facts--dark-color  text--center text--sm-left">
                            <div class="__inner">
                                <div class="row row--md-between">
                                    <div class="col col--sm-6 col--md-3">
                                        <div class="__item text--center">
                                            <img src="/img/2021/time.svg" /><br />
                                            Ultra-fast order execution. < 7.12 ms on average
                                        </div>
                                    </div>

                                    <div class="col col--sm-6 col--md-3">
                                        <div class="__item text--center">
                                            <img src="/img/2021/execution.svg" /><br />
                                            Industry-leading trade engine with real-time risk management
                                        </div>
                                    </div>

                                    <div class="col col--sm-6 col--md-3">
                                        <div class="__item text--center">
                                            <img src="/img/2021/latency.svg" /><br />
                                            Secure and powerful infrastructure powered by Amazon AWS
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end feature -->
                    </div>
                </div>
            </div>
        </section>
        <!-- end section -->

        <!-- start section -->
        <section class="section">
            <div class="grid grid--container">
                <div class="row">
                    <div class="col col--md-6">
                        <div data-aos="zoom-in">
                            <img class="img-responsive lazy" src="img/blank.gif" data-src="/img/img_4.png" alt="demo" />
                        </div>
                    </div>

                    <div class="col hide--md col-MB-40"></div>

                    <div class="col col--md-6">
                        <div data-aos="fade-left">
                            <div class="section-heading  col-MB-30">
{{--                                <h5 class="__subtitle">Benefits</h5>--}}

                                <h2 class="__title">Technology</h2>
                            </div>

                            <p class="col-MB-35">
                                {{ setting('site_name') }} offers a robust trading system for both beginners and professional traders that demand highly reliable
                                market data and performance. Entire infrastructure is designed to facilitate high number of orders per second and extreme loads,
                                while offering ultra-fast order execution and low latency. Our traders of all experience levels can easily design and customize
                                layouts and widgets to best fit their trading style. We are proud to offer such innovative products and professional trading
                                conditions to all our customers.
                            </p>

                            <p>
                                <a class="custom-btn custom-btn--medium custom-btn--style-1" href="{{ route('backend.dashboard') }}">Get Started</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end section -->

        <!-- start section -->
        <section class="section section--light-blue-bg section--custom-14">
            <style type="text/css">
                .section--custom-14
                {
                    background-image: url(img/sectoin_bg_4.png);
                    background-position: 50% 50%;
                    background-repeat: no-repeat;
                }
            </style>

            <div class="grid grid--container">
                <div class="section-heading section-heading--center  col-MB-60">
                    <h5 class="__subtitle">Behind the scene</h5>

                    <h2 class="__title">How we execute your trades</h2>
                </div>

                <p>All trades on {{ setting('site_name') }} trading platform are executed at one of our servers in London and Frankfurt. We are connected with multiple liquidity providers to ensure low latency and smooth pricing on all available assets.</p>
            </div>
                <!-- start team -->
                <div class="team team--style-2 team--dark-color">
                    <div class="__inner" style="background-image: url(/img/2021/execution-bg.png); height: 500px; margin-top: 30px; margin-bottom: 30px">
                    </div>
                </div>
                <!-- end team -->
        </section>
        <!-- end section -->

        <!-- start section -->
        <section class="section section--base-bg">
            <div class="grid grid--container">
                <!-- start testimonial slider -->
                <div class="row">
                    <div class="col col--md-2  hide show--md">
                        <i class="testimonial-ico">“</i>
                    </div>

                    <div class="col col--md-10">
                        <!-- start testimonial -->
                        <div class="testimonial testimonial--slider" data-slick='{"autoplay": true, "arrows": false, "dots": true, "speed": 1000}'>
                            <!-- start item -->
                            <div class="__item">
                                <div class="__text">
                                    <p>
                                        Oh no way! Can I arks you a question, this heaps good hokey pokey is as stuffed as a snarky misses. Good afterble constanoon. Can't handle the jandle, do you happen to have a bucket or a hose bro?, piece of piss. Mean while, in the marae, Maui and the Armed Offenders Squad were up to no good with a bunch of sweet pavlovas. Chur bro, you're not in Guatemala now.
                                    </p>
                                </div>

                                <div class="__author">
                                    <h5 class="__author--name">Joseph Allan</h5>
                                    <span class="__author--position">CEO Silver Development</span>
                                </div>
                            </div>
                            <!-- end item -->

                            <!-- start item -->
                            <div class="__item">
                                <div class="__text">
                                    <p>
                                        Oh no way! Can I arks you a question, this heaps good hokey pokey is as stuffed as a snarky misses. Good afterble constanoon. Can't handle the jandle, do you happen to have a bucket or a hose bro?, piece of piss. Mean while, in the marae, Maui and the Armed Offenders Squad were up to no good with a bunch of sweet pavlovas. Chur bro, you're not in Guatemala now.
                                    </p>

                                    <p>
                                        Oh no way! Can I arks you a question, this heaps good hokey pokey is as stuffed as a snarky misses. Good afterble constanoon. Can't handle the jandle, do you happen to have a bucket or a hose bro?, piece of piss. Mean while, in the marae, Maui and the Armed Offenders Squad were up to no good with a bunch of sweet pavlovas. Chur bro, you're not in Guatemala now.
                                    </p>
                                </div>

                                <div class="__author">
                                    <h5 class="__author--name">Joseph Allan</h5>
                                    <span class="__author--position">CEO Silver Development</span>
                                </div>
                            </div>
                            <!-- end item -->

                            <!-- start item -->
                            <div class="__item">
                                <div class="__text">
                                    <p>
                                        Oh no way! Can I arks you a question, this heaps good hokey pokey is as stuffed as a snarky misses. Good afterble constanoon. Can't handle the jandle, do you happen to have a bucket or a hose bro?, piece of piss. Mean while, in the marae, Maui and the Armed Offenders Squad were up to no good with a bunch of sweet pavlovas. Chur bro, you're not in Guatemala now.
                                    </p>
                                </div>

                                <div class="__author">
                                    <h5 class="__author--name">Joseph Allan</h5>
                                    <span class="__author--position">CEO Silver Development</span>
                                </div>
                            </div>
                            <!-- end item -->
                        </div>
                        <!-- end testimonial -->
                    </div>
                </div>
                <!-- end testimonial slider -->
            </div>
        </section>
        <!-- end section -->

        <section class="section">
            <div class="grid grid--container">
                <div class="section-heading section-heading--center  col-MB-60">
                    <h5 class="__subtitle">Support</h5>

                    <h2 class="__title">Contact us</h2>
                </div>

                <p>The {{ setting('site_name') }} team is committed to providing 24/7 customer service. For all questions, security issues, product related inquiries and business proposals please reach us at support@{{ setting('site_name') }}.com
            </div>
        </section>

        <!-- start section -->
        <section class="section">
            <div class="grid grid--container">
                <div class="logos">
                    <div class="__inner">
                        <div class="row row--xs-center row--xs-around">
                            <div class="col col--xs-auto">
                                <img class="lazy" src="img/blank.gif" data-src="img/logos_img/14.png" alt="demo" />
                            </div>

                            <div class="col col--xs-auto">
                                <img class="lazy" src="img/blank.gif" data-src="img/logos_img/15.png" alt="demo" />
                            </div>

                            <div class="col col--xs-auto">
                                <img class="lazy" src="img/blank.gif" data-src="img/logos_img/16.png" alt="demo" />
                            </div>

                            <div class="col col--xs-auto">
                                <img class="lazy" src="img/blank.gif" data-src="img/logos_img/17.png" alt="demo" />
                            </div>

                            <div class="col col--xs-auto">
                                <img class="lazy" src="img/blank.gif" data-src="img/logos_img/18.png" alt="demo" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end section -->
    </main>
    <!-- end main -->
@endsection
