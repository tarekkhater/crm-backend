@extends($activeTemplate.'layouts.user')

@section('content')
    <section id="main-content" class=" ">
        <div class="wrapper main-wrapper row" style=''>
            {{--            <div class='col-xs-12 '>--}}
            {{--                <div class="page-title">--}}

            {{--                    <div class="pull-left">--}}
            {{--                        <!-- PAGE HEADING TAG - START -->--}}
            {{--                        <h1 class="title">{{ $page_title }}</h1>--}}
            {{--                        <!-- PAGE HEADING TAG - END -->--}}
            {{--                    </div>--}}

            {{--                </div>--}}
            {{--            </div>--}}

            <div class=" col-md-2 col-12 pr-min pl-min">
                <section class="box tbox">
                    <div class="content-body">
                        <div class="row">
                            <div class="col-xs-12">

                                <div class="text-center no-mt no-mb">
                                    <div class="transfer-wraper">

                                        <form action="" method="get">
                                            <div class="form-group">
                                                <div class="col-12 mb-10" style="margin-top: 10px">
                                                    <p class="text-center">Select Asset to trade</p>
                                                    <select name="sym" class="form-control input-lg m-bot15">
                                                        @foreach ($cryptos as $item)
                                                            <option class="text-uppercase">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-sm-12 no-pl">
                                                    <input value="TRADE" type="submit" class="btn btn-success btn-md" style="width:100%" />
                                                </div>
                                            </div>
                                        </form>
                                        <form action="{{route('user.add.practice.balance')}}" method="POST" style="margin-top: 10px">
                                            @csrf
                                            <div class="form-group  no-mb">
                                                <div class="col-12 mb-10" >
                                                    <p class="text-center">Demo Account Bal :</p>
                                                    <input disabled value="{{ auth()->user()->demo_balance }}" class="form-control input-lg m-bot15" />
                                                </div>

                                                <input value="BALANCE TOP UP" type="submit" class="btn btn-success btn-md" style="width:100%" />
                                            </div>
                                        </form>

                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </section>

                <section class="box dbox">
                    <!-- TradingView Widget BEGIN -->
                    <div class="tradingview-widget-container">
                        <div class="tradingview-widget-container__widget"></div>
                        <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/symbols/BTCUSD/?exchange=FX" rel="noopener" target="_blank"><span class="blue-text">BTCUSD Rates</span></a> by TradingView</div>
                        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-mini-symbol-overview.js" async>
                            {
                                "symbol": "{{ $currency->sym }}",
                                "width": "100%",
                                "height": "100%",
                                "locale": "en",
                                "dateRange": "1M",
                                "colorTheme": "light",
                                "trendLineColor": "rgba(41, 98, 255, 1)",
                                "underLineColor": "rgba(41, 98, 255, 0.3)",
                                "underLineBottomColor": "rgba(41, 98, 255, 0)",
                                "isTransparent": false,
                                "autosize": true,
                                "largeChartUrl": ""
                            }
                        </script>
                    </div>
                    <!-- TradingView Widget END -->

                </section>
            </div>



            <div class="col-md-8 col-12 pr-min pl-min">

                <section class="box">
                    <div class="content-body">
                        <div class="row">
                            <!-- TradingView Widget BEGIN -->
                            <div class="tradingview-widget-container">
                                <div id="tradingview_e8053"></div>
                                <script src="https://s3.tradingview.com/tv.js"></script>
                                <script>
                                    new TradingView.widget(
                                        {
                                            "width": "100%",
                                            "height": 490,
                                            "symbol": "{{ $currency->sym }}",
                                            "interval": "1",
                                            "timezone": "Etc/UTC",
                                            "theme": "Light",
                                            "style": "1",
                                            "locale": "en",
                                            "toolbar_bg": "#f1f3f6",
                                            "enable_publishing": false,
                                            "withdateranges": true,
                                            "hide_side_toolbar": false,
                                            "allow_symbol_change": false,
                                            "show_popup_button": true,
                                            "popup_width": "1000",
                                            "details":true,
                                            "popup_height": "500",
                                            "container_id": "tradingview_e8053"
                                        }
                                    );
                                </script>
                            </div>
                            <!-- TradingView Widget END -->
                        </div>
                    </div>
                </section>
            </div>
            <div class=" col-md-2 col-12 pr-min pl-min">
                <section class="box rtbox">
                    <div class="content-body" style="margin-top: 10px">
                        <div class="row">
                            <div class="col-xs-12">

                                <div class=" no-mt no-mb">
                                    <div class="transfer-wraper trade">

                                        <h4 class="mb-20 mt-3 text-center">@lang('Trade') {{$currency->name}}</h4>
                                        <div class="trade-user-price text-h3 text-danger mb-2" v-cloak>@{{ trade_user_price }}</div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="field-1">Close Trade Mode</label>
                                                <select v-model="auto_close" class="form-control m-bot5" v-cloak>
                                                    <option class="text-uppercase" value="0">Manual</option>
                                                    <option class="text-uppercase" value="1">Auto close</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12" v-if="auto_close > 0">
                                            <div class="form-group">
                                                <label class="form-label" for="field-1">End Trade in</label>
                                                <select v-model="playTimeUnit" :disabled="!auto_close" class="form-control m-bot15" v-cloak>
                                                    <option class="text-uppercase" v-for="i in units" :value="i">@{{ i }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12" v-if="auto_close > 0">
                                            <div class="form-group">
                                                <label class="form-label" for="duration">Trade Duration</label>
                                                <div class="controls">
                                                    <input type="number" :disabled="!auto_close" v-model="selectedTime" class="form-control" id="duration">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 " style="margin-top: 5px">

                                        </div>

                                        <div class="">
                                            {{--                                            @forelse($gameSettings as $gameSetting)--}}
                                            {{--                                                <button :class="selectedTime === {{ $gameSetting->time_sec }} ? 'btn-primary':''" @click="selectTime({{$gameSetting}})" class="btn btn-outline-info btn-sm gameTime" style="margin-top: 5px">--}}
                                            {{--                                                    <i class="las la-clock"></i>--}}
                                            {{--                                                    {{$gameSetting->time}} {{$gameSetting->unit}}--}}
                                            {{--                                                </button>--}}
                                            {{--                                            @empty--}}
                                            {{--                                            @endforelse--}}
                                        </div>

                                        <div class="form-group  no-mb">
                                            <p>Amount in USD </p>
                                            <div class="input-group">
                                                <input v-model="amt" class="form-control" />
                                                <a href="#" class="input-group-addon" data-color-class="primary" data-animate=" animated fadeIn" data-toggle="tooltip" data-original-title="copy" data-placement="top"><i class="fa fa-dollar text-dark"></i></a>
                                            </div>

                                            <div class="mt-10 balance">
                                                <strong class="form-label bold">Amount in {{$currency->symbol}} : </strong>
                                                <div class="input-group mb-10">
                                                    <input v-model="priceInCoin" class="form-control" />
                                                    <a href="#" class="input-group-addon" data-color-class="primary" data-animate=" animated fadeIn" data-toggle="tooltip" data-original-title="copy" data-placement="top">{{$currency->symbol}} </a>
                                                </div>
                                            </div>


                                            <div class="col-sm-6 no-pl">
                                                <button :disabled="play" @click="playNow(1)" class="btn btn-success btn-lg mt-20" style="width:100%">BUY</button>
                                            </div>
                                            <div class="col-sm-6 no-pr">
                                                <button :disabled="play" @click="playNow(2)" class="btn btn-danger btn-lg mt-20" style="width:100%">SELL</button>
                                            </div>

                                        </div>


                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </section>
            </div>

            <div class="clearfix"></div>

            <div class="col-md-12 mt-10" style="border-left: 3px solid #e77512;">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#open" data-toggle="tab">
                            Open Orders
                        </a>
                    </li>
                    <li>
                        <a href="#closed" data-toggle="tab">
                            Closed Orders
                        </a>
                    </li>
                    <li>
                        <a href="#history" data-toggle="tab">
                            Order History
                        </a>
                    </li>
                </ul>

                <div class="tab-content" v-cloak>
                    <div class="tab-pane fade in active" id="open">
                        <div class="table-responsive" data-pattern="priority-columns">
                            <table id="tech-companies-1" class="table table-small-font no-mb table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>@lang('Opened at')</th>
                                    <th>@lang('Crypto Currency')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Qty')</th>
                                    <th>@lang('Close at')</th>
                                    <th>@lang('Direction')</th>
                                    <th>@lang('Status')</th>
                                </tr>
                                </thead>
                                <tbody v-if="open.length > 0" >
                                <tr v-for="item in open">
                                    <td>@{{  item.new_date }}</td>
                                    <td>@{{  item.crypto.symbol }}/USD</td>
                                    <td data-label="@lang("Amount")">@{{  formatPrice(item.amount) }} {{$general->cur_text}}</td>
                                    <td data-label="@lang("coin")">@{{  item.coin_value }} @{{  item.crypto.symbol }}</td>
                                    <td>@{{ item.in_time }}</td>
                                    <td data-label="@lang("High/Low")">
                                        <span v-if="item.hilow === 1" class="badge badge-success">@lang('BUY')</span>
                                        <span v-if="item.hilow === 2"  class="badge badge-danger">@lang('SELL')</span>
                                    </td>


                                    <td>
                                        <span v-if="item.status === 0" class="badge badge-primary">@lang('Running')</span>
                                        <span v-else class="badge badge-success">@lang('Complete')</span>
                                    </td>
                                    <td><button class="btn btn-danger" @click="closeOrder(item.id)">Close </button></td>
                                    {{--                                        <td data-label="@lang("Date")">{{showDateTime($practice->created_at, 'd M, Y h:i:s')}}</td>--}}
                                </tr>
                                </tbody>
                                <tbody v-else>
                                <tr>
                                    <td colspan="100%"> @lang('No results found')!</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="closed">
                        <div class="table-responsive" data-pattern="priority-columns">
                            <table id="tech-companies-1" class="table table-small-font no-mb table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>@lang('Opened at')</th>
                                    <th>@lang('Crypto Currency')</th>
                                    <th>@lang('Closed at')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Qty')</th>
                                    <th>@lang('Closed Value')</th>
                                    <th>@lang('Direction')</th>
                                    {{--                                    <th>@lang('Result')</th>--}}
                                    <th>@lang('P/L')</th>
                                </tr>
                                </thead>
                                <tbody v-if="closed.length > 0" >
                                <tr v-for="item in closed">
                                    <td>@{{  item.new_date }}</td>
                                    <td>@{{  item.crypto.symbol }}/USD</td>
                                    <td>@{{  item.in_time }}</td>
                                    <td data-label="@lang("Amount")">@{{  formatPrice(item.amount) }} {{$general->cur_text}}</td>
                                    <td data-label="@lang("coin")">@{{  item.coin_value }} @{{  item.crypto.symbol }}</td>
                                    <td data-label="@lang("closed_value")">@{{  item.coin_closed_at }} @{{  item.crypto.symbol }}</td>
                                    <td data-label="@lang("High/Low")">
                                        <span v-if="item.hilow === 1" class="badge badge-success">@lang('BUY')</span>
                                        <span v-if="item.hilow === 2"  class="badge badge-danger">@lang('SELL')</span>
                                    </td>

                                    <td>@{{ item.pl }} USD</td>
                                    {{--                                        <td data-label="@lang("Date")">{{showDateTime($practice->created_at, 'd M, Y h:i:s')}}</td>--}}
                                </tr>
                                </tbody>
                                <tbody v-else>
                                <tr>
                                    <td colspan="100%"> @lang('No results found')!</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="history">

                        <div class="table-responsive" data-pattern="priority-columns">
                            <table  class="table table-small-font no-mb table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>@lang('Opened at')</th>
                                    <th>@lang('Crypto Currency')</th>
                                    <th>@lang('Closed at')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Qty')</th>
                                    <th>@lang('Closed Value')</th>
                                    <th>@lang('Direction')</th>
                                    {{--                                    <th>@lang('Result')</th>--}}
                                    <th>@lang('P/L')</th>
                                </tr>
                                </thead>
                                <tbody v-if="games.length > 0" >
                                <tr v-for="item in games">
                                    <td>@{{  item.new_date }}</td>
                                    <td>@{{  item.crypto.symbol }}/USD</td>
                                    <td>@{{  item.in_time }}</td>
                                    <td data-label="@lang("Amount")">@{{  formatPrice(item.amount) }} {{$general->cur_text}}</td>
                                    <td data-label="@lang("coin")">@{{  item.coin_value }} @{{  item.crypto.symbol }}</td>
                                    <td data-label="@lang("closed_value")">@{{  item.coin_closed_at }} @{{  item.crypto.symbol }}</td>
                                    <td data-label="@lang("High/Low")">
                                        <span v-if="item.hilow === 1" class="badge badge-success">@lang('BUY')</span>
                                        <span v-if="item.hilow === 2"  class="badge badge-danger">@lang('SELL')</span>
                                    </td>

                                    <td>@{{ item.pl }} USD</td>
                                    {{--                                        <td data-label="@lang("Date")">{{showDateTime($practice->created_at, 'd M, Y h:i:s')}}</td>--}}
                                </tr>
                                </tbody>
                                <tbody v-else>
                                <tr>
                                    <td colspan="100%"> @lang('No results found')!</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

                <div class="clock w-100"></div>

            </div>

            <div class="clearfix"></div>

            <!-- MAIN CONTENT AREA ENDS -->
        </div>
    </section>

@endsection


@push('style-lib')
    <link rel="stylesheet" href="{{asset('assets/css/flipclock.css')}}">
@endpush

@push('script-lib')
    <script src="{{asset('assets/js/flipclock.min.js')}}"></script>
    <script src="{{asset($activeTemplateTrue.'user/plot/plotly-latest.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            var _SETTINGS = window._SETTINGS || {};

            var chatarea = $(".page-chatapi");
            var chatwindow = $(".chatapi-windows");
            var topbar = $(".page-topbar");
            var mainarea = $("#main-content");
            var menuarea = $(".page-sidebar");

            console.log(menuarea)

            menuarea.addClass("collapseit").removeClass("expandit").removeClass("chat_shift");
            topbar.addClass("sidebar_shift").removeClass("chat_shift");
            mainarea.addClass("sidebar_shift").removeClass("chat_shift");
            _SETTINGS.mainmenuCollapsed();



            _SETTINGS.mainmenuCollapsed = function() {

                if ($(".page-sidebar.chat_shift #main-menu-wrapper").length > 0 || $(".page-sidebar.collapseit #main-menu-wrapper").length > 0) {
                    //console.log("collapse menu");
                    var topbar = $(".page-topbar").height();
                    var windowheight = window.innerHeight;
                    var minheight = windowheight - topbar;
                    var fullheight = $(".page-container #main-content .wrapper").height();

                    var height = fullheight;

                    if (fullheight < minheight) {
                        height = minheight;
                    }

                    $('.fixedscroll #main-menu-wrapper').perfectScrollbar('destroy');

                    $('.page-sidebar.chat_shift #main-menu-wrapper .wraplist, .page-sidebar.collapseit #main-menu-wrapper .wraplist').height(height);

                    /*hide sub menu of open menu item*/
                    $("li.open .sub-menu").attr("style", "");

                }

            };


            // $("#mainSidebar").addClass('collapseit');
        })
    </script>
