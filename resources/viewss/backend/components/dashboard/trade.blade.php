<div class="row">

    <div class="col-12  col-md-8 col-xl-8 mt-3">
        <!-- TradingView Widget BEGIN -->
        <div class="tradingview-widget-container" style="min-height: 620px">
            <div id="tradingview_3c684"></div>
            <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/symbols/BTCUSD/?exchange=BITBAY" rel="noopener" target="_blank"><span class="blue-text">BTCUSD Chart</span></a> by TradingView</div>
            <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
            <script type="text/javascript">
                new TradingView.widget(
                    {
                        "autosize" : true,
                        "symbol": "BITBAY:BTCUSD",
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
    <div class="col-12  col-md-4 mt-3">
        <div style="height:620px; background-color: #FFFFFF; overflow:hidden; box-sizing: border-box; border: 1px solid #56667F; border-radius: 4px; text-align: right; line-height:14px; font-size: 12px; font-feature-settings: normal; text-size-adjust: 100%; box-shadow: inset 0 -20px 0 0 #56667F; padding: 0px; margin: 0px; width: 100%;"><div style="height:620px; padding:0px; margin:0px; width: 100%;"><iframe src="https://widget.coinlib.io/widget?type=full_v2&theme={{ $theme }}&cnt=10&pref_coin_id=1505&graph=yes" width="100%" height="645px" scrolling="auto" marginwidth="0" marginheight="0" frameborder="0" border="0" style="border:0;margin:0;padding:0;"></iframe></div><div style="color: #FFFFFF; line-height: 14px; font-weight: 400; font-size: 11px; box-sizing: border-box; padding: 2px 6px; width: 100%; font-family: Verdana, Tahoma, Arial, sans-serif;"></div></div>





        @section('hide')
            <div class="twitter-gradient p-5 text-center">
                <div id="demo" class="carousel slide" data-ride="carousel">
                    <!-- The slideshow -->
                    <div class="carousel-inner">
                        <div class="carousel-item py-3 active">
                            <i class="icon-social-twitter p-3 border-white border rounded-circle h1 mb-4 mx-auto d-table"></i>
                            In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.
                            <br/><small>24 January, 2018</small><br/><br/>
                            <div class="love px-2 py-1 d-inline-block"><i class="ion ion-heart"></i> 200   <i class="ml-3 ion ion-chatboxes"></i> 192</div>
                        </div>
                        <div class="carousel-item py-3">
                            <i class="icon-social-twitter p-3 border-white border rounded-circle h1 mb-4 mx-auto d-table"></i>
                            In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.
                            <br/><small>24 January, 2018</small><br/><br/>
                            <div class="love px-2 py-1 d-inline-block"><i class="ion ion-heart"></i> 200   <i class="ml-3 ion ion-chatboxes"></i> 192</div>
                        </div>
                        <div class="carousel-item py-3">
                            <i class="icon-social-twitter p-3 border-white border rounded-circle h1 mb-4 mx-auto d-table"></i>
                            In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.
                            <br/><small>24 January, 2018</small><br/><br/>
                            <div class="love px-2 py-1 d-inline-block"><i class="ion ion-heart"></i> 200   <i class="ml-3 ion ion-chatboxes"></i> 192</div>
                        </div>

                    </div>
                    <!-- Indicators -->
                    <ul class="carousel-indicators position-relative mb-0">
                        <li data-target="#demo" data-slide-to="0" class="active"></li>
                        <li data-target="#demo" data-slide-to="1"></li>
                        <li data-target="#demo" data-slide-to="2"></li>
                    </ul>
                </div>
            </div>
        @endsection

    </div>

    <div class="col-12 col-md-7 col-xl-7 mt-3">
        <div class="card">
            <div class="card-content">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Trade History</h4>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 text-nowrap">
                            <thead>
                            <tr>
                                <th>@lang('Opened at')</th>
                                <th>@lang('Crypto Currency')</th>
                                <th>@lang('Closed at')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Qty')</th>
                                <th>@lang('Closed Value')</th>
                                <th>@lang('Direction')</th>
                                <th>@lang('P/L')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($trades as $item)
                                <tr>
                                    <td>{{  $item->new_date }}</td>
                                    <td>{{  optional($item->crypto)->symbol }}/USD</td>
                                    <td>{{  $item->in_time }}</td>
                                    <td data-label="@lang("Amount")">{{ $item->amount }}</td>
                                    <td data-label="@lang("coin")">{{  $item->coin_value }} {{  optional($item->crypto)->symbol }}</td>
                                    <td data-label="@lang("closed_value")">{{  $item->coin_closed_at }} {{  optional($item->crypto)->symbol }}</td>
                                    <td data-label="@lang("High/Low")">
                                        @if ($item->hilow === 1)
                                            <span class="badge badge-success">@lang('BUY')</span>
                                        @else
                                            <span class="badge badge-danger">@lang('SELL')</span>
                                        @endif
                                    </td>

                                    <td>{{ $item->pl }} USD</td>
                                    {{--                                        <td data-label="@lang("Date")">{{showDateTime($practice->created_at, 'd M, Y h:i:s')}}</td>--}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%"> @lang('No results found')!</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12  col-lg-5 mt-3">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Recent Activities</h4>

            </div>
            <div class="card-content">
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-12">
                            <ul class="activities mt-4 mb-2">
                                <li class="activity py-2 px-2 border-left">
                                    <label class="bg-primary"></label>
                                    <span>11:30 PM</span><br/>
                                    <p class="mt-3"> <b>Add New User</b><br/>Orci eget eros faucibus tincidunt. Sed fringilla mauris sit amet nibh. Donec sodales
                                        sagittis magna sed consequat leo eget bibendum sodales, augue.</p>
                                </li>
                                <li class="activity py-2 px-2 border-left">
                                    <label class="bg-success"></label>
                                    <span>12:15 PM</span><br/>
                                    <p class="mt-3"> <b>Write Comment</b><br/>Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>
                                </li>
                                <li class="activity py-2 px-2 border-left">
                                    <label class="bg-danger"></label>
                                    <span>13:30 PM</span><br/>
                                    <p class="mt-3"> <b>Add New User</b><br/>Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Sed fringilla
                                        mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc.</p>
                                </li>
                                <li class="activity py-2 px-2 border-left">
                                    <label class="bg-warning"></label>
                                    <span>14:30 PM</span><br/>
                                    <p class="mt-3"> <b>Write Comment</b><br/>Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes,
                                        nascetur ridiculus mus. Nulla consequat massa quis enim.</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>
