@extends('backend.layouts.backend')

@section('content')
    <div class="container-fluid">
        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Complete your plan setup</h4></div>

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

                        <activate_plan url="{{ route('backend.plan.store') }}" :plan='@json($plan)' :plans='@json($plans)'></activate_plan>

                    </div>
                </div>
            </div>



        </div>
        <!-- END: Card DATA-->
    </div>
@endsection
<script>

</script>
