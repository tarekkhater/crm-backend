@extends('admin.layouts.admin-app')

@section('style')
    @include('admin.partials.dt-css')
@endsection

@section('content')
    <div class="br-mainpanel">
        <div class="br-pageheader pd-y-15 pd-l-20">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="breadcrumb-item" href="#">Investments</a>
                <span class="breadcrumb-item active">{{ $title }}</span>
            </nav>
        </div><!-- br-pageheader -->

        <div class="br-pagebody">

            <div class="br-section-wrapper">

                @include('notification')

                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10" style="padding-bottom: 50px">{{ $title }}
                <a href="{{ route('admin.investments') }}?status=0" class="float-right btn btn-xs btn-primary ">Pending Investments</a>
                <a href="{{ route('admin.investments') }}?status=1" class="float-right btn btn-xs btn-success mr-2">Approved Investments</a>
                </h6>

                <div class="table-wrapper">
                    <table id="datatable1" class="table display table-bordered responsive">
                        <thead>
                        <tr>
                            <th class="wd-5p">S/N</th>
                            <th class="wd-15p">User</th>
                            <th class="wd-15p">Amount</th>
                            <th class="wd-15p">Earned Profit</th>
                            <th class="wd-10p">Plan</th>
                            <th class="wd-10p">Status</th>
                            <th class="wd-10p">Start Date</th>
                            <th class="wd-10p">End Date</th>
                            <th class="wd-10p">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @php
                            $count = 1;
                        @endphp
                        @foreach ($investments as $item)
                            <tr>
                                <td>{{ $count++ }}</td>
                                <td class="text-capitalize"><a href="{{ route('admin.users.show', $item->user ? $item->user->first_name : 1) }}">{{ optional($item->user)->name }}</a></td>
                                <td>{{ amt($item->amount) }} <br/>
                                  ({{ optional($item->plan)->percent_profit }}%)
                                </td>
                                <td>{{ $item->profit  }}</td>

                                <td>
                                    {{ optional($item->plan)->name }}
                                 </td>
                                <td>
                                    @if ($item->status > 0)
                                        <p class="badge btn-success">{{ $item->status_text }}</p>
                                    @else
                                        <p class="badge btn-danger">{{ $item->status_text }}</p>
                                    @endif
                                </td>
                                <td>{{ $item->start_date }}</td>
                                <td>{{ $item->end_date }}</td>

                                @if ($item->status < 1)
                                    <td>
                                    <a href="{{ route('admin.investment.approve',$item->id) }}" class="btn-success btn-sm btn mt-1">Approve</a>
                                        <button data-toggle="modal" data-target="#editUser_{{ $item->id }}"  class="btn-warning btn-sm btn mt-1">Edit</button>
                                    </td>
                                @endif

                                @if ($item->status == 1)
                                <td>
                                    <button data-toggle="modal" data-target="#editUser_{{ $item->id }}"  class="btn-warning btn-sm btn mt-1">Edit</button>
                                    <a href="{{ route('admin.investment.complete',$item->id) }}" class="btn-primary btn-sm btn mt-1">Complete</a>
                                </td>
                                @endif
                                @if ($item->status == 2)
                                <td>
                                    <button disabled class="btn btn-sm btn-primary">Completed</button>
                                </td>
                                    @endif

                            </tr>




                            <div id="modaldemo1" class="modal fade">
                                <div class="modal-dialog modal-dialog-vertical-center" role="document">
                                    <div class="modal-content bd-0 tx-14">
                                        <div class="modal-header pd-y-20 pd-x-25">
                                            <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Approve Payment</h6>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.deposit.approve') }}" method="POST">
                                            @csrf

                                            <div class="modal-body pd-25">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label">Amount In USD : <span class="tx-danger">*</span></label>
                                                <input class="form-control" required type="number" step="any" name="amount" placeholder="opening price">
                                                <input type="hidden" name="user_id" value="{{ $item->user_id }}">
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">Submit</button>
                                            <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Close</button>
                                        </div>
                                        </form>

                                    </div>
                                </div><!-- modal-dialog -->
                            </div><!-- modal -->

                            <div id="editUser_{{ $item->id }}" class="modal fade">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content bd-0 tx-14">
                                        <div class="modal-header pd-y-20 pd-x-25">
                                            <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Edit Investment plan</h6>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form class="" action="{{ route('admin.investment.update') }}" method="POST">
                                            @csrf

                                            <div class="modal-body row pd-25">

                                                <div class="form-group col-12 mg-b-10-force">
                                                    <label class="form-control-label"> Plan : <span class="tx-danger">*</span></label>
                                                    <select name="plan_id" class="form-control">
                                                        @foreach($plans as $it)
                                                            <option {{ $it->id == $item->plan_id ? 'selected' : '' }} value="{{ $it->id }}">{{ $it->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group col-12 mg-b-10-force">
                                                <label class="form-control-label">Amount Invested : <span class="tx-danger">*</span></label>
                                                <input class="form-control" value="{{ $item->amount }}" required type="number" step="any" name="amount" placeholder="amount">
                                                <input type="hidden" name="user_id" value="{{ $item->user_id }}">
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                            </div>

                                                <div class="form-group col-12 mg-b-10-force">
                                                    <label class="form-control-label"> Profit : <span class="tx-danger">*</span></label>
                                                    <input class="form-control" required type="number" step="any" value="{{ $item->profit }}" name="profit" placeholder="profit">
                                                </div>

                                                <div class="form-group col-12 mg-b-10-force">
                                                    <label class="form-control-label"> Start Date ({{ $item->start_date }}): <span class="tx-danger">*</span></label>
                                                    <input class="form-control" required type="date" value="{{ $item->start_date }}" name="start_date" placeholder="Start Date">
                                                </div>

                                                <div class="form-group col-12 mg-b-10-force">
                                                    <label class="form-control-label"> End Date ({{ $item->end_date }}): <span class="tx-danger">*</span></label>
                                                    <input class="form-control" required type="date" value="{{ $item->end_date }}" name="end_date" placeholder="End Date">
                                                </div>
                                            </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium">Submit</button>
                                            <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Close</button>
                                        </div>
                                        </form>

                                    </div>
                                </div><!-- modal-dialog -->
                            </div><!-- modal -->

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
