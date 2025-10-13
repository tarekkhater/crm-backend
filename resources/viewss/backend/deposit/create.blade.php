@extends('backend.layouts.master')

@section('content')

    <div class="content-body">
        <div class="container">
            <div class="row">
                @include('partials.menu')

                <div class="col-xl-12" style="margin-top: 10px">
                    <div class="card">
{{--                        <div class="card-header">--}}
{{--                            <h4 class="card-title">Upgraded account holders benefit from the best trade deals. </h4>--}}
{{--                        </div>--}}
                        <div class="card-body">
                            <div class="important-info">
                                <ul>
                                    <li>

{{--                                        <h4>Security of Funds</h4>--}}
                                        There is often a minimum deposit requirement for account upgrades, starting from $10,000 to $400,000
                                    </li>
                                    <li>
{{--                                        <h4>Bank Fees</h4>--}}
                                        <p>Premium Account Holder gains access to specialized
                                            benefits and innovative trading conditions with Crypto Assets</p>
                                    </li>
                                    <li>
{{--                                        <h4>Third Party Payments</h4>--}}
                                        <p>Access tighter fixed spreads and a full suite of real-time technical analysis tools.</p>

                                    </li>


                                </ul>
                            </div>
                        </div>
                    </div>
                </div>


            @foreach($packages as $item)
                    <div class="col-sm-12">
                        <div class="acc-block clearfix">


                            <div class="acc-price-block">
                                <div class="acc-type-wrap">
                                    <h2><span class="masha_index masha_index2" rel="2"></span>{{ $item->name }}<span><span class="masha_index masha_index3" rel="3"></span>account</span></h2>
                                    <img src="/images/act_type.png">
                                </div>
                                <div class="acc-btn-wrap">
                                    <span><span class="masha_index masha_index5" rel="5"></span>{{ $item->price }}</span>

                                    <a href="{{ route('deposit.purchase', $item->id) }}" class="btn btn-success btn-lg " rel="nofollow">
                                        <img src="/images/user.png" />
                                        Open
                                    </a>


                                </div>
                            </div>

                            <ul class="acc-list">
                                <li><i class="fa fa-check"></i><span class="masha_index masha_index7" rel="7"></span> Full access to all features</li>
                                <li><i class="fa fa-check"></i><span class="masha_index masha_index8" rel="8"></span> More than {{ $item->minimum_purchase / 5 }} assets</li>
                                <li><i class="fa fa-check"></i><span class="masha_index masha_index9" rel="9"></span> Up to {{ $item->percent_profit }} % bonus</li>
                                <li><i class="fa fa-check"></i><span class="masha_index masha_index10" rel="10"></span> Education courses available</li>
                                <li><i class="fa fa-check"></i><span class="masha_index masha_index11" rel="11"></span> Account manager</li>
                            </ul>
                            <ul class="acc-list">
                                <li><i class="fa fa-check"></i><span class="masha_index masha_index12" rel="12"></span> Economic calendar</li>
                                <li><i class="fa fa-check"></i><span class="masha_index masha_index13" rel="13"></span> Withdrawal- Your first withdrawal is free</li>
                            </ul>
                            <ul class="acc-list">
                                <li><i class="fa fa-check"></i><span class="masha_index masha_index41" rel="41"></span> Economic calendar</li>
                                <li><i class="fa fa-check"></i><span class="masha_index masha_index42" rel="42"></span> Daily analytics</li>
                                <li><i class="fa fa-check"></i><span class="masha_index masha_index43" rel="43"></span> Market reviews</li>
                                <li><i class="fa fa-check"></i><span class="masha_index masha_index44" rel="44"></span> Fundamental and technical analysis</li>
{{--                                <li><i class="fa fa-check"></i><span class="masha_index masha_index45" rel="45"></span> Withdrawal- One free withdrawal per month</li>--}}
                            </ul>
                        </div>
                    </div>
                @endforeach


                <div class="col-xl-12" style="margin-top: 10px">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Premium account generally offers the following benefits:</h4>
                        </div>
                        <div class="card-body">
                            <div class="important-info">
                                <ul>
                                    <li>
{{--                                        <h4>Payment process</h4>--}}
                                        => Fixed spreads from 0.9 pip – unlike market-adjusted spreads that could cause fees to increase when driven by volatility.
                                    </li>
                                    <li>

