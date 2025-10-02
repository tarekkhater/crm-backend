<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Twitter -->
    <meta name="twitter:site" content="@themepixels">
    <meta name="twitter:creator" content="@themepixels">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ setting('site_name') }}">
    <meta name="twitter:description" content="{{ setting('site_description') }}">
    <meta name="twitter:image" content="{{ asset('bracket/img/bracket-social.png') }}">

    <!-- Facebook -->
    <meta property="og:url" content="{{ setting('site_url') }}">
    <meta property="og:title" content="{{ setting('site_name') }}">
    <meta property="og:description" content="{{ setting('site_description') }}">

    <link rel="stylesheet" href="{{asset('/dist/vendors/toastr/toastr.min.css')}}"/>

    <!-- Meta -->
    <meta name="description" content="{{ setting('site_description') }}">
    <meta name="author" content="{{ setting('site_name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ setting('site_title') }}</title>
    <link href="{{ asset('/vendors/css/bootstrap.min.css') }}" rel="stylesheet"  crossorigin="anonymous">
    <link href="{{ asset('/vendors/css/element.css') }}" rel="stylesheet"  crossorigin="anonymous">


    <link href="{{ asset('font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/Ionicons/css/ionicons.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/jquery-switchbutton/jquery.switchButton.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/bootstrap-select.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/jquery.dataTables.min.css') }}">
{{--    <script src="{{ asset('assets/js/all.min.js') }}"></script>--}}

    <link href="{{ asset('lib/select2/css/select2.min.css')}}" rel="stylesheet">

    <!-- FAVICONS ICON -->

    <link rel="icon" type="image/png" sizes="16x16" href="{{ setting('favicon','/images/fav.png') }}">

    <link rel="shortcut icon" type="image/x-icon" href="{{ setting('favicon','/images/fav.png') }}" />

    @yield('style')
    <!-- Bracket CSS -->
    <link rel="stylesheet" href="{{ asset('css/bracket.css') }}">
    <style>
        .switch {
            position: relative;
            display: inline-block;
        }
        .switch-input {
            display: none;
        }
        .switch-label {
            display: block;
            width: 48px;
            height: 34px;
            text-indent: -150%;
            clip: rect(0 0 0 0);
            color: transparent;
            user-select: none;
        }
        .switch-label::before,
        .switch-label::after {
            content: "";
            display: block;
            position: absolute;
            cursor: pointer;
        }
        .switch-label::before {
            width: 100%;
            height: 100%;
            background-color: #dedede;
            border-radius: 9999em;
            -webkit-transition: background-color 0.25s ease;
            transition: background-color 0.25s ease;
        }

        .switch-label::after {
            top: 2px;
            left: 0;
            width: 32px;
            height: 30px;
            border-radius: 50%;
            background-color: #fff;
            box-shadow: 0 0 2px rgba(0, 0, 0, 0.45);
            -webkit-transition: left 0.25s ease;
            transition: left 0.25s ease;
        }
        .switch-input:checked + .switch-label::before {
            background-color: #89c12d;
        }
        .switch-input:checked + .switch-label::after {
            left: 24px;
        }
    </style>

    <style>
        [v-cloak] { display: none; }

        .alert-danger {
            color: #ffff!important;
            background-color: #F44336!important;
            border-color: #F44336!important;
        }

        .br-section-wrapper {
            padding: 15px!important;
        }
        /*.br-menu-item {*/
        /*    height: 30px;*/
        /*    letter-spacing: 0.1px;*/
        /*    !*font-size: .65rem;*!*/
        /*}*/
        /*.br-menu-item .svg-inline--fa{*/
        /*    width: 1.05em!important;*/
        /*}*/
    </style>
    @yield('headJS');
    @vite(['resources/js/app.js'])
</head>

<body>

@include('admin.layouts.admin-sidenav')

<!-- ########## START: MAIN PANEL ########## -->
{{-- <div class="br-mainpanel"> --}}
        <div class="app">
            @yield('content')
        </div>

    @include('sweetalert::alert')
    <footer class="br-footer py-2" style="position: fixed; bottom: 0; width: 100%;">
        <div class="footer-left">
            <div class="mg-b-2">Copyright &copy; {{ date('Y') }}. {{ setting('site_name') }}. All Rights Reserved.</div>
        </div>
    </footer>
{{-- </div><!-- br-mainpanel --> --}}
<!-- ########## END: MAIN PANEL ########## -->


    <script src="{{ asset('lib/jquery/jquery.js') }}"></script>
    <script src="{{ asset('lib/popper.js/popper.js') }}"></script>
    <script src="{{ asset('lib/bootstrap/bootstrap.js') }}"></script>
    <script src="{{ asset('lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js') }}"></script>
    <script src="{{ asset('lib/moment/moment.js') }}"></script>
    <script src="{{ asset('lib/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('lib/jquery-switchbutton/jquery.switchButton.js') }}"></script>
    <script src="{{ asset('lib/peity/jquery.peity.js') }}"></script>
    {{-- <script src="{{ asset('js/custom.js') }}"></script> --}}
    <script src="{{ asset('js/bracket.js') }}"></script>
    <script src="{{ asset('assets/dist/js/bootstrap-select.js') }}"></script>
<script src="{{ asset('/dist/vendors/toastr/toastr.min.js') }}"></script>

<script src="{{ asset('/vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
<script src="{{ asset('/vendors/js/element.js') }}"></script>

<script>
    $('.lfm').filemanager('image');
</script>

<script>
    $(document).ready(function() {
        $("#showActions").click(function() {
            $("#actions-details").toggle();
        });
        $('.selectpicker').selectpicker();
    })
</script>

    @yield('js')

@include('partials.alerts')
</body>
</html>
