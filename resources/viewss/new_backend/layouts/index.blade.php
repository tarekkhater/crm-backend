<!doctype html>
<html lang="en" dir="ltr"> <!-- This "app.blade.php" master page is used for all the pages content present in "views/livewire" except "custom" and "switcher" pages -->

<!-- Mirrored from laravel8.spruko.com/noa/index by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 02 Jan 2023 17:10:22 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
@include('new_backend.layouts.header_style')

<body class="ltr app sidebar-mini">


<!-- Switcher -->
@include('new_backend.layouts.switcher')
<!-- End Switcher -->


<!-- GLOBAL-LOADER -->
<div id="global-loader">
    <img src="{{asset('new_admin')}}/assets/images/loader.svg" class="loader-img" alt="Loader">
</div>
<!-- /GLOBAL-LOADER -->

<!-- PAGE -->
<div class="page">
    <div class="page-main">

        <!-- app-Header -->
        @include('new_backend.layouts.header')
        <!-- /app-Header -->

        <!--APP-SIDEBAR-->
        @include('new_backend.layouts.sidebar')
        <!--/APP-SIDEBAR-->

        <!--app-content open-->
        @yield('content')
        <!-- CONTAINER CLOSED -->
    </div>



    @include('new_backend.layouts.footer')

    <!-- FOOTER CLOSED -->

</div>
<!-- page -->

<!-- BACK-TO-TOP -->

@include('new_backend.layouts.footar_style')
@yield('js')
</body>


<!-- Mirrored from laravel8.spruko.com/noa/index by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 02 Jan 2023 17:11:54 GMT -->
</html>
