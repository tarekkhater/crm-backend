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
        <h4 class="tx-gray-800 mg-b-5">SMTP Settings</h4>
    </div>

    <div class="br-pagebody">
            @include('notification')
    </div>
        <div class="br-pagebody">
            <div class="br-section-wrapper">

                <form action="{{ route('admin.settings.storesmtp') }}" method="POST">

                    @csrf

                    <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">SMPT Settings (Make sure to disable APP_DEBUG After testing email)</h6>

                    <a href="{{ route('admin.settings.testmail') }}" class="btn btn-primary float-right mr-2">Test SMTP</a>

                <input value="Save" type="submit" class="btn btn-success float-right  mr-2" />
                <div class="table-wrapper">
                    <table class="table table-bordered display table-condensed responsive nowrap">
                        <thead>
                        <tr>
                            <th class="wd-20p"> Name</th>
                            <th class="wd-70p">Value</th>

                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <td class="text-">APP_DEBUG (#false) {{ env('APP_DEBUG') }}</td>
                            <td>
                                <select name="APP_DEBUG" class="form-control">
                                    <option {{ env('APP_DEBUG') == true ? 'selected' : '' }} value="true">Enable</option>
                                    <option  {{ env('APP_DEBUG') == false ? 'selected' : '' }}  value="false">Disable</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-">MAIL_HOST (#smtp.mailgun.org)</td>
                            <td>
                                <input class="form-control" width="70%" name="MAIL_HOST" type="text"  required value="{{ env('MAIL_HOST') }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-">MAIL_PORT (#578)</td>
                            <td>
                                <input class="form-control" width="70%" name="MAIL_PORT" type="number"  required value="{{ env('MAIL_PORT') }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-">MAIL_ENCRYPTION (#tls)</td>
                            <td>
                                <input class="form-control" width="70%" name="MAIL_ENCRYPTION" type="text"  required value="{{ env('MAIL_ENCRYPTION') }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">MAIL_USERNAME </td>
                            <td>
                                <input class="form-control" width="70%" name="MAIL_USERNAME" type="text"  required value="{{ env('MAIL_USERNAME') }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">MAIL_PASSWORD</td>
                            <td>
                                <input class="form-control" width="70%" name="MAIL_PASSWORD" type="text"  required value="{{ env('MAIL_PASSWORD') }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-">MAIL_FROM_ADDRESS</td>
                            <td>
                                <input class="form-control" width="70%" name="MAIL_FROM_ADDRESS" type="email"  required value="{{ env('MAIL_FROM_ADDRESS') }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">TEST EMAIL</td>
                            <td>
                                <input class="form-control" width="70%" name="TEST_EMAIL" type="email"  required value="{{ setting('TEST_EMAIL') }}">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div><!-- table-wrapper -->

                {{-- <input value="Save" type="submit" class="btn btn-success float-right" /> --}}

                </form>

            </div><!-- br-pagebody -->
</div><!-- br-mainpanel -->
<!-- ########## END: MAIN PANEL ########## -->

@endsection


