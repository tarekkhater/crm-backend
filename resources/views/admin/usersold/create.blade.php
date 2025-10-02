@extends('admin.layouts.admin-app')

@section('css')
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="Stylesheet">
@endsection

@section('content')
    <div class="br-mainpanel">
    <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <h4 class="tx-gray-800 mg-b-5">Add new {{ $role }}</h4>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper">

        <div class="row mg-t-10">
            <div class="col-xl-10">

            @include('notification')

                @if ($errors->any())

                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            <div class="form-layout form-layout-4">
                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Add {{ $role }} User</h6>

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="row">
                    <label class="col-sm-4 form-control-label">First Name: <span class="tx-danger">*</span></label>
                    <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                        <input id="name" type="text" placeholder="First name" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="name" autofocus>

                        @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <small><strong>{{ $message }}</strong></small>
                            </span>
                        @enderror
                    </div>
                    </div><!-- row -->

           <div class="row mg-t-20">
                    <label class="col-sm-4 form-control-label">Last Name: <span class="tx-danger">*</span></label>
                    <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                        <input id="last_name" type="text" placeholder="Last name" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="name" autofocus>

                        @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <small><strong>{{ $message }}</strong></small>
                            </span>
                        @enderror
                    </div>
                    </div>

                    <div class="row mg-t-20">
                    <label class="col-sm-4 form-control-label">Email: <span class="tx-danger">*</span></label>
                    <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                        <input id="email" type="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <small><strong>{{ $message }}</strong></small>
                            </span>
                        @enderror
                    </div>
                    </div>

                    <div class="row mg-t-20">
                    <label class="col-sm-4 form-control-label">Country: <span class="tx-danger">*</span></label>
                    <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                        <select onchange="endpoint('{{ route('get-country-info','') }}',this.value)" name="country" class="form-control @error('country') is-invalid @enderror" autocomplete="email" require autofocus>
                            <option value=" " onclick="selectC()">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->nicename ?? '' }}">{{ $country->nicename ?? ''}}</option>
                            @endforeach
                        </select>

                        @error('country')
                            <span class="invalid-feedback" role="alert">
                                <small><strong>{{ $message }}</strong></small>
                            </span>
                        @enderror
                    </div>
                    </div>

                    <div class="row mg-t-20">
                    <label class="col-sm-4 form-control-label">Mobile Number: <span class="tx-danger">*</span></label>
                    <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                        <div class="input-group mb-3">

                        <div class="input-group mb-3">
                            <div class="input-group-prepend" style="backgroud-color:#e9ecef;">

                                    <img id="flag-icon" src="{{ asset('./flags/ddd.png') }}" width="32" height="22" style="margin-top:7px;margin-right:4px; backgroud-color:#e9ecef;"/>

                            </div>

                            <input name="phone_code" id="phone_code" value=""  style="width:80px;"  require readonly/>
                            <div class="input-group-append">

                                    <input id="phone" type="text" placeholder="Phone number" class=" @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus style="width:400px; height:40px;">

                            </div>
                        </div>


                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <small><strong>{{ $message }}</strong></small>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </div>
                    <div class="row mg-t-20">
                    <label class="col-sm-4 form-control-label">Source: <span class="tx-danger">*</span></label>
                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                            <select required class="form-control" id="source" name="source">
                                <option value="">Select Source</option>
                                @foreach ($sources as $source)
                                    <option value="{{ $source->id }}">{{ $source->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row mg-t-20">
                        <label class="col-sm-4 form-control-label">Address </label>
                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                            <input type="text" placeholder="Address" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required autocomplete="address">
                        </div>
                    </div>

                    <div class="row mg-t-20">
                        <label class="col-sm-4 form-control-label">Permanent Address </label>
                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                            <input type="text" placeholder="Permanent Address" class="form-control @error('permanent_address') is-invalid @enderror" name="permanent_address" value="{{ old('permanent_address') }}" required autocomplete="permanent_address">
                        </div>
                    </div>


                    <div class="row mg-t-20">
                        <label class="col-sm-4 form-control-label">Password <span class="tx-danger">*</span></label>
                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                            <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

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
                            <input id="password-confirm" type="password" placeholder="Confirm Password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

{{--                    <div class="row mg-t-20">--}}
{{--                        <label class="col-sm-4 form-control-label"> <span>Active</span>    </label>                      <div class="col-sm-8 mg-t-10 mg-sm-t-0">--}}
{{--                            <input type="checkbox" value="1" name="active" @if (null !== old('active')) checked @endif>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="row mg-t-20">--}}
{{--                        <label class="col-sm-4 form-control-label">Choose Role<span class="tx-danger">*</span></label>--}}
{{--                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">--}}
{{--                        <select name="role" class="form-control select2" data-placeholder="Choose Role" required>--}}
{{--                            <option label="Choose Role"></option>--}}
{{--                                @foreach ($roles ?? [] as $role)--}}
{{--                                    <option value="{{ $role->id }}" @if ($role->id == old('role')) selected @endif>{{ $role->name }}</option>--}}
{{--                                @endforeach--}}
{{--                            --}}{{-- <option value="USA">Plan A</option>--}}
{{--                            <option value="UK">Plan B</option> --}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                    </div><!-- col-4 -->--}}

                    <hr />

                    @if ($role == 'autotrader')
                        <div class="row mg-t-20">
                            <label class="col-sm-4 form-control-label">Profit (%) <span class="tx-danger">*</span></label>
                            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                                <input id="profit" type="number" step="any" placeholder="Profit" class="form-control" name="profit" required autofocus>
                            </div>
                        </div>
                        <div class="row mg-t-20">
                            <label class="col-sm-4 form-control-label">Fee ($) <span class="tx-danger">*</span></label>
                            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                                <input id="profit" type="number" step="any" placeholder="fee" class="form-control" name="fee" required autofocus>
                            </div>
                        </div>

                    @else
                        <input name="profit" value="" type="hidden">
                        <input name="fee" value="" type="hidden">
                    @endif

                    <input name="role" value="{{ $role }}" type="hidden">

                    <br/>

                    @if(setting('joint_account') == 1)
                    <h4>Joint Account Informations</h4>

                    @foreach($forms as $item)
                        <div class="row mg-t-20">
                            <label class="col-sm-4 form-control-label text-capitalize">{{ str_replace('_',' ',$item) }}</label>
                            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                                <input type="text" placeholder="{{ str_replace('_',' ',$item) }}" class="form-control" name="j_{{ $item }}">
                            </div>
                        </div>
                        @endforeach
                    @endif
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

</script>

<script>
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
