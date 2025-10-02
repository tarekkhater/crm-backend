@extends('layouts.master')

@section('content')

    <section class="works-social-page">
        <div class="container-fluid">
            <h1>How Crypto Assets works?</h1>

        </div>
        <div class="info-social">
            <div class="container-fluid">
                <div class="content-block">
                    <p>An exceptional trader that integrates an automated copy trading platform with the opportunities in open markets will be assigned to your account on your first deposit.</p>

                    <h3>Get a professional trader linked to your account for free</h3>
                    <p>Professional trading service from Trade Assets designed for both beginners and pros.</p>
                    <p>Beginners traders can really totally on our experts to the trading for them.
                        Our Professionals allows you to copy their trades and share own skills and get additional income. Level up your income with Crypto Assets platform.</p>
                </div>
            </div>
        </div>
        <div class="people-in-tifia" >
{{--            <img src="/images/pro.jpeg">--}}
            <div class="container-fluid">
                <h4>Share experience, chat with our professional traders around the world, trade and earn together!</h4>
                <p>Become more confident in making trading decisions and get the most out of your trading with Crypto Assets.</p>

                @guest()
                <div class="group-success">
                    <div class="group">
                        <h3>Join Crypto Assets</h3>
                        <a class="btn-show-right" href="{{ route('login') }}" rel="nofollow" target="blank"><div class="circle"><span class="icon arrow"></span></div><p class="button-text">Login</p></a>                </div>
                </div>
                <div class="btn-block mob-only">
                    <a class="btn btn-red" href="{{ route('register') }}" rel="nofollow" target="blank">Registration</a>
                </div>
                @else
                    <div class="group-success">
                        <div class="group">
                            <h3>Make a deposit today</h3>
                            <a class="btn-show-right" href="{{ route('backend.deposits.create') }}" rel="nofollow" target="blank"><div class="circle"><span class="icon arrow"></span></div><p class="button-text">Start Trading</p></a>                </div>
                    </div>
                    <div class="btn-block mob-only">
                        <a class="btn btn-red" href="{{ route('backend.deposits.create') }}" rel="nofollow" target="blank">Start Trading</a>
                    </div>

                @endguest

            </div>
        </div>
        <div style="background-color: black" class="trader-role">
            <div class="container-fluid">
                <div class="content-block">
                    <h2>Try a trader's role</h2>
                    <h4>Are you an experienced and successful trader? Your experience can yield you more money.<br>Trade with Crypto Assets and get additional income</h4>
                </div>
            </div>
            <div class="container-fluid">
                <ul class="social-trader-step">
                    <li>Become a trader</li>
                    <li><div class="arrow-right"></div></li>
                    <li>Communicate and Trade</li>
                    <li><div class="arrow-right"></div></li>
                    <li>Get an additional income</li>
                </ul>
            </div>
            <div class="container-fluid">
                <div class="content-block">
                    <p><strong>Traders</strong> make trades in their accounts or simply let our professionals do it for them. Besides their own trading profits, traders also receive a commission from their investors' accounts.</p>
                </div>
            </div>
            <div class="container-fluid">
                <div class="steps-ul">
                    <ul>
                        <li class="li-1">
                            <p><a href="{{ route('register') }}" target="_blank">Register</a> with Crypto Assets and then <a href="{{ route('backend.deposits.create') }}" target="_blank">Make A Deposit</a>  If you already have a trading account with Crypto Assets, please, <a href="{{ route('login') }}" target="_blank">Login</a> and make a deposit..</p>
                        </li>
                    </ul>
                    <div class="img-block">
                        <img src="/images/screen/dash.png">
                    </div>
                    <ul>
                        <li class="li-2">
                            <p><strong>Multiple Payment System</strong></p>
                            <p>Easy deposit and withdrawal with our multiple paymment channels.</p>
                        </li>
                    </ul>
                    <div class="img-block">
                        <img src="/images/screen/1.png">
                    </div>
                    <ul>
                        <li class="li-3">
                            <p><strong>Realtime Trading Result.</strong></p>

                        </li>
                    </ul>
                    <div class="img-block">
                        <img src="/images/screen/2.png">
                    </div>

                </div>
            </div>
        </div>
        <div class="trader-role" style="background-color: black">

            <div class="container-fluid">
                <div class="steps-ul">

                    @section('hide')
                    <div class="copy-traders">
                        <h3>Free 1 for 1 Mentoring</h3>
                        <p>This copy type implies that the volume of a trade copied onto an investor’s account is equal to the volume of the respective trade in an trader’s account.</p>
                        <p>For example, if the trader has opened 5 lots in his/her account, a trade of 5 lots will be copied onto the investor’s account.</p>
                        <div class="copy-traders-graph">
                            <div class="block-copy">
                                <p class="min-text">Trader's account</p>
                                <div class="block">
                                    <div class="avatar-block">
                                        <img src="https://tifia.com/images/new-site/social-trading/avatar-1.png">
                                    </div>
                                    <div class="info-block">
                                        <p>volume: 100%</p>
                                        <p><span>5</span> lots</p>
                                    </div>
                                </div>
                            </div>
                            <div class="block-arrow"></div>
                            <div class="block-copy">
                                <p class="min-text">Investor's Account</p>
                                <div class="block">
                                    <div class="avatar-block">
                                        <img src="https://tifia.com/images/new-site/social-trading/avatar-2.png">
                                    </div>
                                    <div class="info-block">
                                        <p>volume: 100%</p>
                                        <p><span>5</span> lots</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="copy-traders">
                        <h3>Copying a fixed size of each trade</h3>
                        <p>This copy type implies that the volume of a trade copied onto the investor's account is always identical to the volume pre-set in copy settings.</p>
                        <p>For example, if the investor sets a "copy fixed size" of 2 lots and the trader opens 5 lots, 2 lots will be opened on the investor’s account.</p>
                        <div class="copy-traders-graph">
                            <div class="block-copy">
                                <p class="min-text">Trader's account</p>
                                <div class="block">
                                    <div class="avatar-block">
                                        <img src="https://tifia.com/images/new-site/social-trading/avatar-1.png">
                                    </div>
                                    <div class="info-block">
                                        <p>volume: 100%</p>
                                        <p><span>5</span> lots</p>
                                    </div>
                                </div>
                            </div>
                            <div class="block-arrow"></div>
                            <div class="block-copy">
                                <p class="min-text">Investor's Account</p>
                                <div class="block">
                                    <div class="avatar-block">
                                        <img src="https://tifia.com/images/new-site/social-trading/avatar-2.png">
                                    </div>
                                    <div class="info-block">
                                        <p>Fixed volume: 2 lots</p>
                                        <p><span>2</span> lots</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="copy-traders">
                        <h3>Copying a predefined % of each trade</h3>
                        <p>This copy type implies that the volume of a trade copied onto the investor's account is identical to a % of the volume of the respective trade on the trader’s account.</p>
                        <p>For example, if the investor sets 50% and the trader opens 5 lots, it will open 2.5 lots on the investor’s account. The investor can set from 1% to 10 000%.</p>
                        <div class="copy-traders-graph">
                            <div class="block-copy">
                                <p class="min-text">Trader's account</p>
                                <div class="block">
                                    <div class="avatar-block">
                                        <img src="https://tifia.com/images/new-site/social-trading/avatar-1.png">
                                    </div>
                                    <div class="info-block">
                                        <p>volume: 100%</p>
                                        <p><span>5</span> lots</p>
                                    </div>
                                </div>
                            </div>
                            <div class="block-arrow"></div>
                            <div class="block-copy">
                                <p class="min-text">Investor's Account</p>
                                <div class="block">
                                    <div class="avatar-block">
                                        <img src="https://tifia.com/images/new-site/social-trading/avatar-2.png">
                                    </div>
                                    <div class="info-block">
                                        <p>Copied volume coefficient: 50%</p>
                                        <p><span>2,5</span> lots</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="copy-traders">
                        <h3>Copying a fixed share of investor’s equity</h3>
                        <p>This copy type implies that the volume of a trade copied onto the investor's account is defined by the copy equity/trader’s equity ratio. The volume of the investor’s equity used for copying trades is defined in copy settings.</p>
                        <p>In case of using "All Equity" as copy equity, it shall be equal to the current amount of funds in the investor's account at the moment of copying the trade.</p>
                        <p><b>The volume of a trade to be copied onto the investor’s account is calculated in the following way:</b><br><i>Trade volume in the trader's account * copy equity / trader's equity = trade volume in the investor's account</i></p>
                        <p>For example, the equity on the trader's and investor’s account is $5000 and $10000 respectively</p>
                        <ul>
                            <li>If the investor sets copy equity at 2,500, trades will be copied onto his/her account with coefficient of 2,500/5,000 = 0.5 (50% of the volume of the trader’s trade).</li>
                            <li>If the investor sets copy equity at 6,000, trades will be copied onto his/her account with coefficient of 6,000/5,000 = 1.2 (120% of the volume of the trader’s trade).</li>
                            <li>If the investor uses all equity as copy equity, trades will be copied onto his/her account with coefficient of 10,000/5,000 = 2 (200% of the volume of the trader’s trade).</li>
                        </ul>
                        <div class="copy-traders-graph copy-traders-graph-more">
                            <div class="block-copy">
                                <p class="min-text">Trader's account</p>
                                <div class="block">
                                    <div class="avatar-block">
                                        <img src="https://tifia.com/images/new-site/social-trading/avatar-1.png">
                                    </div>
                                    <div class="info-block">
                                        <p>volume: 100%</p>
                                        <p><span>5</span> lots</p>
                                    </div>
                                </div>
                            </div>
                            <div class="block-copy-many">
                                <div class="line">
                                    <div class="block-arrow"></div>
                                    <div class="block-copy">
                                        <p class="min-text">Investor's Account</p>
                                        <div class="block">
                                            <div class="avatar-block">
                                                <img src="https://tifia.com/images/new-site/social-trading/avatar-3.png">
                                            </div>
                                            <div class="info-block">
                                                <p>Total equity: 10 000 USD<br>Part of equity used: 2 500 USD</p>
                                                <p><span>2,5</span> lots</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="line">
                                    <div class="block-arrow no-mob"></div>
                                    <div class="block-copy">
                                        <p class="min-text">Investor's Account</p>
                                        <div class="block">
                                            <div class="avatar-block">
                                                <img src="https://tifia.com/images/new-site/social-trading/avatar-4.png">
                                            </div>
                                            <div class="info-block">
                                                <p>Total equity: 10 000 USD<br>Part of equity used: 6 000 USD</p>
                                                <p><span>6,25</span> lots</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="line-last">
                                    <div class="block-arrow no-mob"></div>
                                    <div class="block-copy">
                                        <p class="min-text">Investor's Account</p>
                                        <div class="block">
                                            <div class="avatar-block">
                                                <img src="https://tifia.com/images/new-site/social-trading/avatar-2.png">
                                            </div>
                                            <div class="info-block">
                                                <p>Total equity: 10 000 USD<br>Part of equity used: 10 000 USD</p>
                                                <p><span>10</span> lots</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endsection



                    <ul>
                        <li class="li-4">
                            <p>Chat with your account manager live and follow their instructions.</p>
                        </li>
                    </ul>
                    <div class="img-block">
                        <!--<img src="https://tifia.com/images/new-site/social-trading/screen5.png">-->
                    </div>
                    <ul>
                        <li class="li-5">
                            <p>Learn, trade and copy successful trades.</p>
                        </li>
                    </ul>
                    <div class="img-block">
                        <!--<img src="https://tifia.com/images/new-site/social-trading/screen6.png">-->
                    </div>
                    <ul>
                        <li class="li-6">
                            <p>Achieve your aims by copying experienced trades.</p>
                        </li>
                    </ul>
                    <div class="img-block">
                        <!--<img src="https://tifia.com/images/new-site/social-trading/screen7.png">-->
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.partials.footer')
@endsection
