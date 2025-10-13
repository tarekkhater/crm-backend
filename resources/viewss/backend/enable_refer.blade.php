@extends('backend.layouts.backend')

@section('content')
    <div class="container-fluid">

        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Referral Program</h4>
                        <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}"> Dashboard</a></li>
                            <li class="breadcrumb-item">Referral Program </li>
                        </ol>
                    </div>


                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->


        <div class="row">


            <div class="col-xl-12 col-lg-12 col-sm-12 mt-3">

                <div style="margin-top: 10px" class="card">
                    <div class="card-header">
                        <h5 class="card-title">Activate Referral Program for your account</h5>
                    </div>
                    <div class="card-body">
                        <p>Activating your account for referral program will allow you to refer friends to
                            {{ setting('site_name') }}</p>
                        <p>I agree to the terms and conditions*</p>

                        <a href="" class="btn btn-primary mt-3 mb-3">Activate Referral Program</a>

                        <p class="text-muted">*This website is not directed at any jurisdiction and is not intended for any use that would be contrary to local law or regulation.</p>

                           <p class="text-muted"> **Risk Warning: Trading leveraged products such as Forex may not be suitable for all investors as
                               they carry a degree of risk to your capital. Please ensure that you fully understand the risks involved,
                               taking into account your investments objectives and level of experience, before trading, and if necessary
                               seek independent advice </p>
                    </div>
                </div>

            </div>
        </div>

    </div>


    @include('partials.datatable')
@endsection


