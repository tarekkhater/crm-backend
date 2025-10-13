@extends('backend.layouts.backend')

@section('content')

    <div class="container-fluid">
        <!-- START: Breadcrumbs-->
        <div class="row">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Account Managers</h4> <b class=" text-capitalize">Connect your account to Auto Trader</b></div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Auto Traders</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <ul class="contacts grid">
            @foreach($traders as $item)
{{--            <div class="col-12 col-lg-6  col-xl-4 mt-3">--}}
{{--                <div class="card text-center">--}}
{{--                    <div class="card-header">--}}
{{--                        <h4 class="card-title">{{ $item->name }}</h4>--}}
{{--                    </div>--}}
{{--                    <div class="card-body">--}}
{{--                        <img src="{{ $item->photo }}" class="img-rounded" style="height: 100px; width: 80px" />--}}
{{--                        <h5 class="card-title">Profit : {{ $item->profit }} %</h5>--}}
{{--                        <p class="card-text">{{ $item->info }}</p>--}}
{{--                        <a href="{{ route('backend.account.connect',$item->id) }}" class="btn btn-primary">Connect Account</a>--}}
{{--                    </div>--}}
{{--                    <div class="card-footer text-muted">--}}
{{--                        <h6 class="card-title">USD{{ $item->fee }}</h6>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}


                <li class="contact  py-3 px-2">
                    <div class="media d-flex w-100 text-white">
                        <a href="#"><img src="/dist/images/author2.jpg" alt="" class="img-fluid ml-0 mt-2 ml-md-2 rounded-circle" width="60"></a>
                        <div class="media-body align-self-center pl-2">
                            <b class="mb-0 text-capitalize"><a href="{{ route('backend.profile.view', $item->id) }}">{{ $item->name }}</a></b><br/>
                            <p class="mb-0 text-muted pt-1">Profitable trades :  {{ number_format($item->profit) }}%</p>
                            <p class="mb-0 text-muted pt-1">  Total Profit : {{ amt($item->totalProfit() * 20) }}  </p>
                            <p class="mb-0 text-muted pt-1">Total Trades : {{ $item->trades_count }}  </p>
                            <p class="mb-0 text-white pt-1 text-white">Connection Fee : ${{ amt($item->fee) }}  </p>
                            <a href="{{ route('backend.profile.view', $item->id) }}">View Profile</a>
                        </div>

                        <div class="ml-auto mail-tools">
                            <a href="{{ route('backend.account.connect',$item->id) }}" class="btn btn-success text-white m-2">Connect</a>

                        </div>
                    </div>
                </li>

            @endforeach
            </ul>
        </div>
        <!-- END: Card DATA-->
    </div>
@endsection

@section('styles')
{{--    <link rel="stylesheet" href="/back/vendor/waves/waves.min.css">--}}
@endsection

@section('js')
    @if (session('fund'))
    <script>
        swal({
                title: "Insufficient Balance",
                text: "{{ session('fund') }}",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-danger',
                confirmButtonText: 'Make Deposit!',
                cancelButtonText: "Try Another Plan!",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function(isConfirm){
                if (isConfirm){
                    window.location = "{{ route('backend.deposit.fund') }}"
                }
            });
    </script>
    @endif
@endsection
