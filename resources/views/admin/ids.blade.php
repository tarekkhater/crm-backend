@extends('admin.layouts.admin-app')

@section('style')
    @include('admin.partials.dt-css')
@endsection

@section('content')
    <div class="br-mainpanel">
        <div class="br-pageheader pd-y-15 pd-l-20">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="breadcrumb-item" href="#">Identities</a>
            </nav>
        </div><!-- br-pageheader -->

        <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
            <h4 class="tx-gray-800 mg-b-5"> Uploaded Identity</h4>
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
                            <th class="wd-15p">Front ID</th>
                            <th class="wd-15p">Back ID</th>
                            <th class="wd-15p">Credit Card Front</th>
                            <th class="wd-15p">Credit Card Back</th>
                            <th class="wd-15p">Proof Of Address</th>
                            <th class="wd-15p">Status&nbsp; </th>
                        </tr>
                        </thead>
                        <tbody>

                        @php
                            $count = 1;
                        @endphp
                        @foreach ($ids as $item)
                            @if ($item->user)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td><a href="{{ route('admin.users.show',$item->user->id) }}">{{ $item->user->name }}</a> </td>
                                    <td>
                                        @if ($item->front)
                                            <a href="{{ $item->front }}" target="_blank">
                                                <img height="50px" width="50px" src="{{ $item->front }}" />
                                            </a>
                                            <br>
                                            <small>{{ $item->type }}</small>
                                        @else
                                            Not uploaded
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->back)
                                            <a href="{{ $item->back }}" target="_blank">
                                                <img height="50px" width="50px" src="{{ $item->back }}" />
                                            </a>
                                            <br>
                                            <small>{{ $item->type }}</small>
                                        @else
                                            Not Uploaded
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->credit_card_front)
                                            <a href="{{ $item->credit_card_front }}" target="_blank">
                                                <img height="50px" width="50px" src="{{ $item->credit_card_front }}" />
                                            </a>
                                        @else
                                            Not uploaded
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->credit_card_back)
                                            <a href="{{ $item->credit_card_back }}" target="_blank">
                                                <img height="50px" width="50px" src="{{ $item->credit_card_back }}" />
                                            </a>
                                        @else
                                            Not uploaded
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->proof_of_address)
                                            <a href="{{ $item->proof_of_address }}" target="_blank">
                                                <img height="50px" width="50px" src="{{ $item->proof_of_address }}" />
                                            </a>
                                            <br>
                                            <small>{{ $item->proof_of_address_type }}</small>
                                        @else
                                            Not uploaded
                                        @endif
                                    </td>
                                    <td>
                                        <span class="mb-2 badge badge-pill @if($item->status == 'approved') badge-success @elseif($item->status == 'disapproved') badge-danger @else badge-secondary @endif">{{ $item->status }}</span>
                                        <br />
                                        @if ($item->status != 'approved')
                                            <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#approval{{ $item->id }}">Approve</a>
                                            {{-- <a href="{{ route('admin.approve.id', $item->id) }}" class="btn btn-primary btn-sm">Approve</a> --}}

                                            <div class="modal fade" id="approval{{$item->id}}" tabindex="0" aria-labelledby="Notes" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Approving {{ $item->user->name }}<h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('admin.approve.id', $item->id) }}" method="post">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <label class="col-sm-12 form-control-label">Approval Message:</label>
                                                                    <div class="col-sm-12">
                                                                        <textarea class="form-control" style="width: 100%" placeholder="Message" name="message" required></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Done</button>
                                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($item->status != 'disapproved')
                                            <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#disapproval{{ $item->id }}">Disapprove</a>
                                            <div class="modal fade" id="disapproval{{$item->id}}" tabindex="0" aria-labelledby="Notes" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title text-danger">Disapproving {{ $item->user->name }}<h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('admin.disapprove.id', $item->id) }}" method="post">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <label class="col-sm-12 form-control-label">Approval Message:</label>
                                                                    <div class="col-sm-12">
                                                                        <textarea style="width: 100%" placeholder="Message" name="message" rows="5" required></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Done</button>
                                                                <button type="submit" class="btn btn-sm btn-danger">Dispprove</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
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
