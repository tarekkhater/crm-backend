<!doctype html>
<html lang="en">
<head>
    <title>{{ setting('site_name') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/auth/css/style.css">
    <link rel="stylesheet" href="/auth/css/extra.css">
    <style>
        /*.home-bg {*/
        /*    background: url(/app-assets/images/backgrounds/bg-2.jpg) center center no-repeat fixed;*/
        /*    -webkit-background-size: cover;*/
        /*    background-size: cover;*/
        /*}*/
        .text-wrap:after {
            background-color: #121833;
            opacity: .99;
        }
        .vertical-stepper h3 {
            color: #f8f9fa;
        }
    </style>
    @yield('css')
</head>
<body class="home-bg ">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ setting('site_info_url') }}">
                    <img src="{{ setting('logo','asset/images/logo.png') }}" alt="logo" width="100%" height="60" class=""/>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 " style="width: 100%">
            <section class="ftco-section">
                <div class="container ">
                    @yield('content')
                </div>
            </section>
        </main>

        <script src="/auth/js/jquery.min.js"></script>
        <script src="/auth/js/popper.js"></script>
        <script src="/auth/js/bootstrap.min.js"></script>
        <script src="/auth/js/main.js"></script>
    </body>
</html>
