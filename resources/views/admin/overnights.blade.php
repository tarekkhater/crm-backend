@extends('admin.layouts.admin-app')

@section('style')
    @include('admin.partials.dt-css')
@endsection

@section('content')
    <div class="br-mainpanel">
        <div class="br-pageheader pd-y-15 pd-l-20">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="breadcrumb-item" href="#">Overnight Commissions</a>
            </nav>
        </div><!-- br-pageheader -->

        <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
            <h4 class="tx-gray-800 mg-b-5"> Overnight Commissions</h4>
        </div>



        <div class="br-pagebody">

            <div class="br-section-wrapper">

                @include('notification')

                <div class="table-wrapper">
                    <table id="datatable1" class="table display table-bordered responsive nowrap">
                        <thead>
                        <tr>
                            <th class="wd-10p">S/N</th>
                            <th  class="wd-15p">User</th>
                            <th class="wd-45p">Traded Amt</th>
                            <th class="wd-15p">Commission (%) </th>
                            <th class="wd-15p">Fee&nbsp; </th>
                            <th class="wd-15p">Charged at&nbsp; </th>
                        </tr>
                        </thead>
                        <tbody>

                        @php
                            $count = 1;
                        @endphp
                        @foreach ($items as $item)
                            @if ($item->user)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td><a href="{{ route('admin.users.show',$item->user->first_name) }}">{{ $item->user->name }}</a> </td>
                                    <td>
                                        {{ amt($item->amount) }}
                                    </td>
                                    <td>
                                        {{ $item->fee }}%
                                    </td>
                                    <td>
                                        {{ amt($item->com) }}
                                    </td>
                                    <td>
                                        {{ $item->charged_at }}
                                    </td>
                                </tr>
                            @endif

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
