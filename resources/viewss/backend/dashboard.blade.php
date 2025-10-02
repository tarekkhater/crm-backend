@extends('backend.layouts.backend')

@section('content')

    <div class="container-fluid">
        <!-- START: Breadcrumbs-->
        <div class="row">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Dashboard</h4> <b class=" text-capitalize">Welcome {{ auth()->user()->name }}</b></div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->



        <!-- START: Card Data-->
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

        @if (setting('trade'))
            @include('backend.components.dashboard.trade')
        @endif

        @if (setting('invest'))
            @include('backend.components.dashboard.investment')
        @endif
    </div>
@endsection

@section('styles')
{{--    <link rel="stylesheet" href="/back/vendor/waves/waves.min.css">--}}
@endsection

@section('js')

@endsection
