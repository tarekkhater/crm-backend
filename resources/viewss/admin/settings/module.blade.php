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
        <h4 class="tx-gray-800 mg-b-5">Modules Settings</h4>
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

                    <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Module Settings List</h6>

                <input value="Save" type="submit" class="btn btn-success float-right" />

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
                            <td><span class="text-capitalize">Separate Bonus</span>
                            <br />
                                separate_bonus
                            </td>
                            <td>
                                <select required name="separate_bonus" class="form-control">
                                    <option {{ setting('separate_bonus') == 'no' ? 'selected' : '' }} value="no">No</option>
                                    <option {{ setting('separate_bonus') == 'yes' ? 'selected' : '' }} value="yes">YES</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="text-capitalize">Disable Mobile View</span>
                            <br />
                                disable_mobile_view
                            </td>
                            <td>
                                <select required name="disable_mobile_view" class="form-control">
                                    <option {{ setting('disable_mobile_view') == 1 ? 'selected' : '' }} value="1">YES</option>
                                    <option {{ setting('disable_mobile_view') == 0 ? 'selected' : '' }} value="0">No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="text-capitalize">Enable Multi Currency</span>
                            <br />
                                multi_currency
                            </td>
                            <td>
                                <select required name="multi_currency" class="form-control">
                                    <option {{ setting('multi_currency') == 1 ? 'selected' : '' }} value="1">YES</option>
                                    <option {{ setting('multi_currency') == 0 ? 'selected' : '' }} value="0">No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="text-capitalize">Enable Can Trade</span>
                            <br />
                                can_trade
                            </td>
                            <td>
                                <select required name="can_trade" class="form-control">
                                    <option {{ setting('can_trade') == 1 ? 'selected' : '' }} value="1">YES</option>
                                    <option {{ setting('can_trade') == 0 ? 'selected' : '' }} value="0">No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="text-capitalize">Enable Auto Profit & Loss</span>
                            <br />
                                auto_profit_lose
                            </td>
                            <td>
                                <select required name="auto_profit_lose" class="form-control">
                                    <option {{ setting('auto_profit_lose') == 1 ? 'selected' : '' }} value="1">YES</option>
                                    <option {{ setting('auto_profit_lose') == 0 ? 'selected' : '' }} value="0">No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="text-capitalize">Enable Joint Account</span>
                            <br />
                                joint_account
                            </td>
                            <td>
                                <select required name="joint_account" class="form-control">
                                    <option {{ setting('joint_account') == 1 ? 'selected' : '' }} value="1">YES</option>
                                    <option {{ setting('joint_account') == 0 ? 'selected' : '' }} value="0">No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="text-capitalize">Enable Account Connection</span>
                            <br />
                                autotrader
                            </td>
                            <td>
                                <select required name="autotrader" class="form-control">
                                    <option {{ setting('autotrader') == 1 ? 'selected' : '' }} value="1">YES</option>
                                    <option {{ setting('autotrader') == 0 ? 'selected' : '' }} value="0">No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="text-capitalize">Enable Investment</span>
                            <br />
                                [invest]
                            </td>
                            <td>
                                <select required name="invest" class="form-control">
                                    <option {{ setting('invest') == 1 ? 'selected' : '' }} value="1">YES</option>
                                    <option {{ setting('invest') == 0 ? 'selected' : '' }} value="0">No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="text-capitalize">Enable Crypto Exchange</span>
                            <br />
                                [exchange]
                            </td>
                            <td>
                                <select required name="exchange" class="form-control">
                                    <option {{ setting('exchange') == 1 ? 'selected' : '' }} value="1">YES</option>
                                    <option {{ setting('exchange') == 0 ? 'selected' : '' }} value="0">No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="text-capitalize">Enable Multi Language</span>
                            <br />
                                [multi_lang]
                            </td>
                            <td>
                                <select required name="multi_lang" class="form-control">
                                    <option {{ setting('multi_lang') == 1 ? 'selected' : '' }} value="1">YES</option>
                                    <option {{ setting('multi_lang') == 0 ? 'selected' : '' }} value="0">No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="text-capitalize">Enable Referrals</span>
                            <br />
                                [referrals]
                            </td>
                            <td>
                                <select required name="referrals" class="form-control">
                                    <option {{ setting('referrals') == 1 ? 'selected' : '' }} value="1">YES</option>
                                    <option {{ setting('referrals') == 0 ? 'selected' : '' }} value="0">No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="text-capitalize">Enable Must Verify KYC</span>
                            <br />
                                [kyc_verify]
                            </td>
                            <td>
                                <select required name="kyc_verify" class="form-control">
                                    <option {{ setting('kyc_verify') == 1 ? 'selected' : '' }} value="1">YES</option>
                                    <option {{ setting('kyc_verify') == 0 ? 'selected' : '' }} value="0">No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="text-capitalize">Enable Verify KYC Button</span>
                            <br />
                                [kyc_verify_button]
                            </td>
                            <td>
                                <select required name="kyc_verify_button" class="form-control">
                                    <option {{ setting('kyc_verify_button') == 1 ? 'selected' : '' }} value="1">YES</option>
                                    <option {{ setting('kyc_verify_button') == 0 ? 'selected' : '' }} value="0">No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="text-capitalize">Enable Overnight Commission</span>
                            <br />
                                [overnight_com]
                            </td>
                            <td>
                                <select required name="overnight_com" class="form-control">
                                    <option {{ setting('overnight_com') == 1 ? 'selected' : '' }} value="1">YES</option>
                                    <option {{ setting('overnight_com') == 0 ? 'selected' : '' }} value="0">No</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="text-capitalize">Enable Trading</span>
                            <br />
                                [trade]
                            </td>
                            <td>
                                <select required name="trade" class="form-control">
                                    <option {{ setting('trade') == 1 ? 'selected' : '' }} value="1">YES</option>
                                    <option {{ setting('trade') == 0 ? 'selected' : '' }} value="0">No</option>
                                </select>
                            </td>
                        </tr>
{{--                        <tr>--}}
{{--                            <td><span class="text-capitalize">Default User Account</span>--}}
{{--                            <br />--}}
{{--                                [user]--}}
{{--                            </td>--}}
{{--                            <td>--}}

{{--                                <select required name="user_account" class="form-control">--}}
{{--                                @foreach($packages as $package)--}}
{{--                                --}}{{-- {{ $key }} --}}
{{--                                    <option {{ setting('user_account') == $package->id ? 'selected' : '' }} value="{{ $package->id }}">{{ $package->name }}</option>--}}
{{--                                    --}}
{{--                                @endforeach--}}
{{--                                    --}}{{-- <option {{ setting('user_account') == 1 ? 'selected' : '' }} value="1">SILVER</option>--}}
{{--                                    <option {{ setting('user_account') == 0 ? 'selected' : '' }} value="0">GOLD</option> --}}
{{--                                </select>--}}
{{--                            </td>--}}
{{--                        </tr>--}}

                        </tbody>
                    </table>
                </div><!-- table-wrapper -->

                {{-- <input value="Save" type="submit" class="btn btn-success float-right" /> --}}

                </form>

            </div><!-- br-pagebody -->
</div><!-- br-mainpanel -->
<!-- ########## END: MAIN PANEL ########## -->

@endsection


