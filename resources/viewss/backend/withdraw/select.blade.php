@extends('backend.layouts.backend')


@section('content')
    <div class="container-fluid">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h6 class="mb-0">Account Fund Withdrawal</h6></div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}"> Dashboard</a></li>
                        <li class="breadcrumb-item">Withdraw Funds</li>
                    </ol>
                </div>
            </div>
        </div>


        <div class="row mt-2">
            <div class="col-md-12">
                <div class="card">
                    <div style="width: 100%" class=" card-body">
                        <h6 class="card-title"> Account Balance :  {{ auth()->user()->balance() }}</h6>
                    </div>
                </div>
            </div>

        </div>

        <div class="row mt-2">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6 pull-left">
                                <h6 class="card-title text-capitalize">Select account to withdraw to</h6></div>
                            <div class="col-6 pull-right">
                                <a href="{{ route('backend.pending.withdrawal') }}" style="color: red" class="float-right pull-right tx-danger">All Withdrawals</a>
                            </div>
                        </div>

                        {{--                            <h4 class="card-title">Make Deposit <a href="" class="float-right tx-danger">Pending Deposits</a> </h4>--}}
                    </div>
                    <div class="card-body" id="deposits">
                        <div class="tab-content" style="margin-top: 20px; margin-bottom: 30px">
                            <div class="tab-pane fade active show" id="tab1">
                                <div class="row payment-methods">

                                    @if(setting('crypto_withdraw',1) > 0)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="card text-center p-1">
                                            <div class="card-body">
                                                <h5 class="mt-2">Crypto Wallet</h5>
                                                <div class="m-4">
                                                    @if (auth()->user()->accounts()->where('type','crypto')->count() > 0)
                                                        <a href="{{ route('backend.withdraw.wallet', 'crypto') }}" class="btn btn-primary">Select</a>
                                                    @else
                                                        <button  data-toggle="modal" data-target="#addAccount" class="btn btn-primary p-2 ">
                                                            <span class="text-white font-weight-bold h6">+ Add Account</span></button>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if(setting('wire_withdraw') > 0)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="card text-center p-1">
                                            <div class="card-body">
                                                <h5 class="mt-2">Wire Transfer</h5>
                                                <div class="m-4">
                                                    @if (auth()->user()->accounts()->where('type','wire')->count() > 0)
                                                        <a href="{{ route('backend.withdraw.wallet', 'wire') }}" class="btn btn-primary">Select</a>
                                                    @else
                                                        @if (auth()->user()->wireAccounts()->count() > 0)
                                                            <button  data-toggle="modal" data-target="#addAccount" class="btn btn-primary p-2 ">
                                                                <span class="text-white font-weight-bold h6">+ Add Account</span></button>
                                                        @else
                                                            <button  data-toggle="modal" data-target="#addWireAccount" class="btn btn-primary p-2 ">
                                                                <span class="text-white font-weight-bold h6">+ Add Wire Account</span></button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
{{--                                    @if(setting('wire_withdraw') > 0)--}}
{{--                                    <div class="col-md-4 col-sm-6">--}}
{{--                                        <div class="card text-center p-1">--}}
{{--                                            <div class="card-body">--}}
{{--                                                <h5 class="mt-2">Paypal</h5>--}}
{{--                                                <div class="m-4">--}}
{{--                                                    <a href="#" disabled="" class="btn btn-primary">Disabled</a>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    @endif--}}
                                </div>


                            </div>
                            <div class="tab-pane fade" id="tab2">
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
                                            <label for="inputEmail3" class="col-form-label">Withdrawal Currency
                                                <br>
                                                {{--                                                    <small>Please double check this address</small>--}}
                                            </label>
                                        </div>
                                        <input type="hidden" name="method" value="Wire Transfer">
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
                                                <small>Minimum amount : {{ setting('minimum_withdraw','$1000') }}</small>
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
        </div>



        <div class="row">
            <div style="margin-top: 10px" class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Status definitions</h6>
                    </div>
                    <div class="card-body">
                        <div class="important-info">
                            <ul>
                                <li>
                                    <i class="mdi mdi-checkbox-blank-circle"></i>
                                    <span class="badge badge-info">Processing</span>
                                    We have received confirmation of your withdrawal request, and will be processing your request shortly.
                                </li>
                                <li>
                                    <i class="mdi mdi-checkbox-blank-circle"></i>
                                    <span class="badge badge-success">Completed</span>
                                    Your withdrawal request has been successfully processed and your funds are on the way to your designated account.
                                </li>
                                <li>
                                    <i class="mdi mdi-checkbox-blank-circle"></i>
                                    <span class="badge badge-warning">Cancelled</span>
                                    Your withdrawal has been canceled as per your request.
                                </li>

                                <li>
                                    <i class="mdi mdi-checkbox-blank-circle"></i>
                                    <span class="badge badge-danger">Declined</span>
                                    Your withdrawal request was not able to be processed. An email has been sent to you with more details.
                                    If you require any further clarifications please contact {{ setting('support_email') }}
                                </li>



                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


@endsection

@section('js')

@endsection
