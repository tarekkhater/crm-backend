@extends('admin.layouts.admin-app')

@section('style')
    @include('admin.partials.dt-css')
@endsection

@section('content')
    <div class="br-mainpanel">
        <div class="br-pageheader pd-y-15 pd-l-20">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="breadcrumb-item" href="#">Login Logins</a>
            </nav>
        </div><!-- br-pageheader -->

        <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
            <h4 class="tx-gray-800 mg-b-5"> {{ $user->name  }} Login Logins

                <div class="dropdown show">
                    <a class="btn btn-success dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Settings
                    </a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="{{ route('admin.activity.notifications.settings') }}">Activity Notification Settings</a>
                        <hr />
                        <a class="dropdown-item" href="{{ route('admin.settings.notifications') }}">Activity Notification Email</a>
                    </div>
                </div>

            </h4>


        </div>

        <div class="br-pagebody">

            <div class="br-section-wrapper">

                <div class="table-wrapper">
                    <table id="datatable1" class="table display table-bordered responsive nowrap">
                        <thead>
                        <tr>
                            <th class="wd-10p">S/N</th>
                            <th  class="wd-15p">Ip Address</th>
                            <th class="wd-45p">Device Details</th>
                            <th class="wd-15p">Login DateTime </th>
                            <th class="wd-15p">Logout DateTime&nbsp; </th>
                        </tr>
                        </thead>
                        <tbody>

                        @php
                            $count = 1;
                        @endphp
                        @foreach ($details as $item)
                            <tr>
                                <td>{{ $count++ }}</td>
                                <td>{{ $item->ip_address }}</td>
                                <td>{{ $item->user_agent }}</td>
                                <td>{{ $item->login_at }}</td>
                                <td>{{ $item->logout_at }}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div><!-- table-wrapper -->
            </div>
        </div>
        @endsection

        @section('js')
            <script>
                function destroyUser(e) {
                    e.preventDefault();

                    if (confirm('There is no reversal to this!\nAre you sure you want to remove this item entirely from the system? '))
                        document.getElementById('delete-customer-form').submit()
                    else
                        return false;
                }
            </script>

    @include('admin.partials.dt-js')
@endsection
