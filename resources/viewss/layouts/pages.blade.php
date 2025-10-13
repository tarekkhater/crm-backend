
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Crypto Assets" />
    <!-- SITE TITLE -->
    <title>Crypto Assets </title>
    <!-- CSRF Token -->

    <!-- favicon -->
    <!-- Animation CSS -->
    <link rel="stylesheet" href="/css/animate.css" >
    <!-- Latest Bootstrap min CSS -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="/font-awesome/css/font-awesome.css" >
    <!-- ionicons CSS -->
    <link rel="stylesheet" href="/css/ionicons.min.css">
    <!-- cryptocoins CSS -->
    <link rel="stylesheet" href="/css/cryptocoins.css">
    <!--- owl carousel CSS-->
    <link rel="stylesheet" href="/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/css/owl.theme.default.min.css">
    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="/css/magnific-popup.css">
{{--    <link rel="stylesheet" href="https://binaryoptionsfx.online/asset/assets/css/spop.min.css">--}}
    <!-- Style CSS -->
    <link rel="stylesheet" href="/css/style1.css">
    <link rel="stylesheet" href="/css/responsive.css">
    <!-- Color CSS -->
    <link id="layoutstyle" rel="stylesheet" href="/css/theme.css">

    <link rel="stylesheet" href="/css/rev/settings.css">
    <link rel="stylesheet" href="/css/rev/layers.css">
    <link rel="stylesheet" href="/css/rev/navigation.css">

    <link rel="icon" type="image/png" sizes="16x16" href="{{ setting('favicon','/images/fav.png') }}">

    <style>
        .faq_question h5 {
            margin-top: 20px;
        }
        .bg-fix {
            background: url(/images/new-site/banner1.jpg) no-repeat fixed 50% 0;
            background-size: cover;
            position: relative;
            transition: all 2s linear;
        }

        .card-body {
            text-align: justify;
        }

    </style>

{{--    <script src="//code.jivosite.com/widget/vhb4amL596" async></script>--}}

</head>

<body class="v_dark" data-spy="scroll" data-offset="110">


<header class="header_wrap fixed-top">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand page-scroll animation" href="#home_section" data-animation="fadeInDown" data-animation-delay="1s">
                <a class="navbar-brand" href="/">
                    <img style="max-height: 70px; max-width: 80px;" src="/images/fav.png" id="logo" width="70%" alt="logo">
                </a>
            </a>
            <button class="navbar-toggler animation" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" data-animation="fadeInDown" data-animation-delay="1.1s">
                <span class="ion-android-menu"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav m-auto">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li><a href="{{ route('fags') }}">FAQ</a></li>

                    <li class="">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Trading
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('withdrawal') }}">Withdrawal Info</a>
{{--                            <a class="dropdown-item" href="{{ route('deposits') }}">Deposit </a>--}}
                            <a class="dropdown-item" href="{{ route('verification') }}">Verifications</a>
                        </div>
                    </li>
                    <li><a href="{{ route('contact') }}">Support</a></li>
                    @guest()
                    <li><a href="{{ route('register') }}">Register</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                    @else
                        <li><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                    @endguest

                </ul>
            </div>
        </nav>
    </div>
</header>
<!-- END HEADER -->
<div class="bg-fix">
    @yield('content')
</div>

<!-- START FOOTER SECTION -->

