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
            <span class="breadcrumb-item active"> Trading Hours Layouts</span>
        </nav>
    </div><!-- br-pageheader -->
    <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <h4 class="tx-gray-800 mg-b-5"> Trading Hours</h4>
    </div>



    <div class="br-pagebody">
            <div class="br-section-wrapper">
                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10"> Trading Hours List</h6>

                <div class="table-wrapper table-responsive">
                    <table class="table mg-b-0 text-md-nowrap table-hover " id="datatable1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>ex_sym</th>
                                <th>com</th>
                                <th>sym</th>
                                <th>rate</th>
                                <th>base</th>
                                <th>type</th>
                                <th>open_at</th>
                                <th>close_at</th>
                                <th>days</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($currency_pairs as $key => $currency_pair)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $currency_pair->name }}</td>
                                    <td>{{ $currency_pair->ex_sym }}</td>
                                    <td>{{ $currency_pair->com }}</td>
                                    <td>{{ $currency_pair->sym }}</td>
                                    <td>{{ $currency_pair->rate }}</td>
                                    <td>{{ $currency_pair->base }}</td>
                                    <td>{{ $currency_pair->type }}</td>
                                    <td>{{ $currency_pair->open_at }}</td>
                                    <td>{{ $currency_pair->close_at }}</td>
                                    <td>{{ $currency_pair->days }}</td>
                                    <td>

                                            <a class="btn btn-success btn-sm"
                                                href="{{ route('admin.trading_hours.show', $currency_pair->id) }}">Show</a>


                                            <a class="btn btn-primary btn-sm"
                                                href="{{ route('admin.trading_hours.edit', $currency_pair->id) }}">Edit</a>


                                                <form action="{{route('admin.trading_hours.destroy',$currency_pair->id)}}" method="DELETE">
                                                   <button class="btn btn-danger btn-sm">Delete</button>
                                                </form>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $currency_pairs->links() }}
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
                        "ordering": true,
                        "paging": false,
                        "bPaginate":false,

                    });

                    // Select2
                    $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

                });
            </script>
       @endsection

@endsection
