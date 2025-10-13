@extends('admin.layouts.admin-app')

@section('css')
    @include('admin.partials.dt-css')
@endsection

@section('content')
    <div class="br-mainpanel">
        <div class="br-pageheader pd-y-15 pd-l-20">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="breadcrumb-item" href="#">Packages</a>
                <span class="breadcrumb-item active">{{ $title }}</span>
            </nav>
        </div><!-- br-pageheader -->

        <div class="br-pagebody">

            <div class="br-section-wrapper">

                @include('notification')

                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10" style="padding-bottom: 50px">{{ $title }} </h6>

                <div class="table-wrapper">
                    <table id="datatable1" class="table display responsive nowrap">
                        <thead>
                        <tr>
                            <th class="wd-5p">S/N</th>
                            <th class="wd-15p">Seeker Id</th>
                            <th class="wd-15p">Package</th>
                            <th class="wd-30p">Paid Amount</th>
                            <th class="wd-15p">Purchased Date</th>
                            <th class="wd-10p">Expiry Date</th>
                        </tr>
                        </thead>
                        <tbody>

                        @php
                            $count = 1;
                        @endphp
                        @foreach ($packages as $item)
                            <tr>
                                <td>{{ $count++ }}</td>
                                <td>{{ $item->first_name }}</td>
                                <td><img height="40" src="{{ $item->avatar }}"></td>
                                <td>{{ optional(\App\Models\Company::whereUserId($item->id)->first())->name }}</td>
                                <td>{{ $item->job_plan->product->name }}</td>
{{--                                <td>--}}
{{--                                    <a href="{{ route('admin.users.edit', $item) }}" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Edit User"><em class="fa fa-edit"></em></a>--}}
{{--                                    <a href="{{ route('admin.users.destroy', $item) }}" onclick="destroyUser(event)" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Delete"><em class="fa fa-trash"></em>--}}
{{--                                        <form id="delete-customer-form" action="{{ route('admin.users.destroy', $item) }}" method="POST" class="d-none">--}}
{{--                                            @csrf--}}
{{--                                            @method('DELETE')--}}
{{--                                        </form>--}}
{{--                                    </a>--}}
{{--                                </td>--}}

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

                    if (confirm('There is no reversal to this!\nAre you sure you want to remove this user entirely from the system? '))
                        document.getElementById('delete-customer-form').submit()
                    else
                        return false;
                }
            </script>

    @include('admin.partials.dt-js')
@endsection
