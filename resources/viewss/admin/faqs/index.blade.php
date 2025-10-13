@extends('admin.layouts.admin-app')

@section('style')
    <link href="{{ asset('lib/datatables/jquery.dataTables.css')}}" rel="stylesheet">
@endsection

@section('content')
@include('sweetalert::alert')
<!-- ########## START: MAIN PANEL ########## -->
<div class="br-mainpanel">
    <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
            <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <span class="breadcrumb-item active"> Faqs Layouts</span>
        </nav>
    </div><!-- br-pageheader -->
    <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <h4 class="tx-gray-800 mg-b-5"> Faq</h4>
    </div>

    <div class="br-pagebody">
        <p>
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                Add Faq
            </button>
        </p>

        @include('notification')
        <div class="collapse" id="collapseExample">
        <div class="br-section-wrapper">
            <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Add Faq</h6>

            <form action="{{ route('admin.faqs.store') }}" method="POST">
                @csrf
                <div class="form-layout form-layout-1">
                    <div class="row mg-b-25">

                        <div class="col-md-12">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Title / Name: <span class="tx-danger">*</span></label>
                                <input class="form-control" required type="text" name="title" placeholder="Enter Name">
                            </div>
                        </div><!-- col-8 -->
                        <div class="col-md-12">
                            <div class="form-group mg-b-10-force">
                                <label class="form-control-label"> Details: <span class="tx-danger">*</span></label>
                                <textarea class="form-control" required  name="details"></textarea>
                            </div>
                        </div><!-- col-8 -->

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
                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10"> Faqs List</h6>

                <div class="table-wrapper table-responsive">
                    <table id="datatable1" class="table table-bordered table-condensed ">
                        <thead>
                        <tr>
                            <th class="wd-5p">S.No</th>
                            <th class="wd-15p">Title</th>
                            <th class="wd-70p">Details</th>
                            <td></td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($faqs as $faq)
                        <tr>
                            <td>{{ $faq->id }}</td>
                            <td>{{ $faq->title }}</td>
                            <td>{{ $faq->details }}</td>
                            <td class="text-center">
                                <form method="POST" action="{!! route('admin.faqs.destroy', $faq->id) !!}" accept-charset="UTF-8">
                                    <input name="_method" value="DELETE" type="hidden">
                                    {{ csrf_field() }}

                                    <div class="btn-group justify-center" role="group">
                                        <a href="{{ route('admin.faqs.edit', $faq->id ) }}" class="btn btn-primary" title="Edit Faq">
                                            <span class="fa fa-edit" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete Faq" onclick="return confirm(&quot;Click Ok to delete Record.&quot;)">
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
            <script src="{{ asset('lib/datatables/jquery.dataTables.js') }}"></script>
            <script src="{{ asset('lib/datatables-responsive/dataTables.responsive.js') }}"></script>
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
