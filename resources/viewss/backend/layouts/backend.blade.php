<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> {{ setting('site_name') }} </title>
    <link rel="shortcut icon" href="{{ setting('favicon', '/asset/images/logosm.png') }}" />
    @if (setting('disable_mobile_view', 1) == 0)
        <meta name="viewport" content="width=device-width,initial-scale=1">
    @endif

{{--    <link href="/dist/vendors/bootstrap-tour/css/bootstrap-tour-standalone.min.css" rel="stylesheet">--}}

    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- START: Template CSS-->

    <link rel="stylesheet" href="{{ asset('dist/vendors/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/vendors/jquery-ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/vendors/jquery-ui/jquery-ui.theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/vendors/simple-line-icons/css/simple-line-icons.css') }}">
{{--    <link rel="stylesheet" href="/dist/vendors/flags-icon/css/flag-icon.min.css">--}}
{{--    <link rel="stylesheet" href="/dist/vendors/flag-select/css/flags.css">--}}
    <!-- END Template CSS-->

    <!-- START: Page CSS-->

{{--    <link rel="stylesheet" href="/dist/vendors/starrr/starrr.css">--}}
    <link rel="stylesheet" href="{{ asset('dist/vendors/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/vendors/ionicons/css/ionicons.min.css') }}">
    <!-- END: Page CSS-->


    <!-- START: Custom CSS-->
    <link rel="stylesheet" href="{{ asset('dist/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/dashboard-small.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css">
    <!-- END: Custom CSS-->


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/css/iziModal.min.css" integrity="sha512-8vr9VoQNQkkCCHGX4BSjg63nI5CI4B+nZ8SF2xy4FMOIyH/2MT0r55V276ypsBFAgmLIGXKtRhbbJueVyYZXjA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{ asset('dist/vendors/toastr/toastr.min.css') }}"/>

    <link rel="stylesheet" href="{{ asset('dist/vendors/sweetalert/sweetalert.css') }}">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/wave.css') }}">
    <link href="https://unpkg.com/bootstrap-table@1.21.0/dist/bootstrap-table.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
    <style>

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            margin-left: 4px;
        }

        .washlist {
            position: relative;
        }

        .sidebar-card {
            /* height : 90vh; */
            border: 1px solid #2a2b30;
            margin-bottom: 55px;
        }



        .washlist .first_half {
            height: 40vh;
            /*background: #2a2b30;*/
        }

        .right-card{
            background: #2a2b30!important;
        }
        .bg-card {
            background: #2a2b30!important;
        }
        .bg-sec {
            background: #2a2b30!important;
        }
        .washlist .last_half {
            padding-top: 30%;
            height: 42vh;
            position: relative;
            bottom: 0;
        }



        .custom_disabled {
            /*pointer-events: none;*/
            opacity: 0.5;
            /*background: #CCC;*/
        }

        .switch  input[type=checkbox]{
            height: 0;
            width: 0;
            visibility: hidden;
        }

        .switch label {
            cursor: pointer;
            text-indent: -9999px;
            width: 100px;
            height: 40px;
            background: #fff;
            display: block;
            border-radius: 40px;
            position: relative;
            border: 1px solid ;
        }

        [v-cloak] { display: none; }
        .switch  label:after {
            content: '';
            position: absolute;
            top: 1.5px;
            left: 1px;
            width: 21px;
            height: 21px;
            background: #FF0000;
            border-radius: 40px;
            transition: 0.3s;
        }



        .switch  input:checked + label {
            background: #fff;
        }

        .contact p {
            font-size: 16px;
        }

        .contact b {
            font-size: 1.6em;
        }

        .switch  input:checked + label:after {
            left: calc(100% - 1px);
            transform: translateX(-100%);
            background: #00d589;
        }

        .switch label:active:after {
            width: 30px;
        }

        /*body {*/
        /*    display: flex;*/
        /*    justify-content: center;*/
        /*    align-items: center;*/
        /*    height: 100vh;*/
        /*}*/


        #form-wrapper {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .vrange {
            width: 90%;
            max-width: 500px;
            /*height: 60px;*/
            margin-top: 10px;
        }

        .vrange #debt-amount-slider {
            display: flex;
            flex-direction: row;
            align-content: stretch;
            position: relative;
            width: 100%;
            height: 50px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .vrange #debt-amount-slider::before {
            content: " ";
            position: absolute;
            height: 2px;
            width: 100%;
            width: calc(100% * (4 / 5));
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #009688;
        }
        .vrange #debt-amount-slider input,
        .vrange #debt-amount-slider label {
            box-sizing: border-box;
            flex: 1;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            cursor: pointer;
        }
        .vrange #debt-amount-slider label {
            display: inline-block;
            position: relative;
            width: 20%;
            height: 100%;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            transform: scale(0.7);
        }
        .vrange #debt-amount-slider label::before {
            content: attr(data-debt-amount);
            position: absolute;
            /*left: 50%;*/
            /*padding-top: 10px;*/
            /*transform: translate(-50%, 45px);*/
            font-size: 14px;
            letter-spacing: 0.4px;
            font-weight: 400;
            white-space: nowrap;
            opacity: 0.85;
            padding: 0 5px;
            background: gray;
            border-radius: 2px;
            /*transform: rotate(-deg);*/
            background: linear-gradient(to right bottom, white 0%,white 50%, #ffffff 50%, #ffffff 50%,#ffffff 100%);

            transition: all 0.15s ease-in-out;
            display: none;
            margin-top: -20px;
        }
        .vrange #debt-amount-slider label::after {
            content: " ";
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 30px;
            height: 30px;
            border: 2px solid #000;
            background: gray;
            border-radius: 50%;
            pointer-events: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            z-index: 1;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
        }
        .vrange #debt-amount-slider label:hover::after {
            transform: translate(-50%, -50%) scale(1.45);
            background: white;
        }
        .vrange #debt-amount-slider input {
            display: none;
        }
        .vrange #debt-amount-slider input:checked + label::before {
            font-weight: 800;
            opacity: 1;
            display: block;
        }
        .vrange #debt-amount-slider input:checked + label::after {
            border-width: 4px;
            transform: translate(-50%, -50%) scale(1.9);
            background: white;
        }
        .vrange #debt-amount-slider input:checked ~ #debt-amount-pos {
            opacity: 1;
        }
        .vrange #debt-amount-slider input:checked:nth-child(1) ~ #debt-amount-pos {
            left: 10%;
        }
        .vrange #debt-amount-slider input:checked:nth-child(3) ~ #debt-amount-pos {
            left: 30%;
        }
        .vrange #debt-amount-slider input:checked:nth-child(5) ~ #debt-amount-pos {
            left: 50%;
        }
        .vrange #debt-amount-slider input:checked:nth-child(7) ~ #debt-amount-pos {
            left: 70%;
        }
        .vrange #debt-amount-slider input:checked:nth-child(9) ~ #debt-amount-pos {
            left: 90%;
        }
        .vrange #debt-amount-slider #debt-amount-pos {
            display: block;
            position: absolute;
            top: 50%;
            width: 12px;
            height: 12px;
            background: #000;
            border-radius: 50%;
            transition: all 0.15s ease-in-out;
            transform: translate(-50%, -50%);
            border: 2px solid #fff;
            opacity: 0;
            z-index: 2;
        }
        form:valid #debt-amount-slider input + label::before {
            transform: translate(-50%, 45px) scale(0.9);
            transition: all 0.15s linear;
        }
        form:valid #debt-amount-slider input:checked + label::before {
            transform: translate(-50%, 45px) scale(1.1);
            transition: all 0.15s linear;
        }
        .vrange + button {
            display: block;
            position: relative;
            margin: 56px auto 0;
            padding: 10px 20px;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            transition: all 0.15s ease-in-out;
            font-family: inherit;
            font-size: 24px;
            font-weight: 600;
            background: #fff;
            border: 2px solid #000;
            border-radius: 8px;
            outline: 0;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            cursor: pointer;
        }
        .vrange + button:hover {
            background: #000;
            color: #fff;
        }
        .vrange + button:hover:active {
            transform: scale(0.9);
        }
        .vrange + button:focus {
            background: #4caf50;
            border-color: #4caf50;
            color: #fff;
            pointer-events: none;
        }
        .vrange + button:focus::before {
            -webkit-animation: spin 1s linear infinite;
            animation: spin 1s linear infinite;
        }
        .vrange + button::before {
            display: inline-block;
            width: 0;
            opacity: 0;
            content: "";
            font-family: "Font Awesome 5 Pro";
            font-weight: 900;
            margin-right: 0;
            transform: rotate(0deg);
        }
        form:invalid + button {
            pointer-events: none;
            opacity: 0.25;
        }

        @-webkit-keyframes spin {
            from {
                transform: rotate(0deg);
                width: 24px;
                opacity: 1;
                margin-right: 12px;
            }
            to {
                transform: rotate(360deg);
                width: 24px;
                opacity: 1;
                margin-right: 12px;
            }
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
                width: 24px;
                opacity: 1;
                margin-right: 12px;
            }
            to {
                transform: rotate(360deg);
                width: 24px;
                opacity: 1;
                margin-right: 12px;
            }
        }
        .crypto-row .asset-item {
            /* border: 1px solid black; */
            padding: 5px;
            margin: 2px;
        }

        .btn-warning {
            background-color: #ff7700!important;
            border-color: #ff7700!important;
            color: #fff !important;
        }

        .bal {
            line-height: 1;
        }

        .sidebar {
            height : 100%!important;
        }

        #header-fix nav {
            border-bottom: 1px solid #50535e;
        }

        .bal .bonus {
            font-size: 0.6em;
            float: right;
            color: var(--bodycolor);
        }
        .text-default {
            color: var(--bodycolor)!important;
        }
        .bal .bonus_fig{
            color: #2cac40!important;
            font-size: 14px;
            font-weight: 600;
        }
    </style>

    <style>
        .btn-primary:not(:disabled):not(.disabled):active {
            background-color: #ff7700 !important;
            border-color: #ff7700!important;
        }
        .btn-primary {
            background-color: #ff7700!important;
            border-color: #ff7700!important;
        }

        .btn-primary:hover {
            color: #fff;
            background-color: #ff7700 !important;
            border-color: #ff7700!important;
        }

       .wizard-tab .nav-item {
           width: 100%!important;
       }
        .wizard-tab .nav-link {
            width: 100%!important;
        }
       .deposit .col-3 {
           background-color: #3a393b;
       }
    </style>


    @yield('styles')
