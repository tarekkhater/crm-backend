@extends('admin.layouts.admin-app')

@section('css')
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="Stylesheet">
@endsection

@section('content')
    <div class="br-mainpanel">
    <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <h4 class="tx-gray-800 mg-b-5">Edit {{ $user->name }}</h4>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper">

        <div class="row mg-t-10">
            <div class="col-xl-10">

                @include('notification')

            <div class="form-layout form-layout-4">
                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Edit {{ $user->name }}</h6>

                <form action="{{ route('admin.profile.password-update') }}" method="POST">
                    @csrf
                    @method('PUT')

                 

                    <div class="row mg-t-20">
                        <label class="col-sm-4 form-control-label">Password <span class="tx-danger">*</span></label>
                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                            <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <small><strong>{{ $message }}</strong></small>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mg-t-20">
                        <label class="col-sm-4 form-control-label">Confirm Password <span class="tx-danger">*</span></label>
                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                            <input id="password-confirm" type="password" placeholder="Confirm Password" class="form-control" name="password_confirmation" autocomplete="new-password">
                        </div>
                    </div>

                    <div class="row mg-t-20">
                        <label class="col-sm-4 form-control-label">Current Password <span class="tx-danger">*</span></label>
                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                            <input id="current-pass" type="password" placeholder="Password" class="form-control @error('current_pass') is-invalid @enderror" name="current_pass" autocomplete="new-password">

                            @error('current_pass')
                                <span class="invalid-feedback" role="alert">
                                    <small><strong>{{ $message }}</strong></small>
                                </span>
                            @enderror
                        </div>
                    </div>

   
                    <div class="form-layout-footer mg-t-30">
                    <button class="btn btn-info">Submit Form</button>
                    <a href="{{ url('admin/users') }}" class="btn btn-secondary">Cancel</a>

                    </div><!-- form-layout-footer -->
                </form>
            </div><!-- form-layout -->
            </div><!-- col-6 -->

        </div>
        </div>
</div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.js" integrity="sha512-RT3IJsuoHZ2waemM8ccCUlPNdUuOn8dJCH46N3H2uZoY7swMn1Yn7s56SsE2UBMpjpndeZ91hm87TP1oU6ANjQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
        function endpoint(url,value){

            axios.get(url+'/'+value, {
                c_name : value
            })
            .then(function (response) {

                document.getElementById('phone_code').value = "+"+response.data.phonecode;
                document.getElementById('phone_code').innerHTML = "+"+response.data.phonecode;
                document.getElementById('flag-icon').src =  response.data.iso;

                console.log(response.data.iso)

            }).catch(function (error) {
                toastr.error('An error occuied, Please try again', 'Failed',{
                    "positionClass" : "toast-top-right"
                })
            });
        }
        function selectC()
        {
            $('#phone_code').val(" ");
            $('#phone_code').html(" ");
        }

        function phone_code(phone_code)
        {
            $('#phone_code').val("+"+phone_code);
            $('#phone_code').html("+"+phone_code);
        }
</script>
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
@endsection
