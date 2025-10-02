

<div class="card  invest shadow-lg mt-4 p-3">
            <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="card">
                <div class="card-body ">
                    <div class="row">
                        <div class="col-3">
                            <div class="text-center icon-big"><i class="fa fa-download text-warning"></i></div>
                        </div>
                        <div class="col-9 col-stats">
                            <div class="numbers">
                                <p class="card-category">Invested</p>
                                <h4 class="card-title text-light">$0.00</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card">
                <div class="card-body ">
                    <div class="row">
                        <div class="col-3">
                            <div class="text-center icon-big"><i class="fa fa-arrow-up text-success"></i></div>
                        </div>
                        <div class="col-9 col-stats">
                            <div class="numbers">
                                <p class="card-category">Profit</p>
                                <h4 class="card-title text-light">$0.00</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="text-center icon-big"><i class="fa fa-gift text-danger"></i></div>
                        </div>
                        <div class="col-9 col-stats">
                            <div class="numbers">
                                <p class="card-category">Bonus</p>
                                <h4 class="card-title text-light">$ 0.00</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card ">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="text-center icon-big"><i class="fa fa-retweet text-primary"></i></div>
                        </div>
                        <div class="col-9 col-stats">
                            <div class="numbers">
                                <p class="card-category">Ref. Bonus</p>
                                <h4 class="card-title text-light">$0.00</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="text-center icon-big"><i class="fa fa-envelope text-danger"></i></div>
                        </div>
                        <div class="col-9 col-stats">
                            <div class="numbers">
                                <p class="card-category">Total Plans</p>
                                <h4 class="card-title text-light">1</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card ">
                <div class="card-body">
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
        <div class="col-sm-6 col-md-3">
            <div class="card ">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="text-center icon-big"><i class="fa fa-clipboard-list text-primary"></i></div>
                        </div>
                        <div class="col-9 col-stats">
                            <div class="numbers">
                                <p class="card-category">Pending Plans</p>
                                <h4 class="card-title text-light">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>


        <div class="row">


            <div class="col-12">

                <div style="margin-top: 10px" class="card">
                    <div class="card-header">
                        <h5 class="card-title">Investment plans</h5>
                    </div>
                    <div class="card-body">
                        <div class="transaction-table">
                            <div class="table-responsive">
                                <table id="datatable" class="display table dataTable table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <td></td>
                                        <th>Currency</th>
                                        <th>Currency Pair</th>
                                        <th>Trade Time</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Profit / Lose</th>
                                        <th>Opening Price</th>

                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($trades as $item)
                                        <tr>
                                            @if ($item->is_win)
                                                <td><span class="text-success"><i class="fa fa-arrow-up"></i></span>
                                                </td>
                                            @else
                                                <td><span class="text-danger"><i class="fa fa-arrow-down"></i></span>
                                                </td>
                                            @endif

                                            <td><img src="{{ optional($item->currency)->image }}" height="30" />
                                            </td>
                                            <td>{{ $item->currency_pair }}
                                            </td>
                                            <td>{{ $item->created_at }}
                                            </td>
                                            <td>${{ $item->traded_amount }}</td>
                                            <td class="text-success">Closed</td>


                                            <td class="{{ $item->is_win ? 'text-success' : 'text-danger' }}">{{ $item->is_win ? 'Win' : 'Loss' }}</td>

                                            <td>{{ $item->o_price }}</td>
                                            {{--                                                    <td>{{ $item->created_at }}</td>--}}
                                        </tr>


                                        {{--                                                <tr>--}}
                                        {{--                                                    @if ($item->is_win)--}}
                                        {{--                                                        <td><span class="buy-thumb"><i class="mdi mdi-arrow-up"></i></span>--}}
                                        {{--                                                        </td>--}}
                                        {{--                                                    @else--}}
                                        {{--                                                        <td><span class="sold-thumb"><i class="mdi mdi-arrow-down"></i></span>--}}
                                        {{--                                                        </td>--}}
                                        {{--                                                    @endif--}}

                                        {{--                                                    <td><img src="{{ optional($item->currency)->image }}" height="30" />--}}
                                        {{--                                                    </td>--}}
                                        {{--                                                    <td>{{ $item->currency_pair }}--}}
                                        {{--                                                    </td>--}}
                                        {{--                                                       <td>{{ $item->created_at }}--}}
                                        {{--                                                    </td>--}}
                                        {{--                                                        <td>${{ $item->traded_amount }}</td>--}}


                                        {{--                                                    <td class="{{ $item->is_win ? 'text-success' : 'text-danger' }}">{{ $item->is_win ? 'Win' : 'Loss' }}</td>--}}

                                        {{--                                                        <td>{{ $item->is_win ? '+' : '-' }}${{ $item->payout }}</td>--}}
                                        {{--                                                        <td>{{ $item->o_price }}</td>--}}
                                        {{--                                                        <td>{{ $item->c_price }}</td>--}}
                                        {{--                                                    <td>{{ $item->created_at }}</td>--}}
                                        {{--                                                </tr>--}}

                                    @endforeach


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        @include('partials.datatable')
