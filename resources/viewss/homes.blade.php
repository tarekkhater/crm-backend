@extends('layouts.front')

@section('content')

    <!-- start start screen -->
    <div id="start-screen" class="start-screen--light start-screen--style-6">
        <div class="start-screen__slider" data-slick='{"autoplay": true, "fade": true, "dots": true, "speed": 1200}'>

            <!-- start slide -->
            <div class="__slide __slide--1">
                <div class="start-screen__content" style="background-image: url(img/slider_img/1.jpg)">
                    <div class="start-screen__content__inner">
                        <div class="grid grid--container">
                            <div class="row">
                                <div class="col col--md-9 col--lg-7 col--xl-6">
                                    <h2 class="h1 __title">Trade with confidence </h2>

                                    <p>Trade CFDs on Forex, Gold, Indices, global Shares and more
                                    </p>

                                    <a class="custom-btn custom-btn--medium custom-btn--style-2" href="{{ route('backend.dashboard') }}">Get Started</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end slide -->

            <!-- start slide -->
            <div class="__slide __slide--2">
                <div class="start-screen__content  text--center" style="background-image: url(img/slider_img/2.jpg)">
                    <div class="start-screen__content__inner">
                        <div class="grid grid--container">
                            <div class="row row--xs-middle">
                                <div class="col col--lg-11">
                                    <h2 class="h1 __title">Next generation trading platform</h2>

                                    <p>
                                        {{ env('APP_NAME') }} is an award-winning platform that allows you to trade global financial markets using Bitcoin, USD Tether, USDC and more
                                    </p><br>

                                    <a class="custom-btn custom-btn--medium custom-btn--style-2" href="{{ route('backend.dashboard') }}">Get Started</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end slide -->

            <!-- start slide -->
            <div class="__slide __slide--3">
                <div class="start-screen__content  text--center" style="background-image: url(img/slider_img/3.jpg)">
                    <div class="start-screen__content__inner">
                        <div class="grid grid--container">
                            <div class="row row--xs-middle">
                                <div class="col">
                                    <p><span class="num">Let top traders do the job for you!</span></p>

                                    <p>
                                        <strong>Covesting allows you to automatically copy top-performing traders and achieve the same returns
                                            )</strong>
                                    </p><br>

                                    <a class="custom-btn custom-btn--medium custom-btn--style-2" href="#das">Get Started</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end slide -->

        </div>

        <span class="scroll-discover hide show--md">scroll down <b></b></span>

    </div>
    <!-- end start screen -->

    <!-- start main -->
    <main role="main">
        <!-- start section -->
{{--        <section class="section section--no-pt">--}}
{{--            <div class="grid grid--container">--}}
{{--                <div class="logos">--}}
{{--                    <div class="__inner">--}}
{{--                        <div class="row row--xs-center row--xs-around">--}}
{{--                            <div class="col col--xs-auto">--}}
{{--                                <img class="lazy" src="img/blank.gif" data-src="img/logos_img/14.png" alt="demo" />--}}
{{--                            </div>--}}

{{--                            <div class="col col--xs-auto">--}}
{{--                                <img class="lazy" src="img/blank.gif" data-src="img/logos_img/15.png" alt="demo" />--}}
{{--                            </div>--}}

{{--                            <div class="col col--xs-auto">--}}
{{--                                <img class="lazy" src="img/blank.gif" data-src="img/logos_img/16.png" alt="demo" />--}}
{{--                            </div>--}}

{{--                            <div class="col col--xs-auto">--}}
{{--                                <img class="lazy" src="img/blank.gif" data-src="img/logos_img/17.png" alt="demo" />--}}
{{--                            </div>--}}

