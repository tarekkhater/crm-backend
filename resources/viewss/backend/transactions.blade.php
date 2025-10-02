@extends('backend.layouts.backend')

@section('content')
    <div class="container-fluid">

        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Transaction History</h4>
                        <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}"> Dashboard</a></li>
                            <li class="breadcrumb-item">Transaction History ({{ count($transactions) }})</li>
                        </ol>
                    </div>


                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->


        <div class="row">


            <div class="col-xl-12 col-lg-12 col-sm-12">

                <div style="margin-top: 10px" class="card">
{{--                    <div class="card-header">--}}
{{--                        <h5 class="card-title">Your Transaction History</h5>--}}
{{--                    </div>--}}
                    <div class="card-body">
                        <div class="transaction-table">
                            <div class="table-responsive">
                                <div class="d-sm-none d-md-block hide-sm_mol">
                                <table id="datatable" class="display text-center table dataTable  table-striped">
                                    <thead class="text-center">
                                    <tr>
                                        <th class="min-phone-l  text-center"></th>

                                        <th class="text-center">Type</th>
{{--                                        <th class="text-center">Account</th>--}}


                                        <th class="text-center">Amount</th>

                                        <th class="text-center">Date</th>
                                        <th class="text-center">Note</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($transactions as $item)
                                        <tr>
{{--                                            <td>{{ $loop->index+1 }}</td>--}}
                                            @if ($item->status)
                                                <td class="min-phone-l" >
{{--                                                    <span class="buy-thumb"><i class="fa fa-arrow-up"></i></span>--}}
                                                    <span class="text-success">In</span>

                                                </td>
                                            @else
                                                <td class="min-phone-l">
                                                    <span class="text-danger">
                                                       Out
                                                    </span>

{{--                                                    <span class="sold-thumb"><i class="fa fa-arrow-down"></i></span>--}}
                                                </td>
                                            @endif


                                            <td>
                                                <span class="badge {{ $item->status ? 'text-success' : 'text-danger' }} p-2">{{ $item->type }}</span>
                                            </td>
{{--                                            <td>--}}
{{--                                                <i class="cc BTC"></i> {{ $item->account_type }}--}}
{{--                                            </td>--}}


                                            <td class="{{ $item->account_type == 'withdrawal' ? 'text-danger' : 'text-success' }}">{{ $item->amount }} USD</td>

                                            <td>{{ $item->created_at }}</td>
                                            <td class="">
                                                {{ $item->note  }}
                                            </td>
                                        </tr>

                                    @endforeach
                                    </tbody>
                                </table>
                                </div>
                                <div class="d-md-none d-block">

                                    <table style="font-size: 13px" id="datatable2" class="display  table dataTable  table-striped">
                                        <thead class="text-center">
                                        <tr>


                                            <th class="">Type</th>



                                            <th class="">Amount</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($transactions as $item)
                                            <tr>



                                                <td>
                                                    <span class="badge {{ $item->status ? 'text-success' : 'text-danger' }} p-2">{{ $item->type }}</span>
                                                    <br>
                                                    <span style="color: #555A64"> {{ $item->account_type }}</span>
                                                <br>
                                                   <span style="color: #555A64">{{ $item->created_at }}</span>

                                                    <br>
                                                <span style="color: #555A64">
                                                     {{ $item->note  }}
                                                </span>






                                                <td class="{{ $item->account_type == 'withdrawal' ? 'text-danger' : 'text-success' }}">{{ $item->amount }} USD</td>


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

    </div>


    @include('partials.datatable')
@endsection


