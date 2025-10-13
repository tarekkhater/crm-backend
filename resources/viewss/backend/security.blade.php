@extends('backend.layouts.backend')

@section('content')

    <div class="container-fluid">

        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><span class="h4 my-auto">Security</span></div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="#">Security</a></li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->


        <div class="row mt-2">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-xl-4">
                                    <div class="id_card">
                                        <img src="/back/images/id.png" alt="" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="id_info">
                                        <h4>{{ auth()->user()->name }}</h4>
                                        <p class="mb-1 mt-3">@ {{ auth()->user()->email }} </p>
                                        <p class="mb-1">Status: <span class="font-weight-bold">{{ auth()->user()->identity->status }}</span></p>
                                        <a href="{{ route('backend.account.upload.id') }}" class="btn btn-success mt-3">Re upload ID</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        <div class="row mt-2">
            <div class="col-xl-12">
                <div class="card pt-3 ">
            <div class="col-xl-12">
                <div class="phone_verify">
                    <h6 class="card-title mb-3">Email Address</h6>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="phone_verified">
                    <h6> <span><i class="fa fa-envelope"></i></span> {{ auth()->user()->email }}</h6>
                    <div class="verify">
                        <div class="verified">
                            <span><i class="la la-check"></i></span>
                            <a href="#">Verified</a>
                        </div>
                    </div>
                </div>
            </div>
                    <hr />
            <div class="col-xl-12">
                <div class="phone_verify">
                    <h6 class="card-title mb-3">Phone Number</h6>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="phone_verified">
                    <p> <span><i class="fa fa-phone"></i></span> {{ auth()->user()->phone }}</p>
                    <div class="verify">
                        {{--                                            <div class="verified">--}}
                        {{--                                                <span><i class="la la-check"></i></span>--}}
                        {{--                                                <a href="#">UnVerified</a>--}}
                        {{--                                            </div>--}}
                    </div>
                </div>
            </div>
                    <hr />
                    <div class="col-xl-12">
                        <div class="phone_verify">
                            <h6 class="card-title mb-3">Update Password</h6>
                        </div>
                    </div>
                    <div class="col-xl-12 pb-4">
                        <div class="phone_verified">
                            <div class="verify">
                                <a href="{{ route('backend.update_password') }}" class="btn btn-primary">Change password</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('js')

@endsection
