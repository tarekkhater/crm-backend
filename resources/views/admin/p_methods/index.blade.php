@extends('admin.layouts.admin-app')

@section('style')
    <link href="{{ asset('lib/datatables/jquery.dataTables.css')}}" rel="stylesheet">
@endsection

@section('content')
<!-- ########## START: MAIN PANEL ########## -->
<div class="br-mainpanel">
    <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
            <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <span class="breadcrumb-item active">Crypto Payment Methods</span>
        </nav>
    </div><!-- br-pageheader -->
    <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <h4 class="tx-gray-800 mg-b-5"> Crypto Payment Methods</h4>
    </div>

    <div class="br-pagebody">
        <p>
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                Add Crypto Payment Methods
            </button>
        </p>

        @include('notification')
        <div class="collapse" id="collapseExample">
        <div class="br-section-wrapper">
            <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Add Crypto Payment Methods</h6>

            <form action="{{ route('admin.p_methods.store') }}" method="POST">
                @csrf
                <div class="form-layout form-layout-1">
                    <div class="row mg-b-25">

                        <div class="col-md-6">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Title / Name: <span class="tx-danger">*</span></label>
                                <input class="form-control" required type="text" name="name" placeholder="e.g Bitcoin">
                            </div>
                        </div><!-- col-8 -->

                        <div class="col-md-6">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Symbol: <span class="tx-danger">*</span></label>
                                <input class="form-control" required type="text" name="symbol" placeholder="e.g BTC">
                            </div>
                        </div><!-- col-8 -->

                        <div class="col-md-6">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Wallet ID : <span class="tx-danger">*</span></label>
                                <input class="form-control" required type="text" name="wallet" placeholder="Enter Wallet ID">
                            </div>
                        </div><!-- col-8 -->

                        <div class="col-md-6">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Barcode :<span class="tx-danger">*</span></label>
                                @include('admin.partials.image-upload',['field' => 'barcode','id' => 'image'])

                            </div>
                        </div><!-- col-8 -->



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
    </div>

    <div class="br-pagebody">
            <div class="br-section-wrapper">
                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10"> Crypto Payment Methods List</h6>

                <div class="table-wrapper">
                    <table id="datatable1" class="table table-bordered table-condensed display responsive nowrap">
                        <thead>
                        <tr>
                            <th class="wd-5p">S.No</th>
                            <th class="wd-20p">Title</th>
                            <th class="wd-60p">Wallet ID</th>
                            <th class="wd-15p">Barcode</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($p_methods as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->symbol }}
                            <br /> {{ $item->name }}
                            </td>
                            <td>{{ $item->wallet }}</td>
                            <td><img src="{{ $item->barcode }}" height="40" /> </td>
                            <td class="text-center">
                                <form method="POST" action="{!! route('admin.p_methods.destroy', $item->id) !!}" accept-charset="UTF-8">
                                    <input name="_method" value="DELETE" type="hidden">
                                    {{ csrf_field() }}

                                    <div class="btn-group justify-center" role="group">
{{--                                        <a href="{{ route('admin.p_methods.edit', $item->id ) }}" class="btn btn-primary" title="Edit Payment method">--}}
{{--                                            <span class="fa fa-edit" aria-hidden="true"></span>--}}
{{--                                        </a>--}}

                                        <button type="submit" class="btn btn-danger" title="Delete Method" onclick="return confirm(&quot;Click Ok to delete Record.&quot;)">
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
       @endsection

@endsection
