
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ setting('site_name') }}</title>
    <!-- Favicon icon -->

    <link rel="icon" type="image/png" sizes="16x16" href="{{ setting('favicon','/images/fav.png') }}">

    <!-- Custom Stylesheet -->
    {{--    <link rel="stylesheet" href="/back/vendor/nice-select/css/nice-select.css">--}}
    <link rel="stylesheet" href="/back/vendor/toastr/toastr.min.css">
    <link rel="stylesheet" href="/back/css/style.css">
    <link rel="stylesheet" href="/css/gen.css">


    @yield('styles')

{{--    <script src="//code.jivosite.com/widget/vhb4amL596" async></script>--}}
    <style>
        a {
            color: #ffff;
            font-weight: 800;
        }
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
            /*background: #f8f1f1*/
        }
        .payment-methods .card .card-body {
            border-radius: 0;
        }
        .payment-methods .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #363C4E;
            background: #99999973;
            border-radius: 0!important;
            padding: 15px 20px;
        }
        #dashboard {
            background-color: #3a3c4e;
        }
        @media only screen and (max-width: 767px) {
            .content-body {
                margin: 18px 6px 45px 5px;
            }
            .sidebar {
                top: auto;
                bottom: 0;
                width: 100%;
                height: 50px;
                padding: 0px;
                z-index:1;
            }
            .menu {
                margin-top: 1px;
            }
            .menu ul li a i {
                font-size: 30px;
            }
            .menu ul li {
                text-align: center;
            }
            .menu ul li a {
                padding: 2px 0;
                font-size: 0px;
            }
            .menu ul {
                display: flex;
                justify-content: space-around;
                align-items: center;
            }
        }

        .card {
            background-color: #3a3c4e;
        }
        .card .card-body {
            margin-bottom: 5px;
        }

        .card-header:first-child {
            border-radius: 10px;
            margin-bottom: 5px;
        }

    </style>
</head>

<body id="dashboard">

{{--<div id="preloader">--}}
{{--    <div class="sk-three-bounce">--}}
{{--        <div class="sk-child sk-bounce1"></div>--}}
{{--        <div class="sk-child sk-bounce2"></div>--}}
{{--        <div class="sk-child sk-bounce3"></div>--}}
{{--    </div>--}}
{{--</div>--}}

<div id="main-wrapper" class="show">
    <div class="header dashboard">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <nav class="navbar navbar-expand-lg navbar-light px-0 justify-content-between">
                        <a class="navbar-brand" href="{{ route('backend.dashboard') }}"><img style="height: 40px" src="/images/logo.png" alt=""></a>

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
                                        <a href="{{ route('backend.update_pass') }}" class="dropdown-item">
                                            <i class="mdi mdi-security"></i> Update Password
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

    <div class="sidebar">
        <div class="menu">
            <ul>
                <li>
                    <a href="{{ route('backend.dashboard') }}" data-toggle="tooltip" data-placement="right" title="dashboard">
                        <span><i class="mdi mdi-view-dashboard"></i></span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('backend.account.overview') }}" data-toggle="tooltip" data-placement="right" title="Overview">
                        <span><i class="mdi mdi-bullseye"></i></span>
                        <span class="nav-text">Overview</span>
                    </a>
                </li>
{{--                <li>--}}
{{--                    <a href="{{ route('backend.upgrade') }}" data-toggle="tooltip" data-placement="right" title="Upgrade">--}}
{{--                        <span><i class="mdi mdi-arrow-up"></i></span>--}}
{{--                    </a>--}}
{{--                </li>--}}

                <li>
                    <a href="{{ route('deposit.create') }}" data-toggle="tooltip" data-placement="right" title="Deposit">
                        <span><i class="mdi mdi-tumblr-reblog"></i></span>
                        <span class="nav-text">Deposit</span>
                    </a>
                </li>
{{--                <li>--}}
{{--                    <a href="{{ route('backend.profile.edit') }}" data-toggle="tooltip" data-placement="right" title="Edit Account">--}}
{{--                        <span><i class="mdi mdi-face-profile"></i></span>--}}
{{--                    </a>--}}
{{--                </li>--}}
                <li>
                    <a href="{{ route('backend.trades.index') }}" data-toggle="tooltip" data-placement="right" title="Trades">
                        <span><i class="mdi mdi-database"></i></span>
                        <span class="nav-text">Trades</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('backend.withdraw.index') }}" data-toggle="tooltip" data-placement="right" title="Withdrawals">
                        <span><i class="mdi mdi-pentagon"></i></span>
                        <span class="nav-text">Withdrawals</span>
                    </a>
                </li>
{{--                <li>--}}
{{--                    <a href="{{ route('backend.account.security') }}" data-toggle="tooltip" data-placement="right" title="Security Setting">--}}
{{--                        <span><i class="mdi mdi-settings"></i></span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li>--}}
{{--                    <a href="{{ route('backend.user.login.logins') }}" data-toggle="tooltip" data-placement="right" title="login login">--}}
{{--                        <span><i class="mdi mdi-diamond"></i></span>--}}
{{--                    </a>--}}
{{--                </li>--}}
                <li>
                    <a href="{{ route('backend.transactions') }}" data-toggle="tooltip" data-placement="right" title="Transaction History">
                        <span><i class="mdi mdi-history"></i></span>
                        <span class="nav-text">Transactions</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    {{--    @endsection--}}



    @yield('content')


    @section('hide')
        <div class="footer dashboard">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-8 col-12">
                        <div class="copyright">
                            <p>© Copyright 2020 <a href="https://themeforest.net/user/quixlab/portfolio">Quixlab</a> I
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


<!--Start of Tawk.to Script-->
{{--<script type="text/javascript">--}}
{{--    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();--}}
{{--    (function(){--}}
{{--        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];--}}
{{--        s1.async=true;--}}
{{--        s1.src='https://embed.tawk.to/6065c68c067c2605c0be6bed/1f26ov6ja';--}}
{{--        s1.charset='UTF-8';--}}
{{--        s1.setAttribute('crossorigin','*');--}}
{{--        s0.parentNode.insertBefore(s1,s0);--}}
{{--    })();--}}
{{--</script>--}}
<!--End of Tawk.to Script-->

</body>
</html>
