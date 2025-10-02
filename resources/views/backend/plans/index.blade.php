@extends('backend.layouts.backend')

@section('content')
    <div class="container-fluid">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Account Plans</h4>
                        <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a> </li>
                            <li class="breadcrumb-item">Plans</li>
                        </ol>
                    </div>


                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <!-- START: Card Data-->
        <div class="row">
            <div class="col-12  mt-3">
                {{-- @include('notification') --}}
                <div class="card">

                    <div class="card-body">

                        <div class="row justify-content-md-center">
                            @foreach($plans as $item)
                            <div class="col-12 col-md-4 col-xl-3 mb-4 mb-lg-0">
                                <div class="price-table   text-center  p-3">
                                    <h2 class="font-weight-bold text_gold">{{ $item->name }} </h2>
                                    <div class="price-block text-wrap" style="font-size: 20px">${{ $item->amount }}
                                    <br>
{{--                                        <span style="color: #96A5AE;font-size: 9px">--}}
{{--                                            per user / month--}}
{{--                                        </span>--}}
                                    </div>
                                    <div class="divider-default my-3"></div>
                                    <ul class="list-unstyled text-left">
                                        @foreach ($item->features as $feature)
                                            <li class="mb-3 list_plan"><i style="margin: 5px" class="fa fa-check text_gold"></i> {{ $feature }}</li>
                                        @endforeach
                                        {{-- <li><a href="#" data-toggle="modal" data-target="#investItem-{{ $item->id }}" class="btn btn-primary btn-circle btn-default mt-3">JOIN PLAN</a></li> --}}
                                        @if ($item->name == auth()->user()->plan)
                                            <li class="text-center"><a href="#" class="btn btn-primary btn-circle mt-3">ACTIVE</a></li>
                                        @else
                                            <li class="text-center"><a href="#" data-toggle="modal" data-target="#joinPlanModal-{{ $item->id }}" class="btn btn-primary btn-circle btn-default mt-3">JOIN PLAN</a></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            {{-- <div class="col-12 col-md-4 col-xl-3 mb-4 mb-lg-0">
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
                            </div> --}}

                                {{-- <div class="modal fade" id="investItem-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <activate_plan url="{{ route('backend.plan.store') }}" :user='@json(auth()->user())' id="{{ $item->id }}" key="{{ $item->id }}" :plan='@json($item)' :plans='@json($plans)'></activate_plan>
                                    </div>
                                </div> --}}

                                {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#joinPlanModal">
                                    Launch demo modal
                                  </button> --}}

                                  <!-- Modal -->
                                  <div class="modal fade" id="joinPlanModal-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="joinPlanModalTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="joinPlanModalTitle">{{ $item->name }} @ {{ number_format($item->amount) }} USD</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <form action="{{ route('backend.plans.upgrade', $item->id) }}" method="post">
                                            @csrf
                                        <div class="modal-body">
                                                <div class="row mt-2 mb-2">
                                                    <div class="col-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <h5 class="font-weight-light">Account Balance : {{ number_format(auth()->user()->balance)}} USD </h5>
                                                                    @if (auth()->user()->balance < $item->amount)
                                                                        <p class="text-danger">Insufficient balance! <br />Please fund your account to continue.</p>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    @if (auth()->user()->balance > $item->amount)
                                                        <div class="col-12 mt-2">
                                                            <section class="col-md-12 border border-light rounded">
                                                                <p class="card-text m-2 " style="font-size: 20px">Are you sure you want to activate this plan?</p>
                                                            </section>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button @if(auth()->user()->balance < $item->amount) disabled @endif type="submit" class="btn btn-primary">Activate Plan</button>
                                            </div>
                                        </form>
                                      </div>
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
