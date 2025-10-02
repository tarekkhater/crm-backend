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
        <h4 class="tx-gray-800 mg-b-5">Margin Call Settings</h4>
    </div>

    <div class="br-pagebody">
            @include('notification')
    </div>
        <div class="br-pagebody">
            <div class="br-section-wrapper">

                <form action="{{ route('admin.settings.store') }}" method="POST">

                    @csrf

                    <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Margin Call Settings </h6>

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
                            <td class="text-capitalize">Enable Margin Call</td>
                            <td>
                                <select required name="enable_margin_call" class="form-control">
                                    <option {{ setting('enable_margin_call') == '1' ? 'selected' : '' }} value="1">Yes</option>
                                    <option {{ setting('enable_margin_call') == '0' ? 'selected' : '' }} value="0">No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">Margin to balance % for margin call</td>
                            <td>
                                <input class="form-control" width="70%" name="margin_percent" type="number"  required value="{{ setting('margin_percent',10) }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">Margin to balance % for liquidation call</td>
                            <td>
                                <input class="form-control" width="70%" name="liquidation_percent" type="number"  required value="{{ setting('liquidation_percent',10) }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">Margin call mail subject</td>
                            <td>
                                <input class="form-control" width="70%" name="mail_subject" type="text"  required value="{{ setting('mail_subject') }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">Mail Template</td>
                            <td>
                                <textarea class="form-control" cols="4" name="margin_mail"  required >{{ setting('margin_mail') }}</textarea>
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


