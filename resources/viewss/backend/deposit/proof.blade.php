@extends('backend.layouts.master')

{{--@section('styles')--}}
{{--    <style>--}}
{{--        .form-control {--}}
{{--            background: #fff3cd!important;--}}
{{--        }--}}
{{--</style>--}}

{{--@endsection--}}

@section('content')

    <div class="content-body">
        <div class="container">
            <div class="row">

                @include('partials.menu')

                <div class="col-xl-6 col-lg-6 col-md-6">

                    @include('notification')
                        <form method="POST" action="{{ route('backend.deposits.update',$deposit->id) }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input name="_method" type="hidden" value="PUT">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Payment Proof</h4>
                                </div>
                                <div class="card-body">
                                    <div class="input-group mt-2">
                                        <input type="text" class="form-control"
                                               value="{{ setting('wallet_id') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-primary text-white">Copy</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between my-3">

                                        <div class="" >
                                            <p><input type="file"  accept="image/*" name="proof" id="file"  onchange="loadFile(event)" style="display: none;"></p>
                                            <label class="btn btn-primary"  for="file" style="cursor: pointer;">Upload Payment Proof</label>
                                            <span class="">Maximum file size is 2mb</span>
                                            <p><img id="output" width="100" /></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="btn-group mb-3">
                                <button type="submit" class="btn btn-success">Complete Transaction</button>
                            </div>
                        </form>

                </div>

            </div>
        </div>
    </div>

@endsection

@section('js')

    <script>
        var loadFile = function(event) {
            var image = document.getElementById('output');
            image.src = URL.createObjectURL(event.target.files[0]);
        };
    </script>
@endsection
