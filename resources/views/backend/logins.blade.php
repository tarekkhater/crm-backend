

@extends('backend.layouts.master')

@section('content')

    <div class="content-body">
        <div class="container">
            <div class="row">
                @include('partials.menu')

                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Login Histories</h4>
                        </div>
                        <div class="card-body">
                            <div class="transaction-table">
                                <div class="table-responsive">
                                    <table style="width: 100%;" id="example"
                                           class="table table-hover table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th  width="30%" class="text-center">Ip Address</th>
                                            <th width="30%" class="text-center">Device Details</th>
                                            <th>Login DateTime </th>
                                            <th>Logout DateTime&nbsp; </th>


                                        </tr>
                                        </thead>
                                        <tbody>


                                        @foreach($details as $item)
                                            <tr>
                                                <td>{{ $item->ip_address }}</td>
                                                <td>{{ $item->user_agent }}</td>
                                                <td>{{ $item->login_at }}</td>
                                                <td>{{ $item->logout_at }}</td>
                                                {{--<td>{{ $item-> }}</td>--}}

                                                {{--<td>{{ $item-> }}</td>--}}
                                                {{--<td>{{ $item-> }}</td>--}}
                                                {{--<td>{{ $item->}}</td>--}}

                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection

@section('js')

@endsection
