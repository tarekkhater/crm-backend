@extends('admin.layouts.admin-app')

@section('css')
    @include('admin.partials.dt-css')
@endsection

@section('content')
    <div class="br-mainpanel">
        <div class="br-pageheader pd-y-15 pd-l-20">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="breadcrumb-item" href="#">Users</a>
                <span class="breadcrumb-item active">{{ $title }} Newsletter</span>
            </nav>
        </div><!-- br-pageheader -->

        <div class="br-pagebody">

            <div class="br-section-wrapper">

                @include('notification')

                <form action="{{ route('admin.newsletter.submit') }}" method="POST">
                    @csrf

                    <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10" style="padding-bottom: 50px">{{ $title }} Newsletter

                    <button class="btn btn-success" type="" style="float: right">Send to selected users</button>
                </h6>

                    <input type="hidden" name="title" value="{{ $title }}">
                <div class="table-wrapper">
                    <table id="datatable1" class="table display table-bordered responsive nowrap">
                        <thead>
                        <tr>
                            <th class="wd-5p">S/N</th>
                            <th class="wd-5p"></th>
                            <th class="wd-15p">Username</th>
                            <th class="wd-10p">Photo</th>
                            <th class="wd-20p">Function Area</th>
                            @if ($title == 'Jobseeker')
                                <th class="wd-25p">Industry</th>
                            @else
                                <th class="wd-25p">Company</th>
                            @endif
                            <th class="wd-20p">Date Posted</th>
{{--                            <th class="wd-15p">Phone</th>--}}

{{--                            <th class="wd-10p">Action</th>--}}

                        </tr>
                        </thead>
                        <tbody>

                        @php
                            $count = 1;
                        @endphp
                        @foreach ($users as $user)

                            <tr>
                                <td>{{ $count++ }}</td>
                                <td><input type="checkbox" name="users[]" value="{{ $user->id }}"></td>
                                <td>{{ $user->first_name }}</td>
                                <td><img height="40" src="{{ $user->avatar }}"></td>
                                <td>{{ $user->function_area }}</td>
                                @if ($title == 'Jobseeker')
                                <td>{{ $user->industry }}</td>
                                @else
                                    <td>{{ optional(\App\Models\Company::whereUserId($user->id)->first())->name }}</td>
                                    @endif
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
{{--                                <td>{{ $user->phone }}</td>--}}


                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div><!-- table-wrapper -->
                </form>
            </div>
        </div>
        @endsection

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
