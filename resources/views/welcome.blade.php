
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Dashboard</title>
    <link rel="shortcut icon" href="/asset/images/logosm.png" />
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="T9GZd2OvYRiJxZujtqOr8xwJyleqrP6B22YA3uAe">


    <!-- START: Template CSS-->

    <link rel="stylesheet" href="dist/vendors/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="dist/vendors/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="dist/vendors/jquery-ui/jquery-ui.theme.min.css">
    <link rel="stylesheet" href="dist/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="dist/vendors/flags-icon/css/flag-icon.min.css">
    <link rel="stylesheet" href="dist/vendors/flag-select/css/flags.css">
    <!-- END Template CSS-->

    <!-- START: Page CSS-->


    <link rel="stylesheet" href="dist/vendors/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="dist/vendors/ionicons/css/ionicons.min.css">
    <!-- END: Page CSS-->


    <!-- START: Custom CSS-->
    <link rel="stylesheet" href="dist/css/main.css">
    <link rel="stylesheet" href="dist/css/dashboard-small.css">
    <!-- END: Custom CSS-->


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/css/iziModal.min.css" integrity="sha512-8vr9VoQNQkkCCHGX4BSjg63nI5CI4B+nZ8SF2xy4FMOIyH/2MT0r55V276ypsBFAgmLIGXKtRhbbJueVyYZXjA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="dist/vendors/toastr/toastr.min.css"/>

    <link rel="stylesheet" href="dist/vendors/sweetalert/sweetalert.css">

    <link rel="stylesheet" href="/css/styles.css">

    <style>

        .custom_disabled {
            pointer-events: none;
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
            background: grey;
            display: block;
            border-radius: 40px;
            position: relative;
        }

        .switch  label:after {
            content: '';
            position: absolute;
            top: 5px;
            left: 5px;
            width: 30px;
            height: 30px;
            background: #fff;
            border-radius: 40px;
            transition: 0.3s;
        }

        .switch  input:checked + label {
            background: #55d3da;
        }

        .switch  input:checked + label:after {
            left: calc(100% - 5px);
            transform: translateX(-100%);
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
    </style>

</head>
<!-- END Head-->

<!-- START: Body-->
<body id="main-container" class="default">


<!-- START: Pre Loader-->
<div class="se-pre-con">
    <img src="dist/images/logo.png" alt="logo" width="23" class="img-fluid"/>
</div>
<!-- END: Pre Loader-->

<!-- START: Header-->
<div id="header-fix" class="header fixed-top">
    <nav class="navbar navbar-expand-lg  p-0">
        <div class="navbar-header h4 mb-0 align-self-center d-flex">
            <a href="#l" class="horizontal-logo align-self-center d-flex d-none">
                <img src="/asset/images/logosm.png" alt="logo" width="23" class="img-fluid"/> <span class="h5 align-self-center mb-0 ">360Invest</span>
            </a>
            <a href="#" class="sidebarCollapse ml-2" id="collapse"><i class="icon-menu body-color"></i></a>
        </div>
        <div class="d-inline-block position-relative">
            <button  data-toggle="modal" data-target=".bd-example-modal-lg" class="btn btn-primary p-2  mx-3 h6 mb-0 line-height-1 d-none d-lg-block">
                <span class="text-white font-weight-bold h6">+ DEPOSIT</span>
            </button>
        </div>



        <form class="float-left d-none d-lg-block search-form">
            <div class="form-group mb-0 position-relative">
                <input type="text" class="form-control border-0 rounded bg-search pl-5" placeholder="Search anything...">
                <div class="btn-search position-absolute top-0">
                    <a href="#"><i class="h5 icon-magnifier body-color"></i></a>
                </div>
                <a href="#" class="position-absolute close-button mobilesearch d-lg-none" data-toggle="dropdown" aria-expanded="false"><i class="icon-close h5"></i>
                </a>

            </div>
        </form>

        <button class="btn btn-md btn-outline-primary btn-amount" >$0.00</button>

        <div class="navbar-right ml-auto">
            <ul class="ml-auto p-0 m-0 list-unstyled d-flex">
                <li class="mr-1 d-inline-block my-auto d-block d-lg-none">
                    <a href="#" class="nav-link px-2 mobilesearch" data-toggle="dropdown" aria-expanded="false"><i class="icon-magnifier h4"></i>
                    </a>
                </li>











                <li class="dropdown align-self-center mr-1 d-inline-block">
                    <a href="#" class="nav-link px-2" data-toggle="dropdown" aria-expanded="false"><i class="icon-bell h4"></i>
                        <span class="badge badge-default"> <span class="ring">
                                    </span><span class="ring-point">
                                    </span> </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right border   py-0">
                        <li><a class="dropdown-item text-center py-2" href="#"> <strong>No Notification </strong></a></li>
                    </ul>
                </li>
                <li class="dropdown user-profile d-inline-block py-1 mr-2">
                    <a href="#" class="nav-link px-2 py-0" data-toggle="dropdown" aria-expanded="false">
                        <div class="media">
                            <div class="media-body align-self-center d-none d-sm-block mr-2">
                                <p class="mb-0 text-uppercase line-height-1 text-capitalize"><b>admin</b><br/><span>  </span></p>

                            </div>
                            <img src="dist/images/author.jpg" alt="" class="d-flex img-fluid rounded-circle" width="45">

                        </div>
                    </a>

                    <div class="dropdown-menu  dropdown-menu-right p-0">
                        <a href="#" class="dropdown-item px-2 align-self-center d-flex">
                            <span class="icon-pencil mr-2 h6 mb-0"></span> Edit Profile</a>
                        <a href="#" class="dropdown-item px-2 align-self-center d-flex">
                            <span class="icon-user mr-2 h6 mb-0"></span> View Profile</a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item px-2 align-self-center d-flex">
                            <span class="icon-support mr-2 h6  mb-0"></span> Help Center</a>
                        <a href="#" class="dropdown-item px-2 align-self-center d-flex">
                            <span class="icon-settings mr-2 h6 mb-0"></span> Account Settings</a>
                        <div class="dropdown-divider"></div>

                        <a  onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" href="https://online.360investhub.com/logout" class="dropdown-item px-2 text-danger align-self-center d-flex">
                            <span class="icon-logout mr-2 h6  mb-0"></span> Sign Out
                        </a>
                        <form id="logout-form" action="https://online.360investhub.com/logout" method="POST" class="d-none">
                            <input type="hidden" name="_token" value="T9GZd2OvYRiJxZujtqOr8xwJyleqrP6B22YA3uAe">                        </form>
                    </div>

                </li>

            </ul>
        </div>
    </nav>
</div>
<!-- END: Header-->


<!-- START: Main Menu-->
<div class="sidebar">
    <a href="#" class="sidebarCollapse float-right h6 dropdown-menu-right mr-2 mt-2 position-absolute d-block d-lg-none">
        <i class="icon-close"></i>
    </a>
    <!-- START: Logo-->
    <a href="#" class="sidebar-logo d-flex">
        <img src="/asset/images/logosm.png" alt="logo" width="25" class="img-fluid mr-2"/>
        <span class="h5 align-self-center mb-0">360Invest</span>
    </a>
    <!-- END: Logo-->

    <!-- START: Menu-->
    <ul id="side-menu" class="sidebar-menu">
        <li class=""><a href="https://online.360investhub.com/dashboard"><i class="icon-speedometer"></i>Dashboard</a></li>
        <li class="active"><a href="https://online.360investhub.com/dashboard/tradestation"><i class="fa fa-mail-bulk"></i>Trade Station</a></li>
        <li class=""><a href="https://online.360investhub.com/dashboard/markets"><i class="icon-film"></i>Market</a></li>

        <li class=""><a href="https://online.360investhub.com/dashboard/plans"><i class="fa fa-cubes"></i>Invest & Earn</a></li>
        <li class=""><a href="https://online.360investhub.com/dashboard/transactions"><i class="icon-chart"></i>Transaction History</a></li>

        <li class=""><a href="https://online.360investhub.com/dashboard/investments"><i class="icon-chart"></i>Investments</a></li>



        <li class=""><a href="https://online.360investhub.com/dashboard/withdraw/select"><i class="icon-support"></i>Withdraw</a></li>
        <li class=""><a href="https://online.360investhub.com/dashboard/deposits"><i class="far fa-bookmark"></i>Deposits</a></li>
        <li class="dropdown"><a href="#"><i class="icon-user"></i>Overview</a>
            <div>
                <ul>
                    <li><a href="https://online.360investhub.com/dashboard/account/overview"><i class="icon-people"></i> Profile Overview </a></li>
                    <li><a href="https://online.360investhub.com/dashboard/profile/edit"><i class="fas fa-edit"></i> Edit Profile </a></li>
                    <li><a href="https://online.360investhub.com/dashboard/account/security"><i class="icon-cursor-move"></i> Security Setting</a></li>
                    <li><a href="https://online.360investhub.com/dashboard/account/overview"><i class="icon-user"></i> Account Plan</a></li>

                </ul>
            </div>
        </li>

        <li class=""><a href="https://online.360investhub.com/dashboard/news"><i class="icon-support"></i>News</a></li>
        <li class=""><a href="#"><i class="icon-envelope"></i>Notifications</a></li>



    </ul>
    <!-- END: Menu-->
</div>
<!-- END: Main Menu-->

<!-- START: Main Content-->
<main>






































    <div id="app_secret">


        <div class="container-fluid min-padding">

            <div class="row crypto-row pt-2">
                <div class="col-4 col-md-2 mt-1">
                    <div class="card">
                        <div class="card-body">

                            <a href="#" data-toggle="modal" data-target="#showcrypto">


                                <h6 class="card-title font-weight-light text-center">crypto</h6>
                            </a>

                        </div>
                    </div>
                </div>



                <div class="modal fade" id="showcrypto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle1">Select crypto to trade</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-3 col-md-3">
                                        <div class="card bg-accent-1">
                                            <div class="card-body">

                                                <a href="https://online.360investhub.com/dashboard/tradestation?cur=4">

                                                    <img style="max-height: 20px; max-width: 20px" src="/crypto/ltc.svg" alt="account balance" class="float-left " />
                                                    <h6 class="card-title font-weight-light float-right">LTC</h6>
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 col-md-3">
                                        <div class="card bg-accent-1">
                                            <div class="card-body">

                                                <a href="https://online.360investhub.com/dashboard/tradestation?cur=5">

                                                    <img style="max-height: 20px; max-width: 20px" src="/crypto/xlm.svg" alt="account balance" class="float-left " />
                                                    <h6 class="card-title font-weight-light float-right">XLM</h6>
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 col-md-3">
                                        <div class="card bg-accent-1">
                                            <div class="card-body">

                                                <a href="https://online.360investhub.com/dashboard/tradestation?cur=2">

                                                    <img style="max-height: 20px; max-width: 20px" src="/crypto/eth.svg" alt="account balance" class="float-left " />
                                                    <h6 class="card-title font-weight-light float-right">ETH</h6>
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 col-md-3">
                                        <div class="card bg-accent-1">
                                            <div class="card-body">

                                                <a href="https://online.360investhub.com/dashboard/tradestation?cur=1">

                                                    <img style="max-height: 20px; max-width: 20px" src="/crypto/btc.svg" alt="account balance" class="float-left " />
                                                    <h6 class="card-title font-weight-light float-right">BTC</h6>
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 col-md-3">
                                        <div class="card bg-accent-1">
                                            <div class="card-body">

                                                <a href="https://online.360investhub.com/dashboard/tradestation?cur=3">

                                                    <img style="max-height: 20px; max-width: 20px" src="/crypto/xrp.svg" alt="account balance" class="float-left " />
                                                    <h6 class="card-title font-weight-light float-right">XRP</h6>
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-4 col-md-2 mt-1">
                    <div class="card">
                        <div class="card-body">

                            <a href="#" data-toggle="modal" data-target="#showstocks">


                                <h6 class="card-title font-weight-light text-center">stocks</h6>
                            </a>

                        </div>
                    </div>
                </div>



                <div class="modal fade" id="showstocks" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle1">Select stocks to trade</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-3 col-md-3">
                                        <div class="card bg-accent-1">
                                            <div class="card-body">

                                                <a href="https://online.360investhub.com/dashboard/tradestation?cur=6">

                                                    <img style="max-height: 20px; max-width: 20px" src="https://online.360investhub.com/storage/photos/2/611e8541e062e.png" alt="account balance" class="float-left " />
                                                    <h6 class="card-title font-weight-light float-right">TSLA</h6>
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-4 col-md-2 mt-1">
                    <div class="card">
                        <div class="card-body">

                            <a href="#" data-toggle="modal" data-target="#showforex">


                                <h6 class="card-title font-weight-light text-center">forex</h6>
                            </a>

                        </div>
                    </div>
                </div>



                <div class="modal fade" id="showforex" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle1">Select forex to trade</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="text-center mx-auto">
                                        <p class="text-center">No forex available for trading</p>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-4 col-md-2 mt-1">
                    <div class="card">
                        <div class="card-body">

                            <a href="#" data-toggle="modal" data-target="#showindices">


                                <h6 class="card-title font-weight-light text-center">indices</h6>
                            </a>

                        </div>
                    </div>
                </div>



                <div class="modal fade" id="showindices" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle1">Select indices to trade</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="text-center mx-auto">
                                        <p class="text-center">No indices available for trading</p>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-8col-md-2 mt-1">
                    <div class="card">
                        <div class="card-body">

                            <a href="#" data-toggle="modal" data-target="#showcommodities">


                                <h6 class="card-title font-weight-light text-center">commodities</h6>
                            </a>

                        </div>
                    </div>
                </div>



                <div class="modal fade" id="showcommodities" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle1">Select commodities to trade</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="text-center mx-auto">
                                        <p class="text-center">No commodities available for trading</p>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>




            <div class="row mt-2 min-padding">

                <div class="col-md-10 col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- TradingView Widget BEGIN -->
                            <div class="tradingview-widget-container" style="height: 700px; overflow-y: auto;">
                                <div id="tradingview_3c684 h-100"></div>
                                <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/symbols/BTCUSD/?exchange=BITBAY" rel="noopener" target="_blank"><span class="blue-text">BTCUSD Chart</span></a> by TradingView</div>
                                <script type="application/javascript" src="https://s3.tradingview.com/tv.js"></script>
                                <script type="application/javascript">
                                    new TradingView.widget(
                                        {
                                            "autosize" : true,
                                            "symbol": "BITSTAMP:BTCUSD",
                                            "interval": "1",
                                            "timezone": "Etc/UTC",
                                            "theme": "dark",
                                            "style": "0",
                                            "locale": "en",
                                            "toolbar_bg": "#f1f3f6",
                                            "enable_publishing": false,
                                            "withdateranges": true,
                                            "allow_symbol_change": true,
                                            "watchlist": [
                                                "BITBAY:BTCUSD",
                                                "COINBASE:ETHUSD",
                                                "BINANCE:BNBUSD",
                                                "BITTREX:DOGEUSD",
                                                "BINANCE:TROYUSD",
                                                "KRAKEN:USDTUSD",
                                                "COINBASE:MATICUSD"
                                            ],
                                            "details": true,
                                            "hotlist": true,
                                            "container_id": "tradingview_3c684"
                                        }
                                    );
                                </script>
                            </div>
                            <!-- TradingView Widget END -->
                        </div>
                    </div>

                </div>

                <div class=" col-md-2 col-12">
                    <div class="card hidden-sm">
                        <div class="card-body" style="margin-top: 10px; padding : 5px!important">

                            <p class="mb-20 mt-3 text-center text-capitalize">Trade Bitcoin</p>


                            <div class="form-group">
                                <div class="col-12">
                                    <label >Amount</label>
                                </div>
                                <div class="input-group">
                                    <div class="col-12">
                                        <input type="number" v-model="amt" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-12">
                                    <label >Auto close</label>
                                </div>
                                <div class="input-group">
                                    <div class="col-12">
                                        <select v-model="autoclose" class="form-control">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" v-if="autoclose">
                                <div class="col-12">
                                    <label >Duration</label>
                                </div>
                                <div class="input-group">
                                    <div class="col-12">
                                        <select class="form-control" v-model="duration">
                                            <option value="1">1 minutes</option>
                                            <option value="5">5 minutes</option>
                                            <option value="10">10 minutes</option>
                                            <option value="10">20 minutes</option>
                                            <option value="10">30 minutes</option>
                                            <option value="60">1 hour</option>
                                            <option value="120">2 hours</option>
                                            <option value="180">3 hours</option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="row align-items-center p-4"@click="canNotTrade('buy')" >
                                <button
                                    class="btn btn-success d-inline custom_disabled"
                                    @click="playNow('buy')"
                                    style="margin-right:10px; padding:9px 35px; margin-bottom: 5px;"
                                >
                                    <i class="icon-chart text-white"></i><br/>
                                    BUY
                                </button>
                                <button
                                    class="btn btn-danger d-inline custom_disabled"
                                    @click="playNow('sell')"
                                    style="margin-right:10px; padding:9px 35px; margin-bottom: 5px;"
                                >
                                    <i class="icon-chart text-white"></i><br/>
                                    SELL
                                </button>

                                <div class="d-flex flex-column switch custom_disabled " @click="turnTrader">
                                    <span style="color:#fff;"class="">AUTOCOPY <br>TRADER: </span>
                                    <input type="checkbox" checked disabled id="switch" /><label for="switch">Toggle</label>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>


            <!-- buy and sell mobile -->
            <div class="d-md-none d-lg-none mt-2">
                <div class="row buy-row align-items-center p-4"@click="canNotTrade('buy')" >
                    <button
                        class="btn btn-success d-inline custom_disabled"
                        data-toggle="modal"
                        data-target="#buyForm"
                        style="margin-right:10px; padding:9px 35px; margin-bottom: 5px;"
                    >
                        <i class="icon-chart text-white"></i><br/>
                        BUY
                    </button>
                    <button
                        class="btn btn-danger d-inline custom_disabled"
                        data-toggle="modal"
                        data-target="#sellForm"
                        style="margin-right:10px; padding:9px 35px; margin-bottom: 5px;"
                    >
                        <i class="icon-chart text-white"></i><br/>
                        SELL
                    </button>

                    <div class="d-flex flex-column switch custom_disabled " @click="turnTrader">
                        <span style="color:#fff;"class="">AUTOCOPY <br>TRADER: </span>
                        <input type="checkbox" checked disabled id="switch" /><label for="switch">Toggle</label>
                    </div>
                </div>

                <!-- Buy modal for mobile -->
                <div class="modal fade" id="buyForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle1">Select forex to trade</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card">
                                    <div class="card-body" style="margin-top: 10px; padding : 5px!important">

                                        <p class="mb-20 mt-3 text-center text-capitalize">Trade Bitcoin</p>

                                        <div class="mx-3">
                                            <div class="form-group">
                                                <label >Amount</label>
                                                <input type="number" v-model="amt" class="form-control" />
                                            </div>
                                            <div class="form-group">
                                                <label >Auto close</label>
                                                <select v-model="autoclose" class="form-control">
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                            <div class="form-group" v-if="autoclose">
                                                <label >Duration</label>
                                                <select class="form-control" v-model="duration">
                                                    <option value="1">1 minutes</option>
                                                    <option value="5">5 minutes</option>
                                                    <option value="10">10 minutes</option>
                                                    <option value="10">20 minutes</option>
                                                    <option value="10">30 minutes</option>
                                                    <option value="60">1 hour</option>
                                                    <option value="120">2 hours</option>
                                                    <option value="180">3 hours</option>
                                                </select>
                                            </div>
                                            <div class="text-right">
                                                <button class="btn btn-success px-4"  @click="playNow('buy')">Buy</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sell modal for mobile -->
                <div class="modal fade" id="sellForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle1">Select forex to trade</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card">
                                    <div class="card-body" style="margin-top: 10px; padding : 5px!important">

                                        <p class="mb-20 mt-3 text-center text-capitalize">Trade Bitcoin</p>

                                        <div class="mx-3">
                                            <div class="form-group">
                                                <label >Amount</label>
                                                <input type="number" v-model="amt" class="form-control" />
                                            </div>
                                            <div class="form-group">
                                                <label >Auto close</label>
                                                <select v-model="autoclose" class="form-control">
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                            <div class="form-group" v-if="autoclose">
                                                <label >Duration</label>
                                                <select class="form-control" v-model="duration">
                                                    <option value="1">1 minutes</option>
                                                    <option value="5">5 minutes</option>
                                                    <option value="10">10 minutes</option>
                                                    <option value="10">20 minutes</option>
                                                    <option value="10">30 minutes</option>
                                                    <option value="60">1 hour</option>
                                                    <option value="120">2 hours</option>
                                                    <option value="180">3 hours</option>
                                                </select>
                                            </div>
                                            <div class="text-right">
                                                <button class="btn btn-primary px-4" @click="playNow('sell')">Sell</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>



        <div class="modal fade bd-example-modal-lg" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Make</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <deposit upload_url="https://online.360investhub.com/dashboard/post/image" url="https://online.360investhub.com/dashboard/deposits"  :wallets='[]'></deposit>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="makeDeposit" tabindex="-1" role="dialog" aria-labelledby="makeDepositTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Make Deposit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 justify-content-center text-center">
                                        <h3>Make a deposit and start trading!</h3>
                                        <div class="p-4">
                                            <img src="/img/2021/img.png">
                                        </div>
                                        <div class="m-4">
                                            <p>To start trading  and access all of the platform's features,
                                                you will first need to make a deposit to your personal Wallet.</p>
                                            <p>
                                                Simply click the Deposit button to bring up your Wallet address and QR code
                                                It’s quick, safe and easy!</p>
                                        </div>
                                        <div class="text-center justify-content-center mr-auto">
                                            <button id="openDeposit" class="btn btn-primary p-2  mx-3 h6 mb-0 line-height-1">
                                                <span class="text-white font-weight-bold h6">+ DEPOSIT</span></button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addAccount" tabindex="-1" role="dialog" aria-labelledby="addAccountTitle" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <add_account :wires='[]' url="https://online.360investhub.com/dashboard/account/store"></add_account>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addWireAccount" tabindex="-1" role="dialog" aria-labelledby="addWireAccountTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add New Wire Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <add_wire_account url="https://online.360investhub.com/dashboard/account/wire/account/store"></add_wire_account>
                    </div>
                </div>
            </div>
        </div>


    </div>




</main>
<!-- END: Content-->







<!-- START: Footer-->



<!-- END: Footer-->

<!-- START: Back to top-->
<a href="#" class="scrollup text-center">
    <i class="icon-arrow-up"></i>
</a>
<!-- END: Back to top-->

<!-- START: Template JS-->
<script src="dist/vendors/jquery/jquery-3.3.1.min.js"></script>
<script src="dist/vendors/jquery-ui/jquery-ui.min.js"></script>
<script src="dist/vendors/moment/moment.js"></script>
<script src="dist/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/vendors/slimscroll/jquery.slimscroll.min.js"></script>
<script src="dist/vendors/flag-select/js/jquery.flagstrap.min.js"></script>
<!-- END: Template JS-->

<!-- START: APP JS-->
<script src="dist/js/app.js"></script>
<!-- END: APP JS-->

<!-- START: Page Vendor JS-->
<script src="dist/vendors/raphael/raphael.min.js"></script>
<script src="dist/vendors/morris/morris.min.js"></script>
<script src="dist/vendors/chartjs/Chart.min.js"></script>
<script src="dist/vendors/starrr/starrr.js"></script>
<script src="dist/vendors/jquery-flot/jquery.canvaswrapper.js"></script>
<script src="dist/vendors/jquery-flot/jquery.colorhelpers.js"></script>
<script src="dist/vendors/jquery-flot/jquery.flot.js"></script>
<script src="dist/vendors/jquery-flot/jquery.flot.saturated.js"></script>
<script src="dist/vendors/jquery-flot/jquery.flot.browser.js"></script>
<script src="dist/vendors/jquery-flot/jquery.flot.drawSeries.js"></script>
<script src="dist/vendors/jquery-flot/jquery.flot.uiConstants.js"></script>
<script src="dist/vendors/jquery-flot/jquery.flot.legend.js"></script>
<script src="dist/vendors/jquery-flot/jquery.flot.pie.js"></script>
<script src="dist/vendors/bootstrap-tour/js/bootstrap-tour-standalone.min.js"></script>
<!-- END: Page Vendor JS-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/js/iziModal.min.js" integrity="sha512-8aOKv+WECF2OZvOoJWZQMx7+VYNxqokDKTGJqkEYlqpsSuKXoocijXQNip3oT4OEkFfafyluI6Bm6oWZjFXR0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="dist/js/home.script.js"></script>

<script src="dist/vendors/sweetalert/sweetalert.min.js"></script>
<script src="dist/vendors/toastr/toastr.min.js"></script>



<script src="/js/app.js"></script>

<!-- END: Page JS-->
<!-- START: Page JS-->

<script>
</script>

<script>
    $(window).on('load', function() {
        $("#openDeposit").button().click(function(){
            $('#makeDeposit').modal('hide');
            $('.bd-example-modal-lg').modal('show');
        });
    });
</script>


<script>
    // "use strict";
    // $(document).ready(function () {
    //     $(".trade-btn").click(function(){
    //         swal("Opps!", "You cant buy / sell when auto trading is enabled", "error");
    //     })
    // });
</script>


</body>
<!-- END: Body-->

</html>