@endpush
@push('scripts')
    <script>
        "use strict";
        var arrayLength = 15;
        var newArray = [];
        var xArray = [];
        var timezone;
        var gtime;
        var coinSymbol = "{{$currency->symbol}}";

        for (var i = 0; i < arrayLength; i++) {
            var y;
            var x;
            newArray[i] = y
            xArray[i] = x
        }
        var baseColor = "#{{ $general->base_color }}"
        var trace1 = {
            x: xArray,
            y: newArray,
            showlegend: true,
            line: {color: baseColor},
            visible: true,
            xaxis: 'x1',
            yaxis: 'y1',
        };
        var data = [trace1];
        var layout = {
            xaxis: {
                tickfont: {
                    size: 14,
                    color: '#fff'
                },
                ticklen: 8,
                tickwidth: 2,
                tickcolor: '#8f2331'
            },
            yaxis: {
                tickfont: {
                    size: 14,
                    color: '#fff'
                },
                ticklen: 8,
                tickwidth: 2,
                tickcolor: '#8f2331'
            },
            paper_bgcolor: '#141c24',
            plot_bgcolor: '#141c24',
            showlegend: false,
        };
        // Plotly.plot('graph', {
        //     data: data,
        //     layout: layout
        // });
        var inter = setInterval(function () {
            var dateTime = new Date();
            timezone = dateTime.getTimezoneOffset() / 60;
            gtime = dateTime.getHours() + ':' + dateTime.getMinutes() + ':' + dateTime.getSeconds();
            var time = dateTime.getHours() + ':' + dateTime.getMinutes() + ':' + dateTime.getSeconds();
            $.get("{{route('user.crypto.rate')}}", {coinSymbol: coinSymbol}, function (data) {
                $('#cryptoPrice').text(data);
                var y = data;
                var x = time;
                newArray = newArray.concat(y)
                newArray.splice(0, 1)
                xArray = xArray.concat(x)
                xArray.splice(0, 1)
            });

            var data_update = {
                x: [xArray],
                y: [newArray]
            };
            Plotly.update('graph', data_update)
        }, 1000);

        $(document).ready(function () {
            var gameLogId;
            var playTime;
            var playTimeUnit;
            var second;
            var highlowType;
            var coinId = "{{$currency->id}}";
            const highLowArray = [1, 2];
            const userBalance = {{ auth()->user()->demo_balance }};

            $(document).on('click', '.gameTime', function () {
                $(".highlight").children().removeClass('active');
                $(this).addClass('active');
                playTime = $(this).data('time');
                playTimeUnit = $(this).data('unit');
            });

            $(".highlowButton").on('click', function () {
                highlowType = $(this).val();
            })

            $("#playGame").on('submit', function (event) {
                event.preventDefault();
                var amount = $('input[name="amount"]').val();
                var timeCount = secondCount();

                if (!highLowArray.includes(parseInt(highlowType))) {
                    notify('error', 'Invalid Game Type');
                } else if (userBalance < amount) {
                    notify('error', 'Your Practice Balance {{ getAmount(auth()->user()->demo_balance) }} {{$general->cur_text}} Not Enough! Please Add Practice Amount');
                } else if (isNaN(amount) || amount <= 0) {
                    notify('error', 'Please Insert Valid Amount')
                } else if (isNaN(timeCount) || timeCount <= 0) {
                    notify('error', 'Please Select Valid Time')
                } else {
                    $('input[name="amount"]').val("");
                    $.ajax({
                        headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}",},
                        url: "{{ route('user.demo.play.store') }}",
                        method: "POST",
                        data: {
                            amount: amount,
                            coinId: coinId,
                            highlowType: highlowType,
                            duration: playTime,
                            unit: playTimeUnit
                        },
                        success: function (response) {
                            if (response.value == 1) {
                                gameLogId = response.gameLogId;
                                countDown(timeCount, gameLogId)

                                if (highlowType == 1) {

                                    $(".trade-user-price").text("You Trade High. Price Was " + response.trade + " " + "USD");

                                    notify('success', 'Trade High');
                                } else {

                                    $(".trade-user-price").text("You Trade Low. Price Was " + response.trade + " " + "USD");

                                    notify('success', 'Trade Low');
                                }

                            } else if (response.value == 2) {
                                notify('error', response.message);
                            } else {
                                $.each(response, function (i, val) {
                                    notify('error', val)
                                });
                            }
                        }
                    });
                }
            });

            function secondCount() {
                if (playTimeUnit == 'seconds') {
                    second = playTime;
                    return second;
                } else if (playTimeUnit == 'minutes') {
                    second = playTime * 60;
                    return second;
                } else if (playTimeUnit == 'hours') {
                    second = playTime * 60 * 60;
                    return second;
                }
            }

            function countDown(timeCount, gameLogId) {
                var clock = $('.clock').FlipClock({
                    defaultClockFace: 'HourlyCounter',
                    autoStart: false,
                    callbacks: {
                        stop: function () {
                            gameResult(gameLogId)
                        }
                    }
                });
                clock.setTime(timeCount - 1);
                clock.setCountdown(true);
                clock.start();
            }

            function gameResult(gameLogId) {
                $.ajax({
                    headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}",},
                    url: "{{ route('user.demo.game.result') }}",
                    method: "POST",
                    data: {gameLogId: gameLogId},
                    success: function (response) {
                        if (response == 1) {
                            notify('success', 'Trade Win');
                        } else if (response == 2) {
                            notify('error', 'Trade Lose');
                        } else if (response == 3) {
                            notify('error', 'Trade Draw');
                        } else {
                            $.each(response, function (i, val) {
                                notify('error', val)
                            });
                        }
                        setTimeout(function () {
                            location.reload();
                        }, 5000);
                    }
                });
            }
        });
    </script>
