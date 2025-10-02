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
        <h4 class="tx-gray-800 mg-b-5"> Settings</h4>
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
{{--        @if (check_permission('create-settings'))--}}
{{--                <p>--}}
{{--                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">--}}
{{--                        Add Setting--}}
{{--                    </button>--}}
{{--                </p>--}}
{{--        @endif--}}

        <div class="collapse" id="collapseExample">
        <div class="br-section-wrapper">

            <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Add Settings</h6>

            <form action="{{ route('admin.settings.store') }}" method="POST">
                @csrf
                <div class="form-layout form-layout-1">
                    <div class="row mg-b-25">

                        <div class="col-lg-4">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Setting Key: <span class="tx-danger">*</span></label>
                                <input class="form-control" type="text" name="key" value="{{ old('key') }}" required placeholder="Enter Key">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Setting Value: <span class="tx-danger">*</span></label>
                                <input class="form-control" type="text" value="{{ old('value') }}" name="value" required placeholder="Enter Value">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label">Type:  <span class="tx-danger">*</span></label>
                                <select name="type" class="form-control" required data-placeholder="Select type:">
                                    {{--                                    <option label="Choose Category"></option>--}}
                                    <option value="text">Text</option>
                                    <option value="image">Image</option>
                                    <option value="textarea">Textarea</option>
                                </select>
                            </div>
                        </div><!-- col-4 -->

                    </div><!-- row -->

                    <div class="form-layout-footer">
                        <button class="btn btn-primary" type="submit">Add Setting</button>
                    </div><!-- form-layout-footer -->
                </div><!-- form-layout -->
            </form>
        </div><!-- br-section-wrapper -->
        </div>
            @include('notification')
    </div>
        <div class="br-pagebody">
            <div class="br-section-wrapper">

                <form action="{{ route('admin.settings.store') }}" method="POST">

                    @csrf

                    <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10"> Settings List</h6>

                <input value="Save" type="submit" class="btn btn-success float-right" />

                <div class="table-wrapper">
                    <table class="table table-bordered display table-condensed responsive nowrap">
                        <thead>
                        <tr>
                            <th class="wd-10p"> S.No</th>
                            <th class="wd-20p"> Name</th>
                            <th class="wd-70p">Value</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td class="text-capitalize">Site Logo</td>
                            <td>
                                @include('admin.partials.image-upload',['field' => 'logo','image' => setting('logo'),'id' => 'logo'])

{{--                                <textarea class="form-control" name="first_banner_text">{{ setting('first_banner_text') }}</textarea>--}}
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td class="text-capitalize">Site Favicon</td>
                            <td>
                                @include('admin.partials.image-upload',['field' => 'favicon','image' => setting('favicon'),'id' => 'favicon'])

{{--                                <textarea class="form-control" name="first_banner_text">{{ setting('first_banner_text') }}</textarea>--}}
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td class="text-capitalize">Site Name</td>
                            <td>
                                <input class="form-control" width="70%" name="site_name" required value="{{ setting('site_name') }}">
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td class="text-capitalize">Site Info URL</td>
                            <td>
                                <input class="form-control" width="70%" name="site_info_url" required value="{{ setting('site_info_url') }}">
                            </td>
                        </tr>
                        <tr>
                            <td>3a</td>
                            <td class="text-capitalize">Privacy Policy URL</td>
                            <td>
                                <input class="form-control" width="70%" name="privacy_policy" required value="{{ setting('privacy_policy','#') }}">
                            </td>
                        </tr>


            <tr>
                            <td>3b</td>
                            <td class="text-capitalize">Terms & Condition URL</td>
                            <td>
                                <input class="form-control" width="70%" name="terms" required value="{{ setting('terms','#') }}">
                            </td>
                        </tr>
            <tr>
                            <td>3c</td>
                            <td class="text-capitalize">Button Primary Color</td>
                            <td>
                                <input class="form-control" width="70%" name="primary_color" required value="{{ setting('primary_color','#') }}">
                            </td>
                        </tr>
            <tr>
                            <td>3d</td>
                            <td class="text-capitalize">Button Primary Color Hover</td>
                            <td>
                                <input class="form-control" width="70%" name="primary_color_hover" required value="{{ setting('primary_color_hover','#') }}">
                            </td>
                        </tr>

              <tr>
                            <td>3</td>
                            <td class="text-capitalize">Disabled Trade Message</td>
                            <td>
                                <input class="form-control" width="70%" name="cannot_trade_msg" required value="{{ setting('cannot_trade_msg') }}">
                            </td>
                        </tr>

                        <tr>
                            <td>4</td>
                            <td class="text-capitalize">Support Email</td>
                            <td>
                                <input class="form-control" width="70%" name="support_email" required value="{{ setting('support_email') }}">
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td class="text-capitalize">Contact Phone</td>
                            <td>
                                <input class="form-control" width="70%" name="phone" required value="{{ setting('phone') }}">
                            </td>
                        </tr>

                        <tr>
                            <td>6</td>
                            <td class="text-capitalize">Site Currency</td>
                            <td>
                                <select required name="currency" class="form-control">
                                    <option value="{{ setting('currency') }}">{{ setting('currency','Select Currency') }}</option>
                                    <option value="USD">USD</option>
                                    <option value="BTC">BTC</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td class="text-capitalize">Twak io Script</td>
                            <td>
                                <textarea name="twak_io" id="twak_io" cols="130" rows="10">{{ setting('twak_io') }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td class="text-capitalize">New Password</td>
                            <td>
                                <input class="form-control" width="70%" name="new_password" required value="{{ setting('new_password') }}">
                            </td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td class="text-capitalize"> Message New Password</td>
                            <td>
                                <input class="form-control" width="70%" name="new_password" required value="{{ setting('message_new_password') }}">
                            </td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td class="text-capitalize">Static Ip</td>
                            <td>
                                <input class="form-control" width="70%" name="static_Ip" required value="{{ setting('static_Ip') }}">
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


