@extends('backend.layouts.backend')

@section('content')

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

            @include('partials.menu')

            <div class="col-md-12">
                <div style="width: 100%" class="card">
                    <div class="card-body">
                        <h6>Available Balance :{{ auth()->user()->aBalance() }}</h6>
                    </div>
                </div>
            </div>


            @if (auth()->user()->can_withdraw)
                <div class="col-xl-12 mt-2">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6 pull-left">
                                    <h6 class="card-title text-capitalize">{{ $title }}</h6>
                                </div>
                                <div class="col-6 pull-right">
                                    <a href="{{ route('backend.pending.withdrawal') }}" style="color: red" class="float-right tx-danger">Pending Withdrawals</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="deposits">
                            <div class="row">
                                <form method="post" action="{{ route('backend.withdrawals.store') }}" class="py-5 col-md-6 col-sm-12">

                                    {{ csrf_field() }}

                                    <input type="hidden" name="type" value="{{ $wallet }}" />

{{--                                    @if ($wallet == 'crypto')--}}
{{--                                        <div class="form-group row align-items-center">--}}
{{--                                            <div class="col-sm-4">--}}
{{--                                                <label for="inputEmail3" class="col-form-label text-capitalize">Select {{ $wallet }} account--}}
{{--                                                    <br>--}}
{{--                                                    <small>Please select the crypto account to withdraw to</small>--}}
{{--                                                </label>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-sm-8">--}}
{{--                                                <div class="input-group mb-3">--}}

{{--                                                    <select class="form-control">--}}
{{--                                                        <option>USD</option>--}}
{{--                                                    </select>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}


                                    <div class="form-group row align-items-center">
                                        <div class="col-sm-4">
                                            <label for="inputEmail3" class="col-form-label">Selected Account
                                                <br>
                                                <small> <a href="{{ route('backend.withdraw.select') }}">Change Account</a></small>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group mb-3">
                                                <input name="account" required  disabled  value="{{ $title }}" class="form-control text-capitalize">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group row align-items-center">
                                            <div class="col-sm-4">
                                                <label for="inputEmail3" class="col-form-label text-capitalize">Select {{ $wallet }} account
                                                    <br>
                                                    <small>Please select the {{ $wallet }} account to withdraw to</small>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="input-group mb-3">

                                                    <select name="account_id" class="form-control">
                                                        @foreach($accounts as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                    <div class="form-group row align-items-center">
                                        <div class="col-sm-4">
                                            <label for="inputEmail3" class="col-form-label">Amount In USD
                                                <br>
                                                <small>Minimum amount : $ {{ setting('minimum_withdraw',100) }}</small>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group mb-3">
                                                <input value="{{ old('amount') }}" name="amount" required type="number" step="any" class="form-control text-right" placeholder="5000 USD">
                                            </div>
                                        </div>
                                    </div>

{{--                                    @if ($wallet == 'crypto')--}}
{{--                                        <div class="form-group row align-items-center">--}}
{{--                                            <div class="col-sm-4">--}}
{{--                                                <label for="inputEmail3" class="col-form-label">Wallet Address--}}
{{--                                                    <br>--}}
{{--                                                    <small>Please double check this address </small>--}}
{{--                                                </label>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-sm-8">--}}
{{--                                                <div class="input-group mb-3">--}}
{{--                                                    <div class="input-group-prepend">--}}
{{--                                                        <label class="input-group-text bg-primary"><i class="mdi mdi-currency-btc fs-18 text-white"></i></label>--}}
{{--                                                    </div>--}}
{{--                                                    <input name="wallet" required type="text" class="form-control text-right" placeholder="">--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}

                                    <div class="text-right">
                                        <button class="btn btn-primary">Proceed </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-12 mt-2">
                    <div class="alert alert-danger">
                        <h6>Withdrawal is disabled for this account</h6>
                        You cant make withdrawal from this account, pls contact support if you are seeing this by mistake
                    </div>
                </div>

                @endif


            <div class="col-xl-12 mt-2">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Important Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="important-info">
                            <ul>
                                <li>
                                    <i class="mdi mdi-checkbox-blank-circle"></i>
                                    For security reasons, {{ env('APP_NAME') }} process withdrawals by review once a day.
                                </li>
                                <li>
                                    <i class="mdi mdi-checkbox-blank-circle"></i>
                                    Submit your withdrawals by 07:00 UTC +00 (about 11 hour) to be included in
                                    the days batch
                                </li>
                                <li>
                                    <i class="mdi mdi-checkbox-blank-circle"></i>
                                    If you are using a public server for your transactions, please ensure you logout before leaving the browser
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
