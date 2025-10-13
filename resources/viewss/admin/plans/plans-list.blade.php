@extends('admin.layouts.admin-app')

@section('style')
    <link href="{{ asset('lib/highlightjs/github.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/datatables/jquery.dataTables.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <!-- ########## START: MAIN PANEL ########## -->
    <div class="br-mainpanel">
        <div class="br-pageheader pd-y-15 pd-l-20">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="breadcrumb-item active"> Plans Layouts</span>
            </nav>
        </div><!-- br-pageheader -->
        <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
            <h4 class="tx-gray-800 mg-b-5"> Plans</h4>
        </div>

        <div class="br-pagebody">
            <p>
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample"
                    aria-expanded="false" aria-controls="collapseExample">
                    Add Plans
                </button>
            </p>
            @include('notification')
            <div class="collapse" id="collapseExample">
                <div class="br-section-wrapper">
                    <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Add Plans</h6>

                    <form action="{{ route('admin.plans.store') }}" method="POST" accept-charset="utf-8"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-layout form-layout-1">
                            <div class="row mg-b-25">

                                <div class="col-md-4">
                                    <div class="form-group mg-b-10-force">
                                        <label class="form-control-label"> Title / Name: <span
                                                class="tx-danger">*</span></label>
                                        <input class="form-control" required type="text" name="name"
                                            value="{{ old('name') }}" placeholder="Enter Name">
                                    </div>
                                </div><!-- col-8 -->

                                <div class="col-md-3">
                                    <div class="form-group mg-b-10-force">
                                        <label class="form-control-label"> Amount : <span class="tx-danger">*</span></label>
                                        <input class="form-control" step="any" required type="number" name="amount"
                                            value="{{ old('amount') }}" placeholder="Amount">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mg-b-10-force">
                                        <label class="form-control-label"> Plan Color : <span
                                                class="tx-danger">*</span></label>
                                        <input class="form-control" step="any" required type="color" name="color"
                                            value="{{ old('color') }}" placeholder="color">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mg-b-10-force">
                                        <label class="form-control-label"> Plan Icon : <span
                                                class="tx-danger">*</span></label>
                                        @include('admin.partials.image-upload', [
                                            'field' => 'icon',
                                            'id' => 'icon',
                                        ])
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group mg-b-10-force">
                                        <label class="form-control-label"> Status: <span class="tx-danger">*</span></label>
                                        <select name="status" class="form-control">
                                            <option value="1" @if (old('status') == '1') selected @endif>Active
                                            </option>
                                            <option value="0" @if (old('status') == '0') selected @endif>Not
                                                Active</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mg-b-10-force planFeatures">
                                        <label class="form-control-label"> Features: <span
                                                class="tx-danger">*</span></label>
                                        <div>
                                            <input class="form-control mt-2" type="text" name="features[]" required>
                                            <a href="#" class="delete text-danger">Delete</a>
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-default add_form_field">Add Feature &nbsp;
                                        <span style="font-size:16px; font-weight:bold;">+ </span>
                                    </button>
                                </div><!-- col-8 -->


                                <!-- col-8 -->
                                {{--                        <div class="col-lg-6"> --}}
                                {{--                                @include('admin.partials.image-uploader',['field' => 'logo']) --}}

                                {{--                        </div><!-- col-8 --> --}}
                            </div><!-- row -->

                            <div class="form-layout-footer">
                                <button class="btn btn-primary" type="submit">Create Plan</button>
                            </div><!-- form-layout-footer -->
                        </div><!-- form-layout -->
                    </form>
                </div><!-- br-section-wrapper -->
            </div>
        </div>

        <div class="br-pagebody">
            <div class="br-section-wrapper">
                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10"> plans List</h6>

                <div class="table-wrapper">
                    <table id="datatable1" class="table table-bordered table-condensed display responsive nowrap">
                        <thead>
                            <tr>
                                <th class="wd-15p">S.No</th>
                                <th class="wd-15p">Title</th>
                                <th class="wd-15p">Amount</th>
                                <th class="wd-20p"> Color</th>
                                <th class="wd-20p"> Icon</th>
                                <th class="wd-20p"> Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($plans as $plan)
                                <tr>
                                    <td>{{ $plan->id }}</td>
                                    <td>{{ $plan->name }}</td>
                                    <td>{{ $plan->amount }}</td>
                                    <td>
                                        <table>
                                            <tr style="background-color: #f9f9f9">
                                                <td style="border: none;">{{ $plan->color }}</td>
                                                <td style="border: none;">
                                                    <div
                                                        style="background-color: {{ $plan->color }}; width: 40px; height: 20px;">
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                    <td><img src="{{ $plan->icon }}" style="width: 50px; height: 20px" /></td>
                                    <td>{{ $plan->status ? 'Active' : 'Not Active' }}</td>
                                    <td class="text-center">
                                        <form method="POST" action="{!! route('admin.plans.destroy', $plan->id) !!}" accept-charset="UTF-8">
                                            <input name="_method" value="DELETE" type="hidden">
                                            {{ csrf_field() }}

                                            <div class="btn-group justify-center" role="group">
                                                <a href="{{ route('admin.plans.edit', $plan->id) }}"
                                                    class="btn btn-primary" title="Edit Job">
                                                    <span class="fa fa-edit" aria-hidden="true"></span>
                                                </a>

                                                <button type="submit" class="btn btn-danger" title="Delete Job"
                                                    onclick="return confirm(&quot;Click Ok to delete Record.&quot;)">
                                                    <span class="fa fa-trash" aria-hidden="true"></span>
                                                </button>
                                            </div>

                                        </form>

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div><!-- table-wrapper -->

            </div><!-- br-pagebody -->
        </div><!-- br-mainpanel -->
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
            $(function() {
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
                $('.dataTables_length select').select2({
                    minimumResultsForSearch: Infinity
                });

            });
        </script>

        <script>
            $(document).ready(function() {
                var max_fields = 9;
                var wrapper = $(".planFeatures");
                var add_button = $(".add_form_field");

                var x = 1;
                $(add_button).click(function(e) {
                    e.preventDefault();
                    if (x < max_fields) {
                        x++;
                        $(wrapper).append(
                            '<div><input class="form-control mt-2" type="text" name="features[]"/ required><a href="#" class="delete text-danger">Delete</a></div>'
                        ); //add input box
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
