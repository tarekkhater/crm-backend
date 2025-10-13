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
                <span class="breadcrumb-item active"> Permission Layouts</span>
            </nav>
        </div><!-- br-pageheader -->
        <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
            <h4 class="tx-gray-800 mg-b-5"> Permission</h4>
        </div>

        <div class="br-pagebody">
            <p>
{{--                @if(check_permission('create-roles'))--}}
{{--                    <a class="btn btn-primary btn-sm" href="{{ route('admin.roles.create') }}"> <i class="fa fa-plus"></i> Add</a>--}}
{{--                @endif--}}
            </p>

            @include('notification')

        </div>

        <div class="br-pagebody">
            <div class="br-section-wrapper">
                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10"> Roles List</h6>

                <div class="table-wrapper table-responsive">
                    <form method="post" action="{{route('admin.permissions.store')}}">
                        @csrf
                    <table class="table mg-b-0 text-md-nowrap table-hover text-center " id="datatable1">
                        <thead class="text-center">
                        <tr>
                            <th class="text-center">#</th>

                            <th class="text-center">Description</th>
                            <th class="text-center">Name</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($rows as $key => $row)
                            <tr>
                                <td>{{ ++$key }}</td>

                                <td><input class="form-control" name="display_name[{{$row['id']}}]" value="{{$row['display_name']}}"></td>
                                <td>

                                  <input class="form-control" name="permission[{{$row['id']}}]" value="{{$row['description']}}">


                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                        <button class="btn btn-primary">Save</button>
                    </form>
                </div><!-- table-wrapper -->

            </div><!-- br-pagebody -->
        </div><!-- br-mainpanel -->
        <!-- ########## END: MAIN PANEL ########## -->

        @section('js')
            <script src="{{ asset('lib/datatables/jquery.dataTables.js') }}"></script>
            <script src="{{ asset('lib/datatables-responsive/dataTables.responsive.js') }}"></script>
            <script>
                // $(function(){
                //     'use strict';
                //
                //     $('#datatable1').DataTable({
                //
                //         language: {
                //             searchPlaceholder: 'Search...',
                //             sSearch: '',
                //             lengthMenu: '_MENU_ items/page',
                //         },
                //         "ordering": true
                //     });
                //
                //     $('#datatable2').DataTable({
                //         bLengthChange: false,
                //         searching: false,
                //         "ordering": true
                //
                //     });
                //
                //     // Select2
                //     $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });
                //
                // });
            </script>
@endsection

@endsection
