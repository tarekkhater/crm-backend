@extends('admin.layouts.admin-app')

@section('css')
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="Stylesheet">
@endsection

@section('content')
    <div class="br-mainpanel">
        <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
            <h4 class="tx-gray-800 mg-b-5">Add Role</h4>
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
                            <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Add Role</h6>

                            {!! Form::open(array('route' => 'admin.roles.store','method'=>'POST')) !!}
                            <!-- row -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card mg-b-20">
                                        <div class="card-body">
                                            <div class="main-content-label mg-b-5">
                                                <div class="col-xs-7 col-sm-7 col-md-7">
                                                    <div class="form-group">
                                                        <p>اسم الصلاحية :</p>
                                                        {!! Form::text('name', null, array('class' => 'form-control')) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <!-- col -->
                                                <div class="col-lg-4">
                                                    <ul id="treeview1">
                                                        <li><a href="#">الصلاحيات</a>
                                                            <ul>
                                                        </li>
                                                        @foreach($permission as $value)
                                                        <label
                                                            style="font-size: 16px;">{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
                                                            {{ $value->name }}</label>

                                                        @endforeach
                                                        </li>

                                                    </ul>
                                                    </li>
                                                    </ul>
                                                </div>
                                                <!-- /col -->
                                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                                    <button type="submit" class="btn btn-main-primary">تاكيد</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- row closed -->
                            </div>
                            <!-- Container closed -->
                            </div>
                            <!-- main-content closed -->

                            {!! Form::close() !!}
                        </div><!-- form-layout -->
                    </div><!-- col-6 -->

                </div>
            </div>
        </div>
    @endsection

    @section('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.js"
            integrity="sha512-RT3IJsuoHZ2waemM8ccCUlPNdUuOn8dJCH46N3H2uZoY7swMn1Yn7s56SsE2UBMpjpndeZ91hm87TP1oU6ANjQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            function endpoint(url, value) {

                axios.get(url + '/' + value, {
                        c_name: value
                    })
                    .then(function(response) {

                        document.getElementById('phone_code').value = "+" + response.data.phonecode;
                        document.getElementById('phone_code').innerHTML = "+" + response.data.phonecode;
                        document.getElementById('flag-icon').src = response.data.iso;

                        console.log(response.data.iso)

                    }).catch(function(error) {
                        toastr.error('An error occuied, Please try again', 'Failed', {
                            "positionClass": "toast-top-right"
                        })
                    });
            }
        </script>

        <script>
            function selectC() {
                $('#phone_code').val(" ");
                $('#phone_code').html(" ");
            }

            function phone_code(phone_code) {
                $('#phone_code').val("+" + phone_code);
                $('#phone_code').html("+" + phone_code);
            }
        </script>
        <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
    @endsection
