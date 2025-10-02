@extends('admin.layouts.admin-app')

@section('style')
@include('admin.partials.dt-css')
{{-- <link rel="stylesheet" href="{{ asset('css/custom.css') }}"> --}}
@endsection

@section('content')
<div class="br-mainpanel">
    <div class="br-pageheader pd-y-15 pd-l-20">
        <nav class="breadcrumb pd-0 mg-0 tx-12">
            <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="breadcrumb-item" href="#">Users</a>
            <span class="breadcrumb-item active">{{ $title }}</span>
        </nav>
    </div><!-- br-pageheader -->

    <div class="br-pagebody">

        <div class="br-section-wrapper">

            @include('notification')
            <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10" style="padding-bottom: 50px">{{ $title }}</h6>
            <div class="py-4 my-3 ">
                <form action="{{ route('admin.assignUsersSave') }}" method="POST">
                @csrf
                    <div class="form-group">
                        <input type="text" value="{{ $id }}" hidden name="managerID">
                        <select class="selectpicker" name="id[]"  multiple data-live-search="true" title="Search Users" style="outline: none; ">
                            @foreach ($unmanagedUsers as $unmanagedUser)
                                @if($unmanagedUser->hasRole(['superadmin','admin','manager'])){

                                }@else{
                                    <option  value="{{ $unmanagedUser->id }}">{{ $unmanagedUser->id }} . {{ $unmanagedUser->first_name }} </option>
                                }
                                @endif
                            @endforeach
                        </select>
                        <button type="submit" role="button" class="btn btn-success ">Assign</button>
                    </div>
                </form>
            </div>

            <div class="table-wrapper">
                <p class="lead">Assigned Users</p>
                <table id="datatable1"  class="table table-bordered display responsive nowrap">
                    <thead>
                        <tr>
                            <th>ID </th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Currency</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($managedUsers as $managedUser)
                            <tr>
                                <td>{{ $managedUser->id }}</td>
                                <td>{{ $managedUser->first_name }}</td>
                                <td>{{ $managedUser->email }}</td>
                                <td>{{ $managedUser->phone }}</td>
                                <td>{{ $managedUser->cur }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>

    </script>

    @include('admin.partials.dt-js')
@endsection
