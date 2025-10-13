

@extends('backend.layouts.backend')

@section('content')
    <div class="container-fluid">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Withdraw History</h4></div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}"> Dashboard</a></li>
                        <li class="breadcrumb-item">Withdrawals</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <div class="row">


            <div class="col-xl-12 col-lg-12 col-sm-12">

                <div style="margin-top: 10px" class="card">
                    <div class="card-header">
                        <h5 class="card-title">Your Withdrawals</h5>
                    </div>
                    <div class="card-body">
                        <div class="transaction-table">
                            <div class="table-responsive">
                                <table id="datatable" class="display table dataTable table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <td></td>
                                        <th>Type</th>
                                        <th>Method</th>
{{--                                        <th>Type</th>--}}
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Note</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($withdrawals as $item)
                                        <tr>
                                            @if ($item->approved)
                                                <td><span class="buy-thumb"><i class="fa fa-arrow-up"></i></span>
                                                </td>
                                            @else
                                                <td><span class="sold-thumb"><i class="fa fa-arrow-down"></i></span>
                                                </td>
                                            @endif


                                            <td class="text-uppercase">
                                                {{ optional($item->account)->type }}
{{--                                                <span class="badge {{ $item->approved ? 'badge-success' : 'badge-danger' }} p-2">{{ $item->approved ? 'Approved' : 'Pending' }}</span>--}}
                                            </td>
                                            <td class="text-uppercase">
                                                @if (optional($item->account)->type == 'crypto')
                                                    <i class="cc BTC"></i> {{ optional($item->account)->wallet }}
                                                @else
                                                    <i class="cc BTC"></i> Others
                                                @endif
                                            </td>
{{--                                            <td>--}}
{{--                                                <i class="cc BTC"></i> {{ $item->type }}--}}
{{--                                            </td>--}}

                                            <td class="{{ $item->status ? 'text-danger' : 'text-success' }}">{{ $item->amount }} USD</td>
                                            <td class="">
                                                @if(!$item->approved)
                                                    @if (!$item->status == 'declined')
                                                        @if ($item->processed)
                                                            <a class="badge badge-warning p-2" href="#">Pending Approval</a>
                                                        @else
                                                            <a class="badge badge-primary p-2" href="{{ route('backend.withdrawal.processing',$item->id)  }}">Continue Transaction</a>
                                                        @endif
                                                    @else
                                                        <a href="#" class="badge badge-danger p-2">Declined</a>
                                                    @endif
                                                   
                                                @else
                                                    <a href="#" class="badge badge-success p-2">Completed</a>
                                                @endif
                                            </td>
                                            <td>{{ $item->note }}</td>
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

    </div>


    @include('partials.datatable')

@endsection


@section('js')

@endsection
