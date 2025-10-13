<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <title>{{ setting('site_name') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta name="viewport" content="user-scalable=no, width=device-width, height=device-height, initial-scale=1, maximum-scale=1, minimum-scale=1, minimal-ui" />

    <meta name="theme-color" content="#3F6EBF" />
    <meta name="msapplication-navbutton-color" content="#3F6EBF" />
    <meta name="apple-mobile-web-app-status-bar-style" content="#3F6EBF" />

    <!-- Favicons
    ================================================== -->
    <link rel="shortcut icon" href="img/favicon.html">
    <link rel="apple-touch-icon" href="img/apple-touch-icon.html">
    <link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.html">
    <link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.html">

    <!-- CSS
    ================================================== -->
    <link rel="stylesheet" href="css/style.min.css" type="text/css">

    <!-- Load google font
    ================================================== -->
    <script type="text/javascript">
        WebFontConfig = {
            google: { families: [ 'Open+Sans:300,400,500','Lato:900', 'Poppins:400', 'Catamaran:300,400,500,600,700'] }
        };
        (function() {
            var wf = document.createElement('script');
            wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
                '://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js';
            wf.type = 'text/javascript';
            wf.async = 'true';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(wf, s);
        })();
    </script>

    <!-- Load other scripts
    ================================================== -->
    <script type="text/javascript">
        var _html = document.documentElement,
            isTouch = (('ontouchstart' in _html) || (navigator.msMaxTouchPoints > 0) || (navigator.maxTouchPoints));

        _html.className = _html.className.replace("no-js","js");

        isTouch ? _html.classList.add("touch") : _html.classList.add("no-touch");
    </script>
    <script type="text/javascript" src="js/device.min.js"></script>
    <style>
        #google_translate_element{
            padding-top: 5px;
            padding-left: 5px;
        }
        .lang #top-bar__choose-lang .list-wrap {
            padding-top:25%;
        }
        @media only screen and (min-width: 992px){
            .section {
                padding-top: 30px;
                padding-bottom: 30px;
            }

        }

        .__item--fourth{
            background-color: #292b30;
        }
        .support {
            padding-top: 30px!important;
        }
       .support .__item {
            color: black;
           background: #fff;
           border: 1px solid #e7ebee;
           box-sizing: border-box;
           border-radius: 16px;
           margin-right: 5px;
           position: relative;
        }
    </style>

    <style type="text/css">
        /* OVERRIDE GOOGLE TRANSLATE WIDGET CSS BEGIN */
        div#google_translate_element div.goog-te-gadget-simple {
            border: none;
            background-color: transparent;
            /*background-color: #17548d;*/ /*#e3e3ff*/
        }

        div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value:hover {
            text-decoration: none;
        }

        div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value span {
            color: #aaa;
        }

        div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value span:hover {
            color: white;
        }

        .goog-te-gadget-icon {
            display: none !important;
            /*background: url("url for the icon") 0 0 no-repeat !important;*/
        }

        /* Remove the down arrow */
        /* when dropdown open */
        div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value span[style="color: rgb(213, 213, 213);"] {
            display: none;
        }
        /* after clicked/touched */
        div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value span[style="color: rgb(118, 118, 118);"] {
            display: none;
        }
        /* on page load (not yet touched or clicked) */
        div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value span[style="color: rgb(155, 155, 155);"] {
            display: none;
        }

        /* Remove span with left border line | (next to the arrow) in Chrome & Firefox */
        div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value span[style="border-left: 1px solid rgb(187, 187, 187);"] {
            display: none;
        }
        /* Remove span with left border line | (next to the arrow) in Edge & IE11 */
        div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value span[style="border-left-color: rgb(187, 187, 187); border-left-width: 1px; border-left-style: solid;"] {
            display: none;
        }
        /* HIDE the google translate toolbar */
        .goog-te-banner-frame.skiptranslate {
            display: none !important;
        }
        body {
            top: 0px !important;
        }

        .loan-rates {
            color: #fff!important;
            margin-bottom: 0px;
            font-weight: 800;
            line-height: 1;
        }
        /* OVERRIDE GOOGLE TRANSLATE WIDGET CSS END */
    </style>
