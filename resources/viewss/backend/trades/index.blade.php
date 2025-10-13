@extends('backend.layouts.backend')

@section('content')
    <div class="container-fluid">
    <!-- START: Breadcrumbs-->
    <div class="row ">
        <div class="col-12  align-self-center">
            <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
                <div class="w-sm-100 mr-auto"><h4 class="mb-0">Trade History</h4>
                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0" style="font-size: 13px">
                        <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}"> Dashboard</a></li>
                        <li class="breadcrumb-item">Your Order Book</li>
                    </ol>

                </div>


            </div>
        </div>
    </div>
    <!-- END: Breadcrumbs-->

                <order_book_all interv="{{ setting('api_interval',1000) }}"   plan="{{ auth()->user()->plan }}" :trades='@json($trades)' :open_trades='@json($open_trades)' check_trade_url="{{ route('backend.api.trade.check') }}" close_trade_url="{{ route('backend.api.trade.close') }}" trade_url="{{ route('backend.trade.store') }}" all_trade_url="{{ route('backend.api.trades') }}"
                                :balance="{{ auth()->user()->balance }}"
                                :bonus="{{ auth()->user()->bonus }}">

                    <div class="text-center">
                        <img width="400px" src="/images/loading.gif" />
                    </div>

                </order_book_all>

    </div>



    @include('partials.datatable')

@endsection

