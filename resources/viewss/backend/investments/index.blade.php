@extends('backend.layouts.backend')

@section('content')
    <div class="container-fluid">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Invested Plans</h4>
                        At a glance summary of your investment.
                    </div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('backend.plans') }}" class="btn btn-primary mt-3">Invest & Earn</a> </li>

                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12 col-md-5 mt-2">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Investment Account </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <h5 class="card-title">{{ amt(auth()->user()->invEarning()) }}</h5>
                                <p class="card-text">Available Funds</p>
                            </div>
                            <div class="col-2"><h4>+</h4></div>
                            <div class="col-5">
                                <h5 class="card-title">{{ amt(auth()->user()->lockedInvFund()) }} </h5>
                                <p class="card-text">Locked</p>
                            </div>

                        </div>

                        <a href="#" class="btn btn-primary mt-3">Transfer Fund</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-7 mt-2">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Amount Invested </h5>
                    </div>
                    <div class="card-body mb-2">
                        <div class="row">
                            <div class="col-md-8 col-12">
                                <div class="row">
                                    <div class="col-5">
                                        <h5 class="card-title">{{ auth()->user()->activeInvested() }} USD</h5>
                                        <p class="card-text">Currently Invested</p>
                                    </div>
                                    <div class="col-2"><h4>+</h4></div>
                                    <div class="col-5">
                                        <h5 class="card-title">{{ auth()->user()->activeInvestedProfit() }}% </h5>
                                        <p class="card-text">Approx Profit</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Transactions</a> </li>
                            <li class="breadcrumb-item">History</li>
                        </ol>
                    </div>
                </div>
            </div>

        </div>
        <!-- END: Card DATA-->

        @if(count($pending_investments) > 0)
            <div class="row mt-2">
                <div class="col-xl-12 col-lg-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Pending Investments ({{ count($pending_investments) }})</h5>
                        </div>
                        <div class="card-body">
                            <div class="transaction-table">
                                <div class="table-responsive">
                                    <table class="display table dataTable table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <td></td>
                                            <th>Plan</th>
                                            <th>Amount</th>
                                            <th>Earned Profit</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Period</th>
                                            <th>Status</th>
                                            <th>Date Created</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($pending_investments as $item)
                                            <tr>
                                                @if ($item->status > 0)
                                                    <td><span class="text-success"><i class="fa fa-arrow-up"></i></span>
                                                    </td>
                                                @else
                                                    <td><span class="text-danger"><i class="fa fa-arrow-down"></i></span>
                                                    </td>
                                                @endif

                                                <td>{{ optional($item->plan)->name }}</td>
                                                <td>{{ amt($item->amount) }}</td>
                                                <td>{{ $item->profit }} %</td>
                                                <td>{{ $item->start_date }}</td>
                                                <td>{{ $item->end_date }}</td>
                                                <td>{{ optional($item->plan)->plan_period }}</td>
                                                <td>
                                                    @if ($item->status < 1)
                                                        <span class="badge badge-danger">{{ $item->status_text }}</span>
                                                    @else
                                                        <span class="badge badge-success">{{ $item->status_text }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->created_at }}</td>

                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @endif
        @if(count($active_investments) > 0)
            <div class="row mt-2">
                <div class="col-xl-12 col-lg-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Active Investments ({{ count($active_investments) }})</h5>
                        </div>
                        <div class="card-body">
                            <div class="transaction-table">
                                <div class="table-responsive">
                                    <table class="display table dataTable table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <td></td>
                                            <th>Plan</th>
                                            <th>Amount</th>
                                            <th>Approx Profit</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Period</th>
                                            <th>Status</th>
                                            <th>Date Created</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($active_investments as $item)
                                            <tr>
                                                @if ($item->status > 0)
                                                    <td><span class="text-success"><i class="fa fa-arrow-up"></i></span>
                                                    </td>
                                                @else
                                                    <td><span class="text-danger"><i class="fa fa-arrow-down"></i></span>
                                                    </td>
                                                @endif

                                                <td>{{ $item->plan()->name }}</td>
                                                <td>{{ amt($item->amount) }}</td>
                                                <td>{{ $item->profit }} %</td>
                                                <td>{{ $item->start_date }}</td>
                                                <td>{{ $item->end_date }}</td>
                                                <td>{{ $item->period }} day(s)</td>
{{--                                                <td>{{ optional($item->plan)->plan_period }}</td>--}}
                                                <td>
                                                    @if ($item->status < 1)
                                                        <span class="badge badge-danger">{{ $item->status_text }}</span>
                                                    @else
                                                        <span class="badge badge-success">{{ $item->status_text }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->created_at }}</td>

                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @endif
        @if(count($completed_nvestments) > 0)
            <div class="row mt-2">
                <div class="col-xl-12 col-lg-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Completed Investments ({{ count($completed_nvestments) }})</h5>
                        </div>
                        <div class="card-body">
                            <div class="transaction-table">
                                <div class="table-responsive">
                                    <table class="display table dataTable table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <td></td>
                                            <th>Plan</th>
                                            <th>Amount</th>
                                            <th>Earned</th>
                                            <th>Approx Profit</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Period</th>
                                            <th>Status</th>
                                            <th>Date Created</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($completed_nvestments as $item)
                                            <tr>
                                                @if ($item->status > 0)
                                                    <td><span class="text-success"><i class="fa fa-arrow-up"></i></span>
                                                    </td>
                                                @else
                                                    <td><span class="text-danger"><i class="fa fa-arrow-down"></i></span>
                                                    </td>
                                                @endif

                                                <td>{{ optional($item->plan)->name }}</td>
                                                <td>{{ amt($item->amount) }}</td>
                                                <td>{{ amt($item->earned) }}</td>
                                                <td>{{ $item->profit }} %</td>
                                                <td>{{ $item->start_date }}</td>
                                                <td>{{ $item->end_date }}</td>
                                                <td>{{ optional($item->plan)->plan_period }}</td>
                                                <td>
                                                    @if ($item->status < 1)
                                                        <span class="badge badge-danger">{{ $item->status_text }}</span>
                                                    @else
                                                        <span class="badge badge-success">{{ $item->status_text }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->created_at }}</td>

                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @endif

    </div>

{{--    @include('partials.datatable')--}}

@endsection
