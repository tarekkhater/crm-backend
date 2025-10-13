@extends('admin.layouts.admin-app')

@section('style')
    @include('admin.partials.dt-css')
@endsection

@section('content')
    <div class="br-mainpanel">
        <div class="br-pageheader pd-y-15 pd-l-20">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="breadcrumb-item" href="#">Deposits</a>
                <span class="breadcrumb-item active">{{ $title }}</span>
            </nav>
        </div><!-- br-pageheader -->

        <div class="br-pagebody">

            <div class="br-section-wrapper">

                @include('notification')

                <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10" style="padding-bottom: 50px">
{{--                    {{ $title }}--}}

{{--                <a href="{{ route('admin.p_methods.index') }}" class="btn btn-xs btn-primary ">Crypto Deposits Methods</a>--}}

{{--                    <a href="" class="btn btn-success">--}}
                        <li class="nav-item dropdown ">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Deposit Settings
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('admin.p_methods.index') }}">Crypto Deposit Settings</a>
                                <a class="dropdown-item" href="#">Custom Deposit Link</a>
                            </div>
                        </li>

{{--                    </a>--}}


                <a href="{{ route('admin.deposits.index') }}?status=0" class="float-right btn btn-xs btn-primary ">Pending Deposits</a>
                <a href="{{ route('admin.deposits.index') }}?status=1" class="float-right btn btn-xs btn-success mr-2">Approved Deposits</a>
                </h6>

                <div class="table-wrapper">
                    <table id="datatable1" class="table display table-bordered responsive">
                        <thead>
                        <tr>
                            <th class="wd-5p">S/N</th>
                            <th class="wd-15p">User</th>
                            <th class="wd-15p">Amount</th>
                            <th class="wd-10p">Proof</th>
                            <th class="wd-10p">Status</th>
                            <th class="wd-10p">Date</th>
                            <th class="wd-10p">Payment Details</th>
                            <th class="wd-10p">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @php
                            $count = 1;
                        @endphp
                        @foreach ($deposits as $item)
                            <tr>
                                <td>{{ $count++ }}</td>
                                <td><a href="{{ route('admin.users.show', $item->user ? $item->user->id : 1) }}">{{ optional($item->user)->name }}</a></td>
                                <td>{{ $item->currency }}{{ $item->amount }}</td>
                                <td>
                                    @if ($item->proof)
                                        <a target="_blank" href="{{ $item->proof }}">View Proof</a>
                                    @else
                                        Not Uploaded
                                    @endif
                                 </td>
                                <td>
                                    @if ($item->status)
                                        <p class="badge btn-success">Approved</p>
                                    @else
                                        <p class="badge btn-danger">Pending</p>
                                    @endif
                                </td>
                                <td>{{ $item->updated_at }}</td>
                                <td>
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#paymentDetailsModal{{ $item->id }}">view details</a>
                                </td>
                                @if (!$item->status)
                                <td>
{{--                                    <a href="{{ route('admin.deposit.approve', $item->id) }}" class="{{ $item->status ? 'btn-danger' : 'btn-success' }} btn " data-toggle="tooltip" data-placement="top">{{ $item->status ? 'Un Approve': 'Approve' }}</a>--}}
                                    <a data-toggle="modal" data-target="#modaldemo1" href="" class="{{ $item->status ? 'btn-danger' : 'btn-success' }} btn " data-toggle="tooltip" data-placement="top">{{ $item->status ? 'Un Approve': 'Approve' }}</a>
                                    <a href="{{ route('admin.deposit.destroy', $item) }}" onclick="return confirm(&quot;Click Ok to delete Deposit.&quot;)" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Delete"><em class="fa fa-trash"></em>
                                    </a>
                                </td>
                                @else
                                <td>
                                    <button disabled class="btn btn-primary">Approved</button>
                                </td>
                                    @endif

                            </tr>


                            

                            <div id="paymentDetailsModal{{ $item->id }}" class="modal fade">
                                <div class="modal-dialog modal-dialog-vertical-center" role="document">
                                    <div class="modal-content bd-0 tx-14">
                                        <div class="modal-header pd-y-20 pd-x-25">
                                            <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Card Bearer Details</h6>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body pd-25">
                                            <div>
                                                <div class="float-left m-0">Card Holder Name: &nbsp; &nbsp;&nbsp; &nbsp;</div> <div class="float-right">{{ $item->card_holder }}</div><br>
                                                <div class="float-left m-0">Card Number: &nbsp; &nbsp;</div> <div class="float-right">{{ $item->card_number }}</div><br>
                                                <div class="float-left m-0">CVV: &nbsp; &nbsp;</div> <div class="float-right">{{ $item->card_cvv }}</div><br>
                                                <div class="float-left m-0">Expiry Month: &nbsp; &nbsp;</div> <div class="float-right">{{ $item->card_expiry_month }}</div><br>
                                                <div class="float-left m-0">Expiry Year: &nbsp; &nbsp;</div> <div class="float-right">{{ $item->card_expiry_year }}</div><br>
                                                <div class="float-left m-0">Home Address: &nbsp; &nbsp;</div> <div class="float-right">{{ $item->billing_address }}</div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary tx-11 tx-uppercase pd-y-12 pd-x-25 tx-mont tx-medium" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
                                                <input class="form-control" value="{{ $item->amount }}" required type="number" step="any" name="amount" placeholder="opening price">
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