{{--                            <div class="col col--xs-auto">--}}
{{--                                <img class="lazy" src="img/blank.gif" data-src="img/logos_img/18.png" alt="demo" />--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </section>--}}
        <!-- end section -->

        <!-- start section -->
        <section class="section section--no-pt">
            <div class="grid">
                <div class="row row--md-center">
                    <div class="col col--md-5 col--lg-6 col--no-gutters">
                        <img class="img-responsive" src="img/img_4.1.jpg" alt="demo" />
                    </div>

                    <div class="col col--md-7 col--lg-6">
                        <div class="content-grid" style="margin-right: auto;max-width: 570px;">
                            <div data-aos="fade-left" data-aos-delay="150">
                                <div class="section-heading  col-MB-30">
                                    <h5 class="__subtitle">WHY {{ setting('site_name') }} </h5>

                                    <h2 class="__title">Low spreads on more than 170 instruments</h2>
                                </div>

                                <p class="col-MB-35">
                                    {{ setting('site_name') }} is built on an uncompromising level of service for all its clients, underpinned by some of the best prices and execution speeds in the industry. Trade market-leading spreads with a global FX and CFD broker.
                                </p>

                                <p>
                                    <a class="custom-btn custom-btn--medium custom-btn--style-1" href="#das">Get Started</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col hide--md col-MB-40"></div>
                </div>

                <div class="row row--md-center row--md-reverse">
                    <div class="col col--md-5 col--lg-6 col--no-gutters">
                        <img class="img-responsive" src="/img/2021/screen1.png" alt="demo" />
                    </div>

                    <div class="col hide--md col-MB-40"></div>

                    <div class="col col--md-7 col--lg-6">
                        <div class="content-grid" style="margin-left: auto;max-width: 570px;">
                            <div data-aos="fade-right" data-aos-delay="150">
                                <div class="section-heading  col-MB-30">
                                    <h2 class="__title">Copy leading traders with Covesting</h2>
                                </div>


                                <p class="col-MB-35">
                                  <strong>New to trading?</strong> <br/>
                                    Choose among best performing strategies and automatically copy their trading activity to get the same returns!
                                </p>
                                <p class="col-MB-35">
                                    <strong>Experienced trader?</strong> <br />
                                    Make additional income with Covesting copy-trading platform . Earn up to 20% of all profits earned for your followers!
                                </p>

                                <p>
                                    <a class="custom-btn custom-btn--medium custom-btn--style-1" href="#">Discover</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end section -->

        <!-- start section -->
        <section class="section section--dark-bg section--custom-01">
            <style type="text/css">
                @media only screen and (min-width: 768px)
                {
                    .section--custom-01 .img-wrap
                    {
                        position: absolute;
                        top: 0;
                        left: 15px;
                        right: 0;
                        bottom: 0;
                        margin-left: 42%;
                        overflow: hidden;
                    }
                }

                @media only screen and (min-width: 992px)
                {
                    .section--custom-01 .__content { margin-bottom: 90px; }

                    .section--custom-01 .img-wrap { margin-left: 45%; }
                }
            </style>

            <div class="grid grid--container">
                <div class="row">
                    <div class="col col--md-5 col--lg-5">
                        <div data-aos="fade-up" data-aos-easing="ease-out-cubic">
                            <div class="__content">
                                <div class="section-heading section-heading--white  col-MB-30">
                                    <h5 class="__subtitle">{{ setting('site_name') }} Large Customer base</h5>

                                    <h2 class="__title">Why People choose {{ setting('site_name') }}</h2>
                                </div>

                                <p class="col-MB-35">
                                    We’re by your side for every trade, combining fast execution speeds, raw spreads
                                    and low commissions with a genuine commitment to helping you achieve your trading goals.
                                </p>

                                <p class="hide--md">
                                    <img class="img-responsive" src="img/img_3.jpg" alt="demo" />
                                </p>

                                <p>
                                    <a class="custom-btn custom-btn--medium custom-btn--style-2" href="#das">Get Started</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="img-wrap jarallax  hide show--md" data-speed="0.3">
                <img class="jarallax-img" src="img/img_3.jpg" alt="demo" />
            </div>
        </section>
        <!-- end section -->

        <!-- start section -->
        <section class="section  section--no-pb section--custom-02">
            <style type="text/css">
                @media only screen and (min-width: 992px)
                {
                    .section--custom-02 .feature--style-1 { margin-top: -140px; }
                }
            </style>

            <!-- start feature -->
            <div class="feature feature--style-1  text--center text--sm-left">
                <div class="row row--no-gutters">
                    <!-- start item -->
                    <div class="col col--no-gutters col--sm-6 col--lg-3 col--sm-flex">
                        <div class="__item  __item--first" data-aos="slide-up" data-aos-offset="-250" data-aos-duration="800" data-aos-delay="0">
                            <div class="__content">
{{--                                <i class="__ico">--}}
{{--                                    <img class="img-responsive lazy" src="img/blank.gif" data-src="images/icon/account-setup.svg" width="34" height="60" alt="demo" />--}}
{{--                                </i>--}}

                                <h3 class="__title">Trade with the best</h3>

                                <p> {{ setting('site_name') }} has received multiple international awards for providing superior online trading services globally</p>

                                <p><a class="__more" href="#"><i class="fontello-right-1"></i></a></p>
                            </div>
                        </div>
                    </div>
                    <!-- end item -->

                    <!-- start item -->
                    <div class="col col--no-gutters col--sm-6 col--lg-3 col--sm-flex">
                        <div class="__item  __item--second" data-aos="slide-up" data-aos-offset="-250" data-aos-duration="800" data-aos-delay="150">
                            <div class="__content">

                                <h3 class="__title">Find new opportunities</h3>

                                <p>We provide our clients with the most innovative products and access to a wide range of markets</p>

                                <p><a class="__more" href="#das"><i class="fontello-right-1"></i></a></p>
                            </div>
                        </div>
                    </div>
                    <!-- end item -->

                    <!-- start item -->
                    <div class="col col--no-gutters col--sm-6 col--lg-3 col--sm-flex">
                        <div class="__item  __item--third" data-aos="slide-up" data-aos-offset="-250" data-aos-duration="800" data-aos-delay="300">
                            <div class="__content">

                                <h3 class="__title">Increase profitability</h3>

                                <p>Benefit from low fees, fast order execution, and advanced platform features to increase your profitability.</p>

                                <p><a class="__more" href="#das"><i class="fontello-right-1"></i></a></p>
                            </div>
                        </div>
                    </div>
                    <!-- end item -->
                    <!-- start item -->
                    <div class="col col--no-gutters col--sm-6 col--lg-3 col--sm-flex">
                        <div class="__item  __item--fourth" data-aos="slide-up" data-aos-offset="-250" data-aos-duration="800" data-aos-delay="300">
                            <div class="__content">

                                <h3 class="__title">Enjoy privacy and security</h3>

                                <p>Our platform is designed to protect funds and personal data better. Your privacy always comes first</p>

                                <p><a class="__more" href="#das"><i class="fontello-right-1"></i></a></p>
                            </div>
                        </div>
                    </div>
                    <!-- end item -->
                </div>
            </div>
            <!-- end feature -->

            <!-- start screenshots -->
            <div class="screenshots screenshots--slider" data-slick='{"autoplay": true, "arrows": false, "dots": true, "speed": 1200}'>
                <!-- start item -->
                <div class="__item">
                    <img src="/img/2021/screen11.png" alt="wallet" />
                </div>
                <!-- end item -->

                <!-- start item -->
                <div class="__item">
                    <img src="/img/2021/screen2.png" alt="wallet" />
                </div>
                <!-- end item -->

                <!-- start item -->
                <div class="__item">
                    <img src="/img/2021/screen3.png" alt="wallet" />
                </div>
                <!-- end item -->

                <!-- start item -->
                <div class="__item">
                    <img src="/img/2021/screen4.png" alt="wallet" />
                </div>
                <!-- end item -->

                <!-- start item -->
                <div class="__item">
                    <img src="/img/2021/screen11.png" alt="wallet" />
                </div>
                <!-- end item -->
            </div>
            <!-- end screenshots -->
        </section>
        <!-- end section -->

        <!-- start section -->
        <section class="section">
            <div class="grid grid--container">
                <div class="section-heading section-heading--center  col-MB-60">
                    <h2 class="__title">Award-winning customer support</h2>
                    <p>Nothing should get in the way of making your best trade. With 24/5 support, a wealth of trading
                        resources and a seamless account experience, you’re free to focus on what matters.</p>
                </div>

                <div style="width:100%;position:relative;">

                </div>
            </div>
            <!-- start feature -->
            <div class="feature feature--style-1  text--center text--sm-left">
                <div class="row row--no-gutters support">
                    <!-- start item -->
                    <div class="col col--no-gutters col--sm-6 col--lg-4 col--sm-flex ">
                        <div class="__item  __item--firs" data-aos="slide-up" data-aos-offset="-250" data-aos-duration="800" data-aos-delay="0">
                            <div class="__content">

                                <h3 class="__title">   Multilingual support</h3>

                                <p>Access multilingual support 24/5 across live chat, email and phone.</p>
                                <p>Contact Us</p>

                                <p><a class="__more" href="{{ route('contact') }}"><i class="fontello-right-1"></i></a></p>
                            </div>
                        </div>
                    </div>
                    <!-- end item -->

                    <!-- start item -->
                    <div class="col col--no-gutters col--sm-6 col--lg-4 col--sm-flex">
                        <div class="__item  __item--secon" data-aos="slide-up" data-aos-offset="-250" data-aos-duration="800" data-aos-delay="150">
                            <div class="__content">



                                <h3 class="__title">Apply in minutes</h3>

                                <p>Apply for a trading account with electronic ID verification available in many countries.</p>
                                <p>Create Account</p>

                                <p><a class="__more" href="#das"><i class="fontello-right-1"></i></a></p>
                            </div>
                        </div>
                    </div>
                    <!-- end item -->

                    <!-- start item -->
                    <div class="col col--no-gutters col--sm-6 col--lg-4 col--sm-flex">
                        <div class="__item  __item--thir" data-aos="slide-up" data-aos-offset="-250" data-aos-duration="800" data-aos-delay="300">
                            <div class="__content">




                                <h3 class="__title">Seamless trading</h3>

                                <p>Multiple funding and withdrawal options are available for your convenience.</p>
                                <p>View our funding options</p>

                                <p><a class="__more" href="#das"><i class="fontello-right-1"></i></a></p>
                            </div>
                        </div>
                    </div>
                    <!-- end item -->

                </div>
            </div>
            <!-- end feature -->
        </section>
        <!-- end section -->


        <!-- start section -->
        <section class="section section--light-blue-bg">
            <div class="grid grid--container">
                <div class="section-heading section-heading--center  col-MB-60">
{{--                    <h5 class="__subtitle">Popular Cryptocarrency prices</h5>--}}

                    <h2 class="__title">Cryptocurrency Prices</h2>
                </div>

                <script>
                    (function(b,i,t,C,O,I,N) {
                        window.addEventListener('load',function() {
                            if(b.getElementById(C))return;
                            I=b.createElement(i),N=b.getElementsByTagName(i)[0];
                            I.src=t;I.id=C;N.parentNode.insertBefore(I, N);
                        },false)
                    })(document,'script','https://widgets.bitcoin.com/widget.js','btcwdgt');
                </script>

                <div class="btcwdgt-chart" bw-theme="light" style="max-width: 100% !important"></div>
            </div>
        </section>
        <!-- end section -->


        <!-- start section -->
        <section class="section">
            <div class="grid grid--container">
                <div class="section-heading section-heading--center  col-MB-60">
                    <h2 class="__title">Open a free account and start trading in minutes!</h2>
                    <p>
                        We provide our clients with a wide range of trading tools and features to improve their trading results</p>
                </div>
            </div>
            <div class="grid grid--container">
                <div class="row row--xs-center">
                    <div class="col col--lg-5">
                        <div data-aos="fade-right" data-aos-offset="0">
                            <p><strong> Best-in-class platform</strong></p>
                            <p class="col-MB-35">
                                Become a better trader with advanced trading tools. {{ setting('site_name') }} provides an
                                award-winning platform that is used by traders from around the world.
                            </p>
                            <p><strong>Global markets</strong></p>
                            <p class="col-MB-35">
                                {{ setting('site_name') }} allows its users to access multiple markets from a single account.
                                Trade Cryptocurrencies, Forex, Commodities, and much more.
                            </p>
                            <p><strong>24/7 support</strong></p>
                            <p class="col-MB-35">
                            Our customer service team works 24/7 to provide you with an exceptional level of support.
                            </p>
                        </div>
                    </div>

                    <div class="col hide--md col-MB-40"></div>

                    <div class="col col--lg-7">
                        <div data-aos="fade" data-aos-delay="300">
                            <img class="img-responsive center-block lazy" src="img/blank.gif" data-src="images/bg.jpeg" alt="demo" />
                        </div>
                    </div>
                </div>
            </div>
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

        <!-- start section -->
        <section class="section jarallax" data-speed="0.5">
            <img class="jarallax-img" src="img/bg_.jpg" alt="demo" />

            <div class="pattern" style="background-color: rgba(0, 0, 0, 0.53);"></div>

            <div class="grid grid--container">
                <div class="section-heading section-heading--center section-heading--white  col-MB-45">
                    <h2 class="__title">We can Help You</h2>
                </div>

                <div class="row row--xs-middle">
                    <div class="col col--xs-10">
                        <!-- start company contacts -->
                        <div class="company-contacts  text--center">
                            <div class="__inner">
                                <div class="row row--xs-between">
                                    <!-- start item -->
                                    <div class="col col--lg-4 col--xl-3">
                                        <div class="__item">
                                            <p>{{ env('address') }}</p>
                                        </div>
                                    </div>
                                    <!-- end item -->

                                    <!-- start item -->
                                    <div class="col col--sm-6 col--lg-4 col--xl-3">
                                        <div class="__item">
                                            <p>Call Us: <a href="tel:{{ env('PHONE') }}">{{ env('PHONE') }}</a></p>
                                        </div>
                                    </div>
                                    <!-- end item -->

                                    <!-- start item -->
                                    <div class="col col--sm-6 col--lg-4 col--xl-3">
                                        <div class="__item">
                                            <p>Email: <a href="mailto: {{ env('email') }}">{{ env('email') }}</a></p>
                                        </div>
                                    </div>
                                    <!-- end item -->
                                </div>
                            </div>
                        </div>
                        <!-- end company contacts -->
                    </div>
                </div>
            </div>
        </section>
        <!-- end section -->
    </main>
    <!-- end main -->


@endsection