</head>
<!-- END Head-->

<!-- START: Body-->
<body id="main-container" class="default dark-alt hide-sidebar" style="background: #131722">

<div id="app_secret">

    <!-- START: Pre Loader-->
{{--<div class="se-pre-con">--}}
{{--    <img src="/dist/images/logo.png" alt="logo" width="23" class="img-fluid"/>--}}
{{--</div>--}}
<!-- END: Pre Loader-->

<!-- START: Header-->
@include('partials.backend.header')
<!-- END: Header-->


<!-- START: Main Menu-->
@include('partials.backend.sidebar')
<!-- END: Main Menu-->

<!-- START: Main Content-->
    <main>

    @yield('content')

    {{--        //deposit modal, add account modal--}}
    @include('backend.inc.modals')
</main>

</div>
<!-- END: Content-->


{{--<div id="app">--}}




<!-- START: Footer-->

{{--<footer class="site-footer">© 2017 - {{ date('Y') }} {{ env('APP_NAME') }}.</footer>--}}

<!-- END: Footer-->

<!-- START: Back to top-->
<a href="#" class="scrollup text-center">
    <i class="icon-arrow-up"></i>
</a>
<!-- END: Back to top-->

<!-- Modal -->
<div class="modal fade" id="update_bassword_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="id_info">
                        <form method="POST" action="{{ route('backend.update_pass') }}">
                            @csrf

                            @foreach ($errors->all() as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach




                                <div class="form-group">
                                    <input id="password" type="password" required
                                           class="form-control" name="current_password" placeholder="Current Password" autocomplete="current-password">
                                </div>




                                <div class="form-group">
                                    <input id="new_password" type="password"  required class="form-control"
                                           placeholder="New Password"
                                           name="new_password" autocomplete="current-password">
                                </div>



                                <div class=" form-group">
                                    <input id="new_confirm_password" required type="password"
                                           placeholder="New Confirm Password"
                                           class="form-control" name="new_confirm_password" autocomplete="current-password">
                                </div>


                            <div class="form-group row mb-0">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary" style="width: 100%">
                                        Change Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>





<!-- START: Template JS-->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="{{ asset('dist/vendors/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('dist/vendors/moment/moment.js') }}"></script>
<script src="{{ asset('dist/vendors/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/vendors/slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('dist/vendors/flag-select/js/jquery.flagstrap.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<!-- END: Template JS-->

<!-- START: APP JS-->
<script src="{{ asset('dist/js/app.js') }}"></script>
<!-- END: APP JS-->

<!-- START: Page Vendor JS-->
<script src="{{ asset('dist/vendors/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('dist/vendors/morris/morris.min.js') }}"></script>
<script src="{{ asset('dist/vendors/chartjs/Chart.min.js') }}"></script>
<script src="{{ asset('dist/vendors/starrr/starrr.js') }}"></script>
<script src="{{ asset('dist/vendors/jquery-flot/jquery.canvaswrapper.js') }}"></script>
<script src="{{ asset('dist/vendors/jquery-flot/jquery.colorhelpers.js') }}"></script>
<script src="{{ asset('dist/vendors/jquery-flot/jquery.flot.js') }}"></script>
<script src="{{ asset('dist/vendors/jquery-flot/jquery.flot.saturated.js') }}"></script>
<script src="{{ asset('dist/vendors/jquery-flot/jquery.flot.browser.js') }}"></script>
<script src="{{ asset('dist/vendors/jquery-flot/jquery.flot.drawSeries.js') }}"></script>
<script src="{{ asset('dist/vendors/jquery-flot/jquery.flot.uiConstants.js') }}"></script>
<script src="{{ asset('dist/vendors/jquery-flot/jquery.flot.legend.js') }}"></script>
<script src="{{ asset('dist/vendors/jquery-flot/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('dist/vendors/bootstrap-tour/js/bootstrap-tour-standalone.min.js') }}"></script>
<link href='https://fonts.googleapis.com/css?family=Cairo' rel='stylesheet'>

<!-- END: Page Vendor JS-->
<script src="https://cdn.datatables.net/plug-ins/1.10.16/sorting/numeric-comma.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/js/iziModal.min.js" integrity="sha512-8aOKv+WECF2OZvOoJWZQMx7+VYNxqokDKTGJqkEYlqpsSuKXoocijXQNip3oT4OEkFfafyluI6Bm6oWZjFXR0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{ asset('dist/js/home.script.js') }}"></script>

<script src="{{ asset('dist/vendors/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('dist/vendors/toastr/toastr.min.js') }}"></script>
<!--Start of Tawk.to Script-->

{!! setting('twak_io') !!}



<!--End of Tawk.to Script-->


<script src="{{ mix('js/app.js') }}"></script>

<!-- END: Page JS-->
<!-- START: Page JS-->

@include('partials.alerts')

@if (session('no_deposit'))
    <script>
        $(window).on('load', function() {
            $('#makeDeposit').modal('show');
        });
    </script>
@endif

@if (setting('autotrader'))
@if (Auth()->user()->trader_request == 'pending')
    <script>
        $(window).on('load', function() {
            $('.connect').modal('show');
        });
    </script>
@endif
@endif
<script>
     function count_tr(){
        var count=  $('#order-table table tbody tr').length
         alert(count)
       if(count>1){
        alert(count)
       }
     }
    $(document).ready( function () {


      $('#order-table table tbody tr').each(function(){
      var count = $(this).find("td").length
       if(count==0){
        $(this).remove()
       }
      })

// $('#order-table table').DataTable({
//         search: {
//             return: true,
//         },
//     columnDefs: [
//         {
//             "orderSequence": ["desc", "asc"],
//             type: 'numeric-comma', targets: 0
//         }
//     ],
//     });
    })


    $(window).on('load', function() {
        $("#openDeposit").button().click(function(){
            $('#makeDeposit').modal('hide');
            $('.bd-example-modal-lg').modal('show');
        });
    });
</script>

@yield('js')

{{--    <script type="text/javascript">--}}
{{--        function googleTranslateElementInit() {--}}
{{--            new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');--}}
{{--        }--}}
{{--    </script>--}}

{{--    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>--}}

</body>
<!-- END: Body-->

</html>
