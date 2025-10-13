<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ setting('site_name') }}</title>
    <!-- Favicon icon -->
{{--    <link rel="icon" type="image/png" sizes="16x16" href="/back/images/favicon.png">--}}

    <link rel="icon" type="image/png" sizes="16x16" href="{{ setting('favicon','/images/fav.png') }}">
    

    <!-- Custom Stylesheet -->
    {{--    <link rel="stylesheet" href="/back/vendor/nice-select/css/nice-select.css">--}}
    <link rel="stylesheet" href="/back/vendor/toastr/toastr.min.css">
    <link rel="stylesheet" href="/back/css/style.css">
    @yield('styles')

    <style>
        .header .navbar-brand img {
            max-width: 185px;
        }
        .alert-danger {
            color: #ffff!important;
            background-color: #F44336!important;
            border-color: #F44336!important;
        }
        .payment-methods .card {
            border: 0px solid #363C4E;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 0 13px 0 rgba(82, 63, 105, 0.05);
            background: #673AB7
        }
        .payment-methods .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #363C4E;
            background: #673AB7;
            padding: 15px 20px;
        }
        #dashboard {
            background-image: url(/assets/images/cryptic-slider2.jpg);
            /*background-image: url(/assets/images/cryptic-decentralized-bg11-3.jpg);*/
        }
    </style>
{{--    <script src="//code.jivosite.com/widget/vhb4amL596" async></script>--}}
</head>


<body id="dashboard">

<div id="main-wrapper" class="show">

    <div class="header dashboard">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <nav class="navbar navbar-expand-lg navbar-light px-0 justify-content-between">
                        <a class="navbar-brand" href="{{ route('backend.dashboard') }}"><img style="height: 50px" src="{{ setting('logo','asset/images/logosm.png') }}" alt=""></a>

                        <div class="header-right d-flex my-2 align-items-center">
                            <div class="language">
                                <div class="dropdown">
                                    <div class="icon">

                                        <span>{{ \Illuminate\Support\Str::limit(auth()->user()->name, 10,'..') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="dashboard_log">
                                <div class="profile_log dropdown">
                                    <div class="user" data-toggle="dropdown">
                                        <span class="thumb"><i class="mdi mdi-account"></i></span>
                                        <span class="arrow"><i class="la la-angle-down"></i></span>
                                    </div>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <div class="user-email">
                                            <div class="user">
                                                <span class="thumb"><i class="mdi mdi-account"></i></span>
                                                <div class="user-info">
                                                    <h6>{{ auth()->user()->name }}</h6>
                                                    <span>{{ auth()->user()->name }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="user-balance">
                                            <div class="available">
                                                <p>Balance</p>
                                                <span>{{ auth()->user()->total() }}</span>
                                            </div>
                                            <div class="total">
                                                <p>Bonus</p>
                                                <span>${{ auth()->user()->bonus }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('backend.account.overview') }}" class="dropdown-item">
                                            <i class="mdi mdi-account"></i> Account
                                        </a>
                                        <a href="{{ route('backend.trades.index') }}" class="dropdown-item">
                                            <i class="mdi mdi-history"></i> Trades
                                        </a>
                                        <a href="{{ route('backend.profile.edit') }}" class="dropdown-item">
                                            <i class="mdi mdi-settings"></i> Setting
                                        </a>
                                        <a  onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" href="{{ route('logout') }}" class="dropdown-item logout">
                                            <i class="mdi mdi-logout"></i> Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{--    @section('hide')--}}


    @yield('content')


    @section('hide')
        <div class="footer dashboard">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-8 col-12">
                        <div class="copyright">
                            <p>© Copyright {{ date('Y') }} <a href="https://themeforest.net/user/quixlab/portfolio">Crypto Assets</a>
                                All Rights Reserved</p>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="footer-social">
                            <ul>
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="#"><i class="fa fa-youtube"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
</div>



<script src="/back/js/global.js"></script>

<script src="/back/vendor/toastr/toastr.min.js"></script>
{{--<script src="/back/vendor/toastr/toastr-init.js"></script>--}}

<script>

    @if (session('failure'))
    toastr.success("{{ session('failure') }}", "Warning", {
        // timeOut: 500000,
        closeButton: !0,
        debug: !1,
        newestOnTop: !0,
        progressBar: !0,
        positionClass: "toast-top-right demo_rtl_class",
        preventDuplicates: !0,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
        tapToDismiss: !1,
        closeHtml: '<div class="circle_progress"></div><span class="progress_count"></span> <i class="la la-close"></i> <a href="#">Continue</a>'
    });
    @endif

    @if (session('success'))
    toastr.success("{{ session('success') }}", "Success", {
        // timeOut: 500000,
        closeButton: !0,
        debug: !1,
        newestOnTop: !0,
        progressBar: !0,
        positionClass: "toast-top-right demo_rtl_class",
        preventDuplicates: !0,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
        tapToDismiss: !1,
        closeHtml: '<div class="circle_progress"></div><span class="progress_count"></span> <i class="la la-close"></i> <a href="#">Continue</a>'
    });
    @endif
</script>

<script src="/back/vendor/circle-progress/circle-progress.min.js"></script>
<script src="/back/vendor/circle-progress/circle-progress-init.js"></script>


<!--  flot-chart js -->
<script src="/back/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>


<!-- <script src="./js/dashboard.js"></script> -->

<script src="/back/js/scripts.js"></script>


<script src="/back/js/settings.js"></script>

<script src="/back/js/quixnav-init.js"></script>



{{--<script src="/back/js/styleSwitcher.js"></script>--}}



<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

@yield('js')



</body>
</html>
