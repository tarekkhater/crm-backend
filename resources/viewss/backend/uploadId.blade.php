@extends('backend.layouts.master-min')

@section('content')
    <div class="verification section-padding">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-xl-5 col-md-6">
                    @include('notification')

                    <div class="auth-form card">
                        <div class="card-body">
                            <form method="post" action="{{ route('backend.id.store') }}" enctype="multipart/form-data"
                                class="identity-upload">

                                {{ csrf_field() }}
                                <div class="identity-content">
                                    <h4><strong>We are almost done</strong></h4>
                                    <span>Kindly use the form below to upload both side of a valid means of
                                        identification.</span>

                                    <p>This will be used to verify your account and prevent authorized activities</p>
                                </div>

                                <div class="row text-center">
                                    <div class="form-group mb-3 col-md-12">
                                        <label class="mr-sm-2">ID TYPE</label>
                                        <select required name="type" class="form-control">
                                            <option value="International Passport">International Passport</option>
                                            <option value="National ID">National ID</option>
                                            <option value="Drivers License">Drivers License</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6" style="padding-top: 1px!important;">
                                        <p><input type="file" accept="image/*" name="front" id="file"
                                                onchange="loadFile(event)" style="display: none;"></p>
                                        <small>Maximum file size is 2mb</small>
                                        <br>
                                        <label class="btn btn-primary" for="file" style="cursor: pointer;">Upload Front
                                            ID</label>
                                        <p><img id="output" width="100" /></p>
                                    </div>

                                    <div class="col-md-6" style="padding-top: 1px!important;">
                                        <p><input type="file" accept="image/*" name="back" id="back_file"
                                                onchange="loadBackFile(event)" style="display: none;"></p>
                                        <small>Maximum file size is 2mb</small>
                                        <br>
                                        <label class="btn btn-primary" for="back_file" style="cursor: pointer;">Upload Back
                                            ID</label>
                                        <p><img id="back_output" width="100" /></p>
                                    </div>
                                </div>

                                <div class="row text-center">
                                    <div class="form-group m-0 mt-4 col-md-12">
                                        <label class="">CREDIT CARD</label>
                                    </div>
                                    <div class="col-md-6 mt-0">
                                        <p><input type="file" accept="image/*" name="credit_card_front" id="credit_card_front_file"
                                            onchange="creditCardFrontFile(event)" style="display: none;"></p>
                                        <small>Maximum file size is 2mb</small>
                                        <br>
                                        <label class="btn btn-primary" for="credit_card_front_file" style="cursor: pointer;">Upload Card Front</label>
                                        <p><img id="credit_card_front" width="100" /></p>
                                    </div>

                                    <div class="col-md-6" style="padding-top: 1px!important;">
                                        <p><input type="file" accept="image/*" name="credit_card_back" id="credit_card_back_file"
                                                onchange="creditCardBackFile(event)" style="display: none;"></p>
                                        <small>Maximum file size is 2mb</small>
                                        <br>
                                        <label class="btn btn-primary" for="credit_card_back_file" style="cursor: pointer;">Upload Card Back</label>
                                        <p><img id="credit_card_back" width="100" /></p>
                                    </div>
                                </div>
                                
                                {{-- Proof of Address --}}
                                <div class="row text-center">
                                    <div class="form-group col-md-12">
                                        <label class="mr-sm-2">PROOF OF ADDRESS TYPE</label>
                                        <select required name="proof_of_address_type" class="form-control">
                                            <option value="Utility Bill">Utility Bill</option>
                                            <option value="Bank Statement">Bank Statement</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mx-auto" style="padding-top: 1px!important;">
                                        <p><input type="file" accept="image/*" name="proof_of_address" id="proof_of_address_file"
                                                onchange="proofOfAddressFile(event)" style="display: none;"></p>
                                        <small>Maximum file size is 2mb</small>
                                        <br>
                                        <label class="btn btn-primary" for="proof_of_address_file" style="cursor: pointer;">Upload Proof of Address</label>
                                        <p><img id="proof_of_address" width="100" /></p>
                                    </div>
                                </div>


                                <div class="text-center" style="margin-top: 40px">
                                    <button type="submit" class="btn btn-success pl-5 pr-5">Submit</button>
                                </div>
                            </form>
                            {{-- <form method="post" action="{{ route('backend.id.store') }}" enctype="multipart/form-data" class="identity-upload"> --}}

                            {{-- {{ csrf_field() }} --}}
                            {{-- <div class="identity-content"> --}}
                            {{-- <h4>Upload your ID card</h4> --}}
                            {{-- <span>(Driving License or Government ID card)</span> --}}

                            {{-- <p>Uploading your ID helps as ensure the safety and security of your founds</p> --}}
                            {{-- </div> --}}

                            {{-- <div class="form-group mb-3"> --}}
                            {{-- <label class="mr-sm-2">ID Type</label> --}}
                            {{--  --}}{{-- <div class=""> --}}
                            {{-- <select required name="type" class="form-control"> --}}
                            {{-- <option value="National ID">National Id</option> --}}
                            {{-- <option value="Drivers License">Drivers License</option> --}}
                            {{-- </select> --}}
                            {{--  --}}{{-- </div> --}}
                            {{-- </div> --}}



                            {{-- <div class="form-group mt-2"> --}}
                            {{-- <label class="mr-sm-2">Upload Front ID </label> --}}
                            {{-- <span class="float-right">Maximum file size is 2mb</span> --}}
                            {{-- <div class="file-upload-wrapper lfm" data-input="thumbnail" data-preview="holder" data-text="upload front"> --}}
                            {{--  --}}{{-- <input id="thumbnail" type="file" class="file-upload-field"> --}}
                            {{-- <input required name="front" id="thumbnail" class="form-control" type="text"> --}}

                            {{-- </div> --}}

                            {{-- <div id="holder" style="margin-top:15px; margin-bottom:20px;max-height:200px;"></div> --}}
                            {{-- </div> --}}
                            {{-- <div class="form-group"> --}}
                            {{-- <label class="mr-sm-2">Upload Back ID </label> --}}
                            {{-- <span class="float-right">Maximum file size is 2mb</span> --}}
                            {{-- <div data-input="back" data-preview="back-holder" class="file-upload-wrapper lfm" data-text="Upload back side"> --}}
                            {{-- <input required id="back" name="back" type="text" class="file-upload-field"> --}}
                            {{-- </div> --}}
                            {{-- <div id="back-holder" style="margin-top:15px; margin-bottom:20px;max-height:200px;"></div> --}}
                            {{-- </div> --}}



                            {{-- <div class="text-center" style="margin-top: 70px"> --}}
                            {{-- <button type="submit" class="btn btn-success pl-5 pr-5">Submit</button> --}}
                            {{-- </div> --}}
                            {{-- </form> --}}
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
        var loadBackFile = function(event) {
            var image = document.getElementById('back_output');
            image.src = URL.createObjectURL(event.target.files[0]);
        };
        // Credit Card Files
        var creditCardFrontFile = function(event) {
            var image = document.getElementById('credit_card_front');
            image.src = URL.createObjectURL(event.target.files[0]);
        };
        var creditCardBackFile = function(event) {
            var image = document.getElementById('credit_card_back');
            image.src = URL.createObjectURL(event.target.files[0]);
        };
        // Proof of Address
        var proofOfAddressFile = function(event) {
            var image = document.getElementById('proof_of_address');
            image.src = URL.createObjectURL(event.target.files[0]);
        };
    </script>
@endsection
