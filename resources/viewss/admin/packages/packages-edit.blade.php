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
            <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Update Package</h6>

            <form action="{{ route('admin.packages.update', $package->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-layout form-layout-1">
                    <div class="row mg-b-25">

                        <div class="col-md-4">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Title / Name: <span class="tx-danger">*</span></label>
                                <input class="form-control" value="{{ old('name', optional($package)->name) }}" required type="text" name="name" placeholder="Enter Name">
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-md-4">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label text text-capitalize">Percent Profit: <span class="tx-danger">*</span></label>
                                <input class="form-control" value="{{ old('percent_profit', optional($package)->percent_profit) }}" required type="number" name="percent_profit" placeholder="Percent Profit">
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-md-4">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Period / Duration (Months): <span class="tx-danger">*</span></label>
                                <input class="form-control"  value="{{ old('period', optional($package)->period) }}" required type="number" name="period" placeholder="Period">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label text text-capitalize">Min Unit: <span class="tx-danger">*</span></label>
                                <input class="form-control" value="{{ old('min_unit', optional($package)->min_unit) }}" required type="number" name="min_unit" placeholder="Min unit">
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Max Unit: <span class="tx-danger">*</span></label>
                                <input class="form-control"  value="{{ old('max_unit', optional($package)->max_unit) }}" required type="number" name="max_unit" placeholder="Max Unit">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Price: <span class="tx-danger">*</span></label>
                                <input class="form-control"  value="{{ old('price', optional($package)->price) }}" required type="number" name="price" placeholder="price" step="any">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Status: <span class="tx-danger">*</span></label>
                                <select name="status" class="form-control">
                                    <option {{ $package->status ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ !$package->status ? 'selected' : '' }} value="0">Not Active</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Description: <span class="tx-danger">*</span></label>
                                <textarea name="description" class="form-control"></textarea>

                            </div>
                        </div>



                        <!-- col-8 -->
                        {{--                        <div class="col-lg-6">--}}
                        {{--                                @include('admin.partials.image-uploader',['field' => 'logo'])--}}

                        {{--                        </div><!-- col-8 -->--}}
                    </div><!-- row -->

                    <div class="form-layout-footer">
                        <button class="btn btn-primary" type="submit">Submit Form</button>
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
                        responsive: true,
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
                        responsive: true,
                        "ordering": true
                    });

                    // Select2
                    $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

                });
            </script>
       @endsection

@endsection
