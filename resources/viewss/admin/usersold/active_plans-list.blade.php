@extends('admin.layouts.admin-app')

@section('style')
    @include('admin.partials.dt-css')
@endsection

@section('content')
    <div class="br-mainpanel">
        <div class="br-pageheader pd-y-15 pd-l-20">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="breadcrumb-item" href="#">Users</a>
                <span class="breadcrumb-item active">Active plans</span>
            </nav>
        </div><!-- br-pageheader -->

        <div class="br-pagebody">

            <div class="br-section-wrapper">

                @include('notification')

                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10" style="padding-bottom: 50px">Active Plans User List</h6>

                <div class="table-wrapper">
                        <table id="datatable1" class="table table-bordered table-condensed display responsive nowrap">
                        <thead>
                        <tr>
                            <th class="wd-5p">S/N</th>
                            <th class="wd-10p">User Email</th>
                            <th class="wd-10p">Photo</th>
                            <th class="wd-10p">Balance</th>

{{--                            <th class="wd-15p">Phone</th>--}}
                            <th class="wd-10p">Trades</th>

                        </tr>
                        </thead>
                        <tbody>

                        @php
                            $count = 1;
                        @endphp
                        @foreach ($users as $item)
                            <tr>
                                <td>{{ $count++ }}</td>
                                <td><a href="{{ route('admin.users.show',$item->first_name) }}"> {{ $item->email }}</a></td>
                                <td><img height="40" src="{{ $item->avatar }}"></td>
                                <td>{{ $item->balance() }}</td>

                                <td>
                                    <a href="{{ route('admin.trades.index') }}?user={{$item->id}}" >{{ \App\Models\Trade::whereUserId($item->id)->count() }}</a>
{{--                                    <a href="{{ route('admin.users.destroy', $user) }}" onclick="destroyUser(event)" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Delete"><em class="fa fa-trash"></em>--}}
{{--                                        <form id="delete-customer-form" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-none">--}}
{{--                                            @csrf--}}
{{--                                            @method('DELETE')--}}
{{--                                        </form>--}}
{{--                                    </a>--}}
                                </td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div><!-- table-wrapper -->
            </div>
        </div>


        @section('js')
            <script>
                function destroyUser(e) {
                    e.preventDefault();

                    if (confirm('There is no reversal to this!\nAre you sure you want to remove this user entirely from the system? '))
                        document.getElementById('delete-customer-form').submit()
                    else
                        return false;
                }
            </script>

    @include('admin.partials.dt-js')
@endsection
@endsection
