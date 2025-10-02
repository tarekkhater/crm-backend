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
        <h4 class="tx-gray-800 mg-b-5">Apis Settings</h4>
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

                    <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Apis Settings List</h6>
                    [Note : Once provider or api key changes, update assets to get valid assests]

                <input value="Save" type="submit" class="btn btn-success float-right" />

                    <a class="btn btn-danger" href="{{ route('api.update.all.assets') }}">Update all assets</a>

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
                            <td class="text-capitalize">Use API</td>
                            <td>
                                <select required name="disable_api" class="form-control">
                                    <option {{ setting('disable_api') == 'no' ? 'selected' : '' }} value="no">YES</option>
                                    <option {{ setting('disable_api') == 'disabled' ? 'selected' : '' }} value="disabled">NO</option>
                                </select>
                            </td>
                        </tr>

                        @if (setting('disable_api') != 'disabled')
                        <tr>
                            <td class="text-capitalize">API Interval<br /> [minimum 1000ms]</td>
                            <td>
                                <input class="form-control" width="70%" name="api_interval" type="number" min="1000" required value="{{ setting('api_interval') }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">Crypto Api Provider</td>
                            <td>
                                <select required name="crypto_provider" class="form-control">
                                    <option {{ setting('crypto_provider') == 'crypto_compare' ? 'selected' : '' }} value="crypto_compare">Crypto Compare</option>
                                    <option {{ setting('crypto_provider') == 'iexcloud' ? 'selected' : '' }} value="iexcloud">IEX Cloud</option>
                                    <option {{ setting('crypto_provider') == 'fm' ? 'selected' : '' }} value="fm">Financial Modelling</option>
                                    <option {{ setting('crypto_provider') == 'binance' ? 'selected' : '' }} value="binance">Binance</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">Stock Api Provider<br /></td>
                            <td>
                                <select required name="stock_provider" class="form-control">
                                    <option {{ setting('stock_provider') == 'fm' ? 'selected' : '' }} value="fm">Financial Modelling</option>
                                    <option {{ setting('stock_provider') == 'yahoo' ? 'selected' : '' }} value="yahoo">Yahoo Finance</option>
                                    <option {{ setting('stock_provider') == 'iexcloud' ? 'selected' : '' }} value="iexcloud">IEX Cloud</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">Commodity Api Provider<br /></td>
                            <td>
                                <select required name="com_provider" class="form-control">
                                    <option {{ setting('com_provider') == 'fm' ? 'selected' : '' }} value="fm">Financial Modelling</option>
                                    <option {{ setting('com_provider') == 'oanda' ? 'selected' : '' }} value="oanda">Oanda</option>
                                    <option {{ setting('com_provider') == 'iexcloud' ? 'selected' : '' }} value="iexcloud">IEX Cloud</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">Indices Api Provider<br /></td>
                            <td>
                                <select required name="indices_provider" class="form-control">
                                    <option {{ setting('indices_provider') == 'fm' ? 'selected' : '' }} value="fm">Financial Modelling</option>
                                    <option {{ setting('indices_provider') == 'oanda' ? 'selected' : '' }} value="oanda">Oanda</option>
                                    <option {{ setting('indices_provider') == 'iexcloud' ? 'selected' : '' }} value="iexcloud">IEX Cloud</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">Forex Api Provider<br /></td>
                            <td>
                                <select required name="forex_provider" class="form-control">
                                    <option {{ setting('forex_provider') == 'fm' ? 'selected' : '' }} value="fm">Financial Modelling</option>
                                    <option {{ setting('forex_provider') == 'oanda' ? 'selected' : '' }} value="oanda">Oanda</option>
                                    <option {{ setting('forex_provider') == 'iexcloud' ? 'selected' : '' }} value="iexcloud">IEX Cloud</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">Crypto Api Key<br /> [{{ setting('crypto_provider') }}]</td>
                            <td>
                                <input class="form-control" width="70%" name="crypto_api" required value="{{ setting('crypto_api') }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">Stock Api Key <br />[{{ setting('stock_provider') }}]</td>
                            <td>
                                <input class="form-control" width="70%" name="stock_api" required value="{{ setting('stock_api') }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">Indices Api Key<br />[{{ setting('indices_provider') }}]</td>
                            <td>
                                <input class="form-control" width="70%" name="indices_api" required value="{{ setting('indices_api') }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">Commodity Api Key<br />[{{ setting('com_provider') }}]</td>
                            <td>
                                <input class="form-control" width="70%" name="com_api" required value="{{ setting('com_api') }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-capitalize">Forex Api Key<br />[{{ setting('forex_provider') }}]</td>
                            <td>
                                <input class="form-control" width="70%" name="forex_api" required value="{{ setting('forex_api') }}">
                            </td>
                        </tr>
                        @endif
                        </tbody>
                    </table>
                </div><!-- table-wrapper -->

                    @if (setting('disable_api') == 'disabled')
                    <h5 class="text-center">Please enable use of api for api settings</h5>
                    @endif

                {{-- <input value="Save" type="submit" class="btn btn-success float-right" /> --}}

                </form>

            </div><!-- br-pagebody -->
</div><!-- br-mainpanel -->
<!-- ########## END: MAIN PANEL ########## -->

@endsection


