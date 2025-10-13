@extends('backend.layouts.backend')

@section('content')
{{--    <div class="content-body">--}}
        <div class="container-fluid">

            <!-- START: Breadcrumbs-->
            <div class="row ">
                <div class="col-12  align-self-center">
                    <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
                        <div class="w-sm-100 mr-auto"><h4 class="mb-0">Make a deposit </h4></div>

                        <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item">Deposit</li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- END: Breadcrumbs-->


            <div class="row">


                <div class="col-xl-12 col-lg-12 col-sm-12">

                    <div style="margin-top: 10px" class="card">
                        <div class="card-body">

                                            <div class="p-4">
                                                <deposit></deposit>
                                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
{{--    </div>--}}


@endsection