</head>

<body>
<!-- start header -->
<header id="top-bar" class="top-bar--light">
    <div id="top-bar__inner">
        <a id="top-bar__logo" class="site-logo" href="{{ url('/') }}">
            <img class="img-responsive" width="175" height="42" src="img/site_logo.png" alt="demo" />
            <img class="img-responsive" width="175" height="42" src="img/site_logo.png" alt="demo" />
        </a>

        <a id="top-bar__navigation-toggler" href="javascript:void(0);"><span></span></a>

        <div id="top-bar__navigation-wrap">
            <div>
                <nav id="top-bar__navigation" class="navigation" role="navigation">
                    <ul>
                        <li class="active">
                            <a href="/"><span>Home</span></a>
                        </li>

                        <li>
                            <a href="javascript:void(0);"><span>Company</span></a>
                            <div class="submenu">
                                <ul>
                                    <li><a href="{{ route('about') }}">Why {{ setting('site_name') }}</a></li>
                                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="javascript:void(0);"><span>Markets</span></a>
                            <div class="submenu">
                                <ul>
                                    <li><a href="#">Forex</a></li>
                                    <li><a href="#l">Indices</a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="{{ route('faqs') }}"><span>FAQ’s</span></a>
                        </li>
                        <li>
                            <a href="{{ route('contact') }}"><span>Support</span></a>
                        </li>
                    </ul>
                </nav>

                <br class="hide--lg">

                <ul id="top-bar__subnavigation">
                    @guest()
                    <li><a class="custom-btn custom-btn--small custom-btn--style-3" href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Sign up</a></li>
                    @else
                        <li><a class="custom-btn custom-btn--small custom-btn--style-3" href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                    @endguest

                        <li class="lang">
                        <div id="top-bar__choose-lang">
                            <div class="list-wrap">
                            </div>

                            <i> <div id="google_translate_element"></div></i>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
<!-- end header -->

