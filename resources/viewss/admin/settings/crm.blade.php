@extends('admin.layouts.admin-app')

@section('content')
@include('sweetalert::alert')
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
        <h4 class="tx-gray-800 mg-b-5">CRM Settings</h4>
    </div>

    <div class="br-pagebody">
            @include('notification')
    </div>
        <div class="br-pagebody">
            <div class="br-section-wrapper">

                <form action="{{ route('admin.settings.store') }}" method="POST">

                    @csrf

                    <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">CRM Settings List</h6>

                    <a href="{{ route('admin.users.testcrm') }}" class="btn btn-primary float-right mr-2">Test CRM</a>

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
                            <td class="text-capitalize">Enable CRM</td>
                            <td>
                                <select required name="enable_crm" class="form-control">
                                    <option {{ setting('enable_crm') == '1' ? 'selected' : '' }} value="1">Yes</option>
                                    <option {{ setting('enable_crm') == '0' ? 'selected' : '' }} value="0">No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">CRM URL</td>
                            <td>
                                <input class="form-control" width="70%" name="crm_url" type="text"  required value="{{ setting('crm_url') }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">CRM ACCESS TOKEN</td>
                            <td>
                                <input class="form-control" width="70%" name="crm_token" type="text"  required value="{{ setting('crm_token') }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">CRM LEAD SOURCE</td>
                            <td>
                                <input class="form-control" width="70%" name="crm_lead_source" type="number"  required value="{{ setting('crm_lead_source') }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">CRM ASSIGNED</td>
                            <td>
                                <input class="form-control" width="70%" name="crm_assigned" type="number"  required value="{{ setting('crm_assigned') }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">CRM STATUS</td>
                            <td>
                                <input class="form-control" width="70%" name="crm_status" type="number"  required value="{{ setting('crm_status') }}">
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


