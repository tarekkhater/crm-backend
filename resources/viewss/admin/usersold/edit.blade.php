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

                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                    <label class="col-sm-4 form-control-label">First Name: <span class="tx-danger">*</span></label>
                    <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                        <input id="name" type="text" placeholder="First name" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ $user->first_name }}" required autocomplete="name" autofocus>

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
                        <input id="last_name" type="text" placeholder="Last name" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ $user->last_name }}" required autocomplete="name" autofocus>

                        @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <small><strong>{{ $message }}</strong></small>
                            </span>
                        @enderror
                    </div>
                    </div><!-- row -->

                    <div class="row mg-t-20">
                    <label class="col-sm-4 form-control-label">Email: <span class="tx-danger">*</span></label>
                    <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                        <input id="email" type="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required autocomplete="email" autofocus>

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
                            @foreach ($countries as $country)
                                <option value="{{ $country->nicename }}" <?= ($country->nicename == $user->country) ? 'selected' : ' ' ?> >
                                {{ $country->nicename }}
                            </option>
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

                                    <image id="flag-icon" src="{{ $user->iso }}" width="32" height="22" style="margin-top:7px;margin-right:4px; backgroud-color:#e9ecef;"/>

                            </div>

                            <input name="phone_code" id="phone_code" value="{{ $user->phone_code }}"  style="width:80px;"  require readonly/>
                            <div class="input-group-append">

                                    <input id="phone" type="text" placeholder="Phone number" class=" @error('phone') is-invalid @enderror" name="phone" value="{{ $user->phone }}" required autocomplete="phone" autofocus style="width:530px; height:40px;">

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
                    <!-- <div class="row mg-t-20">
                    <label class="col-sm-4 form-control-label">Mobile Number: <span class="tx-danger">*</span></label>
                    <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                        <div class="input-group mb-3">

                            <div class="input-group-prepend">
                                <select name="phone_code" class="custom-select" id="inputGroupSelect01" require>
                                    <option id="phone_code" value="{{  $user->phone_code }}">{{  $user->phone_code }}</option>
                                </select>
                            </div>
                            <input id="phone" type="text" placeholder="Phone number" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $user->phone }}" required autocomplete="phone" autofocus>

                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <small><strong>{{ $message }}</strong></small>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </div> -->

                    <div class="row mg-t-14">
                        <label class="col-sm-4 form-control-label">Username <span class="tx-danger">*</span></label>
                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                            <input id="name" disabled type="text" placeholder="Username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ $user->first_name }}" required readonly autocomplete="username" autofocus>

                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <small><strong>{{ $message }}</strong></small>
                                </span>
                            @enderror
                        </div>
                    </div>

{{--                    <div class="row mg-t-20">--}}
{{--                        <label class="col-sm-4 form-control-label">Address </label>--}}
{{--                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">--}}
{{--                            <input type="text" placeholder="Address" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address', $user->address) }}" required autocomplete="address">--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="row mg-t-20">--}}
{{--                        <label class="col-sm-4 form-control-label">Permanent Address </label>--}}
{{--                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">--}}
{{--                            <input type="text" placeholder="Permanent Address" class="form-control @error('permanent_address') is-invalid @enderror" name="permanent_address" value="{{ old('permanent_address', $user->permanent_address) }}" required autocomplete="permanent_address">--}}
{{--                        </div>--}}
{{--                    </div>--}}


                @if (auth()->user()->hasRole(['superadmin','admin']))
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
{{--                    @endrole--}}
                    @endif

                    <div class="row mg-t-20">
                        <label class="col-sm-4 form-control-label"> <span>Active</span>    </label>                      <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                            <input type="checkbox" name="active" @if ($user->active) checked @endif @if ($user->hasRole(['superadmin','admin'])) disabled @endif>
                        </div>
                    </div>

                    @if(setting('joint_account') == 1)
                    <div class="row mt-4 mb-3">
                        <h3>Joint Account Details</h3>
                    </div>
                    <div class="row">
                        <label class="col-sm-4 form-control-label">First Name: </label>
                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                            <input id="j_first_name" type="text" placeholder="First name" class="form-control @error('j_first_name') is-invalid @enderror" name="j_first_name" value="{{ $user->j_first_name }}" >
                        </div>
                    </div><!-- row -->
                    <div class="row mg-t-20">
                        <label class="col-sm-4 form-control-label">Last Name: </label>
                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                            <input id="j_last_name" type="text" placeholder="Last name" class="form-control @error('j_last_name') is-invalid @enderror" name="j_last_name" value="{{ $user->j_last_name }}" >
                        </div>
                    </div>

                    <div class="row mg-t-20">
                        <label class="col-sm-4 form-control-label">Email:</label>
                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                            <input id="j_email" type="email" placeholder="Email" class="form-control @error('j_email') is-invalid @enderror" name="j_email" value="{{ $user->j_email }}">
                        </div>
                    </div>
                    <!-- <div class="row mg-t-20">
                        <label class="col-sm-4 form-control-label">Mobile Number: </label>
                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                            <input id="j_mobile_number" type="text" placeholder="Phone number" class="form-control @error('j_phone') is-invalid @enderror" name="j_phone" value="{{ $user->j_phone }}" >
                        </div>
                    </div> -->
                    <div class="row mg-t-20">
                        <label class="col-sm-4 form-control-label">Country: </label>
                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                            <input  type="text" placeholder="Country" class="form-control @error('j_country') is-invalid @enderror" name="j_country" value="{{ $user->j_country }}"  autofocus>
                        </div>
                    </div>
                    @endif

                {{--                    <div class="row mg-t-20">--}}
{{--                        <label class="col-sm-4 form-control-label">Choose Role  {{ $user->role  }}<span class="tx-danger">*</span></label>--}}
{{--                        <div class="col-sm-8 mg-t-10 mg-sm-t-0">--}}
{{--                        <select name="role" class="form-control select2" data-placeholder="Choose Role" required>--}}
{{--                            <option label="Choose Role"></option>--}}
{{--                                @foreach ($admin_roles ?? [] as $role)--}}
{{--                                    <option value="{{ $role ?? '' }}" @if($role == ($user->role)) selected @endif>{{ $role }}</option>--}}
{{--                                @endforeach--}}
{{--                            --}}{{-- <option value="USA">Plan A</option>--}}
{{--                            <option value="UK">Plan B</option> --}}

{{--                        </select>--}}
{{--                    </div>--}}
{{--                    </div>--}}

                    <!-- col-4 -->
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