<footer>
    <div class="top_footer bg_light_dark" data-z-index="1" data-parallax="scroll" >
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12">
                    <div class="footer_logo mb-3 animation" data-animation="fadeInUp" data-animation-delay="0.2s">
                        <a href="#home_section" class="page-scroll">
                            <img height="70px" alt="footer-logo" src="/images/fav.png" width="70px;">
                        </a>
                    </div>
                    <div class="footer_desc">
                        <p class="animation" data-animation="fadeInUp" data-animation-delay="0.4s">
                            Crypto Assets is one of the leading investment platforms incorporated in Mauritius offering an array of regulated investment programs from real trading processes.
                        </p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-5 res_md_mt_30 res_sm_mt_20">
                    <h4 class="footer_title border_title animation" data-animation="fadeInUp" data-animation-delay="0.2s">Links</h4>
                    <ul class="footer_link list_arrow">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('about') }}">About</a></li>
                        <li><a href="{{ route('contact') }}">Support</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                        <li><a href="{{ route('login') }}">Login</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-5 res_md_mt_30 res_sm_mt_20">
                    <h4 class="footer_title border_title animation" data-animation="fadeInUp" data-animation-delay="0.2s">Resources</h4>
                    <ul class="footer_link list_arrow">
                        <li><a href="{{ route('fags') }}">Frequently Asked Questions</a></li>
                        <li><a href="{{ route('options') }}">Types Of Binary Option Trade</a></li>
                        <li><a href="{{ route('trades') }}">Choosing Which Trades To Place</a></li>
                        <li><a href="{{ route('calender') }}">Utilising Economic Calenders</a></li>
                        <li><a href="{{ route('bonus') }}">Trader Bonuses And Promotion</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-4 res_md_mt_30 res_sm_mt_20">
                    <h4 class="footer_title border_title animation" data-animation="fadeInUp" data-animation-delay="0.2s">Contact Us</h4>
                    <ul class="footer_social list_none">
{{--                        <li class="animation" data-animation="fadeInUp" data-animation-delay="0.3s"><a href="#">Phone: .</a></li>--}}
                        <li class="animation" data-animation="fadeInUp" data-animation-delay="0.4s"><a href="#">Email: info@cryptoassest.com/</a></li>
                        <li class="animation" data-animation="fadeInUp" data-animation-delay="0.2s"><a href="#">Address: Crypto Assets is a product of Investment  Solutions Limited incorporated in Mauritius</a></li>
                    </ul>
                </div>

            </div>
            @include('partials.foot')
        </div>
    </div>
    <div class="bottom_footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
{{--                    <p class="_fot_seg" style="font-size:small">Company Information: This website (Binary Option Solutions) is operated by INVESTMENT ANALYTICS SOLUTIONS LTD, a London investment firm, authorised and regulated by the International Financial Services Commission of London with company number C092215. INVESTMENT ANALYTICS SOLUTIONS LTD is located at Binary Solution is a product of Investment  Solutions Limited incorporated in Mauritius.</p>--}}

                    <!--<p class="_fot_seg">According to the payment agent agreement between INVESTMENT ANALYTICS SOLUTIONS LTD and Wonderbridge Services Limited, Wonderbridge Services Limited is acting as a payment agent providing payment services to INVESTMENT ANALYTICS SOLUTIONS LTD.</p>-->



    <p class="_fot_seg _bacx1" style="font-size:small">Risk Warning. Trading on financial markets carries risks. Contracts for Difference (‘CFDs’) are complex financial products that are traded on margin.
        Trading CFDs carries a high level of risk since leverage can work both to your advantage and disadvantage. As a result, CFDs may not be suitable for all investors because you may lose all your
        invested capital. you shouldn't take any chances to invest unless you have a professional trader who can oversee the trade and help manage the trading risk before you decide to invest. you need to
        make sure that you understand the risks associated with trading if you have very low level trading experience we advise that you shouldn't take any chances to invest individually </p>

    <p>Crypto Assets has the best trading experts we can assign to you. our trade experts will help you manage the risk of losing
        and handle all your trades.</p>


                </div>

                <div class="col-md-6">
                    <p class="copyright">Copyright © All Rights Reserved.</p>
                </div>
                <div class="col-md-6">
                    <ul class="list_none footer_menu">
                        <li><a href="/privacy">Privacy Policy</a></li>
                        <li><a href="/terms">Terms & Conditions</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- END FOOTER SECTION -->

<a href="#" class="scrollup btn-default"><i class="ion-ios-arrow-up"></i></a>

<!-- Latest jQuery -->
<script src="/js/jquery.min.js"></script>
<!-- Latest compiled and minified Bootstrap -->
<script src="/js/bootstrap.min.js"></script>
<!-- owl-carousel min js  -->
<script src="/js/owl.carousel.min.js"></script>
<!-- magnific-popup min js  -->
<script src="/js/magnific-popup.min.js"></script>
<!-- waypoints min js  -->
<script src="/js/waypoints.min.js"></script>
<!-- parallax js  -->
<script src="/js/parallax.js"></script>
<!-- countdown js  -->
<script src="/js/jquery.countdown.min.js"></script>
<!-- particles min js  -->
<script src="/js/particles.min.js"></script>
<!-- scripts js -->
<script src="/js/jquery.dd.min.js"></script>
<!-- jquery.counterup.min js -->
<script src="/js/jquery.counterup.min.js"></script>
<!-- modern_canvas js -->
<script src="/js/modern_canvas.js"></script>
<!-- <script src="https://binaryoptionsfx.online/asset/assets/js/spop.min.js"></script>  -->
<script src="/js/notification.js"></script>
<!-- scripts js -->
<script src="/js/scripts1.js"></script>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/6065c68c067c2605c0be6bed/1f26ov6ja';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
    })();
</script>
<!--End of Tawk.to Script-->


</body>
</html>
