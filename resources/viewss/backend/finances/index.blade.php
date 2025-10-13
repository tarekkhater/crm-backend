@extends('backend.layouts.backend')

@section('content')
    <div class="container-fluid">
        <!-- START: Breadcrumbs-->
        <div class="row">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto">
                        <h4 class="mb-0">Finances</h4>
                    </div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}"> Dashboard</a></li>
                        <li class="breadcrumb-item">Your Finances</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-around align-items-center m-4" style="margin-top: 100px">
            <div class="p-4 text-center col-md-4" style="background: #070333">
                <span class="badge badge-secondary m-2" style="font-size: 1em"> Balance</span>
                <h2 class="font-weight-bold display-4">{{ amt(auth()->user()->balance) }} </h2>
            </div>
            <div class="p-4 text-center col-md-4" style="background: #0a0444">
                <span class="badge badge-secondary m-2" style="font-size: 1em"> Bonus</span>
                <h2 class="font-weight-bold display-4">{{ amt(auth()->user()->bonus) }}</h2>
            </div>
            <div class="p-4 text-center col-md-4" style="background: #070333">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <span class="badge badge-success m-2" style="font-size: 1em"> Deposits</span>
                        <h2 class="font-weight-bold display-4">{{  amt(auth()->user()->aBalance()) }}</h2>
                    </div>
{{--                    <div class="col-md-4">--}}
{{--                        <span class="badge badge-primary">Balance</span>--}}
{{--                        <h5>{{ auth()->user()->profit() }}</h5>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->
    </div>

    @include('partials.datatable')

@endsection
