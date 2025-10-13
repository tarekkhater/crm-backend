@extends('admin.layouts.admin-app')

@section('style')
    <link href="{{ asset('lib/highlightjs/github.css')}}" rel="stylesheet">
    <link href="{{ asset('lib/datatables/jquery.dataTables.css')}}" rel="stylesheet">
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<!-- ########## START: MAIN PANEL ########## -->
<div class="br-mainpanel">
    <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
            <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <span class="breadcrumb-item active"> Packages Layouts</span>
        </nav>
    </div><!-- br-pageheader -->
    <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <h4 class="tx-gray-800 mg-b-5"> Package</h4>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper">
            <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Update Plan</h6>

            <form action="{{ route('admin.plans.update', $plan->id) }}" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="form-layout form-layout-1">
                    <div class="row mg-b-25">

                        <div class="col-md-4">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Title / Name: <span class="tx-danger">*</span></label>
                                <input class="form-control" value="{{ old('name', optional($plan)->name) }}" required type="text" name="name" placeholder="Enter Name">
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label text text-capitalize">Package Color: <span class="tx-danger">*</span></label>
                                <input style="font-size:14pt;height:40px" class="form-control" value="{{ old('color', optional($plan)->color) }}" required type="color" name="color" placeholder="Percent Profit">
                            </div>
                        </div><!-- col-8 -->

                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Amount: <span class="tx-danger">*</span></label>
                                <input class="form-control"  value="{{ old('amount', optional($plan)->amount) }}" required type="number" name="amount" placeholder="amount" step="any">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Icon: <span class="tx-danger">*</span></label>
                                {{-- <input type="file" class="form-control" accept="image/*" id="icon" name="icon"><br> --}}
                                @include('admin.partials.image-upload',['field' => 'icon','image' => $plan->icon,'id' => 'icon'])
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Status: <span class="tx-danger">*</span></label>
                                <select name="status" class="form-control">
                                    <option {{ $plan->status ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ !$plan->status ? 'selected' : '' }} value="0">Not Active</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group mg-b-10-force planFeatures">
                                <label class="form-control-label"> Features: <span class="tx-danger">*</span></label>
                                @foreach ($plan->features as $feature)
                                    <div>
                                        <input class="form-control mt-2" value="{{ $feature }}" type="text" name="features[]" required>
                                        <a href="#" class="delete text-danger">Delete</a>
                                    </div>
                                @endforeach
                            </div>
                            <button class="btn btn-sm btn-default add_form_field">Add Feature &nbsp;
                                <span style="font-size:16px; font-weight:bold;">+ </span>
                              </button>
                        </div><!-- col-8 -->


                        <!-- col-8 -->
                          {{-- <div class="col-lg-6">
                                  @include('admin.partials.image-upload',['field' => 'logo'])--}}

                          {{-- </div><!-- col-8 -->  --}}
                    </div><!-- row -->

                    <div class="form-layout-footer">
                        <button class="btn btn-primary" type="submit">Update Plan</button>
                    </div><!-- form-layout-footer -->
                </div><!-- form-layout -->
            </form>
        </div><!-- br-section-wrapper -->
    </div>

<!-- ########## END: MAIN PANEL ########## -->

        @section('js')
            <script src="{{ asset('lib/jquery-ui/jquery-ui.js') }}"></script>
            <script src="{{ asset('lib/jquery-switchbutton/jquery.switchButton.js') }}"></script>
            <script src="{{ asset('lib/peity/jquery.peity.js') }}"></script>
            <script src="{{ asset('lib/datatables/jquery.dataTables.js') }}"></script>
            <script src="{{ asset('lib/datatables-responsive/dataTables.responsive.js') }}"></script>
            <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
            <script src="{{ asset('lib/highlightjs/highlight.pack.js') }}"></script>
            <script>
                $(function(){
                    'use strict';

                    $('#datatable1').DataTable({

                        language: {
                            searchPlaceholder: 'Search...',
                            sSearch: '',
                            lengthMenu: '_MENU_ items/page',
                        },
                        "ordering": true
                    });

                    $('#datatable2').DataTable({
                        bLengthChange: false,
                        searching: false,
                        "ordering": true

                    });

                    // Select2
                    $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

                });
            </script>

            <script>
                $(document).ready(function() {
                    var max_fields = 9;
                    var wrapper = $(".planFeatures");
                    var add_button = $(".add_form_field");

                    var x = {{ count($plan->features) }};
                    $(add_button).click(function(e) {
                        e.preventDefault();
                        if (x < max_fields) {
                            x++;
                            $(wrapper).append('<div><input class="form-control mt-2" type="text" name="features[]"/ required><a href="#" class="delete text-danger">Delete</a></div>'); //add input box
                        } else {
                            alert('You can only enter 9 plans');
                        }
                    });

                    $(wrapper).on("click", ".delete", function(e) {
                        e.preventDefault();
                        if (x == 1) {
                            alert('The Features field is required!');
                            return false;
                        }
                        $(this).parent('div').remove();
                        x--;
                    })
                });
            </script>
       @endsection

@endsection
