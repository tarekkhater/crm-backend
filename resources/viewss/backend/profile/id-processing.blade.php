@extends('backend.layouts.master-min')

@section('content')


    <div class="verification section-padding">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-xl-5 col-md-6">
                    <div class="auth-form card">
                        <div class="card-body">
                            <form action="{{ route('backend.id.proceed') }}" class="identity-upload">
                                <div class="identity-content">
                                    <span class="icon"><i class="fa fa-shield"></i></span>
                                    <h4>We are uploading your ID</h4>
                                    <p>Your identity is being uploaded. We will email you once your verification has
                                        verified.
                                    </p>
                                </div>

                                <div class="upload-loading text-center mb-3">
                                    <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
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

    <script>

        window.setTimeout(function () {
            window.location.href = "{{ route('backend.id.proceed') }}";
        }, 2000);
    </script>

@endsection