{{--                                        <h4>Security of Funds</h4>--}}
                                        => Guaranteed free stop loss and take profit.
                                          </li>
                                    <li>

{{--                                        <h4>Bank Fees</h4>--}}
                                        <p>=> Create a genuinely bespoke trading experience with a variety of instruments and CFDs.</p>


                                    </li>
                                    <li>
{{--                                        <h4>Third Party Payments</h4>--}}
                                        <p>=> Upgrade bonus range from $10,000 to $200000 depending on the chosen upgrade type</p>

                                    </li>
<li>
    => Risk-free trading advantage: What is Risk-free Trading? Risk-managed trading is the trader's right to place a trade
    without risking invested funds initially. If the forecast is correct or wrong
</li>
                                    <li>
                                        => Additional Personal Account manager and trade experts depending on the upgrade type
                                    </li>
                                    <li>
                                        => Trade limit exceed $100 million in trading volume.
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12" style="margin-top: 5px">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">How do I upgrade my Crypto Assets Account?</h4>
                        </div>
                        <div class="card-body">
                            <div class="important-info">
                                <ul>
                                    <li>
                                        You're required to select an upgrade plan that is suitable for your investment size </li>
                                    <li>
                                        Deposit the amount corresponding with your chosen upgrade plan.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
{{--                <div class="col-xl-12" style="margin-top: 5px">--}}
{{--                    <div class="card">--}}
{{--                        <div class="card-header">--}}
{{--                            <h4 class="card-title">Important Information</h4>--}}
{{--                        </div>--}}
{{--                        <div class="card-body">--}}
{{--                            <div class="important-info">--}}
{{--                                <ul>--}}
{{--                                    <li>--}}
{{--                                        <h4>Payment process</h4>--}}
{{--                                        For faster processing we recommend that all account holders deposit funds via Bitcoin cryptocurrency option from inside their Secure Client Area. From your Secure Client Area you will be able to fund your account in real time using cryptocurrency option (Bitcoin) which is the fastest funding option--}}
{{--                                    </li>--}}
{{--                                    <li>--}}

{{--                                        <h4>Security of Funds</h4>--}}
{{--                                        When funding your trading account client money is held in Segregated Client Trust Accounts, your funds are kept in AA rated banks. Electronic payments are processed using SSL (Secure Socket Layer) technology and are encrypted to ensure security. All payment information is confidential and used only for the purpose of funding your trading account with Crypto Asset Trade.--}}
{{--                                    </li>--}}
{{--                                    <li>--}}

{{--                                        <h4>Bank Fees</h4>--}}
{{--                                        <p>Crypto Asset Trade does not charge any additional fees for deposits. You should however be aware that you--}}
{{--                                            may incur fees on payments to and from some international banking institutions crypto exchanger such as--}}
{{--                                            coinbase.com, crypto.com. Crypto Asset Trade accepts no responsibility for any such bank or crypto--}}
{{--                                            exchanger fees.</p>--}}


{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <h4>Third Party Payments</h4>--}}
{{--                                        <p>Crypto Asset Trade does not accept payments from third parties.--}}
{{--                                        Please ensure that all deposits into your trading account come from a bank account in your name.--}}
{{--                                        Payments from Joint Bank Accounts / Credit Cards are accepted if the trading account holder is one--}}
{{--                                            of the parties on the Bank Account / Credit Card.</p>--}}

{{--                                    </li>--}}


{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

            </div>

        </div>
    </div>

@endsection