@yield('content')
<!-- start footer -->
<footer id="footer" class="text--center text--lg-left">
    <div class="grid grid--container">

        <div class="row row--xs-middle">
            <div class="col col--sm-10 col--md-8 col--lg-4">
                <div class="__item">
                    <a class="site-logo" href="{{ url('/') }}">
                        <img class="img-responsive lazy" width="175" height="42" src="/img/blank.gif" data-src="/img/site_logo.png" alt="{{ setting('site_name') }}" />
                    </a>

                    <p class="__text">
                        We have always followed a client-oriented approach and placed our clients’ interests at the center of all our operations.
                        {{ setting('site_name') }} team has a goal to be among the best online trading platforms in
                        the cryptocurrency industry and to retain our reputation as a most reliable and trusted partner.
                    </p>
                </div>
            </div>

            <div class="col col--sm-10 col--md-8 col--lg-3 col--xl-offset-1">
                <div class="__item">
                    <h4 class="__title">Main menu</h4>

                    <nav id="footer__navigation" class="navigation">
                        <div class="row row--xs-middle">
                            <div class="col col--xs-auto col--lg-6">
                                <ul class="__menu">
                                    <li><a href="{{ url('/') }}">Home</a></li>
                                    <li><a href="{{ route('about') }}">About us</a></li>
                                    <li><a href="{{ route('contact') }}">Contacts</a></li>
                                </ul>
                            </div>

                            <div class="col col--xs-auto col--lg-6">
                                <ul class="__menu">
                                    <li><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                                    <li><a href="{{ route('backend.dashboard') }}">Wallet</a></li>
                                    <li><a href="{{ route('faqs') }}">FAQ’s</a></li>
                                    <li><a href="{{ route('contact') }}">Support</a></li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>

            <div class="col col--sm-10 col--md-8 col--lg-5 col--xl-4">
                <div class="__item">
                    <h4 class="__title">Subscribe</h4>

                    <form class="form--horizontal" action="#">
                        <div class="b-table">
                            <div class="cell v-middle">
                                <div class="input-wrp">
                                    <input class="textfield" type="text" value="" placeholder="E-mail" />
                                </div>
                            </div>

                            <div class="cell v-middle">
                                <button class="custom-btn custom-btn--medium custom-btn--style-2" type="submit" role="button">Subscribe</button>
                            </div>
                        </div>
                    </form>

                    <p class="__note">Despite some naming, syntactic, and standard</p>
                </div>
            </div>
        </div>

        <div class="row row--xs-middle row--lg-between">
            <div class="col col--sm-10 col--md-8 col--lg-4">
                <div class="__item">
                    <div class="social-btns">
                        <a class="fontello-twitter" href="#"></a>
                        <a class="fontello-facebook" href="#"></a>
                        <a class="fontello-linkedin-squared" href="#"></a>
                    </div>
                </div>
            </div>

            <div class="col col--sm-10 col--md-8 col--lg-6  text--lg-right">
                <div class="__item">
                    <span class="__copy"><a class="__dev" href="{{ route('about') }}">About Us</a> | <a href="#">Privacy Policy</a> | <a href="#">Sitemap</a></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <p>Risk Warning: trading margined products is risky. It isn't suitable for everyone and you could lose substantially more
                    than your initial investment. You don't own or have rights in the underlying assets. Past performance is no
                    indication of future performance and tax laws are subject to change. The information on this website is general
                    in nature and doesn't take into account your personal objectives, financial circumstances, or needs.
                    Please read our RDN and other legal documents and ensure you fully understand the risks before you make any trading decisions.
                    We encourage you to seek independent advice. </p>

                <p>{{ setting('site_name') }}  Limited 2nd Floor, The Oval, Ring Road Parklands, PO Box 2905-00606 Nairobi, Kenya and
                    is licensed and regulated by the Capital Markets Authority. </p>

                <p>The information on this site and the products and services offered are not intended for distribution to any person in any country
                    or jurisdiction where such distribution or use would be contrary to local law or regulation. </p>

                <p>© {{ date('Y') }} {{ setting('site_name') }}  Limited | Company No.PVT-PJU7Q8K | CMA Licence No.128</p>
            </div>
        </div>
    </div>
</footer>
<!-- end footer -->

<div id="btn-to-top-wrap">
    <a id="btn-to-top" class="circled" href="javascript:void(0);" data-visible-offset="800"></a>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/jquery-2.2.4.min.js"><\/script>')</script>

<script type="text/javascript" src="js/main.min.js"></script>
<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<!-- Google Translate Element begin -->
<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: 'en,es,lt,ru,it,de,bn,ms,ko,ja,pl,zh-CN,ms,th',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE
        }, 'google_translate_element');
    }
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script>
    $('document').ready(function () {
        $('#google_translate_element').on("click", function () {

            // Change font family and color
            $("iframe").contents().find(".goog-te-menu2-item div, .goog-te-menu2-item:link div, .goog-te-menu2-item:visited div, .goog-te-menu2-item:active div") //, .goog-te-menu2 *
                .css({
                    'color': '#544F4B',
                    'background-color': '#e3e3ff',
                    'font-family': '"Open Sans",Helvetica,Arial,sans-serif'
                });

            // Change hover effects  #e3e3ff = white
            $("iframe").contents().find(".goog-te-menu2-item div").hover(function () {
                $(this).css('background-color', '#17548d').find('span.text').css('color', '#e3e3ff');
            }, function () {
                $(this).css('background-color', '#e3e3ff').find('span.text').css('color', '#544F4B');
            });

            // Change Google's default blue border
            $("iframe").contents().find('.goog-te-menu2').css('border', '1px solid #17548d');

            $("iframe").contents().find('.goog-te-menu2').css('background-color', '#e3e3ff');

            // Change the iframe's box shadow
            $(".goog-te-menu-frame").css({
                '-moz-box-shadow': '0 3px 8px 2px #666666',
                '-webkit-box-shadow': '0 3px 8px 2px #666',
                'box-shadow': '0 3px 8px 2px #666'
            });
        });
    });
</script>
<!-- Google Translate Element end -->
</body>
</html>
