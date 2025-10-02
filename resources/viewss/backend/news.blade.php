@extends('backend.layouts.backend')

@section('content')

    <div class="container-fluid min-padding">


        <div class="row mt-2">

            <div class=" col-md-4 col-12">

                <div class="card mt-2" style="height: 700px">
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

            <div class="col-md-8 col-12 mt-2">
                <div class="card">
                    <div class="card-body">
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
                </div>

            </div>

        </div>

    </div>
@endsection

@section('styles')
{{--    <link rel="stylesheet" href="/back/vendor/waves/waves.min.css">--}}
@endsection

@section('js')

    <script>
        "use strict";
        $(document).ready(function () {
            $(".trade-btn").click(function(){
                swal("Opps!", "You cant buy / sell when auto trading is enabled", "error");
            })
        });
    </script>

@endsection
