<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name='robots' content='noindex,follow' />
    <link rel="shortcut icon" href="{{ setting('favicon', '/asset/images/logosm.png') }}">
    <title>{{ setting('site_name') }} </title>

    <!-- CSS -->
    <link rel='stylesheet' type='text/css' media='all' href="/assets/css/simple-line-icons.css"/>
    <link rel='stylesheet' type='text/css' media='all' href='/assets/css/cryptocoins.css'/>
    <link rel='stylesheet' type='text/css' media='all' href='/assets/css/style-inline.css'/>
    <link rel='stylesheet' type='text/css' media='all' href='/assets/css/media-screens.css' />
    <link rel='stylesheet' type='text/css' media='all' href='/assets/css/owl.carousel.css' />
    <link rel='stylesheet' type='text/css' media='all' href='/assets/css/animate.css' />
    <link rel='stylesheet' type='text/css' media='all' href='/assets/css/responsive.css' />
    <link rel='stylesheet' type='text/css' media='all' href='/assets/css/style.css' />
    <link rel='stylesheet' type='text/css' media='all' href='/assets/plugins/font-awesome/css/font-awesome.min.css' />
    <link rel='stylesheet' type='text/css' media='all' href='/assets/plugins/select2/css/select2.min.html' />

    <!-- CHARTS -->
    <link href="/assets/plugins/amcharts/export.css" rel="stylesheet">
    <link href="/assets/plugins/amcharts/pie-chart.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/js/iziModal.min.js" integrity="sha512-8aOKv+WECF2OZvOoJWZQMx7+VYNxqokDKTGJqkEYlqpsSuKXoocijXQNip3oT4OEkFfafyluI6Bm6oWZjFXR0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/css/iziModal.min.css" integrity="sha512-8vr9VoQNQkkCCHGX4BSjg63nI5CI4B+nZ8SF2xy4FMOIyH/2MT0r55V276ypsBFAgmLIGXKtRhbbJueVyYZXjA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Fonts -->
    <link rel='stylesheet' type='text/css' media='all' href='../../fonts.googleapis.com/cssdd33.css?family=Open+Sans%3A300%2C400%2C600%2C700%2C800%7CRaleway%3A100%2C200%2C300%2C400%2C500%2C600%2C700%2C800%2C900%7CDroid+Serif%3A400%2C700%7CMontserrat%7CMontserrat%3Aregular%2C700%2Clatin'/>

    <style>
        .video-presentation-sect {
            height: 420px;
            width: 100%;
            position: relative;
            overflow: hidden;
            background: url(https://www.crypto-express.online/images/bg-video-banner.jpg) no-repeat 50% 0 fixed;
            background-size: cover;
        }
        .site-warning{
            background-color: white;
            padding: 20px;
            color: #0a0c12;
            margin: 200px;
            border: 1px solid #0A72E8;
        }

        .list_title_text {
            font-size: 1.3em!important;
        }
        .bitcurrency-contact {
            background-color: #607D8B!important;
            padding: 50px;
        }
    </style>
</head>

<body class="page-template-default page page-child footer_row1_off is_header_semitransparent home-ico-consultant">
<div class="cryptic_preloader_holder cryptic_preloader_holder_ico v2_ball_pulse">
    <div class="cryptic_preloader v2_ball_pulse">
        <div class="loaders">
            <div class="loader">
                <div class="loader-inner ball-pulse">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modeltheme-modal" id="modal-log-in">
    <div class="modeltheme-content" id="login-modal-content">
        <h3 class="relative"> Login to Your Account </h3>
        <div class="modal-content row">
            <div class="col-md-12">
                @error('email')

                <div class="alert alert-success alert-dismissible cryptic-demo-login">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong style="color: red">{{ $message }}</strong>
                </div>

                @enderror


                <form name="loginform" action="{{ route('login') }}" id="loginform" method="post">
                    @csrf

                    <p class="login-username">
                        <label for="user_login">Username or Email Address</label>
                        <input type="email" name="email" id="user_login" class="input" value="" size="20" />
                    </p>
                    <p class="login-password">
                        <label for="user_pass">Password</label>
                        <input type="password" name="password" id="user_pass" class="input" value="" size="20" />
                    </p>
                    <p class="login-remember">
                        <label><input name="rememberme" type="checkbox" id="rememberme" value="forever" /> Remember Me</label>
                    </p>
                    <p class="login-submit">
                        <input type="submit" name="submit" id="submit" class="button button-primary" value="Log In" />
                        <input type="hidden" name="redirect_to" value="{{ route('home') }}" />
                    </p>
                </form>
                <p class="um-notice err text-center">Registration is currently disabled</p>
            </div>
        </div>
    </div>
    <div class="modeltheme-content" id="signup-modal-content">
        <h3 class="relative"> Personal Details </h3>
        <div class="modal-content row">
            <div class="col-md-12"> [ultimatemember form_id=16587] </div>
        </div>
    </div>
</div>

<div class="modeltheme-overlay"></div>
<!-- Fixed Search Form -->


<!-- HEADER -->
<div class="hfeed site">
    <header class="header4">
        <!-- BOTTOM BAR -->
        <nav class="navbar navbar-default {{ active(['home'],'transparent-nav') }}" id="modeltheme-main-head">
            <div class="container">
                <div class="row">
                    <!-- LOGO -->
                    <div class="navbar-header col-md-2">
                        <!-- NAVIGATION BURGER MENU -->
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <h1 class="logo">
                            <a href="{{ route('home') }}">
                                <img style="height: 50px" src="/images/logo1.png" alt="Binary24Trades" />
                            </a>
                        </h1>
                    <div class="col-12">
                        <div class="main-menu">
                            <div id="navbarNav" class="collapse navbar-collapse">
                                <ul class="">
                                    <li class="mob-only no-bg open-acc"><a class="" href="{{ route('register') }}" rel="nofollow">Registration</a>                                            </li>
                                    <li class="mob-only no-bg"><a class="" href="{{ route('login') }}" rel="nofollow">Sign in</a></li>
                                    <li>
                                        <a style="cursor: pointer" href="{{ route('home') }}">Home<span></span></a>
                                        <div class="bg-menu"></div>
                                    </li>

                                    <li>
                                        <a style="cursor: pointer" href="{{ route('about') }}" rel="nofollow">About us</a>
                                        <div class="bg-menu"></div>
                                    </li>

                                    <li><a>Trading<span></span></a>
                                        <div class="dropdawn-menu-block">
                                            <div class="row d-flex align-items-start">
                                                <div class="col-12 d-flex align-items-start">
                                                    <ul class="dropdawn-menu"><li><a href="{{ route('withdrawal') }}"><span>Withdrawal Info</span></a></li>
                                                        <li><a href="{{ route('verification') }}"><span>FAQ Verifications</span></a></li></ul>                                                        </div>
                                            </div>
                                        </div>
                                        <div class="bg-menu" style="display: none; height: 0px;"></div>
                                    </li>
                                    <li>
                                        <a style="cursor: pointer" href="{{ route('fags') }}" rel="nofollow">Faqs</a>
                                        <div class="bg-menu"></div>
                                    </li>
                                    <li>
                                        <a style="cursor: pointer" href="{{ route('contact') }}" rel="nofollow">Contact us</a>
                                        <div class="bg-menu"></div>
                                    </li>

                                    <li>
                                        <div class="bg-menu"></div>
                                    </li>
                                    <li class="mob-only">
                                        <div style="height: 20px; margin-top: -30px; margin-left: 20px" id="google_translate_element"></div>

                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- NAV MENU -->
                    <div id="navbar" class="navbar-collapse collapse col-md-7">
                        <ul class="menu nav navbar-nav pull-left nav-effect nav-menu">
                            <li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="{{ route('home') }}">Home</a></li>

                            <li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="{{ route('about') }}">About Us</a></li>
                            <li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="{{ route('contact') }}">Contact</a></li>
                         @auth()
                            <li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                            @else
                                <li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="{{ route('login') }}">Login</a></li>
                                <li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="{{ route('register') }}">Register</a></li>

                            @endauth
                        </ul>
                    </div>

                    <!-- RIGHT SIDE SOCIAL / ACTIONS BUTTONS -->
                    <div class="col-md-3 right-side-social-actions visibile_group">

                        <!-- ACTIONS BUTTONS GROUP -->
                        <div class="pull-right actions-group">
                            <!-- MT BURGER -->
                            <div id="mt-nav-burger">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>

                            <!-- SEARCH ICON -->

                            <a class="profile modeltheme-trigger  mt-login-icon" href="{{ route('login') }}" >
                                <i class="fa fa-user-plus" aria-hidden="true"></i>
                            </a>
                        </div>
                        <!-- SOCIAL LINKS -->
{{--                        <ul class="social-links">--}}
{{--                            <li><a href="https://telegram.org/"><i class="fa fa-telegram"></i></a></li>--}}
{{--                            <li><a href="https://facebook.com/"><i class="fa fa-facebook"></i></a></li>--}}
{{--                            <li><a href="https://twitter.com/envato"><i class="fa fa-twitter"></i></a></li>--}}
{{--                            <li><a href="https://plus.google.com/"><i class="fa fa-google-plus"></i></a></li>--}}
{{--                        </ul>--}}
                    </div>
                </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- HEADER TITLE BREADCRUBS SECTION -->

    <!-- Page content -->

    @yield('content')

    <div class="btn-sticky-left">
        <a href="{{ route('backend.dashboard') }}" target="_blank" title="Dashboard"><i class="icon-home icons"></i></a>
        <a href="{{ route('backend.trades.index') }}" title="Trades"><i class="icon-book-open icons"></i></a>
        <a href="{{ route('contact') }}" title="Contact Support"><i class="icon-support icons"></i></a>
        <!-- WP Variant -->
        <a href="{{ route('backend.account.overview') }}" title="Account Overview">
            <i class="fa fa-universal-access" aria-hidden="true"></i>
        </a>
    </div>


    <!-- BEGIN: FLOATING SOCIAL BUTTON -->
{{--    <a data-toggle="tooltip" data-placement="top" title="Connect on Telegram" class="floating-social-btn" target="_blank" href="https://telegram.org/">--}}
{{--        <i class="fa fa-telegram"></i>--}}
{{--    </a>--}}
    <!-- END: FLOATING SOCIAL BUTTON -->


    <!-- BACK TO TOP BUTTON -->
    <a class="back-to-top modeltheme-is-visible modeltheme-fade-out" href="#0">
        <i class="fa fa-long-arrow-up" aria-hidden="true"></i>
    </a>

    <!-- TICKERS DARK -->
{{--    <div class="tickers-black-sm crypto-ticker">--}}
{{--        <ul id="webticker-dark-icons">--}}
{{--            <li data-update="item1"><i class="cc BTC"></i> BTC <span class="coin-value"> $11.039232</span></li>--}}
{{--            <li data-update="item2"><i class="cc ETH"></i> ETH <span class="coin-value"> $1.2792</span></li>--}}
{{--            <li data-update="item3"><i class="cc GAME"></i> GAME <span class="coin-value"> $11.039232</span></li>--}}
{{--            <li data-update="item4"><i class="cc LBC"></i> LBC <span class="coin-value"> $0.588418</span></li>--}}
{{--            <li data-update="item5"><i class="cc NEO"></i> NEO <span class="coin-value"> $161.511</span></li>--}}
{{--            <li data-update="item6"><i class="cc STEEM"></i> STE <span class="coin-value"> $0.551955</span></li>--}}
{{--            <li data-update="item7"><i class="cc LTC"></i> LIT <span class="coin-value"> $177.80</span></li>--}}
{{--            <li data-update="item8"><i class="cc NOTE"></i> NOTE <span class="coin-value"> $13.399</span></li>--}}
{{--            <li data-update="item9"><i class="cc MINT"></i> MINT <span class="coin-value"> $0.880694</span></li>--}}
{{--            <li data-update="item10"><i class="cc IOTA"></i> IOT <span class="coin-value"> $2.555</span></li>--}}
{{--            <li data-update="item11"><i class="cc DASH"></i> DAS <span class="coin-value"> $769.22</span></li>--}}
{{--        </ul>--}}
{{--    </div>--}}

    <!-- FOOTER -->
    <footer>
        <!-- FOOTER TOP -->
        @section('hide')
            <div class="footer-top footer-ico-listing">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 footer-row-1">
                            <div class="row">
                                <div class="col-md-3 sidebar-1">
                                    <aside id="nav_menu-6" class="widget vc_column_vc_container widget_nav_menu">
                                        <h1 class="widget-title">RESOURCES</h1>
                                        <div class="menu-footer1-container">
                                            <ul id="menu-footer1" class="menu">
                                                <li id="menu-item-341" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-341"><a href="#">How to Buy Coin</a></li>
                                                <li id="menu-item-342" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-342"><a href="#">Coin Overview</a></li>
                                                <li id="menu-item-15589" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-15589"><a href="blog.html">Blog News</a></li>
                                                <li id="menu-item-344" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-344"><a href="#">How to Sell Coin</a></li>
                                                <li id="menu-item-4441" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-4441"><a href="#">Purchase Template</a></li>
                                            </ul>
                                        </div>
                                    </aside>
                                </div>
                                <div class="col-md-3 sidebar-2">
                                    <aside id="nav_menu-3" class="widget vc_column_vc_container widget_nav_menu">
                                        <h1 class="widget-title">NETWORK</h1>
                                        <div class="menu-footer2-container">
                                            <ul id="menu-footer2" class="menu">
                                                <li id="menu-item-345" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-345"><a href="#">Network Stats</a></li>
                                                <li id="menu-item-346" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-346"><a href="#">Block Explorers</a></li>
                                                <li id="menu-item-347" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-347"><a href="#">Governance</a></li>
                                                <li id="menu-item-348" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-348"><a href="#">Exchange  Markets</a></li>
                                                <li id="menu-item-4442" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-4442"><a href="#">Get Template</a></li>
                                            </ul>
                                        </div>
                                    </aside>
                                </div>
                                <div class="col-md-3 sidebar-3">
                                    <aside id="nav_menu-4" class="widget vc_column_vc_container widget_nav_menu">
                                        <h1 class="widget-title">SUPPORT</h1>
                                        <div class="menu-footer3-container">
                                            <ul id="menu-footer3" class="menu">
                                                <li id="menu-item-349" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-349"><a href="#">Contact Us</a></li>
                                                <li id="menu-item-350" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-350"><a href="#">Developer Center</a></li>
                                                <li id="menu-item-351" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-351"><a href="#">Helpes</a></li>
                                                <li id="menu-item-352" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-352"><a href="#">Terms &#038; Conditions</a></li>
                                                <li id="menu-item-4443" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-4443"><a href="#">Purchase Template</a></li>
                                            </ul>
                                        </div>
                                    </aside>
                                </div>
                                <div class="col-md-3 sidebar-4">
                                    <aside id="nav_menu-5" class="widget vc_column_vc_container widget_nav_menu">
                                        <h1 class="widget-title">ABOUT US</h1>
                                        <div class="menu-footer4-container">
                                            <ul id="menu-footer4" class="menu">
                                                <li id="menu-item-353" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-353"><a href="#">Our Coin</a></li>
                                                <li id="menu-item-354" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-354"><a href="#">Carrers</a></li>
                                                <li id="menu-item-355" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-355"><a href="#">Our Team</a></li>
                                                <li id="menu-item-356" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-356"><a href="#">Our Project</a></li>
                                                <li id="menu-item-4444" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-4444"><a href="#">Purchase</a></li>
                                            </ul>
                                        </div>
                                    </aside>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 footer-row-2">
                            <div class="row"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 footer-row-3">
                            <div class="row"></div>
                        </div>
                    </div>
                </div>
            </div>

    @stop

        <!-- FOOTER BOTTOM -->
        <div class="footer-div-parent">
            <div class="container-fluid footer">
                <div class="col-md-12">
                    <p class="copyright text-center">
                        <a href="{{ route('home') }}">Binary24Trades </a>2015 - {{ date('Y') }} All Rights Reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <div class="spacer_40"></div>
</div><!-- end HEADER -->
<!-- JS SCRIPTS -->
<script src='assets/js/jquery.js'></script>
<script src='assets/js/cryptic-plugins.js'></script>
<script src='assets/js/cryptic-custom.js'></script>
<script src='assets/js/donut-chart.js'></script>
<!-- WEBTICKER -->
<script src="assets/plugins/webticker/jquery.webticker.min.js"></script>
<!-- PLUGINS -->
<script src="assets/plugins/flipclock/flipclock.js"></script>
<script src='assets/plugins/select2/select2.min.js'></script>
<script src="assets/plugins/bootstrap/bootstrap.min.js"></script>
<script src='assets/plugins/magnific-popup/jquery.magnific-popup.js'></script>
<!-- CHARTS -->
<script src="../../cdnjs.cloudflare.com/ajax/libs/d3/4.2.2/d3.min.js"></script>

<script>
    jQuery(document).ready( function() {
        $("#modal").iziModal();


        jQuery(".mt_slider_members_team1").owlCarousel({
            navigation      : false, // Show next and prev buttons
            pagination      : false,
            autoPlay        : false,
            slideSpeed      : 700,
            paginationSpeed : 700,
            autoWidth: true,
            itemsCustom : [
                [0,     1],
                [450,   1],
                [600,   4],
                [700,   2],
                [1000,  2],
                [1200,  4],
                [1400,  4],
                [1600,  4]
            ]
        });

        jQuery(".mt_slider_members_team1 .owl-wrapper .owl-item:nth-child(2)").addClass("hover_class");
        jQuery(".mt_slider_members_team1 .owl-wrapper .owl-item").hover(
            function () {
                jQuery(".mt_slider_members_team1 .owl-wrapper .owl-item").removeClass("hover_class");
                jQuery(this).addClass("hover_class");
            }
        );

        jQuery(".mt_slider_members_team2").owlCarousel({
            navigation      : false, // Show next and prev buttons
            pagination      : false,
            autoPlay        : false,
            slideSpeed      : 700,
            paginationSpeed : 700,
            autoWidth: true,
            itemsCustom : [
                [0,     1],
                [450,   1],
                [600,   4],
                [700,   2],
                [1000,  2],
                [1200,  4],
                [1400,  4],
                [1600,  4]
            ]
        });

        jQuery(".mt_slider_members_team2 .owl-wrapper .owl-item:nth-child(2)").addClass("hover_class");
        jQuery(".mt_slider_members_team2 .owl-wrapper .owl-item").hover(
            function () {
                jQuery(".mt_slider_members_team2 .owl-wrapper .owl-item").removeClass("hover_class");
                jQuery(this).addClass("hover_class");
            }
        );
    });



</script>
<!--Start of Tawk.to Script-->

<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/60714d00f7ce18270938f291/1f2t99ivh';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
    })();
</script>
<!--End of Tawk.to Script-->
</body>

</html>
