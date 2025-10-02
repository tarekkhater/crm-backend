@extends('backend.layouts.master-min')

@section('content')

    <div class="verification section-padding">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-xl-5 col-md-6">
                    <div class="auth-form card">
                        <div class="card-body">
                            <form action="{{ route('backend.account.upload.id') }}" class="identity-upload">
                                <div class="identity-content">

                                    <div class="qrcode">
                                        <img src="/back/images/qr.svg" alt="" width="150">
                                    </div>

                                    <div class="input-group mt-2">
                                        @if (Request()->get('type') == 'btc')
                                            <input type="text" class="form-control"
                                                   value="{{ setting('wallet_id') }}">
                                        @else
                                            <input type="text" class="form-control"
                                                   value="0xf766EFDf6c573333b6fb1d9a94cDd258C00b7598">
                                        @endif
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-primary text-white">Copy</span>
                                        </div>
                                    </div>

                                    <p>Once you have made a deposit, kindly upload a proof of payment</p>
                                </div>

                                <div class="text-center">
                                    <a href="{{ route('backend.deposit.proof') }}" class="btn btn-success pl-5 pr-5">Upload Proof</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('js')

@endsection
