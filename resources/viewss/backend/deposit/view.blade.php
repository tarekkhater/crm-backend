

@extends('backend.layouts.master')

@section('content')

    <div class="content-body">
        <div class="container">
            <div class="row">
                @include('partials.menu')

                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <div class="card profile_card">
                            <div class="card-body">
                                <div class="media">
                                    <div class="media-body">
                                        <h4 class="mb-2">Deposit Details</h4>
                                    </div>
                                </div>

                                <table class="table table-responsive">
                                    <tr><th>Amount Deposited</th><th>${{ $deposit->amount  }}</th></tr>
                                    <tr><th>Deposit Status</th>
                                        <th>
                                            @if($deposit->status)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-warning">Pending</span>
                                            @endif
                                        </th>
                                    </tr>
                                    <tr><th>Date of Transaction</th><th>{{ $deposit->created_at  }}</th></tr>
                                    <tr><th>Payment Method</th><th>{{ $deposit->payment_method  }}</th></tr>

                                    <tr><th>Deposit Proof</th>
                                        <th>
                                            @if ($deposit->proof)
                                                <a href="{{ $deposit->proof }}">View deposit proof</a>
                                            @else
                                                Not Uploaded
                                            @endif
                                        </th></tr>

                                    <tr><th>Account Name</th><th>{{ Auth()->user()->name  }}</th></tr>
                                    <tr><th>Account Email</th><th>{{ Auth()->user()->email  }}</th></tr>
{{--                                    <tr><th>Amount Deposited Pair</th><th>{{ $deposit->amount  }}</th></tr>--}}

                                </table>

                            </div>
                        </div>
                    </div>
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Trading Histories</h4>
                        </div>
                        <div class="card-body">
                            <div class="transaction-table">
                                <div class="table-responsive">
                                    @if ($deposit->status)
                                        <table class="table table-striped mb-0 table-responsive-sm">
                                            <thead>
                                            <tr>
                                                <th>Amount</th>
                                                <th>Plan</th>
                                                <th> Status</th>
                                                <th>Date </th>
                                                <th>View </th>


                                            </tr>
                                            </thead>
                                            <tbody>


                                            @foreach($deposits as $item)
                                                <tr>
                                                    <td>{{ $item->amount }} USD</td>
                                                    <td>{{ optional($item->plan)->name }}</td>
                                                    <td>
                                                        @if($item->status)
                                                            <p class="badge badge-success">Active</p>
                                                        @else
                                                            <p class="badge badge-warning">Pending</p>
                                                            @endif
                                                    </td>
                                                    <td>{{ $item->created_at->format('Y-m-d') }}</td>
                                                    <td><a class="btn btn-sm btn-success" href="">details</a></td>
                                                    {{--<td>{{ $item-> }}</td>--}}

                                                    {{--<td>{{ $item-> }}</td>--}}
                                                    {{--<td>{{ $item-> }}</td>--}}
                                                    {{--<td>{{ $item->}}</td>--}}

                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    @endif

                                        @if (!$deposit->status)
                                            <div class="text-center">
                                                <h5 class="text-center mt-4">No Trading Yet, deposit still pending, contact admin if your payment confirmation takes longer than 24hrs</h5>

                                                <a class="btn text-center btn-success mt-4" href="{{ route('backend.deposits.proof',$deposit->id) }}">Re upload Payment Proof</a>
                                            </div>

                                        @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection

@section('js')

@endsection
