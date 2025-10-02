@extends('backend.layouts.master')

@section('styles')
    <style>
        .form-control {
            background: #fff3cd!important;
        }
</style>

@endsection

@section('content')


    <div class="content-body">
        <div class="container">

            <div class="row">
                @include('partials.menu')
                @include('notification')


                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">All Pending Deposits</h4>
                        </div>
                        <div class="card-body">

                            <div class="transaction-table">
                                @if (count($deposits) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0 table-responsive-sm">
                                            <tbody>
                                            @foreach($deposits as $item)
                                                <tr>
                                                    @if ($item->status)
                                                        <td><span class="buy-thumb"><i class="mdi mdi-arrow-up"></i></span>
                                                        </td>
                                                    @else
                                                        <td><span class="sold-thumb"><i class="mdi mdi-arrow-down"></i></span>
                                                        </td>
                                                    @endif


                                                    <td>
                                                        <span class="badge {{ $item->status ? 'badge-success' : 'badge-danger' }} p-2">{{ $item->status ? 'Approved' : 'Pending' }}</span>
                                                    </td>
                                                    <td>
                                                        <i class="cc BTC"></i> {{ $item->payment_method ?? 'Bitcoin' }}
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
                                @endif

                                @if (count($deposits) < 1)
                                    <div class="text-center">
                                        <h3 class="text-center">No Pending Deposits, make a deposit to start trading</h3>

                                        <a class="btn text-center btn-success mt-4" href="{{ route('backend.deposit.fund') }}">Make Deposit</a>
                                    </div>

                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
