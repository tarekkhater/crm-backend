@extends('admin.layouts.admin-app')

@section('content')
<!-- ########## START: MAIN PANEL ########## -->
<div class="br-mainpanel">
    <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
            <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="breadcrumb-item" href="{{ route('admin.settings.index') }}">Settings</a>
{{--            <span class="breadcrumb-item active">Settings Layouts</span>--}}
        </nav>
    </div><!-- br-pageheader -->
    <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <h4 class="tx-gray-800 mg-b-5">Payment Method</h4>
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

                    <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Payment Method</h6>

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
                            <td><span class="text-capitalize">Card Payment</span>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="card_payment_status">Status</label>
                                        <select id="card_payment_status" required name="card_payment" class="form-control">
                                            <option {{ setting('card_payment') == 1 ? 'selected' : '' }} value="1">ENABLED</option>
                                            <option {{ setting('card_payment') == 0 ? 'selected' : '' }} value="0">DISABLED</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="card_payment_minimum">Minimum Amount</label>
                                        <input type="number" class="form-control" id="card_payment_minimum" name="card_payment_minimum" value="{{ setting('card_payment_minimum') }}" placeholder="Enter Minimum Amount">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="card_payment_maximum">Maximum Amount</label>
                                        <input type="number" class="form-control" id="card_payment_maximum" name="card_payment_maximum" value="{{ setting('card_payment_maximum') }}" placeholder="Enter Maximum Amount">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="text-capitalize">Crypto Payment</span>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="card_payment_status">Status</label>
                                        <select required name="crypto_payment" class="form-control">
                                            <option {{ setting('crypto_payment') == 1 ? 'selected' : '' }} value="1">ENABLED</option>
                                            <option {{ setting('crypto_payment') == 0 ? 'selected' : '' }} value="0">DISABLED</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="crypto_payment_minimum">Minimum Amount</label>
                                        <input type="number" class="form-control" id="crypto_payment_minimum" name="crypto_payment_minimum" value="{{ setting('crypto_payment_minimum') }}" placeholder="Enter Minimum Amount">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="crypto_payment_maximum">Maximum Amount</label>
                                        <input type="number" class="form-control" id="crypto_payment_maximum" name="crypto_payment_maximum" value="{{ setting('crypto_payment_maximum') }}" placeholder="Enter Maximum Amount">
                                    </div>
                                </div>
                            </td>
                            {{-- <td>
                                <select required name="crypto_payment" class="form-control">
                                    <option {{ setting('crypto_payment') == 1 ? 'selected' : '' }} value="1">ENABLED</option>
                                    <option {{ setting('crypto_payment') == 0 ? 'selected' : '' }} value="0">DISABLED</option>
                                </select>
                            </td> --}}
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


