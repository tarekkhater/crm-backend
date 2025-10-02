@extends('new_backend.layouts.index')


@section('content')

    <div class="app-content main-content mt-0">
        <div class="side-app">

            <!-- CONTAINER -->
            <div class="main-container container-fluid">


                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Dashboard</h1>
                    </div>
                    <div class="ms-auto pageheader-btn">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Overnight Commissions</li>

                        </ol>
                    </div>
                </div>
                <!-- PAGE-HEADER END -->

           <div class="row table-reference">
               <table class="table datatable table-bordered table-striped">
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


           </div>


            </div>
        </div>
    </div>

@endsection
@section('js')





@endsection