@endpush

@section('script-js')
    <script>
        var id = 1;
        const userBalance = {{ auth()->user()->demo_balance }};
        let coinSymbol = "{{$currency->symbol}}";
        let coinSym= "{{$currency->sym}}";
        let coinId = "{{$currency->id}}";
        let url = "{{route('user.api.crypto.rate')}}";
        let playGameUrl = "{{ route('user.demo.play.store') }}";
        let gameLogUrl = "{{route('user.api.practice.trade.log')}}";
        let gameCloseUrl = "{{route('user.api.practice.close')}}";
        let checkGameUrl = "{{route('user.api.practice.trade.check')}}";
        let gsettings =  "{{ $gameSettings }}";
        let demoGameResultUrl = "{{ route('user.demo.game.result') }}";
        document.addEventListener('DOMContentLoaded', function () {
            new Vue({
                el: '#main-content',
                data: {
                    next : ['all'],
                    games : [],
                    cryptos : [],
                    auto_close : 1,
                    units : ['seconds','minutes','hour'],
                    sym : coinSym,
                    gameSettings : gsettings,
                    playTime : 0,
                    amt:10,
                    trade_user_price:'BUY / SELL',
                    highlowType:0,
                    coinPrice:0,
                    play:false,
                    selectedTime:10,
                    playTimeUnit:'seconds',
                    show:false,
                    post_id : id,
                },
                mounted() {
                    // this.getMorePosts();
                    this.timer = setInterval(() => {
                        this.getCoinPrice()

                    }, 10000)
                    this.getCoinPrice();
                    this.getGameLog();
                    // this.getCryptos();
                    // this.getInterval();
                },
                methods: {
                    playNow(type){
                        this.highlowType = type;
                        this.playGame();
                    },
                    selectTime(setting){
                        this.selectedTime = setting.time_sec;
                        this.playTime = setting.time; this.playTimeUnit = setting.unit;
                    },
                    textB(){
                        Swal.fire({
                            icon: "success",
                            text: "Order placed successfully",
                            timer:3000,
                        });
                    },
                    playGame(){
                        this.play = true;
                        if(this.playTimeUnit == 'seconds'){
                            this.playTime = this.selectedTime;
                        }else if(this.playTimeUnit == 'minutes'){
                            this.playTime = this.selectedTime * 60;
                        }else {
                            this.playTime = this.selectedTime * 60 * 60;
                        }
                        let data = {
                            amount: this.amt,
                            coinId: coinId,
                            auto_close : this.auto_close,
                            highlowType: this.highlowType,
                            duration: this.selectedTime,
                            unit: this.playTimeUnit
                        };
                        if (userBalance < this.amt) {
                            this.play = false;
                            notify('error', 'Your Practice Balance {{ getAmount(auth()->user()->demo_balance) }} {{$general->cur_text}} Not Enough! Please Add Practice Amount');
                        } else if (isNaN(this.amt) || this.amt <= 0) {
                            this.play = false;
                            notify('error', 'Please Insert Valid Amount')
                        } else if (this.auto_close > 0) {
                            if(isNaN(this.selectedTime) || this.selectedTime <= 0){
                                this.play = false;
                                notify('error', 'Please Select Valid Time')
                            }else {
                                this.sendGame(data)
                            }
                        } else {
                            this.sendGame(data)
                        }
                    },
                    sendGame(data){
                        axios.post(playGameUrl, data).then((res) => {
                            if (res.data.value === 1) {
                                this.play = false;
                                gameLogId = res.data.gameLogId;
                                this.getGameLog();
                                if(data.auto_close > 0){
                                    this.countDown(this.playTime, gameLogId)
                                }
                                Swal.fire({
                                    icon: "success",
                                    text: "Order placed successfully",
                                    timer:3000,
                                });
                                if (this.highlowType === 1) {
                                    this.trade_user_price = "Your Buy Price Was " + res.data.trade + " " + "USD";

                                } else {
                                    this.trade_user_price = "Your Sell Price Was " + res.data.trade + " " + "USD";
                                }
                            } else if (res.data.value === 2) {
                                this.play = false;
                                notify('error', res.data.message);
                            } else {
                                this.play = false;
                                notify('error', "Something went wrong")
                            }
                        })
                    },
                    closeOrder(id){
                        axios.post(gameCloseUrl, { id : id}).then((res) => {
                            this.getGameLog();
                            Swal.fire({
                                icon: "success",
                                text: "Order closed successfully",
                                timer:3000,
                            });
                        })
                    },

                    countDown(timeCount, gameLogId) {
                        let vm = this;
                        console.log("started clock "+timeCount+" and gamelod "+gameLogId)
                        let clock = $('.clock').FlipClock({
                            defaultClockFace: 'MinuteCounter',
                            showSeconds: true,
                            autoStart: false,
                            callbacks: {
                                stop: function () {
                                    vm.gameResult(gameLogId)
                                }
                            }});
                        clock.setTime(timeCount - 1);
                        clock.setCountdown(true);
                        clock.start();
                    },

                    gameResult(gameLogId) {
                        console.log("trading ..."+gameLogId)
                        axios.post(demoGameResultUrl, {gameLogId: gameLogId}).then((res) => {
                            Swal.fire({
                                icon: "success",
                                text: "Order closed successfully",
                                timer:3000,
                            });
                            if (res.data === 1) {
                                notify('success', 'Trade Win');
                            } else if (res.data === 2) {
                                notify('error', 'Trade Lose');
                            } else if (res.data === 3) {
                                notify('error', 'Trade Draw');
                            } else {
                                notify('error', "Something went wrong")
                            }
                            setTimeout(function () {
                                location.reload();
                            }, 4000);
                        });
                    },

                    formatPrice(value) {
                        let val = (value/1).toFixed(2).replace('.', '.')
                        return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    },
                    getCoinPrice(){
                        axios.post(url, {coinSymbol: coinSymbol}).then((res) =>{
                            this.coinPrice = res.data;
                            if(this.open.length > 0){
                                this.checkGame();
                            }
                        })
                    },
                    getGameLog(){
                        axios.get(gameLogUrl).then((response) => {
                            this.games = response.data.data;
                        })
                    },
                    checkGame(){
                        axios.get(checkGameUrl).then((response) => {
                            if(response.data.status > 0){
                                console.log(response.data)
                                this.getGameLog();
                            }
                        })
                    }
                },
                computed: {
                    priceInCoin(){
                        let newAmt = 0
                        if(this.amt > 0 && this.coinPrice > 0){
                            newAmt = this.amt / this.coinPrice;
                        }
                        return newAmt;
                    },
                    closed(){
                        return this.games.filter(item => {
                            return item.status == 1
                        });
                    },
                    open(){
                        return this.games.filter(item => {
                            return item.status == 0
                        });
                    },
                },
                watch : {
                    amt() {
                        this.getCoinPrice();
                    }
                }
            })
        })
    </script>
@endsection
