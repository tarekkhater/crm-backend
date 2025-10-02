@extends('backend.layouts.backend')

@section('content')

    <div class="container-fluid">

        <!-- START: Breadcrumbs-->
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 px-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h5 class="mb-0">Edit profile</h5></div>

                    <ol class="breadcrumb bg-transparent align-self-center m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}"> Dashboard</a></li>
                        <li class="breadcrumb-item">Edit profile</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- END: Breadcrumbs-->

        <div class="row mt-2">
                <div class="col-12">

                    <form method="post" action="{{ route('backend.profile.update') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">User Profile</h6>
                                </div>
                                <div class="card-body">

                                        <div class="form-row">
                                            <div class="form-group col-12">
                                                <label class="mr-sm-2">Your First Name</label>
                                                <input type="text" value="{{ auth()->user()->first_name }}" disabled class="form-control" placeholder="Username">
                                            </div>

                                            <div class="form-group col-12">
                                                <div class="media align-items-center mb-3">
                                                    <img class="mr-3 rounded-circle mr-0 mr-sm-3"
                                                         src="{{ auth()->user()->avatar }}" width="50" height="50" alt="">
                                                    <div class="media-body">
                                                        <h5 class="mb-0">{{ auth()->user()->name }}</h5>
                                                        <p class="mb-0">Max file size is 2mb
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="file-upload-wrapper" data-text="Change Photo">
                                                    <input name="file-upload-field" type="file"
                                                           class="file-upload-field">
                                                </div>
                                            </div>

                                        </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6">
                        </div>

                    </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Personal Information</h4>
                                    </div>
                                    <div class="card-body">
                                        {{--                                    <form method="post" action="{{ route('backend.profile.update') }}" class="personal_validate">--}}
                                        <div class="form-row">
                                            <div class="form-group col-xl-6 col-md-6">
                                                <label class="mr-sm-2">Your First Name</label>
                                                <input required value="{{ auth()->user()->first_name }}" type="text" class="form-control" placeholder="{{ auth()->user()->first_name }}"
                                                       name="first_name">
                                            </div>
                                            <div class="form-group col-xl-6 col-md-6">
                                                <label class="mr-sm-2">Your Last Name</label>
                                                <input required value="{{ auth()->user()->last_name }}" type="text" class="form-control" placeholder="{{ auth()->user()->last_name }}"
                                                       name="last_name">
                                            </div>
                                            <div class="form-group col-xl-6 col-md-6">
                                                <label class="mr-sm-2">Email</label>
                                                <input disabled type="email" value="{{ auth()->user()->email }}" class="form-control"
                                                       placeholder="{{ auth()->user()->email }}" >
                                            </div>
                                            <div class="form-group col-xl-6 col-md-6">
                                                <label class="mr-sm-2">Date of birth</label>
                                                <input required type="date" class="form-control" placeholder="10-10-2020"
                                                       id="datepicker" value="{{ auth()->user()->dob }}" autocomplete="off" name="dob">
                                            </div>
                                            <div class="form-group col-xl-6 col-md-6">
                                                <label class="mr-sm-2">Present Address</label>
                                                <input required type="text" class="form-control"
                                                       placeholder="address" value="{{ auth()->user()->address }}" name="address">
                                            </div>
                                            <div class="form-group col-xl-6 col-md-6">
                                                <label class="mr-sm-2">Permanent Address</label>
                                                <input required type="text" value="{{ auth()->user()->permanent_address }}" class="form-control"
                                                       placeholder="permanent address"
                                                       name="permanent_address">
                                            </div>
                                            <div class="form-group col-xl-6 col-md-6">
                                                <label class="mr-sm-2">City</label>
                                                <input required type="text" value="{{ auth()->user()->city }}" class="form-control" placeholder="City"
                                                       name="city">
                                            </div>
                                            <div class="form-group col-xl-6 col-md-6">
                                                <label class="mr-sm-2">Postal Code</label>
                                                <input required type="text" value="{{ auth()->user()->postal }}" class="form-control" placeholder="postal"
                                                       name="postal">
                                            </div>
                                            <div class="form-group col-xl-6 col-md-6">
                                                <label class="mr-sm-2">Country</label>
                                                <input required type="text" value="{{ auth()->user()->country }}" class="form-control" placeholder="Country"
                                                       name="country">
                                            </div>

                                        </div>
                                        {{--                                    </form>--}}
                                    </div>
                                </div>

                                @if (setting('joint_account'))
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Joint Account Information</h4>
                                        </div>
                                        <div class="card-body">
                                            {{--                                    <form method="post" action="{{ route('backend.profile.update') }}" class="personal_validate">--}}
                                            <div class="form-row">
                                                <div class="form-group col-xl-6 col-md-6">
                                                    <label class="mr-sm-2"> First Name</label>
                                                    <input required value="{{ auth()->user()->j_first_name }}" type="text" class="form-control" placeholder="{{ auth()->user()->j_first_name }}"
                                                           name="j_first_name">
                                                </div>
                                                <div class="form-group col-xl-6 col-md-6">
                                                    <label class="mr-sm-2"> Last Name</label>
                                                    <input required value="{{ auth()->user()->j_last_name }}" type="text" class="form-control" placeholder="{{ auth()->user()->j_last_name }}"
                                                           name="j_last_name">
                                                </div>
                                                <div class="form-group col-xl-6 col-md-6">
                                                    <label class="mr-sm-2">Email</label>
                                                    <input name="j_email" type="email" value="{{ auth()->user()->j_email }}" class="form-control">
                                                </div>     <div class="form-group col-xl-6 col-md-6">
                                                    <label class="mr-sm-2">Phone</label>
                                                    <input name="j_phone" type="text" value="{{ auth()->user()->j_phone }}" class="form-control">
                                                </div>

                                                <div class="form-group col-xl-6 col-md-6">
                                                    <label class="mr-sm-2">Country</label>
                                                    <input required type="text" value="{{ auth()->user()->j_country }}" class="form-control" placeholder="Country"
                                                           name="j_country">
                                                </div>

                                            </div>
                                            {{--                                    </form>--}}
                                        </div>
                                    </div>
                                @endif

                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group ">
                                            <button type="submit" class="btn btn-success px-4">Save</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>

                </div>
            </div>
    </div>


@endsection

@section('js')

@endsection
