<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Binary 24 Option</title>
    <!-- Favicon icon -->


    <link rel="icon" type="image/png" sizes="16x16" href="/images/fav.png">


    <!-- Custom Stylesheet -->

    <link rel="stylesheet" href="/back/vendor/toastr/toastr.min.css">
    <link rel="stylesheet" href="/back/css/style.css">
    <style>
        .progress {
            height: 40px!important;
        }
        #myProgress {
            width: 100%;
            background-color: #ddd;
        }

        #myBar {
            width: 10%;
            /*height: 30px;*/
            background-color: #4CAF50;
            text-align: center;
            line-height: 30px;
            color: white;
        }

        .meter {
            height: 5px;
            position: relative;
            background: #f3efe6;
            overflow: hidden;
        }

        .meter span {
            display: block;
            height: 100%;
        }

        .progress {
            background-color: #e4c465;
            animation: progressBar 3s ease-in-out;
            animation-fill-mode:both;
        }

        @keyframes  progressBar {
            0% { width: 0; }
            100% { width: 100%; }
        }
    </style>

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

</head>


<body id="dashboard">

<div id="preloader">
    <div class="sk-three-bounce">
        <div class="sk-child sk-bounce1"></div>
        <div class="sk-child sk-bounce2"></div>
        <div class="sk-child sk-bounce3"></div>
    </div>
</div>

<div id="main-wrapper">

    <div class="header dashboard">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <nav class="navbar navbar-expand-lg navbar-light px-0 justify-content-between">
                        <a class="navbar-brand" href="https://binary24trades.com/public/dashboard"><img style="height: 50px" src="/images/logo.png" alt=""></a>

                        <div class="header-right d-flex my-2 align-items-center">
                            <div class="language">
                                <div class="dropdown">
                                    <div class="icon">

                                        <span>admin</span>
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
                                                    <h6>admin</h6>
                                                    <span>admin</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="user-balance">
                                            <div class="available">
                                                <p>Balance</p>
                                                <span>165900 USD</span>
                                            </div>
                                            <div class="total">
                                                <p>Bonus</p>
                                                <span>$0.00</span>
                                            </div>
                                        </div>
                                        <a href="https://binary24trades.com/public/dashboard/account/overview" class="dropdown-item">
                                            <i class="mdi mdi-account"></i> Account
                                        </a>
                                        <a href="https://binary24trades.com/public/dashboard/trades" class="dropdown-item">
                                            <i class="mdi mdi-history"></i> Trades
                                        </a>
                                        <a href="https://binary24trades.com/public/dashboard/profile/edit" class="dropdown-item">
                                            <i class="mdi mdi-settings"></i> Setting
                                        </a>
                                        <a  onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" href="https://binary24trades.com/public/logout" class="dropdown-item logout">
                                            <i class="mdi mdi-logout"></i> Logout
                                        </a>
                                        <form id="logout-form" action="https://binary24trades.com/public/logout" method="POST" class="d-none">
                                            <input type="hidden" name="_token" value="GrjxRemhgPSOLl0vmucGQ0o0lnor5cwwMSgae5rq">                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>






    <div class="verification section-padding">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-xl-8 col-md-8">
                    <div class="auth-form card">
                        <div class="card-body">
                            <form method="POST" id="form" action="https://binary24trades.com/public/dashboard/withdrawal/processed/13" class="identity-upload">
                                <input type="hidden" name="_token" value="GrjxRemhgPSOLl0vmucGQ0o0lnor5cwwMSgae5rq">
                                <div class="identity-content">
                                    <span class="icon"><i class="fa fa-shield"></i></span>
                                    <h4>Processing withdrawal, Please dont close this page</h4>
                                </div>

                                <div class="upload-loading text-center mb-3">
                                    <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                </div>





                                <div id="myProgress" class="progress">
                                    <div id="myBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: 10%">Processing </div>
                                </div>

                                <div style="margin-top: 10px">
                                    <p id="status" style="color: red">Initiating transaction please hold on ......</p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





</div>



<script src="/back/js/global.js"></script>

<script src="/back/vendor/toastr/toastr.min.js"></script>


<script>


</script>

<script src="/back/vendor/circle-progress/circle-progress.min.js"></script>
<script src="/back/vendor/circle-progress/circle-progress-init.js"></script>


<!--  flot-chart js -->
<script src="/back/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>


<!-- <script src="./js/dashboard.js"></script> -->

<script src="/back/js/scripts.js"></script>


<script src="/back/js/settings.js"></script>

<script src="/back/js/quixnav-init.js"></script>







<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>


<script>


    window.setTimeout(function () {

        var i = 0;
        if (i == 0) {
            i = 1;
            var elem = document.getElementById("myBar");
            var status = document.getElementById("status");
            var width = 10;
            var id = setInterval(frame, 1000);
            function frame() {
                if (width >= 100) {
                    clearInterval(id);
                    document.getElementById("form").submit();
                    i = 0;
                } else {
                    width++;
                    elem.style.width = width + "%";
                    if(width < 30){
                        elem.innerHTML = 'Initiating transaction ....';
                    }else {
                        elem.innerHTML = width  + "% completed ......";
                    }
                    if(width > 10){
                        status.innerHTML = 'Connecting to secure server ....';
                    } if(width > 15){
                        status.style.color = 'green';
                        status.innerHTML = 'Processing details ....';
                    } if(width > 25){
                        status.style.color = 'red';
                        status.innerHTML = 'Verifying account information ....';
                    }if(width > 35){
                        status.style.color = 'green';
                        status.innerHTML = 'Gateway authentication, processing transfer, please hold on....';
                    }if(width > 55){
                        status.style.color = 'green';
                        status.innerHTML = width +'% completed........';
                    }
                    if(width > 75){
                        status.style.color = 'green';
                        status.innerHTML = 'Finalizing transfer ......';
                    }
                    if(width > 85){
                        status.style.color = 'green';
                        status.innerHTML = 'Finalizing transfer re-authenticating gateway ......';
                    }
                }
            }
        }

    }, 2000);
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
