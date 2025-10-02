@extends('backend.layouts.master-min')

@section('content')

    <div class="verification section-padding">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-xl-5 col-md-6">
                    @include('notification')
                    <div class="auth-form card">
                        <div class="card-body">
                            <form method="post" action="{{ route('backend.deposits.store') }}" enctype="multipart/form-data" class="identity-upload">

                                {{ csrf_field() }}
                                <div class="identity-content">
                                    <h4>Upload your Payment Proof</h4>

                                    <p>Once your payment is verified your account will be funded with the equivalent amount</p>
                                </div>

                                <div class="form-group">
                                    <label class="mr-sm-2">Enter Amount Deposited in USD</label>
                                    <div class="">
                                        {{--                                        <input id="thumbnail" type="file" class="file-upload-field">--}}
                                        <input required name="amount" placeholder="amount deposited" class="form-control" type="text">

                                    </div>

                                </div>
                                <div class="form-group">
{{--                                    <label class="mr-sm-2">Upload Payment Proof</label>--}}
{{--                                    <span class="float-right">Maximum file size is 2mb</span>--}}
{{--                                    <div data-input="back" data-preview="back-holder" class="file-upload-wrapper lfm" data-text="Upload proof">--}}
{{--                                        <input required id="back" name="proof" type="text" class="file-upload-field">--}}
{{--                                    </div>--}}
{{--                                    <div id="back-holder" style="margin-top:15px; margin-bottom:20px;max-height:200px;"></div>--}}

                                    <div class="" >
                                        <p><input type="file"  accept="image/*" name="proof" id="file"  onchange="loadFile(event)" style="display: none;"></p>
                                        <label class="btn btn-primary"  for="file" style="cursor: pointer;">Upload Payment Proof</label>
                                        <span class="float-right">Maximum file size is 2mb</span>
                                        <p><img id="output" width="100" /></p>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success pl-5 pr-5">Submit</button>
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
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

    <script>
        $('.lfm').filemanager('image');
        $('#lfd').filemanager('image');
    </script>

    <script>
        var loadFile = function(event) {
            var image = document.getElementById('output');
            image.src = URL.createObjectURL(event.target.files[0]);
        };
    </script>

@endsection
