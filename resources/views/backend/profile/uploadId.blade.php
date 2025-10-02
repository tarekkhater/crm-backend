@extends('backend.layouts.master-min')

@section('content')

    <div class="verification section-padding">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-xl-5 col-md-6">
                    <div class="auth-form card">
                        <!-- <div class="card-header">
                            <h4 class="card-title">Link a Debit card</h4>
                        </div> -->
                        <div class="card-body">
                            <form action="{{ route('backend.account.upload.id') }}" class="identity-upload">
                                <div class="identity-content">
                                    <span class="icon"><i class="fa fa-shield"></i></span>
                                    <h4>Please verify your identity to continue using this platform</h4>
                                    <p>Uploading your ID helps us ensure the safety and security of your funds</p>
                                </div>

                                <div class="text-center">
                                    <a href="{{ route('backend.account.upload.id') }}" class="btn btn-success pl-5 pr-5">Upload ID</a>
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
