@extends('backend.layouts.backend')


@section('content')
    <trade_station cannot_trade_msg="{{ setting('cannot_trade_msg', 'Auto trader disabled') }}" check_trade_url="{{ route('backend.api.trade.check') }}" close_trade_url="{{ route('backend.api.trade.close') }}" trade_url="{{ route('backend.trade.store') }}" all_trade_url="{{ route('backend.api.trades') }}" :balance="{{ auth()->user()->balance }}" :cur_currency="{{ json_encode($currency) }}" :currencies="{{ json_encode($currencies) }}" inline-template>
    <div class="container-fluid min-padding">

        <div class="row crypto-row pt-2">
            @foreach($types as $i)
            <div class="{{ $i == 'commodities' ? 'col-8' : 'col-4 ' }}col-md-2 mt-1">
                <div class="card">
                    <div class="card-body">
{{--                    <div class="card-body" @click="loadCur({{$item}})">--}}
                        <a href="#" data-toggle="modal" data-target="#show{{ $i }}">
{{--                        <a href="#">--}}
{{--                            <img style="max-height: 20px; max-width: 20px" src="" alt="account balance" class="float-left " />--}}
                            <h6 class="card-title font-weight-light text-center">{{ $i }}</h6>
                        </a>

                    </div>
                </div>
            </div>



                <div class="modal fade" id="show{{ $i }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle1">Select {{ $i }} to trade</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    @foreach($currencies->where('type', $i) as $item)
                                        <div class="col-3 col-md-3">
                                            <div class="card bg-accent-1">
                                                <div class="card-body">
                                                    {{--                    <div class="card-body" @click="loadCur({{$item}})">--}}
                                                    <a href="{{ route('backend.tradestation') }}?cur={{ $item->id }}">
                                                        {{--                        <a href="#">--}}
                                                        <img style="max-height: 20px; max-width: 20px" src="{{ $item->image }}" alt="account balance" class="float-left " />
                                                        <h6 class="card-title font-weight-light float-right">{{ $item->sym }}</h6>
                                                    </a>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if (count($currencies->where('type', $i)) < 1)
                                        <div class="text-center mx-auto">
                                            <p class="text-center">No {{ $i  }} available for trading</p>
                                        </div>

                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
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
                                        "symbol": "{{ $currency->ex_sym }}",
                                        "interval": "1",
                                        "timezone": "Etc/UTC",
                                        "theme": "{{ $theme }}",
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

            @section('hide')
                <div class=" col-md-2 col-12">
                    <div class="card">
                        <div class="card-body" style="margin-top: 10px; padding : 5px!important">

                            <p class="mb-20 mt-3 text-center text-capitalize">Trade {{ $currency->name }}</p>


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


                            @if(auth()->user()->can_trade > 0)
                                <div class="row p-4">
                                    <div @click="playNow('buy')" class="col-6 mt-2  ">
                                        <a  href="#">
                                            <div class="bg-success text-center p-1" style="border-radius: 10px">
                                                <i class="icon-chart text-white"></i><br/>
                                                <h3>Buy</h3>
                                            </div>
                                        </a>

                                    </div>
                                    <div @click="playNow('sell')" class="col-6 mt-2  ">
                                        <a  href="#">
                                            <div class="bg-danger text-center p-1" style="border-radius: 5px">
                                                <i class="icon-chart text-white"></i><br/>
                                                <h4>SELL</h4>
                                            </div>
                                        </a>

                                    </div>

                                    <div class="col-6 mx-auto switch " @click="turnTrader">
                                        <input type="checkbox" checked disabled id="switch" /><label for="switch">Toggle</label>
                                    </div>
                                </div>
                            @else
                                <div class="row p-4"@click="canNotTrade('buy')" >
                                    <div @click="playNow('buy')" class="col-6 mt-2 custom_disabled ">
                                        <a  href="#">
                                            <div class="bg-success text-center p-1" style="border-radius: 10px">
                                                <i class="icon-chart text-white"></i><br/>
                                                <h3>Buy</h3>
                                            </div>
                                        </a>

                                    </div>
                                    <div @click="playNow('sell')" class="col-6 mt-2 custom_disabled ">
                                        <a  href="#">
                                            <div class="bg-danger text-center p-1" style="border-radius: 5px">
                                                <i class="icon-chart text-white"></i><br/>
                                                <h4>SELL</h4>
                                            </div>
                                        </a>

                                    </div>

                                    <div class="col-6 mx-auto switch custom_disabled " @click="turnTrader">
                                        <input type="checkbox" checked disabled id="switch" /><label for="switch">Toggle</label>
                                    </div>
                                </div>
                            @endif


                        </div>
                    </div>



                </div>
            @endsection
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



        @section('hide')
            <div class="row mt-2">
                <div class="col-12 mt-10" style="border-left: 3px solid #e77512;">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#" @click="setTrade(0)" class="btn btn-outline-primary" data-toggle="tab">
                                        Open Orders (@{{ open_trades.length }})
                                    </a>
                                </li>
                                <li>
                                    <a href="#" @click="setTrade(1)" class="btn btn-outline-primary" data-toggle="tab">
                                        Closed Trades(@{{ closed_trades.length }})
                                    </a>
                                </li>
                                {{--                            <li>--}}
                                {{--                                <a href="#" @click="setTrade(2)" class="btn btn-outline-primary" data-toggle="tab">--}}
                                {{--                                    Order History (@{{ trades.length }})--}}
                                {{--                                </a>--}}
                                {{--                            </li>--}}
                            </ul>
                            <div class="tab-content" v-cloak>
                                <div class="tab-pane fade in active" id="open">
                                    <div class="table-responsive" data-pattern="priority-columns">
                                        <table id="tech-companies-1" class="table table-small-font no-mb table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Opened at</th>
                                                <th>Currency / Asset</th>
                                                <th>Amount</th>
                                                <th>Qty</th>
                                                <th>Opening Price</th>
                                                <th v-if="is_type !== 0">Closing Price</th>
                                                <th>Direction</th>
                                                <th v-if="is_type === 0">Status</th>
                                                <th v-if="is_type === 1">Result</th>
                                                <th v-if="is_type === 0"></th>
                                            </tr>
                                            </thead>
                                            <tbody v-if="!all_trades">
                                            <tr>
                                                <td colspan="100%">No results found</td>
                                            </tr>
                                            </tbody>
                                            <tbody v-else>
                                            <tr v-for="i in all_trades">
                                                <td>@{{ i.open_at }}</td>
                                                <td>@{{ i.currency.name }}</td>
                                                <td>@{{ i.traded_amount }} USD</td>
                                                <td>@{{ i.qty }} @{{ i.currency.sym }}</td>
                                                <td>@{{ i.opening_price }}USD</td>
                                                <td v-if="is_type !== 0">@{{ i.closing_price }} USD</td>
                                                <td>
                                                    <span v-if="i.trade_type === 'buy'" class="badge badge-success p-2">Buy</span>
                                                    <span v-else class="badge badge-warning p-2">Sell</span>
                                                </td>
                                                <td v-if="i.status === 0">
                                                    <span v-if="i.status === 0" class="badge badge-warning p-2">Running</span>
                                                </td>
                                                <td v-if="i.status === 1">
                                                    <span v-if="i.result === 2" class="badge badge-danger p-2">loss</span>
                                                    <span v-if="i.result === 1" class="badge badge-success p-2">Won</span>
                                                    <span v-if="i.result === 3" class="badge badge-warning p-2">Draw</span>
                                                </td>
                                                <td v-if="is_type === 0">
                                                    <button @click="closeOrder(i.id)" class="btn btn-danger">Close</button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                            </div>
                            <div class="clock w-100"></div>
                        </div>
                    </div>

                </div>
            </div>
        @endsection


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
    </trade_station>
@endsection

@section('styles')
{{--    <link rel="stylesheet" href="/back/vendor/waves/waves.min.css">--}}
@endsection

@section('js')

    <script>
        // "use strict";
        // $(document).ready(function () {
        //     $(".trade-btn").click(function(){
        //         swal("Opps!", "You cant buy / sell when auto trading is enabled", "error");
        //     })
        // });
    </script>

@endsection
