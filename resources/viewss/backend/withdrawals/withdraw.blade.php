@extends('backend.layouts.backend')

@section('content')

    <div class="container-fluid">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h5 class="mb-0">Withdrawal</h5></div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}"> Dashboard</a></li>
                        <li class="breadcrumb-item">Withdrawal</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <div class="card  invest shadow-lg mt-4 p-3">
            <div class="card-header">
                <div class="row">
                    <div class="col-6"> <h6>SELECT ACCOUNT TO WITHDRAW FROM</h6></div>
                    <div class="col-6">
                        <a href="{{ route('backend.pending.withdrawal') }}" style="color: red" class="float-right tx-danger">All Withdrawals</a>
                    </div>
                </div>
                {{--                            <h4 class="card-title">Make Deposit <a href="" class="float-right tx-danger">Pending Deposits</a> </h4>--}}
            </div>
            <div class="card-body">
            <div class="row">
                <div class="col-6 col-md-3 mb-2">
                    <div class="card">
                        <div class="card-body mt-4">
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-center icon-big"><i class="fa fa-download text-warning"></i></div>
                                </div>
                                <div class="col-9 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Balance</p>
                                        <h4 class="card-title text-light">$0.00</h4>
                                        <a href="{{ route('backend.btc.withdrawal') }}?t=account_balance" class="withdraw">Select</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <div class="card">
                        <div class="card-body mt-4">
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-center icon-big"><i class="fa fa-arrow-up text-success"></i></div>
                                </div>
                                <div class="col-9 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Profit</p>
                                        <h4 class="card-title text-light">$0.00</h4>
                                        <a href="{{ route('backend.btc.withdrawal') }}?t=profit" class="withdraw">Select</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <div class="card">
                        <div class="card-body mt-4">
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-center icon-big"><i class="fa fa-gift text-danger"></i></div>
                                </div>
                                <div class="col-9 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Bonus</p>
                                        <h4 class="card-title text-light">$ 0.00</h4>
                                        <a href="{{ route('backend.btc.withdrawal') }}?t=bonus" class="withdraw">Select</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <div class="card ">
                        <div class="card-body mt-4">
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-center icon-big"><i class="fa fa-retweet text-primary"></i></div>
                                </div>
                                <div class="col-9 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Ref. Bonus</p>
                                        <h4 class="card-title text-light">$0.00</h4>
                                        <a href="{{ route('backend.btc.withdrawal') }}?t=ref_bonus" class="withdraw">Select</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 mb-2">
                    <div class="card">
                        <div class="card-body mt-4">
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-center icon-big"><i class="fa fa-envelope text-danger"></i></div>
                                </div>
                                <div class="col-9 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">All Withdrawals</p>
                                        <h4 class="card-title text-light">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <div class="card ">
                        <div class="card-body mt-4">
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-center icon-big"><i class="fa fa-envelope-open text-primary"></i></div>
                                </div>
                                <div class="col-9 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Active Plans</p>
                                        <h4 class="card-title text-light">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <div class="card ">
                        <div class="card-body mt-4">
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-center icon-big"><i class="fa fa-clipboard-list text-primary"></i></div>
                                </div>
                                <div class="col-9 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Pending Withdrawals</p>
                                        <h4 class="card-title text-light">0</h4>
                                    </div>
                                </div>
                            </div>
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
