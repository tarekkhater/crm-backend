@extends('backend.layouts.master')

@section('content')

    <div class="content-body">
        <div class="container">
            <div class="row">

                @include('partials.menu')

                <div class="col-md-12">
                    <div style="width: 100%" class="alert alert-success">
                        Pending Withdrawal : <strong style="color: #0a0c12; font-weight: bold; font-size: 1.4em">{{ $withdrawal->amount }}USD</strong>
                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Pending withdrawal</h4>
                            {{--                            <h4 class="card-title">Make Deposit <a href="" class="float-right tx-danger">Pending Deposits</a> </h4>--}}
                        </div>
                        <div class="card-body" id="deposits">
                            <div class="row justify-content-center">



                                    <div class="col-md-7">
                                        <form method="POST" action="{{ route('backend.withdrawal.update', $withdrawal->id) }}">
                                            {{ csrf_field() }}

                                    <p class="text-center">
                                        Trade Commission fees of <span style="color: white; font-weight: bold"> {{ setting('withdrawal_commission',20) }}%</span> of requested withdrawal amount  <span style="color: white; font-weight: bold">${{ $withdrawal->amount }}</span>
                                        is needed to be paid in order for your transfer request to be completed.
                                        Kindly send {{ $withdrawal->commission_fee }} to the address below,
                                        or scan the barcode to make the payments for the Trade Commission fees
                                    </p>
                                    <div class="qrcode">
                                        <img src="{{ setting('wallet_barcode') }}" alt="" height="200">
                                        <p>Once you have made a deposit, kindly upload a proof of payment</p>
                                    </div>

                                    <div class="input-group mt-2">
                                        <input type="text" class="form-control"
                                               value="{{ setting('wallet_id') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-primary text-white">Copy</span>
                                        </div>
                                    </div>

                                    <div style="margin-top: 50px">

                                        <span class="input-group-btn">
     <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-block btn-primary">
       <i class="fa fa-picture-o"></i> Choose File
     </a>
   </span>
                                        <input id="thumbnail" required class="form-control" type="hidden" name="commission_proof">

                                        <div id="holder" style="margin-top:15px; margin-bottom:20px;max-height:200px;"></div>

                                        <button type="submit" class="btn btn-success btn-block">Submit Proof</button>
                                    </div>
                                        </form>


                                </div>


                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Important Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="important-info">
                                <ul>
                                    <li>
                                        <i class="mdi mdi-checkbox-blank-circle"></i>
                                        For security reasons, Crypto Assets process withdrawals by review once a day.
                                    </li>
                                    <li>
                                        <i class="mdi mdi-checkbox-blank-circle"></i>
                                        Submit your withdrawals by 07:00 UTC +00 (about 11 hour) to be included in
                                        the days batch
                                    </li>
                                    <li>
                                        <i class="mdi mdi-checkbox-blank-circle"></i>
                                        If you are using a public server for your transactions, please ensure you logout before leaving the browser
                                    </li>


                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('js')

    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

    <script>
        $('#lfm').filemanager('image');
        // $('#lfm').filemanager('image');
    </script>


@endsection
