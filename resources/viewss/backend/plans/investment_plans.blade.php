@extends('backend.layouts.backend')

@section('content')
    <div class="container-fluid">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Investment Plans</h4></div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a> </li>
                        <li class="breadcrumb-item">Plans</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12  mt-3">
                <div class="card">

                    <div class="card-body">

                        <div class="row justify-content-md-center">
                            @foreach($plans as $item)
                                <div class="col-12 col-md-4 col-xl-3 mb-4 mb-lg-0">
                                    <div class="price-table border rounded  text-center  p-3">
                                        <h2 class="font-weight-bold">{{ $item->name }} </h2>
                                        <div class="price-block">${{ $item->price }}  </div>
                                        Per {{ $item->period_type }}
                                        <div class="divider-default my-3"></div>
                                        <ul class="list-unstyled">
                                            <li class="mb-3"><i class="fa fa-check"></i>Invest ${{ $item->full_price }}</li>
                                            <li class="mb-3"><i class="fa fa-check"></i> {{ $item->percent_profit  }} % ROI</li>
                                            <li class="mb-3"><i class="fa fa-check"></i> MIN UNIT : {{ $item->min_unit }}</li>
                                            <li class="mb-3"><i class="fa fa-check"></i> MAX UNIT : {{ $item->max_unit }}</li>
                                            <li class="mb-3"><i class="fa fa-check"></i> Duration : {{ $item->period }} {{ $item->period_type }}</li>
                                            <li><a href="#" data-toggle="modal" data-target="#investItem-{{ $item->id }}" class="btn btn-primary btn-circle btn-default mt-3">JOIN PLAN</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="modal fade" id="investItem-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <activate_plan url="{{ route('backend.plan.store') }}" :user='@json(auth()->user())' id="{{ $item->id }}" key="{{ $item->id }}" :plan='@json($item)' :plans='@json($plans)'></activate_plan>
                                    </div>
                                </div>

                            @endforeach
                        </div>


                    </div>
                </div>
            </div>



        </div>
        <!-- END: Card DATA-->
    </div>
@endsection
