@extends('backend.layouts.master')

@section('styles')
    <style>
        .form-control {
            background: #fff3cd!important;
        }
</style>

@endsection

@section('content')


    <div class="content-body">
        <div class="container">

            <div class="row">
                @include('partials.menu')


                <div class="col-xl-12">
                    @include('notification')

                    <div class="card">
                        <div class="card-header">
                            <div class="col-6"> <h4 class="card-title">Our Deposit Options </h4></div>
                            <div class="col-6">
                              <a href="{{ route('backend.pending.deposit') }}" style="color: red" class="float-right tx-danger">Pending Deposits</a>
                            </div>
{{--                            <h4 class="card-title">Make Deposit <a href="" class="float-right tx-danger">Pending Deposits</a> </h4>--}}
                        </div>
                        <div class="card-body" id="deposits">
                            Adding funds to your cryptoassest.com Live Account is extremely easy, fast and secure!
                            Take a look at all our deposit options below and please do not hesitate to contact us,
                            should you require any assistance or more information. Our Support Team is always ready to help you!
                        </div>
                        <div class="card-body" id="deposits">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tab1">Payment System</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab2">Bank Wire Transfer</a>
                                </li>
                            </ul>
                            <div class="tab-content" style="margin-top: 20px; margin-bottom: 30px">
                                <div class="tab-pane fade active show" id="tab1">
                                    <div class="row payment-methods">

                                        <div class="col-xl-3 col-lg-3 col-md-3">
                                            <div class="card">
                                                <div class="card-header text-center">
                                                    <h4 class="card-title text-center">Pay with Bitcoin</h4>
                                                </div>
                                                <div class="card-body">
                                                    <img width="100%" height="80px" src="/images/gateway/bitpay.png" />
                                                </div>
                                                <div class="card-footer">
                                                    <h4 class="card-title text-center"><a href="{{ route('backend.deposit.fund.upload') }}?type=btc"> SELECT</a></h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-3">
                                            <div class="card">
                                                <div class="card-header text-center">
                                                    <h4 class="card-title text-center">Pay with Ethereum</h4>
                                                </div>
                                                <div class="card-body">
                                                    <img width="100%" height="80px" src="/img/et.png" />
                                                </div>
                                                <div class="card-footer">
                                                    <h4 class="card-title text-center"><a href="{{ route('backend.deposit.fund.upload') }}?type=eth"> SELECT</a></h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-3">
                                            <div class="card">
                                                <div class="card-header text-center">
                                                    <h4 class="card-title text-center">Fasa Pay</h4>
                                                </div>
                                                <div class="card-body">
                                                    <img width="100%" height="80px" src="/images/gateway/fasapay.jpg" />
                                                </div>
                                                <div class="card-footer">
                                                    <h4 class="card-title text-center"><a href="{{ route('backend.gateway','Fasa Pay Gateway') }}"> SELECT</a></h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-3">
                                            <div class="card">
                                                <div class="card-header text-center">
                                                    <h4 class="card-title text-center">Western Union</h4>
                                                </div>
                                                <div class="card-body">
                                                    <img width="100%" height="80px" src="/images/gateway/3.jpg" />
                                                </div>
                                                <div class="card-footer">
                                                    <h4 class="card-title text-center"><a href="{{ route('backend.gateway','Western Union payment gateway') }}"> SELECT</a></h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-3">
                                            <div class="card">
                                                <div class="card-header text-center">
                                                    <h4 class="card-title text-center">Money Gram</h4>
                                                </div>
                                                <div class="card-body">
                                                    <img width="100%" height="80px" src="/images/gateway/1.jpg" />
                                                </div>
                                                <div class="card-footer">
                                                    <h4 class="card-title text-center"><a href="{{ route('backend.gateway','Money Gram Payment gateway') }}"> SELECT</a></h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-3">
                                            <div class="card">
                                                <div class="card-header text-center">
                                                    <h4 class="card-title text-center">Visa & MasterCard</h4>
                                                </div>
                                                <div class="card-body">
                                                    <img width="100%" height="80px" src="/images/gateway/visa.jpg" />
                                                </div>
                                                <div class="card-footer">

                                                    <h4 class="card-title text-center"><a href="{{ route('backend.gateway','Visa & MasterCard Payment gateway') }}"> SELECT</a></h4>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-3">
                                            <div class="card">
                                                <div class="card-header text-center">
                                                    <h4 class="card-title text-center">Neteller</h4>
                                                </div>
                                                <div class="card-body">
                                                    <img width="100%" height="80px" src="/images/gateway/net.jpg" />
                                                </div>
                                                <div class="card-footer">

                                                    <h4 class="card-title text-center"><a href="{{ route('backend.gateway','Neteller Payment gateway') }}"> SELECT</a></h4>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-lg-3 col-md-3">
                                            <div class="card">
                                                <div class="card-header text-center">
                                                    <h4 class="card-title text-center">Skrill</h4>
                                                </div>
                                                <div class="card-body">
                                                    <img width="100%" height="80px" src="/images/gateway/skril.png" />
                                                </div>
                                                <div class="card-footer">

                                                    <h4 class="card-title text-center"><a href="{{ route('backend.gateway','Skrill Payment gateway') }}"> SELECT</a></h4>

                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <ul>
                                        <li>
                                            <h4>Payment process</h4>
                                            For faster processing we recommend that all account holders deposit funds via Bitcoin cryptocurrency option from inside their Secure Client Area. From your Secure Client Area you will be able to fund your account in real time using cryptocurrency option (Bitcoin) which is the fastest funding option
                                        </li>
                                        <li>

                                            <h4>Security of Funds</h4>
                                            When funding your trading account client money is held in Segregated Client Trust Accounts, your funds are kept in AA rated banks. Electronic payments are processed using SSL (Secure Socket Layer) technology and are encrypted to ensure security. All payment information is confidential and used only for the purpose of funding your trading account with Crypto Asset Trade.
                                        </li>
                                        <li>

                                            <h4>Bank Fees</h4>
                                            <p>Crypto Asset Trade does not charge any additional fees for deposits. You should however be aware that you
                                                may incur fees on payments to and from some international banking institutions crypto exchanger such as
                                                coinbase.com, crypto.com. Crypto Asset Trade accepts no responsibility for any such bank or crypto
                                                exchanger fees.</p>


                                        </li>
                                        <li>
                                            <h4>Third Party Payments</h4>
                                            <p>Crypto Asset Trade does not accept payments from third parties.
                                                Please ensure that all deposits into your trading account come from a bank account in your name.
                                                Payments from Joint Bank Accounts / Credit Cards are accepted if the trading account holder is one
                                                of the parties on the Bank Account / Credit Card.</p>

                                        </li>


                                    </ul>

                                </div>
                                <div class="tab-pane fade" id="tab2">
                                    <form method="post" action="{{ route('backend.deposit.save') }}" class="py-5 col-md-6 col-sm-12">

                                        {{ csrf_field() }}

                                        <div class="form-group row align-items-center">
                                            <div class="col-sm-4">
                                                <label for="inputEmail3" class="col-form-label">Payment Method
                                                    <br>
                                                    <small>Selected Payment Method</small>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group mb-3">
                                                    <img src="/images/wt.png" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row align-items-center">
                                            <div class="col-sm-4">
                                                <label for="inputEmail3" class="col-form-label">Deposit Currency
                                                    <br>
                                                    {{--                                                    <small>Please double check this address</small>--}}
                                                </label>
                                            </div>
                                            <input type="hidden" name="payment_method" value="Wire Transfer">
                                            <div class="col-sm-8">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text  bg-primary"><i class="mdi mdi-currency-usd fs-18 text-white"></i></label>
                                                    </div>
                                                    <select class="form-control">
                                                        <option>USD</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row align-items-center">
                                            <div class="col-sm-4">
                                                <label for="inputEmail3" class="col-form-label">Amount In USD
                                                    <br>
                                                    <small>Minimum amount : {{ setting('minimum_deposit','$1000') }}</small>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text bg-primary"><i class="mdi mdi-currency-usd fs-18 text-white"></i></label>
                                                    </div>
                                                    <input name="amount" required type="number" step="any" class="form-control text-right" placeholder="5000 USD">
                                                </div>
                                            </div>
                                        </div>

                                        {{--                                        <div class="form-group row align-items-center">--}}
                                        {{--                                            <div class="col-sm-6">--}}
                                        {{--                                                <label for="inputEmail3" class="col-form-label">Bitcoin Network Fee--}}
                                        {{--                                                    (BTC)--}}
                                        {{--                                                    <br>--}}
                                        {{--                                                    <small>Transactions on the Bitcoin network are priorirized by--}}
                                        {{--                                                        fees</small>--}}
                                        {{--                                                </label>--}}
                                        {{--                                            </div>--}}
                                        {{--                                            <div class="col-sm-6">--}}
                                        {{--                                                <h4 class="text-right">0.005</h4>--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </div>--}}

                                        <div class="text-right">
                                            <button class="btn btn-primary">Proceed </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                @section('hides')

                <div class="col-xl-12" id="payment">
                    <div class="card">
                        <div class="card-header">
                            <div class="col-6"> <h4 class="card-title">Make Deposit </h4></div>
                        </div>
                        <div class="card-body" id="deposit">

                            <form method="post" action="{{ route('backend.deposit.store') }}" class="py-5 col-md-6 col-sm-12">

                                {{ csrf_field() }}

                                <div class="form-group row align-items-center">
                                    <div class="col-sm-4">
                                        <label for="inputEmail3" class="col-form-label">Payment Method
                                            <br>
                                            <small>Selected Payment Method</small>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group mb-3">
                                            <img src="/images/wt.png" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row align-items-center">
                                    <div class="col-sm-4">
                                        <label for="inputEmail3" class="col-form-label">Deposit Currency
                                            <br>
                                            {{--                                                    <small>Please double check this address</small>--}}
                                        </label>
                                    </div>
                                    <input type="hidden" name="payment_method" value="Wire Transfer">
                                    <div class="col-sm-8">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text  bg-primary"><i class="mdi mdi-currency-usd fs-18 text-white"></i></label>
                                            </div>
                                            <select class="form-control">
                                                <option>USD</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row align-items-center">
                                    <div class="col-sm-4">
                                        <label for="inputEmail3" class="col-form-label">Amount In USD
                                            <br>
                                            <small>Minimum amount : {{ setting('minimum_deposit','$1000') }}</small>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-primary"><i class="mdi mdi-currency-usd fs-18 text-white"></i></label>
                                            </div>
                                            <input name="amount" required type="number" step="any" class="form-control text-right" placeholder="5000 USD">
                                        </div>
                                    </div>
                                </div>

                                {{--                                        <div class="form-group row align-items-center">--}}
                                {{--                                            <div class="col-sm-6">--}}
                                {{--                                                <label for="inputEmail3" class="col-form-label">Bitcoin Network Fee--}}
                                {{--                                                    (BTC)--}}
                                {{--                                                    <br>--}}
                                {{--                                                    <small>Transactions on the Bitcoin network are priorirized by--}}
                                {{--                                                        fees</small>--}}
                                {{--                                                </label>--}}
                                {{--                                            </div>--}}
                                {{--                                            <div class="col-sm-6">--}}
                                {{--                                                <h4 class="text-right">0.005</h4>--}}
                                {{--                                            </div>--}}
                                {{--                                        </div>--}}

                                <div class="text-right">
                                    <button class="btn btn-primary">Proceed </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                @stop


            </div>
        </div>
    </div>

@endsection
