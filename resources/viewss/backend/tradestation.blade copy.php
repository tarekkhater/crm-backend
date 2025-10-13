@extends('backend.layouts.backend')


hiiiiiiiiiiiiiiiiiiiiiiiiiii

@section('content')
    <trade_station :can_trade="{{ setting('can_trade', true) }}" :trades='@json($trades)'
        interv="{{ setting('api_interval', 1000) }}" u_can_trade="{{ auth()->user()->can_trade }}"
        cannot_trade_msg="{{ setting('cannot_trade_msg', 'Auto trader enabled') }}"
        check_trade_url="{{ route('backend.api.trade.check') }}" close_trade_url="{{ route('backend.api.trade.close') }}"
        trade_url="{{ route('backend.trade.store') }}" all_trade_url="{{ route('backend.api.trades') }}"
        :balance="{{ auth()->user()->balance }}" :cur_currency="{{ json_encode($currency) }}"
        :currencies="{{ json_encode($currencies) }}" inline-template>
        <div>
            <div class="container-fluid" style="padding: 0;">
                <div class="d-flex row min-padding m-0">
                    {{-- <div class="sidebar-card col-md-2 m-0 p-0">
                        <div class="card hidden-sm">
                            <div class="card-body">
                                <div class="pb-2 washlist">
                                    <div class="first_half">
                                        <div class="row bg-sec">
                                            <div class="m-0 font-weight-bold p-2 bg-sec" style="height: 40px;">
                                                WATCHLIST
                                            </div>
                                        </div>
                                        <div class="row justify-content-between">
                                            <div style="min-height: 140px; overflow-x: auto; width: 100%;" class="mx-0">
                                                <table id="myTable">
                                                    <thead style="border-bottom: 2px solid #1D2435" class="font-weight-bold">
                                                        <tr>
                                                            <th class="float-left">Symbol</th>
                                                            <th>Buy</th>
                                                            <th>Sell</th>
                                                            <th>&nbsp;</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="watchlist">
                                                        @php
                                                            $watchlistCurrencyPairIds = [];
                                                        @endphp
                                                        @foreach ($watchlist as $item)
                                                            @php
                                                                $watchlistCurrencyPairIds[] = $item->currencyPair->id;
                                                            @endphp
                                                            <tr>
                                                                <td class="float-left">{{ $item->currencyPair->sy }}</td>
                                                                <td>{{ $item->currencyPair->buy_spread }}</td>
                                                                <td>{{ $item->currencyPair->sell_spread }}</td>
                                                                <td><i style="cursor: pointer;" class="fa fa-trash text-danger removeFromWatchlist" data-currency-id="{{ $item->currencyPair->id }}"></i></td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="last_half">
                                        <div class="row bg-sec">
                                            <div class="font-weight-bold p-2" style="height: 40px; width: 100%;">
                                                DETAILS
                                            </div>
                                        </div>
                                        <div class="justify-content-between text-center">
                                            <div class="mt-2">
                                                <div class="row text-white">
                                                    <div class="col-2"><img height="100%" src="{{ $currency->image }}" /></div>
                                                    <div class="col-10" style="text-align: left">
                                                        <span class="ml-4" style="font-size:1.1em" class="text-uppercas">{{ $currency->name }}</span><br/>
                                                        <span class="ml-4 p-1 " style="border-radius: 100px; width: auto; font-size: 12px; background: #F6921E">{{ $currency->type }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mt-2 " >
                                                    <div class="col-12"><span style="float : left">Buy :</span>  <span class="text-white" style="float: right"> {{ $currency->buyPrice() }}</span></div>
                                                    <div class="col-12 mt-2"><span style="float : left">Sell :</span>  <span class="text-white" style="float: right">{{ $currency->sellPrice() }}</span></div>
                                                </div>

                                                <div class="row" style="text-align : right" >
                                                    <div class="col-12 mt-2" id="addToWatchlistButtonLocation{{ $currency->id }}">
                                                        @if (!in_array($currency->id, $watchlistCurrencyPairIds))
                                                            <button class="btn btn-primary btn-sm addToWatchlist" data-currency-id="{{ $currency->id }}">Add to watchlist <i class="fa fa-spinner fa-spin fa-fw" style="display: none;"></i></button>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <div class="col-lg-10 col-md-19 chart_center" style="width: 100%; padding: 0;">
                        {{-- <div class="row crypto-row align-items-center pr-3 pl-2 ml-0">
                        @foreach ($types as $i)
                            <div class="asset-item">
                                <a href="#" data-toggle="modal" data-target="#show{{ $i }}">
                                    {{ $i }} &nbsp;
                                    <i class="fa fa-angle-down hidden-sm"
                                       style="color: rgba(255, 255, 255, 0.7; font-weight: 600;"></i>
                                </a>
                            </div>

                            <div class="modal fade" id="show{{ $i }}" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

                                    @php
                                        $assets = \App\Models\CurrencyPair::where('rate', '!=', 0.0)
                                            ->where('type', $i)
                                            ->where('disabled', 0)
                                            ->get();
                                    @endphp

                                    <all_assets tradestation="{{ route('backend.tradestation') }}" :key="{{ $i }}" id="{{ $i }}" i="{{ $i }}"
                                                :asset='@json($assets)'></all_assets>

                                </div>


                            </div>

                        @endforeach
                        <div class="d-flex align-items-center">
                        </div>
                    </div> --}}

                        <div class="card" style="padding-top:-10px">
                            <div class="card-body pl-1" style="padding: 0;">
                                <div class="tradingview-widget-container" style="height: 55vh;">
                                    <div id="tradingview_3c684"></div>
                                    <div class="tradingview-widget-copyright"><a
                                            href="https://www.tradingview.com/symbols/BTCUSD/?exchange=BITBAY"
                                            rel="noopener" target="_blank"><span class="blue-text">BTCUSD
                                                Chart</span></a> by TradingView</div>
                                    <script type="application/javascript" src="https://s3.tradingview.com/tv.js"></script>
                                    <script type="application/javascript">
                                        new TradingView.widget({
                                            "autosize": true,
                                            "symbol": "{{ $currency->ex_sym }}",
                                            "interval": "1",
                                            "timezone": "Etc/UTC",
                                            "theme": "{{ $theme }}",
                                            "style": "1",
                                            "locale": "en",
                                            "toolbar_bg": "#f1f3f6",
                                            "enable_publishing": false,
                                            "withdateranges": true,
                                            "hide_top_toolbar": false,
                                            "allow_symbol_change": false,
                                            // "watchlist": [
                                            //     "NASDAQ:AAPL",
                                            //     "COINBASE:BTCUSD",
                                            //     "FX:EURUSD",
                                            //     "CURRENCYCOM:US500",
                                            //     "OANDA:XAUUSD"
                                            // ],
                                            "details": true,
                                            "hotlist": true,
                                            "container_id": "tradingview_3c684"
                                        });
                                    </script>
                                </div>

                            </div>

                        </div>
                        <div id="order-book-wrapper" style="position: relative;     margin-top: 20px;">
                            <!-- <div class="d-flex justify-content-between">
                                                <div class="px-3" style="background: #171f2c; padding-top: 3px; color: #fff;">
                                                    <p style="font-size: 12px;"><strong>ORDER BOOK</strong></p>
                                                </div>
                                                <div>
                                                    <i class="fa fa-angle-up" id="toggle"></i>
                                                </div>
                                            </div> -->
                            <div id="accordion">

                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <h5 class="mb-0 d-flex justify-content-between"
                                            style="background: #222D48;padding: 5px">
                                            <span>Total Portifilio</span>
                                            <button style="color: #94969B !important;text-decoration: none"
                                                class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
                                                aria-expanded="true" aria-controls="collapseOne">
                                                Show positions <i class="fa fa-angle-down"></i>
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                        data-parent="#accordion">
                                        <div class="card-body">

                                            <order_book :p_trades="p_trades"
                                                close_trade_url="{{ route('backend.api.trade.close') }}"></order_book>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                    <div class="sidebar-card right-card bg-card col-lg-2 col-md-12">
                        <div class="card hidden-sm bg-card">
                            <div class="card-body" style="padding : 5px!important">
                                <div class="mt-5">
                                    <ul class="nav nav-tabs w-100">
                                        <li class="nav-item w-50 text-center">
                                            <a class="nav-link buy active" data-toggle="tab" href="#buy-content"
                                                id="buy-tab">BUY <br />
                                                <span v-cloak v-if="buy_price > 0">
                                                    @{{ buy_price }}
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav-item w-50 text-center">
                                            <a class="nav-link sell pl-4" data-toggle="tab" href="#sell-content"
                                                id="sell-tab">SELL
                                                <br />
                                                <span v-cloak v-if="buy_price > 0">
                                                    @{{ sell_price }}
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="buy-content" class="tab-pane fade in active">
                                            <div>
                                                {{-- <div v-cloak>
                                                    <a
                                                        href="#"
                                                        class="nav-link px-2 mb-0 text-right"
                                                        data-toggle="dropdown"
                                                        aria-expanded="false"
                                                        style="color: rgb(40 167 69); font-weight: bold; font-size: 1.25rem;"
                                                    >
                                                        {{ auth()->user()->cur_sym }}@{{ formatPrice(bal) }}
                                                    </a>
                                                </div> --}}

                                            @section('hide-bal')
                                                <div class="d-flex justify-content-between align-items-center w-100 form-group pt-2 px-1 my-3"
                                                    v-cloak>
                                                    <label class="mb-0 w-100 font-weight-bold"
                                                        style="color: #fff !important; font-weight: lighter !important; opacity: .3 !important; font-size: 15px !important;">Bal</label>
                                                    <p v-cloak class="mb-0"
                                                        style="color: rgb(40 167 69); font-weight: bold; font-size:16px;">
                                                        {{ auth()->user()->cur_sym }}@{{ formatPrice(bal) }}</p>
                                                </div>
                                                <div
                                                    class="d-flex justify-content-between align-items-center w-100 form-group pt-2 px-1 mb-3">
                                                    <label class="mb-0 w-100 font-weight-bold"
                                                        style="color: #fff !important; font-weight: lighter !important; opacity: .3 !important; font-size: 15px !important;">Bonus</label>
                                                    <p class="mb-0" style="color: #fff; font-weight: bold">
                                                        {{ auth()->user()->bonus }}</p>
                                                </div>
                                            @endsection

                                            <div class="d-flex  justify-content-between  align-items-center w-100 form-group pt-2 px-1 my-3"
                                                v-cloak>
                                                <img height="" style="height: 50px; margin-right: 10px"
                                                    src="{{ $currency->image }}" />
                                                <p class="mb-0" style="font-size: 1.25rem;">
                                                    {{ $currency->name }}
                                                    <br /> <span class="text-default"
                                                        style="font-size : 1.0rem">{{ $currency->type }}</span>
                                                </p>
                                            </div>


                                            <div class="d-flex align-items-center justify-content-between form-group px-2 mt-3 mb-2 text-right"
                                                style="">
                                                <label class="mb-0 font- pr-2"
                                                    style="color: #fff;font-size: 13px">Amount</label>
                                                {{--                                                    <input type="number" v-model="volume" --}}
                                                {{--                                                        class="form-control text-right pl-0 specialBTC" --}}
                                                {{--                                                        style="border: 0; color: #fff;" --}}
                                                {{--                                                        placeholder="Enter volume in {{ $currency->sym }}" min="0" /> --}}

                                                <div class="number-input">
                                                    <button @click="decreaseVolumeupdate">
                                                        -

                                                    </button>
                                                    <input class="quantity bg-light update_input" min="0"
                                                        placeholder="0" v-model="volume" value="1"
                                                        type="number">
                                                    <button @click="increaseVolumeupdate" class="plus">
                                                        +
                                                    </button>
                                                </div>



                                                <span style="color: #fff; font-size: 13px">{{ $currency->sym }}</span>
                                            </div>
                                            <div class="range-wrap">
                                                <input type="range"data-balance="{{ auth()->user()->balance }}"
                                                    v-model="rang_amount" @click="updateRange" min="0"
                                                    max="100" step="10" class="range">
                                                <output class="bubble"></output>
                                            </div>

                                            {{-- <div class="d-flex"> --}}
                                            {{-- <div class="w-25"> --}}
                                            {{-- <button @click="decreaseVolume" class="btn w-100" style="background: #353c51; color: #fff; margin-right: 2px; font-size: 14px; padding: 0.2rem .75rem;">-</button> --}}
                                            {{-- </div> --}}
                                            {{-- <div class="w-50 text-center" v-cloak> --}}
                                            {{-- <input --}}
                                            {{-- type="number" --}}
                                            {{-- v-model="volume" --}}
                                            {{-- class="form-control text-right pl-0 specialBTC" --}}
                                            {{-- style="border: 0; color: #fff;" --}}
                                            {{-- placeholder="Enter volume in {{ $currency->sym }}" --}}
                                            {{-- min="0" --}}
                                            {{-- /> --}}
                                            {{-- </div> --}}
                                            {{-- <div class="w-25"> --}}
                                            {{-- <button @click="increaseVolume" class="btn w-100" style="background: #353c51; color: #fff; margin-left: 2px; font-size: 14px; padding: 0.2rem .75rem;">+</button> --}}
                                            {{-- </div> --}}
                                            {{-- </div> --}}

                                            @section('hide')
                                                <div class="form-group mb-2 px-1 d-flex align-items-center"
                                                    style="background: #404353; border-radius: 4px;">
                                                    <label class="mb-0 font-weight-bold pr-2"
                                                        style="color: #fff;">Amount</label>
                                                    <input type="number" v-model="volume"
                                                        class="form-control text-right pl-0 specialBTC"
                                                        style="border: 0; color: #fff; opacity: 0.6;"
                                                        placeholder="Enter volume in {{ $currency->sym }}"
                                                        min="0" />
                                                    <span style="color: #fff;">{{ $currency->sym }}</span>
                                                </div>
                                            @endsection

                                            @section('suspend')
                                                <div class="range-wrap">
                                                    <input type="range" min="0" max="300" step="5"
                                                        value="150" class="range">
                                                    <output class="bubble"></output>
                                                </div>
                                            @endsection




                                            <div class="d-flex justify-content-between align-items-center form-group px-2 mt-3 mb-2 text-right"
                                                style="border-radius: 4px; margin-top: 20px">
                                                <label class="mb-0 font-weight-bold"
                                                    style="color: #fff !important; font-weight: lighter !important; font-size: 13px !important;">Total</label>
                                                <input type="number" v-model="priceInUsd"
                                                    class="form-control text-right px-1" step="any" disabled
                                                    style="border: 0; color: #fff" placeholder="1" min="0" />
                                                <span style="color: #fff;">USD</span>
                                            </div>

                                            <hr />

                                            <div class="d-flex justify-content-end align-items-center w-100 form-group px-1 mb-1"
                                                style="font-size: 13px; justify-content: space-between !important;">
                                                <div class="">
                                                    <label class="mb-0"
                                                        style="color: #fff !important; font-weight: lighter !important">Leverage</label>
                                                </div>
                                                <div class="">
                                                    <label class="mb-0 font-weight-bold" style="color: #fff;">1 :
                                                        {{ $currency->leverage }}</label>
                                                </div>
                                            </div>


                                            <hr />

                                            <div class="  mb-3">
                                                <div class="d-flex justify-content-between"
                                                    style="justify-content: space-between !important;">
                                                    <div>
                                                        <p title="Take Profit" class="title"
                                                            style="margin-top: -7px;color: #fff !important; font-weight: lighter !important; font-size: 15px !important;">
                                                            T p</p>
                                                    </div>
                                                    <div style="margin-top: -7px;"
                                                        class="d-flex align-content-center align-self-center text-center switch">
                                                        <input v-model="is_take_profit" type="checkbox"
                                                            id="toggle-profit" />
                                                        <label for="toggle-profit" class="ml-2"
                                                            style="width: 52px; height: 25px;">Toggle</label>
                                                    </div>
                                                    <div>
                                                        <input type="number" step="any" v-model="take_profit"
                                                            class="form-control" id="profit-value" value="100"
                                                            :disabled="!is_take_profit" style="max-width: 100px" />
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="   mb-2">
                                                <div class="d-flex justify-content-between"
                                                    style="justify-content: space-between !important;">
                                                    <div>
                                                        <p title="Stop Loss" class="title"
                                                            style="margin-top: -7px;,color: #fff !important; font-weight: lighter !important ; font-size: 15px !important;">
                                                            S L</p>
                                                    </div>
                                                    <div style="margin-top: -7px;"
                                                        class="d-flex align-content-center align-self-center text-center switch">
                                                        <input v-model="is_stop_loss" type="checkbox"
                                                            id="toggle-loss" />
                                                        <label for="toggle-loss" class="ml-2"
                                                            style="width: 52px; height: 25px;">Toggle</label>
                                                    </div>
                                                    <div>
                                                        <input step="any" v-model="stop_loss" type="number"
                                                            class="form-control" id="loss-value" value="100"
                                                            :disabled="!is_stop_loss" style="max-width: 100px" />
                                                    </div>
                                                </div>

                                            </div>




                                            <div @click="can_trade ? '' : canNotTrade()"
                                                class="d-flex flex-column ml-auto justify-content-end">
                                                {{--                                                    <div v-cloak> --}}
                                                {{--                                                        <div class="d-flex justify-content-between switch custom_disabled off_switch" --}}
                                                {{--                                                            v-if="can_trade"> --}}
                                                {{--                                                            <span --}}
                                                {{--                                                                style="color: #fff !important; font-weight: lighter !important; font-size: 13px !important;" --}}
                                                {{--                                                                class="pt-1">AutoCopy Trader </span> --}}
                                                {{--                                                            <input type="checkbox" checked disabled id="switch" /><label --}}
                                                {{--                                                                for="switch" class="ml-2">Toggle</label> --}}
                                                {{--                                                        </div> --}}

                                                {{--                                                        <div class="d-flex justify-content-between  switch custom_disabled " --}}
                                                {{--                                                            style="justify-content: space-between !important;" v-else> --}}
                                                {{--                                                            <div class=""> --}}
                                                {{--                                                                <span --}}
                                                {{--                                                                    style="color: #fff !important; font-weight: lighter !important; font-size: 13px !important;" --}}
                                                {{--                                                                    class="pt-1">AutoCopy Trader </span> --}}
                                                {{--                                                            </div> --}}
                                                {{--                                                            <div class=""> --}}
                                                {{--                                                                <input type="checkbox" checked disabled --}}
                                                {{--                                                                    id="switch" /><label for="switch" --}}
                                                {{--                                                                    class="ml-2">Toggle</label> --}}
                                                {{--                                                            </div> --}}
                                                {{--                                                        </div> --}}
                                                {{--                                                    </div> --}}
                                            </div>


                                            <hr />
                                            <div class="d-flex justify-content-end align-items-center w-100 form-group px-1 mt-3 mb-1"
                                                style="justify-content: space-between !important;">
                                                <div class="">
                                                    <label class="mb-0"
                                                        style="color: #fff !important; font-weight: lighter !important; font-size: 15px !important;">Fee</label>
                                                </div>
                                                <div class="">
                                                    <label class="mb-0 font-weight-bold"
                                                        style="color: #fff; font-size: 13px;">4%</label>
                                                </div>
                                                {{-- <div style="width: 15%;">
                                                            <input disabled type="text" value="4%" class="form-control pl-1 pr-0" style="color: #fff; border: 0; font-size: 13px;" />
                                                        </div> --}}
                                            </div>


                                            <div @click="can_trade ? '' : canNotTrade()"
                                                class="d-flex flex-column ml-auto justify-content-end">
                                                @php
                                                    // 2.30 pm : 9:30 pm
                                                    $start = \Carbon\carbon::createFromTimeString('14.30');
                                                    $end = \Carbon\carbon::createFromTimeString('21.30');
                                                    $now = \Carbon\carbon::now();

                                                    if ($now->between($start, $end)) {
                                                        $between = 1;
                                                    } else {
                                                        $between = 0;
                                                    }
                                                    // $base = $currency->base;
                                                    $base = $currency->ex_sym;
                                                    $AmericaArray = ['NASDAQ', 'NYSE'];
                                                    $NASDAQ = 'NASDAQ:TSLA';
                                                    $NYSE = 'NYSE:AZO';

                                                    $newNASDAQ = substr($base, 0, 6);
                                                    $newNYSE = substr($base, 0, 4);

                                                    if (in_array($newNASDAQ, $AmericaArray)) {
                                                        $base = 'USD';
                                                    }

                                                    if (in_array($newNYSE, $AmericaArray)) {
                                                        $base = 'USD';
                                                    }

                                                @endphp

                                                @if ((setting('can_trade', true) && ($between == 1 && ($base = 'USD'))) || auth()->user()->allow_trade == 1)
                                                    <button class="btn d-flex justify-content-center "
                                                        :class="can_trade ? '' : 'custom_disabled'"
                                                        style="background-color: #00D589; padding:12px 0px 5px 0px; font-size: 12px;"
                                                        :data-target="can_trade ? '#buyModal' : ''"
                                                        data-toggle="modal">
                                                        <svg id="Capa_1" enable-background="new 0 0 512 512"
                                                            height="25" viewBox="0 0 512 512" width="25"
                                                            xmlns="http://www.w3.org/2000/svg" style="fill: white;">
                                                            <g>
                                                                <path
                                                                    d="m512 482h-30v-302h-91v302h-30v-182h-90v182h-30v-242h-90v242h-30v-152h-91v152h-30v30h512z">
                                                                </path>
                                                                <path
                                                                    d="m512 120v-120h-121v30h69.789l-144.789 143.789-120-120-191.605 190.606 21.21 21.21 170.395-169.394 120 120 166-165v68.789z">
                                                                </path>
                                                            </g>
                                                        </svg>
                                                        <p class="ml-2 mt-1 font-weight-bold">BUY
                                                        </p>
                                                    </button>
                                                @else
                                                    <a href="{{ route('backend.notTrade') }}"
                                                        class="btn custom_disabled d-flex"
                                                        style="background-color: #00D589; padding:9px 30px; font-size: 12px;">
                                                        <svg id="Capa_1" enable-background="new 0 0 512 512"
                                                            height="25" viewBox="0 0 512 512" width="25"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            style="fill: white; margin-bottom: 5px;">
                                                            <g>
                                                                <path
                                                                    d="m512 482h-30v-302h-91v302h-30v-182h-90v182h-30v-242h-90v242h-30v-152h-91v152h-30v30h512z">
                                                                </path>
                                                                <path
                                                                    d="m512 120v-120h-121v30h69.789l-144.789 143.789-120-120-191.605 190.606 21.21 21.21 170.395-169.394 120 120 166-165v68.789z">
                                                                </path>
                                                            </g>
                                                        </svg><br />
                                                        <p class="pt-2 pl-2">BUY</p>
                                                    </a>
                                                @endif
                                            </div>



                                        </div>
                                    </div>

                                    <!-- sell tab content -->
                                    <div id="sell-content" class="tab-pane fade">
                                        <div>
                                            {{-- <div v-cloak>
                                                    <a
                                                        href="#"
                                                        class="nav-link px-2 mb-0 text-right"
                                                        data-toggle="dropdown"
                                                        aria-expanded="false"
                                                        style="color: rgb(40 167 69); font-weight: bold; font-size: 1.25rem;"
                                                    >
                                                        {{ auth()->user()->cur_sym }}@{{ formatPrice(bal) }}
                                                    </a>
                                                </div> --}}
                                            @section('hide-bal')
                                                <div class="d-flex justify-content-between align-items-center w-100 form-group pt-2 px-1 my-3"
                                                    v-cloak>
                                                    <label class="mb-0 w-100"
                                                        style="color: #fff !important; font-weight: lighter !important; opacity: .3 !important; font-size: 15px !important;">Bal</label>
                                                    <p v-cloak class="mb-0"
                                                        style="color: rgb(40 167 69); font-weight: bold; font-size: 1.25rem;">
                                                        {{ auth()->user()->cur_sym }}@{{ formatPrice(bal) }}</p>
                                                </div>
                                                <div
                                                    class="d-flex justify-content-between align-items-center w-100 form-group pt-2 px-1 mb-3">
                                                    <label class="mb-0 w-100"
                                                        style="color: #fff !important; font-weight: lighter !important; opacity: .3 !important; font-size: 15px !important;">Bonus</label>
                                                    <p class="mb-0" style="color: #fff; font-weight: bold">
                                                        {{ auth()->user()->bonus }}</p>
                                                </div>
                                            @endsection




                                            <div class="d-flex  justify-content-between  align-items-center w-100 form-group pt-2 px-1 my-3"
                                                v-cloak>
                                                <img height="" style="height: 50px; margin-right: 10px"
                                                    src="{{ $currency->image }}" />
                                                <p class="mb-0" style="font-size: 1.25rem;">
                                                    {{ $currency->name }}
                                                    <br /> <span class="text-default"
                                                        style="font-size : 1.0rem">{{ $currency->type }}</span>
                                                </p>
                                            </div>


                                            <div class="d-flex align-items-center justify-content-between form-group px-2 mt-3 mb-2 text-right"
                                                style="">
                                                <label class="mb-0 font- pr-2"
                                                    style="color: #fff;font-size: 13px">Amount</label>
                                                {{--                                                    <input type="number" v-model="volume" --}}
                                                {{--                                                        class="form-control text-right pl-0 specialBTC" --}}
                                                {{--                                                        style="border: 0; color: #fff;" --}}
                                                {{--                                                        placeholder="Enter volume in {{ $currency->sym }}" min="0" /> --}}

                                                <div class="number-input">
                                                    <button @click="decreaseVolumeupdate">
                                                        -

                                                    </button>
                                                    <input class="quantity bg-light update_input" min="0"
                                                        placeholder="0" v-model="volume" value="1"
                                                        type="number">
                                                    <button @click="increaseVolumeupdate" class="plus">
                                                        +
                                                    </button>
                                                </div>



                                                <span style="color: #fff; font-size: 13px">{{ $currency->sym }}</span>
                                            </div>
                                            <div class="range-wrap">
                                                <input type="range"data-balance="{{ auth()->user()->balance }}"
                                                    v-model="rang_amount" @click="updateRange" min="0"
                                                    max="100" step="10" class="range">
                                                <output class="bubble"></output>
                                            </div>

                                            {{-- <div class="d-flex"> --}}
                                            {{-- <div class="w-25"> --}}
                                            {{-- <button @click="decreaseVolume" class="btn w-100" style="background: #353c51; color: #fff; margin-right: 2px; font-size: 14px; padding: 0.2rem .75rem;">-</button> --}}
                                            {{-- </div> --}}
                                            {{-- <div class="w-50 text-center" v-cloak> --}}
                                            {{-- <input --}}
                                            {{-- type="number" --}}
                                            {{-- v-model="volume" --}}
                                            {{-- class="form-control text-right pl-0 specialBTC" --}}
                                            {{-- style="border: 0; color: #fff;" --}}
                                            {{-- placeholder="Enter volume in {{ $currency->sym }}" --}}
                                            {{-- min="0" --}}
                                            {{-- /> --}}
                                            {{-- </div> --}}
                                            {{-- <div class="w-25"> --}}
                                            {{-- <button @click="increaseVolume" class="btn w-100" style="background: #353c51; color: #fff; margin-left: 2px; font-size: 14px; padding: 0.2rem .75rem;">+</button> --}}
                                            {{-- </div> --}}
                                            {{-- </div> --}}

                                            @section('hide')
                                                <div class="form-group mb-2 px-1 d-flex align-items-center"
                                                    style="background: #404353; border-radius: 4px;">
                                                    <label class="mb-0 font-weight-bold pr-2"
                                                        style="color: #fff;">Amount</label>
                                                    <input type="number" v-model="volume"
                                                        class="form-control text-right pl-0 specialBTC"
                                                        style="border: 0; color: #fff; opacity: 0.6;"
                                                        placeholder="Enter volume in {{ $currency->sym }}"
                                                        min="0" />
                                                    <span style="color: #fff;">{{ $currency->sym }}</span>
                                                </div>
                                            @endsection

                                            @section('suspend')
                                                <div class="range-wrap">
                                                    <input type="range" min="0" max="300" step="5"
                                                        value="150" class="range">
                                                    <output class="bubble"></output>
                                                </div>
                                            @endsection




                                            <div class="d-flex justify-content-between align-items-center form-group px-2 mt-3 mb-2 text-right"
                                                style="border-radius: 4px; margin-top: 20px">
                                                <label class="mb-0 font-weight-bold"
                                                    style="color: #fff !important; font-weight: lighter !important; font-size: 13px !important;">Total</label>
                                                <input type="number" v-model="priceInUsd"
                                                    class="form-control text-right px-1" step="any" disabled
                                                    style="border: 0; color: #fff" placeholder="1" min="0" />
                                                <span style="color: #fff;">USD</span>
                                            </div>

                                            <hr />

                                            <div class="d-flex justify-content-end align-items-center w-100 form-group px-1 mb-1"
                                                style="font-size: 13px; justify-content: space-between !important;">
                                                <div class="">
                                                    <label class="mb-0"
                                                        style="color: #fff !important; font-weight: lighter !important">Leverage</label>
                                                </div>
                                                <div class="">
                                                    <label class="mb-0 font-weight-bold" style="color: #fff;">1 :
                                                        {{ $currency->leverage }}</label>
                                                </div>
                                            </div>


                                            <hr />

                                            <div class="  mb-3">
                                                <div class="d-flex justify-content-between"
                                                    style="justify-content: space-between !important;">
                                                    <div>
                                                        <p title="Take Profit" class="title"
                                                            style="margin-top: -7px;;color: #fff !important; font-weight: lighter !important; font-size: 15px !important;">
                                                            T p</p>
                                                    </div>
                                                    <div style="margin-top: -7px;"
                                                        class="d-flex align-content-center align-self-center text-center switch">
                                                        <input v-model="is_take_profit" type="checkbox"
                                                            id="toggle-profit" />
                                                        <label for="toggle-profit" class="ml-2"
                                                            style="width: 52px; height: 25px;">Toggle</label>
                                                    </div>
                                                    <div>
                                                        <input type="number" step="any" v-model="take_profit"
                                                            class="form-control" id="profit-value" value="100"
                                                            :disabled="!is_take_profit" style="max-width: 100px" />
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="   mb-2">
                                                <div class="d-flex justify-content-between"
                                                    style="justify-content: space-between !important;">
                                                    <div>
                                                        <p title="Stop Loss" class="title"
                                                            style="margin-top: -7px;color: #fff !important; font-weight: lighter !important ; font-size: 15px !important;">
                                                            S L</p>
                                                    </div>
                                                    <div style="margin-top: -7px;"
                                                        class="d-flex align-content-center align-self-center text-center switch">
                                                        <input v-model="is_stop_loss" type="checkbox"
                                                            id="toggle-loss" />
                                                        <label for="toggle-loss" class="ml-2"
                                                            style="width: 52px; height: 25px;">Toggle</label>
                                                    </div>
                                                    <div>
                                                        <input step="any" v-model="stop_loss" type="number"
                                                            class="form-control" id="loss-value" value="100"
                                                            :disabled="!is_stop_loss" style="max-width: 100px" />
                                                    </div>
                                                </div>

                                            </div>







                                            <div @click="can_trade ? '' : canNotTrade()"
                                                class="d-flex flex-column ml-auto justify-content-end mt-4">
                                                {{--                                                    <div v-cloak> --}}
                                                {{--                                                        <div class="d-flex justify-content-end align-content-center align-self-center text-center switch custom_disabled off_switch" --}}
                                                {{--                                                            v-if="can_trade"> --}}
                                                {{--                                                            <span style="color:#5D6069; font-size: 13px" --}}
                                                {{--                                                                class="pt-1">AutoCopy Trader </span> --}}
                                                {{--                                                            <input type="checkbox" checked disabled id="switch" /><label --}}
                                                {{--                                                                for="switch" class="ml-2">Toggle</label> --}}
                                                {{--                                                        </div> --}}

                                                {{--                                                        <div class="d-flex justify-content-end align-content-center align-self-center text-center switch custom_disabled " --}}
                                                {{--                                                            v-else> --}}
                                                {{--                                                            <span style="color:#5D6069; font-size: 13px" --}}
                                                {{--                                                                class="pt-1">AutoCopy Trader </span> --}}
                                                {{--                                                            <input type="checkbox" checked disabled id="switch" /><label --}}
                                                {{--                                                                for="switch" class="ml-2">Toggle</label> --}}
                                                {{--                                                        </div> --}}
                                                {{--                                                    </div> --}}

                                                @if ((setting('can_trade', true) && ($between == 1 && ($base = 'USD'))) || auth()->user()->allow_trade == 1)
                                                <button
                                                class="btn btn-danger d-inline d-flex justify-content-center"
                                                :class="can_trade ? '' : 'custom_disabled'"
                                                data-toggle="modal"
                                                :data-target="can_trade ? '#sellModal' : ''"
                                                style="padding:12px 0px 5px 0px; font-size: 12px;">
                                                <svg id="Capa_1" enable-background="new 0 0 512 512"
                                                    height="25" viewBox="0 0 512 512" width="25"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    style="fill: white; margin-bottom: 5px;">
                                                    <g>
                                                        <path
                                                            d="m482 330h-91v152h-30v-242h-90v242h-30v-182h-90v182h-30v-302h-91v302h-30v30h511l1-30h-30z">
                                                        </path>
                                                        <path
                                                            d="m482 218.789-166-165-120 120-174.789-173.789-21.211 21.211 196 195 120-120 144.789 143.789h-69.789v30h121v-120h-30z">
                                                        </path>
                                                    </g>
                                                </svg><br />

                                                <p class="ml-2 mt-1 font-weight-bold">SELL
                                                </p>


                                            </button>
                                                @else
                                                    <hr />
                                                    <a href="{{route('backend.notTrade')}}" class="btn btn-danger d-inline custom_disabled d-flex"
                                                        style="padding:9px 30px; font-size: 12px;">
                                                        <svg id="Capa_1" enable-background="new 0 0 512 512"
                                                            height="25" viewBox="0 0 512 512" width="25"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            style="fill: white; margin-bottom: 5px;">
                                                            <g>
                                                                <path
                                                                    d="m482 330h-91v152h-30v-242h-90v242h-30v-182h-90v182h-30v-302h-91v302h-30v30h511l1-30h-30z">
                                                                </path>
                                                                <path
                                                                    d="m482 218.789-166-165-120 120-174.789-173.789-21.211 21.211 196 195 120-120 144.789 143.789h-69.789v30h121v-120h-30z">
                                                                </path>
                                                            </g>
                                                        </svg><br />
                                                        <p class="pt-2 pl-2">SELL</p>
                                                    </a>
                                                @endif
                                            </div>
                                            <div
                                                class="d-flex justify-content-end align-items-center w-100 form-group px-1 mb-1">
                                                <div class="w-50 text-right">
                                                    <label class="mb-0 font-weight-bold"
                                                        style="color: #fff; font-size: 13px;">Fee = 4%</label>
                                                </div>
                                                {{-- <div style="width: 15%;">
                                                        <input disabled  type="text" value="4%" class="form-control pl-1 pr-0" style="color: #fff; border: 0; font-size: 13px;" />
                                                    </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- <div>
                                    <div v-cloak>
                                        <a
                                            href="#"
                                            class="nav-link px-2 mb-0 text-right"
                                            data-toggle="dropdown"
                                            aria-expanded="false"
                                            style="color: rgb(40 167 69); font-weight: bold; font-size: 1.25rem;"
                                        >
                                            {{ auth()->user()->cur_sym }}@{{ formatPrice(bal) }}
                                        </a>
                                    </div>


                                    {{-- <p class="mb-20 mt-3 text-center text-capitalize">Trade {{ $currency->name }}</p> --}}
                                    {{-- <div class="form-group pt-2 px-1 mb-2" style="background: #404353; border-radius: 4px;"> --}}
                                    {{-- <label class="mb-0 font-weight-bold" style="color: #fff;">Volume</label> --}}
                                    {{-- <input --}}
                                    {{-- type="number" --}}
                                    {{-- v-model="priceInCoin" --}}
                                    {{-- class="form-control pl-0" --}}
                                    {{-- step="any" --}}
                                    {{-- disabled --}}
                                    {{-- style="border: 0; color: #fff;" --}}
                                    {{-- placeholder="1" --}}
                                    {{-- min="0" --}}
                                    {{-- /> --}}
                                    {{-- </div> --}}


                                    <div class="d-flex justify-content-between align-items-center w-100 form-group pt-2 px-1 mb-3">
                                        <label class="mb-0 w-100 font-weight-bold">Bonus</label>
                                        <p class="mb-0" style="color: #fff; font-weight: bold">0.00</p>
                                    </div>
                                    <div class="form-group mb-2 px-1" style="background: #404353; border-radius: 4px;">
                                        <label class="mb-0 font-weight-bold" style="color: #fff;">Volume (<span style="color: #fff;">{{ $currency->sym }}</span>)</label>
                                        <input
                                            type="number"
                                            v-model="volume"
                                            class="form-control pl-0"
                                            style="border: 0; color: #fff;"
                                            placeholder="Enter volume in {{ $currency->sym }}"
                                            min="0"
                                        />
                                    </div>

                                    <div class="d-flex">
                                        <div class="w-50">
                                            <button @click="decreaseVolume" class="btn w-100" style="background: #353c51; color: #fff; margin-right: 2px; font-size: 14px; padding: 0.2rem .75rem;">-</button>
                                        </div>
                                        <div class="w-50">
                                            <button @click="increaseVolume" class="btn w-100" style="background: #353c51; color: #fff; margin-left: 2px; font-size: 14px; padding: 0.2rem .75rem;">+</button>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center w-100 form-group pt-2 px-1 mb-1">
                                        <label class="mb-0 w-100 font-weight-bold" style="color: #fff;">Leverage = </label>
                                        <div>
                                            <input disabled  type="text" value="{{ $currency->leverage }}X" class="form-control pl-0" style="color: #fff; border: 0;" />
                                        </div>
                                    </div>

                                    <div class="form-group pt-2 px-1 mb-2">
                                        <label class="mb-0 font-weight-bold">Amount in <span style="color: #fff;">USD</span></label>
                                        <input
                                            type="number"
                                            v-model="priceInUsd"
                                            class="form-control pl-0"
                                            step="any"
                                            disabled
                                            style="border: 0; color: #fff;"
                                            placeholder="1"
                                            min="0"
                                        />
                                    </div>

                                    <div @click="can_trade ? '' : canNotTrade()" class="d-flex flex-column ml-auto justify-content-end">

                                        @if (setting('can_trade', true))
<button
                                                class="btn btn-success" :class="can_trade ? '' : 'custom_disabled'"
                                                style="padding:9px 30px;; margin-bottom: 10px; font-size: 12px;"
                                                :data-target="can_trade ? '#buyModal' : ''"
                                                data-toggle="modal"
                                            >
                                                <svg id="Capa_1" enable-background="new 0 0 512 512" height="25" viewBox="0 0 512 512" width="25" xmlns="http://www.w3.org/2000/svg" style="fill: white; margin-bottom: 5px;">
                                                    <g>
                                                        <path d="m512 482h-30v-302h-91v302h-30v-182h-90v182h-30v-242h-90v242h-30v-152h-91v152h-30v30h512z"></path>
                                                        <path d="m512 120v-120h-121v30h69.789l-144.789 143.789-120-120-191.605 190.606 21.21 21.21 170.395-169.394 120 120 166-165v68.789z"></path>
                                                    </g>
                                                </svg><br/>
                                                BUY
                                                <br />

                                            </button>
                                            <button
                                                class="btn btn-danger d-inline" :class="can_trade ? '' : 'custom_disabled'"
                                                data-toggle="modal"
                                                :data-target="can_trade ? '#sellModal' : ''"
                                                style="padding:9px 30px; margin-bottom: 10px; font-size: 12px;"
                                            >
                                                <svg id="Capa_1" enable-background="new 0 0 512 512" height="25" viewBox="0 0 512 512" width="25" xmlns="http://www.w3.org/2000/svg" style="fill: white; margin-bottom: 5px;">
                                                    <g>
                                                        <path d="m482 330h-91v152h-30v-242h-90v242h-30v-182h-90v182h-30v-302h-91v302h-30v30h511l1-30h-30z"></path>
                                                        <path d="m482 218.789-166-165-120 120-174.789-173.789-21.211 21.211 196 195 120-120 144.789 143.789h-69.789v30h121v-120h-30z"></path>
                                                    </g>
                                                </svg><br/>
                                                SELL
                                            </button>
@else
<button
                                                                                            class="btn btn-success custom_disabled"
                                                                                            style="padding:9px 30px;; margin-bottom: 10px; font-size: 12px;">
                                                                                            <svg id="Capa_1" enable-background="new 0 0 512 512" height="25" viewBox="0 0 512 512" width="25" xmlns="http://www.w3.org/2000/svg" style="fill: white; margin-bottom: 5px;">
                                                                                                <g>
                                                                                                    <path d="m512 482h-30v-302h-91v302h-30v-182h-90v182h-30v-242h-90v242h-30v-152h-91v152h-30v30h512z"></path>
                                                                                                    <path d="m512 120v-120h-121v30h69.789l-144.789 143.789-120-120-191.605 190.606 21.21 21.21 170.395-169.394 120 120 166-165v68.789z"></path>
                                                                                                </g>
                                                                                            </svg><br/>
                                                                                            CANT TRADE
                                                                                        </button>
                                                                                        <button
                                                                                            class="btn btn-danger d-inline custom_disabled"
                                                                                            style="padding:9px 30px; margin-bottom: 10px; font-size: 12px;"
                                                                                        >
                                                                                            <svg id="Capa_1" enable-background="new 0 0 512 512" height="25" viewBox="0 0 512 512" width="25" xmlns="http://www.w3.org/2000/svg" style="fill: white; margin-bottom: 5px;">
                                                                                                <g>
                                                                                                    <path d="m482 330h-91v152h-30v-242h-90v242h-30v-182h-90v182h-30v-302h-91v302h-30v30h511l1-30h-30z"></path>
                                                                                                    <path d="m482 218.789-166-165-120 120-174.789-173.789-21.211 21.211 196 195 120-120 144.789 143.789h-69.789v30h121v-120h-30z"></path>
                                                                                                </g>
                                                                                            </svg><br/>
                                                                                            CANT TRADE
                                                                                        </button>
@endif


                                        <div v-cloak>
                                            <div class="d-flex align-content-center align-self-center text-center switch custom_disabled off_switch" v-if="can_trade">
                                                <span style="color:#fff; font-size: 10px" class="pt-1">AutoCopy <raderADER </span>
                                                <input type="checkbox" checked disabled id="switch" /><label for="switch" class="ml-2">Toggle</label>
                                            </div>

                                            <div class="d-flex align-content-center align-self-center text-center switch custom_disabled " v-else>
                                                <span style="color:#fff; font-size: 10px" class="pt-1">AutoCopy <raderADER </span>
                                                <input type="checkbox" checked disabled id="switch" /><label for="switch" class="ml-2">Toggle</label>
                                            </div>
                                        </div>

                                    </div>
                                </div> -->
                        </div>
                    </div>
                </div>

            </div>


            <!-- buy and sell mobile -->
            <div class="d-md-none d-lg-none mt-2" @click="can_trade ? '' : canNotTrade()">
                <div class="row buy-row align-items-center text-center pt-3 pb-4 px-4">
                    <button class="btn btn-success d-inline" :class="can_trade ? '' : 'custom_disabled'"
                        :data-target="can_trade ? '#buyForm' : ''"
                        @if (setting('can_trade', true) == 1) data-toggle="modal" data-target="#buyForm" @endif
                        style="margin-right: 10px; padding: 5px 36px; margin-bottom: 5px; font-size: 16px;">
                        <svg id="Capa_1" enable-background="new 0 0 512 512" height="25" viewBox="0 0 512 512"
                            width="25" xmlns="http://www.w3.org/2000/svg"
                            style="fill: white; margin-bottom: 5px;">
                            <g>
                                <path
                                    d="m512 482h-30v-302h-91v302h-30v-182h-90v182h-30v-242h-90v242h-30v-152h-91v152h-30v30h512z">
                                </path>
                                <path
                                    d="m512 120v-120h-121v30h69.789l-144.789 143.789-120-120-191.605 190.606 21.21 21.21 170.395-169.394 120 120 166-165v68.789z">
                                </path>
                            </g>
                        </svg><br />
                        BUY
                    </button>
                    <button class="btn btn-danger d-inline"
                        @if (setting('can_trade', true) == 1) {{--                   onclick="show_sel_form($(this))" --}}
                    data-toggle="modal" data-target="#sellForm" @endif
                        :data-target="can_trade ? '#sellForm' : 'sellForm'"
                        :class="can_trade ? '' : 'custom_disabled'"
                        style="margin-right: 10px; padding: 5px 36px; margin-bottom: 5px; font-size: 16px;">

                        <svg id="Capa_1" enable-background="new 0 0 512 512" height="25" viewBox="0 0 512 512"
                            width="25" xmlns="http://www.w3.org/2000/svg"
                            style="fill: white; margin-bottom: 5px;">
                            <g>
                                <path
                                    d="m482 330h-91v152h-30v-242h-90v242h-30v-182h-90v182h-30v-302h-91v302h-30v30h511l1-30h-30z">
                                </path>
                                <path
                                    d="m482 218.789-166-165-120 120-174.789-173.789-21.211 21.211 196 195 120-120 144.789 143.789h-69.789v30h121v-120h-30z">
                                </path>
                            </g>
                        </svg><br />
                        SELL
                    </button>

                    <!-- <div class="d-flex flex-column switch off_switch" @click="turnTrader" v-if="can_trade">
                        <span style="color:#5D6069; font-size: 13px" class="pt-1">AutoCopy Trader </span>
                        <input type="checkbox" checked disabled id="switch" /><label for="switch">Toggle</label>
                    </div>
                    <div class="d-flex flex-column switch " @click="turnTrader" v-else>
                        <span style="color:#5D6069; font-size: 13px" class="pt-1">AutoCopy Trader </span>
                        <input type="checkbox" checked disabled id="switch" /><label for="switch">Toggle</label>
                    </div> -->
                </div>

                <!-- Buy modal for mobile -->

            </div>

            <div class="modal fade" id="buyForm" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle1"> Trade {{ $currency->name }}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-body" style="margin-top: 10px; padding : 5px!important">

                                    <div class="mx-3">




                                        <div class="d-flex align-items-center form-group px-2 mt-3 mb-2 text-right"
                                            style="justify-content: space-between !important; ">
                                            <label class="mb-0 font- pr-2"
                                                style="color: #fff;font-size: 16px">Amount</label>

                                            <div class="number-input">
                                                <button @click="decreaseVolumeupdate">
                                                    -

                                                </button>
                                                <input class="quantity bg-light" min="0" placeholder="0"
                                                    v-model="volume" value="1" type="number">



                                                <button @click="increaseVolumeupdate" class="plus">
                                                    +
                                                </button>
                                            </div>



                                            <span style="color: #fff; font-size: 13px">{{ $currency->sym }}</span>
                                        </div>


                                        <div class="form-group align-items-center pt-2 d-flex px-1 mb-2"
                                            style="justify-content: space-between !important;">
                                            <label class="mb-0 " style="color: #fff;font-size: 16px">Total
                                            </label>
                                            <div class="text-center">
                                                <input type="number" v-model="priceInUsd"
                                                    class="form-control text-center pl-0" step="any" disabled
                                                    style="border: 0; color: #fff;" placeholder="1" min="0" />
                                            </div>

                                            <label>USD</label>
                                        </div>

                                        <div class="form-group d-flex pt-2 px-1 mb-2 align-items-center "
                                            style="justify-content: space-between">
                                            <label class="mb-0 " style="color: #fff;font-size: 16px">Leverage</label>
                                            <div></div>
                                            <div>
                                                <input disabled type="text" value="{{ $currency->leverage }}X"
                                                    class="form-control pl-0 text-right"
                                                    style="color: #fff; border: 0;" />
                                            </div>
                                        </div>








                                        <div class="d-flex justify-content-between mb-3">
                                            <div class="d-flex" style="justify-content: space-between;width: 100%">
                                                <div>
                                                    <b class="title">Take Profit</b>
                                                </div>
                                                <div>
                                                    <div
                                                        class="d-flex align-content-center align-self-center text-center switch">
                                                        <input v-model="is_take_profit" type="checkbox"
                                                            id="toggle-profit" />
                                                        <label for="toggle-profit" class="ml-2"
                                                            style="width: 52px; height: 25px;">Toggle</label>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <div style="margin: 10px">
                                            <input type="number" step="any" v-model="take_profit"
                                                class="form-control" id="profit-value" value="100" disabled
                                                style="" />
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="d-flex" style="justify-content: space-between;width: 100%">
                                                <div>
                                                    <p class="title">Stop Loss</p>
                                                </div>
                                                <div>
                                                    <div
                                                        class="d-flex align-content-center align-self-center text-center switch">
                                                        <input v-model="is_stop_loss" type="checkbox"
                                                            id="toggle-loss" />
                                                        <label for="toggle-loss" class="ml-2"
                                                            style="width: 52px; height: 25px;">Toggle</label>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <div style="margin: 10px">
                                            <input step="any" v-model="stop_loss" type="number"
                                                class="form-control" id="loss-value" value="100" disabled />
                                        </div>
                                        <div class="d-flex justify-content-between mb-3">
                                            <div>
                                                <p class="title">Commission</p>
                                            </div>
                                            <div class="text-right"><b>{{ $currency->com }}%</b></div>
                                        </div>

                                        <div class="text-center">
                                            <button style="width: 100%" class="btn btn-success ptn-lg px-4"
                                                @click="playNow('buy')">Buy</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sell modal for mobile -->
            <div class="modal fade" id="sellForm" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle1">Trade {{ $currency->name }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-body" style="margin-top: 10px; padding : 5px!important">

                                    <div class="mx-3">




                                        <div class="d-flex align-items-center form-group px-2 mt-3 mb-2 text-right"
                                            style="justify-content: space-between !important; ">
                                            <label class="mb-0 font- pr-2"
                                                style="color: #fff;font-size: 16px">Amount</label>

                                            <div class="number-input">
                                                <button @click="decreaseVolumeupdate">
                                                    -

                                                </button>
                                                <input class="quantity bg-light" min="0" placeholder="0"
                                                    v-model="volume" value="1" type="number">



                                                <button @click="increaseVolumeupdate" class="plus">
                                                    +
                                                </button>
                                            </div>



                                            <span style="color: #fff; font-size: 13px">{{ $currency->sym }}</span>
                                        </div>



                                        <div class="form-group align-items-center pt-2 d-flex px-1 mb-2"
                                            style="justify-content: space-between !important;">
                                            <label class="mb-0 " style="color: #fff;font-size: 16px">Total
                                            </label>
                                            <div class="text-center">
                                                <input type="number" v-model="priceInUsd"
                                                    class="form-control text-center pl-0" step="any" disabled
                                                    style="border: 0; color: #fff;" placeholder="1" min="0" />
                                            </div>

                                            <label>USD</label>
                                        </div>

                                        <div class="form-group d-flex pt-2 px-1 mb-2 align-items-center "
                                            style="justify-content: space-between">
                                            <label class="mb-0 " style="color: #fff;font-size: 16px">Leverage</label>
                                            <div></div>
                                            <div>
                                                <input disabled type="text" value="{{ $currency->leverage }}X"
                                                    class="form-control pl-0 text-right"
                                                    style="color: #fff; border: 0;" />
                                            </div>
                                        </div>



                                        {{-- //confirming --}}
                                        <div class="d-flex justify-content-between mb-3">
                                            <div class="d-flex" style="width: 100%;justify-content: space-between">
                                                <div>
                                                    <p class="title">Take Profit</p>
                                                </div>
                                                <div
                                                    class="d-flex align-content-center align-self-center text-center switch">
                                                    <input v-model="is_take_profit" type="checkbox"
                                                        id="toggle-profit" />
                                                    <label for="toggle-profit" class="ml-2"
                                                        style="width: 52px; height: 25px;">Toggle</label>
                                                </div>
                                            </div>

                                        </div>

                                        <div style="margin: 10px">
                                            <input type="number" step="any" v-model="take_profit"
                                                class="form-control" id="profit-value" value="100"
                                                style="" />
                                        </div>

                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="d-flex" style="width: 100%;justify-content: space-between">
                                                <div>
                                                    <p class="title">Stop Loss</p>
                                                </div>
                                                <div
                                                    class="d-flex align-content-center align-self-center text-center switch">
                                                    <input v-model="is_stop_loss" type="checkbox" id="toggle-loss" />
                                                    <label for="toggle-loss" class="ml-2"
                                                        style="width: 52px; height: 25px;">Toggle</label>
                                                </div>
                                            </div>

                                        </div>

                                        <div style="margin: 10px">
                                            <input step="any" v-model="stop_loss" type="number"
                                                class="form-control" id="loss-value" value="100"
                                                style="" />
                                        </div>

                                        <div class="d-flex justify-content-between mb-3">
                                            <div>
                                                <p class="title">Commission</p>
                                            </div>
                                            <div class="text-right">
                                                <p>{{ $currency->com }}%</p>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button style="width: 100%" class="btn btn-danger btn-lg px-4"
                                                @click="playNow('sell')">
                                                sell
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- new buy modal -->



            <div ref="conf" class="modal fade" id="buyModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document" style="width: 30%;">
                    <div class="modal-content" style="border: 2px solid #fff;">
                        <div class="modal-header border-0">
                            <h6 class="text-center text-white">CONFIRMATION</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="confirmation-wrapper">
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <p class="title">Unit Price</p>
                                    </div>
                                    <div>
                                        <p>$@{{ coinPrice }}</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <p class="title">{{ $currency->sym }} {{ $currency->base }}
                                            Quantity</p>
                                    </div>
                                    <div>
                                        <p>@{{ volume }}</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <p class="title">Leverage</p>
                                    </div>
                                    <div>
                                        <p>{{ $currency->leverage }}X</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <p class="title">Margin Required</p>
                                    </div>
                                    <div>
                                        <p>@{{ priceInUsd }}</p>
                                    </div>
                                </div>

                                @section('hide')
                                    <div class="d-flex justify-content-between mb-3">
                                        <div class="d-flex">
                                            <div>
                                                <p class="title">Take Profit</p>
                                            </div>
                                            <div class="d-flex align-content-center align-self-center text-center switch">
                                                <input v-model="is_take_profit" type="checkbox" id="toggle-profit" />
                                                <label for="toggle-profit" class="ml-2"
                                                    style="width: 52px; height: 25px;">Toggle</label>
                                            </div>
                                        </div>
                                        <div>
                                            <input :disabled="!is_take_profit" type="number" step="any"
                                                v-model="take_profit" class="form-control" id="profit-value"
                                                value="100" style="width: 75px;" />
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex">
                                            <div>
                                                <p class="title">Stop Loss</p>
                                            </div>
                                            <div class="d-flex align-content-center align-self-center text-center switch">
                                                <input v-model="is_stop_loss" type="checkbox" id="toggle-loss" />
                                                <label for="toggle-loss" class="ml-2"
                                                    style="width: 52px; height: 25px;">Toggle</label>
                                            </div>
                                        </div>
                                        <div>
                                            <input :disabled="!is_stop_loss" step="any" v-model="stop_loss"
                                                type="number" class="form-control" id="loss-value" value="100"
                                                style="width: 75px;" />
                                        </div>
                                    </div>
                                @endsection

                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <p class="title">Commission</p>
                                    </div>
                                    <div class="text-right">
                                        <p>{{ $currency->com }}%</p>
                                    </div>
                                </div>
                                <button @click="playNow('buy')" class="btn btn-success w-100 py-2"
                                    style="font-size: 12px;">CONFIRM BUYING</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- new sell modal -->

            <div ref="confsell" class="modal fade" id="sellModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document" style="width: 30%;">
                    <div class="modal-content" style="border: 2px solid #fff;">
                        <div class="modal-header border-0">
                            <h6 class="text-center text-white">CONFIRMATION</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="confirmation-wrapper">
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <p class="title">Unit Price</p>
                                    </div>
                                    <div>
                                        <p>$@{{ coinPrice }}</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <p class="title">{{ $currency->sym }} {{ $currency->base }}
                                            Quantity</p>
                                    </div>
                                    <div>
                                        <p>@{{ volume }}</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <p class="title">Leverage</p>
                                    </div>
                                    <div>
                                        <p>{{ $currency->leverage }}X</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <p class="title">Margin Required</p>
                                    </div>
                                    <div>
                                        <p>@{{ priceInUsd }}</p>
                                    </div>
                                </div>
                                @section('hide')
                                    <div class="d-flex justify-content-between mb-3">
                                        <div class="d-flex">
                                            <div>
                                                <p class="title">Take Profit</p>
                                            </div>
                                            <div class="d-flex align-content-center align-self-center text-center switch">
                                                <input v-model="is_take_profit" type="checkbox" id="toggle-profit" />
                                                <label for="toggle-profit" class="ml-2"
                                                    style="width: 52px; height: 25px;">Toggle</label>
                                            </div>
                                        </div>
                                        <div>
                                            <input type="number" step="any" v-model="take_profit"
                                                class="form-control" id="profit-value" value="100"
                                                :disabled="!is_take_profit" style="width: 75px;" />
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex">
                                            <div>
                                                <p class="title">Stop Loss</p>
                                            </div>
                                            <div class="d-flex align-content-center align-self-center text-center switch">
                                                <input v-model="is_stop_loss" type="checkbox" id="toggle-loss" />
                                                <label for="toggle-loss" class="ml-2"
                                                    style="width: 52px; height: 25px;">Toggle</label>
                                            </div>
                                        </div>
                                        <div>
                                            <input step="any" v-model="stop_loss" type="number"
                                                class="form-control" id="loss-value" value="100"
                                                :disabled="!is_stop_loss" style="width: 75px;" />
                                        </div>
                                    </div>
                                @endsection
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <p class="title">Commission</p>
                                    </div>
                                    <div class="text-right">
                                        <p>{{ $currency->com }}%</p>
                                    </div>
                                </div>
                                <button @click="playNow('sell')" class="btn btn-danger w-100 py-2"
                                    style="font-size: 12px;">CONFIRM SELLING</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div>


            <div class="d-none" id="newsellModal">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle1">Trade {{ $currency->name }}</h5>

                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body" style="margin-top: 10px; padding : 5px!important">

                            <div class="mx-3">

                                <div class="form-group mb-2 px-1" style="background: #404353; border-radius: 4px;">
                                    <label class="mb-0 font-weight-bold" style="color: #fff;">Volume
                                        ({{ $currency->sym }})</label>
                                    <input type="number" v-model="volume" class="form-control pl-0"
                                        style="border: 0; color: #fff;"
                                        placeholder="Enter volume in {{ $currency->sym }}" min="0" />
                                </div>

                                <div class="form-group pt-2 px-1 mb-2">
                                    <label class="mb-0 font-weight-bold" style="color: #fff;">Leverage</label>
                                    <div>
                                        <input disabled type="text" value="{{ $currency->leverage }}X"
                                            class="form-control pl-0" style="color: #fff; border: 0;" />
                                    </div>
                                </div>

                                <div class="form-group pt-2 px-1 mb-2"
                                    style="background: #404353; border-radius: 4px;">
                                    <label class="mb-0 font-weight-bold" style="color: #fff;">Amount in USD</label>
                                    <input type="number" v-model="priceInUsd" class="form-control pl-0"
                                        step="any" disabled style="border: 0; color: #fff;" placeholder="1"
                                        min="0" />
                                </div>


                                {{-- //confirming --}}
                                <div class="d-flex justify-content-between mb-3">
                                    <div class="d-flex">
                                        <div>
                                            <p class="title">Take Profit</p>
                                        </div>
                                        <div class="d-flex align-content-center align-self-center text-center switch">
                                            <input v-model="is_take_profit" type="checkbox" id="toggle-profit" />
                                            <label for="toggle-profit" class="ml-2"
                                                style="width: 52px; height: 25px;">Toggle</label>
                                        </div>
                                    </div>
                                    <div>
                                        <input type="number" step="any" v-model="take_profit"
                                            class="form-control" id="profit-value" value="100"
                                            style="width: 75px;" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="d-flex">
                                        <div>
                                            <p class="title">Stop Loss</p>
                                        </div>
                                        <div class="d-flex align-content-center align-self-center text-center switch">
                                            <input v-model="is_stop_loss" type="checkbox" id="toggle-loss" />
                                            <label for="toggle-loss" class="ml-2"
                                                style="width: 52px; height: 25px;">Toggle</label>
                                        </div>
                                    </div>
                                    <div>
                                        <input step="any" v-model="stop_loss" type="number"
                                            class="form-control" id="loss-value" value="100"
                                            style="width: 75px;" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <p class="title">Commission</p>
                                    </div>
                                    <div class="text-right">
                                        <p>{{ $currency->com }}%</p>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button class="btn btn-danger px-4" @click="playNow('sell')">Sell</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-none" id="buyModalnew" style="border: 2px solid #fff;">


                <div class="modal-body">
                    <div class="card">
                        <div class="card-body" style="margin-top: 10px; padding : 5px!important">

                            <div class="mx-3">

                                <div class="form-group mb-2 px-1" style="background: #404353; border-radius: 4px;">
                                    <label class="mb-0 font-weight-bold" style="color: #fff;">Volume
                                        ({{ $currency->sym }})</label>
                                    <br>
                                    <input type="number" v-model="volume" class="form-control pl-0"
                                        style="border: 0; color: #fff;"
                                        placeholder="Enter volume in {{ $currency->sym }}" min="0" />
                                </div>





                                <div class="d-flex justify-content-between mb-3">
                                    <div class="d-flex">
                                        <div>
                                            <p class="title">Take Profit</p>
                                        </div>
                                        <div class="d-flex align-content-center align-self-center text-center switch">
                                            <input v-model="is_take_profit" type="checkbox" id="toggle-profit" />
                                            <label for="toggle-profit" class="ml-2"
                                                style="width: 52px; height: 25px;">Toggle</label>
                                        </div>
                                    </div>
                                    <div>
                                        <input type="number" step="any" v-model="take_profit"
                                            class="form-control" id="profit-value" value="100" disabled
                                            style="width: 75px;" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="d-flex">
                                        <div>
                                            <p class="title">Stop Loss</p>
                                        </div>
                                        <div class="d-flex align-content-center align-self-center text-center switch">
                                            <input v-model="is_stop_loss" type="checkbox" id="toggle-loss" />
                                            <label for="toggle-loss" class="ml-2"
                                                style="width: 52px; height: 25px;">Toggle</label>
                                        </div>
                                    </div>
                                    <div>
                                        <input step="any" v-model="stop_loss" type="number"
                                            class="form-control" id="loss-value" value="100" disabled
                                            style="width: 75px;" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <p class="title">Commission</p>
                                    </div>
                                    <div class="text-right">
                                        <p>{{ $currency->com }}%</p>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button class="btn btn-success px-4" @click="playNow('buy')">Buy</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>










        </div>



        <trade_footer plan="{{ auth()->user()->plan }}" :bonus="{{ auth()->user()->bonus }}"
            :bal="bal" :equity="equity" :margin="margin" :free_margin="freeMargin"
            :pnl="pnl"></trade_footer>
    </div>

</trade_station>
@endsection

@section('styles')
{{-- <link rel="stylesheet" href="/back/vendor/waves/waves.min.css"> --}}
<style>
    table {
        border-collapse: collapse;
        width: 100%;
        padding: 0;
        margin: 0;
        font-size: 12px;
    }

    th,
    td {
        text-align: center;
        padding: 2px;
    }

    td:first {
        text-align: left !important;
    }

    th {
        font-weight: bold;
    }

    tr {
        width: 120%;
        border-top: 1.5px solid #1b1a2c
    }

    tr:not(thead>tr:first-child):hover {
        background: #1b1a2c
    }

    .sidebar-card .nav-item .buy {
        background: #00d589a3;
        color: #fff;
    }

    .sidebar-card .nav-item .sell {
        background: #DB4931;
        color: #fff;
    }
</style>
@endsection

@section('styles')
<!-- <link rel="stylesheet" href="/back/vendor/waves/waves.min.css"> -->
<!-- slider styles starts -->
<style>
    @import url(https://fonts.googleapis.com/css?family=Concert+One);
    @import url(https://fonts.googleapis.com/css?family=Advent+Pro:300);

    html,
    body {
        height: 100%;
    }

    body {
        text-align: center;
        background: #d5d5d5;
        /* Old browsers */
        background: -moz-linear-gradient(top, #d5d5d5 0%, #f3f3f3 80%, #feffff 100%);
        /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #d5d5d5), color-stop(80%, #f3f3f3), color-stop(100%, #feffff));
        /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top, #d5d5d5 0%, #f3f3f3 80%, #feffff 100%);
        /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top, #d5d5d5 0%, #f3f3f3 80%, #feffff 100%);
        /* Opera 11.10+ */
        background: -ms-linear-gradient(top, #d5d5d5 0%, #f3f3f3 80%, #feffff 100%);
        /* IE10+ */
        background: linear-gradient(to bottom, #d5d5d5 0%, #f3f3f3 80%, #feffff 100%);
        /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#d5d5d5', endColorstr='#feffff', GradientType=0);
        /* IE6-9 */
    }

    label[for=range] {
        /* position: absolute;
                top: 50%;
                left: 50%;
                margin-left: -175px;
                margin-top: -32px; */
        /* height: 49px; */
        /* padding-top: 6px; */
        /* width: 350px; */
        /* padding-left: 13px; */
        -webkit-transform: skew(-62deg);
        /* overflow: hidden; */
        padding-bottom: 10px;
    }

    label[for=range]:after {
        content: "";
        position: absolute;
        bottom: 11px;
        left: 69px;
        height: 9px;
        width: 278px;
        box-shadow: 0px 3px 10px -3px rgba(0, 0, 0, 0.51);
        -webkit-transform: skew(62deg);
    }

    input[type=range] {
        -webkit-appearance: none;
        /* background-color: transparent; */
        width: 134px;
        height: 38px;
        /* padding-top:10px; */
        overflow: hidden;
        margin: 0;
        margin-left: -5px;
        margin-right: 25px;
        transform-style: preserve-3d;
        perspective: 300;
        transform-origin: 50% 50% 300px;
        perspective-origin: 50% -121%;
        transform: skew(62deg);
    }

    input[type=range]:focus {
        outline: none;
    }

    input[type="range"]::-webkit-slider-thumb {
        position: relative;
        -webkit-appearance: none;
        cursor: pointer;
        background-color: transparent;
        width: 30px;
        height: 18px;
        box-shadow: 1px 5px 10px -1px rgba(0, 0, 0, 0.2),
            -25px 0 0 10px rgba(90, 184, 6, 0.5),
            -75px 0 0 10px rgba(90, 184, 6, 0.5),
            -125px 0 0 10px rgba(90, 184, 6, 0.5),
            -175px 0 0 10px rgba(90, 184, 6, 0.5),
            -225px 0 0 10px rgba(90, 184, 6, 0.5),
            -275px 0 0 10px rgba(90, 184, 6, 0.5),
            -325px 0 0 10px rgba(90, 184, 6, 0.5);
        z-index: 2;
    }

    input[type="range"]::-webkit-slider-thumb:after {
        content: "";
        position: absolute;
        z-index: 1;
        left: -285px;
        top: -28px;
        width: 300px;
        height: 140px;
        background: #9FE472;
        transform: rotateX(90deg);
        transform-origin: 0 0px 0;
        transform: rotateX(90deg) translateY(-140px) translateZ(-18px);
    }

    input[type="range"]::-webkit-slider-thumb:before {
        content: "< >";
        font-family: 'Concert One', cursive;
        position: absolute;
        background: #eaebe5;
        background: -moz-linear-gradient(top, #eaebe5 0%, #dcdedd 100%);
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #eaebe5), color-stop(100%, #dcdedd));
        background: -webkit-linear-gradient(top, #eaebe5 0%, #dcdedd 100%);
        background: -o-linear-gradient(top, #eaebe5 0%, #dcdedd 100%);
        background: -ms-linear-gradient(top, #eaebe5 0%, #dcdedd 100%);
        background: linear-gradient(to bottom, #eaebe5 0%, #dcdedd 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#eaebe5', endColorstr='#dcdedd', GradientType=0);
        top: -2px;
        left: 0px;
        border-radius: 2px;
        color: #5a5a5a;
        text-shadow: 0 1px 0 white;
        height: 22px;
        width: 32px;
        border-top: 1px solid white;
        border-left: 1px solid white;
        box-sizing: border-box;
        text-align: center;
        line-height: 19px;
        font-size: 17px;
    }

    input[type="range"]::-webkit-slider-runnable-track:before {
        content: "";
        position: absolute;
        height: 38px;
        width: 283px;
        background: #e8e8e8;
        background: -moz-linear-gradient(top, #dfdfdf 0%, #d8d8d8 100%);
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #dfdfdf), color-stop(100%, #d8d8d8));
        background: -webkit-linear-gradient(top, #dfdfdf 0%, #d8d8d8 100%);
        background: -o-linear-gradient(top, #dfdfdf 0%, #d8d8d8 100%);
        background: -ms-linear-gradient(top, #dfdfdf 0%, #d8d8d8 100%);
        background: linear-gradient(to bottom, #dfdfdf 0%, #d8d8d8 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfdfdf', endColorstr='#d8d8d8', GradientType=0);
        bottom: 0;
        left: 0;
    }

    input[type="range"]::-webkit-slider-runnable-track:after {
        content: "";
        position: absolute;
        height: 10px;
        width: 270px;
        background: rgb(247, 247, 247);
        top: 0;
        right: 26px;
        transform: skew(62deg);
    }

    input[type=range]:before {
        content: "";
        position: absolute;
        background: rgb(190, 190, 190);
        box-shadow: 0 1px 0 rgb(235, 235, 235);
        width: 283px;
        left: 0;
        height: 1px;
        top: 19px;
        z-index: 1;
    }

    input[type=range]:after {
        content: "";
        position: absolute;
        background: rgb(190, 190, 190);
        width: 7px;
        left: 3px;
        border-radius: 50%;
        height: 6px;
        top: 16px;
        z-index: 1;
        box-shadow: 30px 0 0 rgb(190, 190, 190),
            60px 0 0 rgb(190, 190, 190),
            90px 0 0 rgb(190, 190, 190),
            120px 0 0 rgb(190, 190, 190),
            150px 0 0 rgb(190, 190, 190),
            180px 0 0 rgb(190, 190, 190),
            210px 0 0 rgb(190, 190, 190),
            240px 0 0 rgb(190, 190, 190),
            270px 0 0 rgb(190, 190, 190),
            60px 1px 0 rgb(235, 235, 235),
            90px 1px 0 rgb(235, 235, 235),
            120px 1px 0 rgb(255, 255, 255),
            150px 1px 0 rgb(235, 235, 235),
            180px 1px 0 rgb(235, 235, 235),
            210px 1px 0 rgb(235, 235, 235),
            240px 1px 0 rgb(235, 235, 235),
            270px 1px 0 rgb(235, 235, 235);
    }

    .budget {
        position: absolute;
        top: 50%;
        left: 0;
        margin-top: -100px;
        text-align: center;
        width: 100%;
        font-size: 2em;
        font-family: 'Advent Pro', sans-serif;
        color: rgb(58, 58, 58);
    }

    /* .output {
                position: absolute;
                bottom: 50%;
                left: 0;
                margin-bottom: -100px;
                text-align: center;
                width: 100%;
                font-size: 2em;
                font-family: 'Advent Pro', sans-serif;
                color: rgba(132, 206, 66, 1);
            } */
</style>
<!-- slider styles starts -->
@endsection

@section('js')
<script>
    function show_sel_form(elment) {

        $('#newsellModal').removeClass('d-none')
        $('#buyModalnew').addClass('d-none')
    }

    function show_buy_form(element) {
        $('#buyModalnew').removeClass('d-none')
        $('#newsellModal').addClass('d-none')
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        $('.washlist').on('click', '.removeFromWatchlist', function(e) {
            var removeButton = $(this);
            var currency_id = removeButton.data('currency-id');
            // alert(currency_id);
            removeButton.toggleClass('fa-trash fa-spinner fa-spin');

            $.ajax({
                url: "{{ route('backend.watchlist.destroy') }}",
                dataType: "json",
                type: "POST",
                async: true,
                data: {
                    "currency_pair_id": currency_id
                },
                success: function(data) {
                    removeButton.closest('tr').fadeOut(500);
                    $('#addToWatchlistButtonLocation' + currency_id).html(
                        `<button class="btn btn-primary btn-sm addToWatchlist" data-currency-id="${currency_id}">Add to watchlist <i class="fa fa-spinner fa-spin fa-fw" style="display: none;"></i></button>`
                    );
                },
                error: function(xhr, exception) {
                    console.log(xhr);
                    removeButton.removeClass('fa-spin');
                    removeButton.toggleClass('fa-spinner fa-trash');
                    var msg = "";

                    if (xhr.status === 0) {
                        msg = "Not connect.\n Verify Network." + xhr.responseText;
                    } else if (xhr.status == 404) {
                        msg = "Requested page not found. [404]" + xhr.responseText;
                    } else if (xhr.status == 500) {
                        var res = $.parseJSON(xhr.responseText);
                        msg = "Internal Server Error [500]." + res.message;
                    } else if (xhr.status == 422) {
                        var res = $.parseJSON(xhr.responseText);
                        console.log(res.errors);
                        $.each(res.errors, function(key, value) {
                            console.log(key + ": " + value[0]);
                            msg += value + "\n";
                        });
                    } else if (exception === "parsererror") {
                        msg = "Requested JSON parse failed.";
                    } else if (exception === "timeout") {
                        msg = "Time out error." + xhr.responseText;
                    } else if (exception === "abort") {
                        msg = "Ajax request aborted.";
                    } else {
                        msg = "Error:" + xhr.status + " " + xhr.responseText;
                    }
                    alert(msg);
                }
            });
        })

        $('.washlist').on('click', '.addToWatchlist', function(e) {

            var addToWatchlistButton = $(this);
            var currency_id = addToWatchlistButton.data('currency-id');
            addToWatchlistButton.closest('.addToWatchlist').find('.fa-spinner').show();
            $.ajax({
                url: "{{ route('backend.watchlist.store') }}",
                dataType: "json",
                type: "POST",
                async: true,
                data: {
                    "currency_pair_id": currency_id
                },
                success: function(data) {
                    $('.fa-spinner').hide();
                    addToWatchlistButton.hide(300);

                    var record = '';
                    data.data.forEach(element => {
                        record += `<tr>
                                <td class="float-left">${element.currency_pair.sy}</td>
                                <td>${element.currency_pair.buy_spread}</td>
                                <td>${element.currency_pair.sell_spread}</td>
                                <td><i style="cursor: pointer;" class="fa fa-trash text-danger removeFromWatchlist" data-currency-id="${element.currency_pair.id}"></i></td>
                            </tr>`;
                    });

                    $('#watchlist').html(record);
                },
                error: function(xhr, exception) {
                    console.log(xhr);
                    $('.fa-spinner').hide();
                    var msg = "";

                    if (xhr.status === 0) {
                        msg = "Not connect.\n Verify Network." + xhr.responseText;
                    } else if (xhr.status == 404) {
                        msg = "Requested page not found. [404]" + xhr.responseText;
                    } else if (xhr.status == 500) {
                        var res = $.parseJSON(xhr.responseText);
                        msg = "Internal Server Error [500]." + res.message;
                    } else if (xhr.status == 422) {
                        var res = $.parseJSON(xhr.responseText);
                        console.log(res.errors);
                        $.each(res.errors, function(key, value) {
                            console.log(key + ": " + value[0]);
                            msg += value + "\n";
                        });
                    } else if (exception === "parsererror") {
                        msg = "Requested JSON parse failed.";
                    } else if (exception === "timeout") {
                        msg = "Time out error." + xhr.responseText;
                    } else if (exception === "abort") {
                        msg = "Ajax request aborted.";
                    } else {
                        msg = "Error:" + xhr.status + " " + xhr.responseText;
                    }
                    alert(msg);
                }
            });
        })
    });

    {{-- var outside = document.getElementById('outside'); --}}
    {{-- var inside = document.getElementById('inside'); --}}

    {{-- outside.addEventListener('click', function(e) { --}}
    {{--    inside.style.width = e.offsetX + "px"; --}}

    {{--    // calculate the % --}}
    {{--    var pct = Math.floor((e.offsetX / outside.offsetWidth) * 100); --}}
    {{--    var amount=parseInt('{{auth()->user()->balance}}') --}}
    {{--    var finel=Math.floor((amount*pct)/100) --}}
    {{--   $('.update_input').val(finel) --}}

    {{--    inside.innerHTML = pct + " %"; --}}
    {{-- }, false); --}}
    $('.sidebar-card').hover(function() {
            $(this).removeClass("col-lg-2")
            $(this).addClass("col-lg-3")
            $('.chart_center').removeClass("col-lg-10")
            $('.chart_center').addClass("col-lg-9")


        },
        function() {

            $(this).removeClass("col-lg-3")
            $(this).addClass("col-lg-2")
            $('.chart_center').removeClass("col-lg-9")
            $('.chart_center').addClass("col-lg-10")

        })

    // "use strict";
    // $(document).ready(function () {
    //     $(".trade-btn").click(function(){
    //         swal("Opps!", "You cant buy / sell when auto trading is enabled", "error");
    //     })
    // });

    // $("#toggle").click(function(){
    //     $(this).toggleClass("down");
    //     $("#order-book-wrapper").toggleClass("wrapper-space")
    //     $("#order-table").toggle();
    // });

    $('.switch input#toggle-profit').change(function() {
        $("#profit-value").prop("disabled", !$(this).is(':checked'));
    });

    $('.switch input#toggle-loss').change(function() {
        $("#loss-value").prop("disabled", !$(this).is(':checked'));
    });
</script>

<!-- slider script starts -->
<script>
    $('#range').on("input", function() {
        $('.output').val(this.value + ",000  $");
    }).trigger("change");
</script>
<!-- slider script ends -->
@endsection
