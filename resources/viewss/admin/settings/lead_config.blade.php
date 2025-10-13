@extends('admin.layouts.admin-app')

@section('content')
<!-- ########## START: MAIN PANEL ########## -->
<div class="br-mainpanel">
    <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
            <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="breadcrumb-item" href="{{ route('admin.settings.index') }}"> Settings</a>
{{--            <span class="breadcrumb-item active">Settings Layouts</span>--}}
        </nav>
    </div><!-- br-pageheader -->
    <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <h4 class="tx-gray-800 mg-b-5"> Lead Settings & Config</h4>
    </div>

    <div class="br-pagebody">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


            @include('notification')
    </div>
        <div class="br-pagebody">
            <div class="br-section-wrapper">

                <form action="{{ route('admin.settings.store') }}" method="POST">

                    @csrf

                <div class="table-wrapper">
                    <table class="table table-bordered display table-condensed responsive nowrap">
                        <thead>
                        <tr>

                            <th class="wd-20p"> Name</th>
                            <th class="wd-80p">Value</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="text-capitalize">Get response <br/>
                            Webhook Url
                            </td>
                            <td>
                                <input class="form-control" width="70%" required value="{{ route('getresponse.webhook') }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">Get Response <br/> Leads Count </td>
                            <td>
                                {{ $lead_count }}

                                @if ($lead_count > 0)
                                    <a href="{{ route('admin.users.webhooks') }}">View Leads</a>
                                @endif

{{--                                <textarea class="form-control" name="first_banner_text">{{ setting('first_banner_text') }}</textarea>--}}
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div><!-- table-wrapper -->

                </form>

            </div><!-- br-pagebody -->
</div><!-- br-mainpanel -->
<!-- ########## END: MAIN PANEL ########## -->

@endsection


