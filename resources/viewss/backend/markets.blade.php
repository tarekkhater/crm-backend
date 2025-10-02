@extends('backend.layouts.backend')

@section('content')

    <div class="container-fluid min-padding">
        <!-- START: Breadcrumbs-->
        <div class="row">
            <div class="col-12  align-self-center">
                <div class="sub-header py-3 px-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h5 class="mb-0">Cryptocurrency Market</h5>
{{--                        <b class=" text-capitalize">This is your go-to page to see all available crypto assets</b>--}}
                        <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Markets</li>
                        </ol>
                    </div>


                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->


        <div class="row mt-2">

            <div class=" col-md-4 col-12">

                <div class="card" style="height: 700px">
                    <div class="card-body">
                        <!-- TradingView Widget BEGIN -->
                        <div class="tradingview-widget-container">
                            <div class="tradingview-widget-container__widget"></div>
                            <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com" rel="noopener" target="_blank"><span class="blue-text"> History</span></a> by TradingView</div>
                            <script type="application/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-timeline.js" async>
                                {
                                    "colorTheme": "dark",
                                    "isTransparent": true,
                                    "displayMode": "regular",
                                    "width": "100%",
                                    "height": "100%",
                                    "locale": "en"
                                }
                            </script>
                        </div>
                        <!-- TradingView Widget END -->
                    </div>
                </div>

            </div>

            <div class="col-12 col-md-8">
                <div class="card" style="height:700px">
                    <div class="card-body" >
                        <!-- TradingView Widget BEGIN -->
                        <div class="tradingview-widget-container">
                            <div class="tradingview-widget-container__widget"></div>
                            <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/markets/cryptocurrencies/prices-all/" rel="noopener" target="_blank"><span class="blue-text">Cryptocurrency Markets</span></a> by TradingView</div>
                            <script type="application/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-screener.js" async>
                                {
                                    "width": "100%",
                                    "height": "100%",
                                    "defaultColumn": "overview",
                                    "screener_type": "crypto_mkt",
                                    "displayCurrency": "USD",
                                    "colorTheme": "dark",
                                    "locale": "en",
                                    "isTransparent": false
                                }
                            </script>
                        </div>
                        <!-- TradingView Widget END -->
                    </div>
                </div>

            </div>

        </div>



        <!-- START: Card Data-->
        @section('hide')
            <div class="row">
                <div class="col-12 col-sm-6 col-xl-3 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <img src="/dist/images/wallet.png" alt="account balance" class="float-right" />
                            <h6 class="card-title font-weight-light">ACCOUNT BALANCE</h6>
                            {{--                        <h6 class="card-subtitle mb-2 text-muted">Today</h6>--}}
                            <h4 class="font-weight-light">${{ Auth()->user()->total() }}</h4>
                            <span class="text-success"><i class="ion ion-android-arrow-dropup"></i> ${{ Auth()->user()->withdrawable }} Withdrawable</span> balance
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <img src="/dist/21/bonus1.png" alt="cart" class="float-right" />
                            <h6 class="card-title font-weight-light">BONUS BALANCE</h6>
                            <h4 class="font-weight-light">${{ Auth()->user()->bonus }}</h4>
                            <span class="text-success"><i class="ion ion-android-arrow-dropdown"></i> 100% Withdrawable</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <img src="/dist/images/money.png" alt="money" class="float-right" />
                            <h6 class="card-title font-weight-light">ACCOUNT PLAN</h6>
                            <h4 class="font-weight-light">{{ auth()->user()->plan }}</h4>
                            <span class="text-success"><i class="ion ion-android-arrow-dropup"></i> Current active</span> plan
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <img src="/dist/21/verified.png" alt="wallet" class="float-right" />
                            <h6 class="card-title font-weight-light">ACCOUNT STATUS</h6>
                            @if (setting('autotrader'))
                                @if (auth()->user()->manager_id)
                                    <h4 class="font-weight-light text-success">Connected </h4>
                                    <span class="text-success"><i class="ion ion-android-arrow-dropdown"></i>Your account is connected</span>
                                @else
                                    <h4 class="font-weight-light text-danger">Not connected </h4>
                                    <span class="text-danger"><i class="ion ion-android-arrow-dropdown"></i>Account not connected</span>
                                @endif
                            @endif
                            @if (!setting('autotrader'))
                                @if (auth()->user()->is_active)
                                    <h4 class="font-weight-light text-success">Verified </h4>
                                    <span class="text-success"><i class="ion ion-android-arrow-dropdown"></i>Your account is verified and active</span>
                                @else
                                    <h4 class="font-weight-light text-danger">Unverified </h4>
                                    <span class="text-danger"><i class="ion ion-android-arrow-dropdown"></i>Unverified account has limited features</span>
                                @endif
                            @endif


                        </div>
                    </div>
                </div>

            </div>
        @endsection


    </div>
@endsection

@section('styles')
{{--    <link rel="stylesheet" href="/back/vendor/waves/waves.min.css">--}}
@endsection

@section('js')

@endsection
