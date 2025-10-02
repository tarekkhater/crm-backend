@extends('backend.layouts.backend')

@section('content')
    <div class="container-fluid">

        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Deposit Histories</h4></div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}"> Dashboard</a></li>
                        <li class="breadcrumb-item">Deposit Histories</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->


        <div class="row">


            <div class="col-xl-12 col-lg-12 col-sm-12">

                <div style="margin-top: 10px" class="card">
                    <div class="card-header">
                        <h5 class="card-title">Your Deposit History</h5>
                    </div>
                    <div class="card-body">
                        <div class="transaction-table">
                            <div class="table-responsive">
                                <table id="datatable" class="display table dataTable table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Status</th>
                                        <th>Currency</th>
                                        <th>Amount</th>
                                        <th>Proofs</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($deposits as $item)
                                        <tr>
{{--                                            <td>{{ $loop->index+1 }}</td>--}}
                                            @if ($item->status)
                                                <td><span class="buy-thumb"><i class="fa fa-arrow-up"></i></span>
                                                </td>
                                            @else
                                                <td><span class="sold-thumb"><i class="fa fa-arrow-down"></i></span>
                                                </td>
                                            @endif


                                            <td>
                                                <span class="badge {{ $item->status ? 'badge-success' : 'badge-danger' }} p-2">{{ $item->status ? 'Approved' : 'Pending' }}</span>
                                            </td>
                                            <td>
                                                <i class="cc BTC"></i> Bitcoin
                                            </td>

                                            <td class="{{ $item->status ? 'text-danger' : 'text-success' }}">{{ $item->amount }} USD</td>
                                            <td class="">
                                                @if(!$item->proof)
                                                    <a class="badge badge-primary p-2" href="{{ route('backend.deposits.proof',$item->id)  }}">Upload Proof</a>
                                                @else
                                                    <a class="badge badge-success p-2" target="_blank" href="{{ $item->proof }}">Payment Proof</a>
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

    </div>


    @include('partials.datatable')
@endsection


