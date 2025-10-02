@extends('backend.layouts.master-min')

@section('content')


    <div class="verification section-padding">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-xl-8 col-md-8">
                    <div class="auth-form card">
                        <div class="card-body">
                            <form method="POST" id="form" class="identity-upload">
                                {{ csrf_field() }}
                                <div class="identity-content">
                                    <span class="icon"><i class="fa fa-shield"></i></span>
                                    <h4>{{ $title }}, Pending verification, please contact admin if payment verification is taking longer than usual</h4>
                                </div>

{{--                                <div class="upload-loading text-center mb-3">--}}
{{--                                    <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>--}}
{{--                                </div>--}}

                                <div class="row justify-content-center text-center" style="margin-top: 20px">
                                    <a class="btn btn-success text-center" href="{{ route('backend.pending.withdrawal') }}">Back to Withdrawals</a>

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
    </script>

@endsection
