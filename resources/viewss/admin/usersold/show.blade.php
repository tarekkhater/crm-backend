@extends('admin.layouts.admin-app')

@section('style')
<link rel="stylesheet" href="/dist/vendors/sweetalert/sweetalert.css">
@include('admin.partials.dt-css')
@endsection
{{--@section('style')--}}
{{--    @include('admin.partials.dt-css')--}}
{{--@endsection--}}

{{--@section('js')--}}
{{--    @include('admin.partials.dt-js')--}}
{{--@endsection--}}

@section('content')
    <div class="br-mainpanel br-profile-page">

        <div class="mx-3">
            <div class="mt-1 mb-1">
                <h3 class="text-center">{{ $user->name }} => {{ $user->email}}</h3>
            </div>
            <div class="ht-50 bg-gray-100 d-flex align-items-center shadow-base ">
                <ul class="nav nav-outline active-info align-items-center flex-row mt-2" role="tablist">
                    <li class="nav-item active"><a class="nav-lin btn btn-dark active"  href="{{ route('admin.users.show', $user->id) }}">Profile</a></li>
{{--                    @if (auth()->user()->hasRole(['superadmin']))--}}
{{--                    @endif--}}
                    @if (check_permission('show-balance') || auth()->user()->can_add_fund)
                        <li class="nav-item"><a class="nav-lin btn btn-primary " data-toggle="tab" href="#posts" role="tab">Balance / Bonus</a></li>
                        <li class="nav-item"><a class="nav-lin  btn btn-success"  data-toggle="tab"
                                                href="#photos"
                                                role="tab">Trades</a></li>

                    @endif
                    @if(check_permission('view-transaction'))
                        <li class="nav-item"><a class="nav-lin btn btn-dark" data-toggle="tab" href="#trans" role="tab">Transactions</a></li>
                    @endif
                    @if (check_permission('add-balance') || auth()->user()->can_add_fund)

                     <a href="" data-toggle="modal" data-target="#fundBalance" class="btn btn-success mb-2 ml-2">Add / Substract Balance</a>

                    @endif
                    @if(check_permission('view-withdrawals'))
                        <li class="nav-item"><a class="nav-lin btn btn-danger" ONCLICK="show_section($(this))" data-toggle="tab" href="#withdrawals" role="tab">Withdrawals</a></li>
                    @endif


                    <li class="nav-item">
                        <a data-toggle="tab" ONCLICK="show_section($(this))" href="#activities" role="tab" class="btn btn-warning">Activities</a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.user.send-message-page',$user->id) }}" class="btn btn-danger ">Send Message</a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.user.gainaccess', $user->id) }}" onclick="alert('You are about to login as a Normal User that means you will be logged out as an admin on this browser')" target="_blank" class="btn btn-primary text-capitalize">Login as {{ $user->first_name }}</a>
                    </li>
                    @if(check_permission_user('login-manger',$user))
                        @if(check_permission('create-roles'))
                        <li class="nav-item">
                            <a data-toggle="tab" ONCLICK="show_section($(this))" href="#roles" role="tab" class="btn btn-warning">Role</a>
                        </li>
                       @endif
                        @endif

                </ul>
            </div>

            @include('notification')

            <div class="mt-3 font-weight-bold" style="font-size: 20px;">
                {{ $user->name }}
            </div>
            <div class="tab-content br-profile-body px-0 pb-2" style=" max-width: 100%!important; margin-top: -10px;">
                <div class="tab-pane fade active show" id="profile-details">

            <div class="d-flex  align-items-center">
                <!-- <div>
                    <button type="button" class="btn btn-danger mb-2" id="showActions" style="cursor: pointer;">Actions</button>
                </div> -->
                <div class="card pd-20 pd-xs-30 shadow-base bd-0 m-2" id="actions-details" style="display: none">
                    <div class="row btn-bloc">
                        {{-- <div class="col-md-6"> --}}
                        <a href="" class="btn btn-primary mb-2 ml-2">Edit Profile</a>
                        <a href="{{ route('admin.trades.index') }}?user={{$user->id}}" class="btn btn-success mb-2 ml-2">Trade For {{ $user->first_name }}</a>
                        @if (check_permission('add-balance') || auth()->user()->can_add_fund)
                        <a href="" data-toggle="modal" data-target="#fundBalance" class="btn btn-success mb-2 ml-2">Add / Substract Balance</a>
                        @endif
                        <a href="{{ route('admin.user.logins',$user->id) }}" class="btn btn-warning mb-2 ml-2">Login Logins</a>
                        <a href="{{ route('admin.user.logins',$user->id) }}" class="btn btn-danger mb-2 ml-2">Send Message</a>
                    </div>
                    @if (check_permission('add-balance')  || auth()->user()->can_add_fund)
                    <div style="margin-top: 5px" class="row btn-bloc">
                        <a href="" data-toggle="modal" data-target="#fundBonus" class="btn btn-primary mb-2 ml-2">Add / Substract Bonus</a>

                        @if (check_permission('users-update'))

                        <a href="{{ route('admin.user.trade.toggle', $user->id) }}" class="btn  {{ $user->can_trade ? 'btn-danger' : 'btn-success' }} mb-2 ml-2">{{ $user->can_trade ? 'Disable' : 'Enable' }} Trade</a>


                        <a href="" data-toggle="modal" data-target="#planUpgrade" class="btn btn-primary mb-2 ml-2">Plan Upgrade</a>
                        {{-- <a href="{{ route('admin.user.upgrade.toggle', $user->id) }}" class="btn {{ $user->can_upgrade ? 'btn-danger' : 'btn-success' }} mb-2 ml-2">{{ $user->can_upgrade ? 'Deactivate' : 'Activate' }} Plan Upgrade</a>--}}
                        <a href="{{ route('admin.user.withdraw.toggle', $user->id) }}" class="btn {{ $user->can_withdraw ? 'btn-danger' : 'btn-success' }} mb-2 ml-2">{{ $user->can_withdraw ? 'Disable' : 'Enable' }} Withdraw</a>
                        <a href="" data-toggle="modal" data-target="#fundWithdrawable" class="btn btn-info mb-2 ml-2">Add Withdraw</a>
                        <a href="" data-toggle="modal" data-target="#setNotification" class="btn btn-success mb-2 ml-2">Notifications</a>
                            @endif
                    </div>
                    @endif
                    {{-- </div>--}}
                </div><!-- card -->
            </div>

                </div>
            </div>


            <div class="tab-content br-profile-body px-0 pb-2" style=" max-width: 100%!important;">

                <div class="tab-pane fade {{ request()->get('asset') ? ' ' : 'active show' }} " id="profile">
                    <div class="card shadow-base bd-0 rounded-0 widget-4">

                        <div class="card-body text-left">
                            <div class="d-flex align-items-center pb-4">
                                @if (setting('autotrader'))
                                    @if ($user->hasRole('user'))
                                        @if(!$user->trader_request)
                                            <div><a href="" data-toggle="modal" data-target="#connectTrader" class="btn btn-success mb-2">Send Autotrader request</a></div>
                                        @else
                                            <div><a href="#" class="btn btn-outline-success mb-2">Request : {{ $user->trader_request }}</a><div>
                                        @endif
                                            @if($user->manager_id)
                                                <p><a href="{{ route('admin.trades.index') }}?user={{$user->manager_id}}" class="btn  btn-outline-success">{{ $user->account_officer }}</a></p>
                                            @else
                                                <p><a href="#" class="btn  {{ $user->manager_id ? 'btn-outline-success' : 'btn-outline-danger' }}">{{ $user->account_officer }}</a></p>
                                            @endif
                                    @endif
                                @endif

                                {{-- @else --}}
                                {{-- <p class="tx-white">Account connected</p> --}}
                                {{-- @endif --}}
                                <a class="btn btn-primary " href="{{ url('admin/users') }}" style="background-color:#00b297; border-color:#00b297;">Back</a>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-lg-4 col-md-5">
                                    <a class="btn btn-primary " href="{{ url('admin/users') }}" style="background-color:#00b297; border-color:#00b297;"> << Back</a>

                                    <div class="d-flex align-items-center mb-3">

                                        <div class="card-profile-img">
                                            <img src="{{ $user->avatar  }}" alt="">
                                        </div><!-- card-profile-img -->
                                        <div class="ml-4">
                                            <h4 class="tx-bold tx-roboto tx-black tx-capitalize mb-1">{{ $user->name }}</h4>
                                            <p class="tx-dark mb-1">{{ $user->email }}</p>
                                            <p class="tx-dark mb-0">Current Plan : {{ $user->plan }}</p>
                                            @if (check_permission('edite-Users'))
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="">Edit Profile</a><br/>

                                            @elseif ($user->hasRole(['manager'])&&check_permission('edite-conversion-manager'))
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="">Edit Profile</a><br/>
                                            @endif


{{--                                            @if (check_permission('users-update'))--}}
{{--                                            <a href="" data-toggle="modal" data-target="#planUpgrade" class="btn btn-primary">Plan Upgrade</a>--}}
{{--                                            @endif--}}

                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-md-7">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <div class="tx-center tx-sm-left">
                                                <p class="tx-black tx-bold mb-1">ESTIMATE BALANCE </p>
                                                <p class="mb-0" style="color: #29c359!important; font-size: 27px;">{{ $user->userInfo->balance() }}</p>

                                                <p class="tx-black tx-bold mb-1">PNL BALANCE </p>
                                                <p class="mb-0" style="color: #29c359!important; font-size: 27px;">{{ $user->userInfo->pnl }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="tx-center tx-sm-left">
                                                <p class="tx-black tx-bold mb-1">BONUS IN <span style="color: #ff7700;">USD</span></p>
                                                <p class="mb-0" style="color: #29c359!important; font-size: 27px;">{{ $user->userInfo->bonus }} USD</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div>
                                                @if (setting('autotrader'))
                                                    @if ($user->hasRole('user'))
                                                        @if(!$user->trader_request)
                                                            <div><a href="" data-toggle="modal" data-target="#connectTrader" class="btn btn-success mb-2">Send Autotrader request</a></div>
                                                        @else
                                                            <div><a href="#" class="btn btn-outline-success mb-2">Request : {{ $user->trader_request }}</a><div>
                                                        @endif
                                                            @if($user->manager_id)
                                                                <p><a href="{{ route('admin.trades.index') }}?user={{$user->manager_id}}" class="btn  btn-outline-success">{{ $user->account_officer }}</a></p>
                                                            @else
                                                                <p><a href="#" class="btn  {{ $user->manager_id ? 'btn-outline-success' : 'btn-outline-danger' }}">{{ $user->account_officer }}</a></p>
                                                            @endif
                                                    @endif
                                                @endif

                                                {{-- @else --}}
                                                {{-- <p class="tx-white">Account connected</p> --}}
                                                {{-- @endif --}}
                                                <div>


                                                    @if (setting('auto_profit_lose') == 1)
                                                        @if ($autoPNL->status)
                                                            <div class="alert alert-success">Auto Profit / Loss is currently running for this user</div>
                                                        @else
                                                            <div class="alert alert-danger">Auto Profit / Loss is disabled for this user</div>
                                                        @endif
                                                            @if(check_permission('show-balance'))
                                                            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#autoPL" style="cursor: pointer;">Modify Auto P/L</button>
                                                                @endif
                                                    @endif

                                                        @if(check_permission('show-balance'))
                                                            <a href="{{ route('admin.user.toggle', $user->id) }}" class="btn {{ $user->is_active ? 'btn-danger' : 'btn-success' }}" data-toggle="tooltip" ><em class="fa {{ $user->is_active ? 'fa-times ' : ' fa-check' }} "></em></a>
                                                        @endif
                    {{--                                                    <button type="button" class="btn btn-danger mb-2" id="showActions" style="cursor: pointer;">Actions</button>--}}
                                                    <div class="card pd-20 pd-xs-30 shadow-base bd-0" id="actions-details" style="display: none;">
                                                        <div class="row btn-bloc">
                                                            {{-- <div class="col-md-6"> --}}
                                                            <a href="" class="btn btn-primary mb-2">Edit Profile</a>
                                                            <a href="{{ route('admin.user.logins',$user->id) }}" class="btn btn-danger mb-2">Send Message</a>
                                                            <a href="" data-toggle="modal" data-target="#fundBalance" class="btn btn-success mb-2">Add / Substract Fund</a>
                        {{--                                                            <a href="" data-toggle="modal" data-target="#fundBonus" class="btn btn-primary mb-2">Add / Substract Bonus</a>--}}
                                                        </div>
                                                            <div style="margin-top: 5px" class="row btn-bloc">
                                                            <a href="{{ route('admin.trades.index') }}?user={{$user->id}}" class="btn btn-success mb-2">Trade For {{ $user->first_name }}</a>
                                                            <a href="{{ route('admin.user.trade.toggle', $user->id) }}" class="btn  {{ $user->can_trade ? 'btn-danger' : 'btn-success' }} mb-2">{{ $user->can_trade ? 'Disable' : 'Enable' }} Trade</a>
                                                            <a href="" data-toggle="modal" data-target="#planUpgrade" class="btn btn-primary mb-2">Plan Upgrade</a>
                                                            {{-- <a href="{{ route('admin.user.upgrade.toggle', $user->id) }}" class="btn {{ $user->can_upgrade ? 'btn-danger' : 'btn-success' }} mb-2">{{ $user->can_upgrade ? 'Deactivate' : 'Activate' }} Plan Upgrade</a>--}}
                                                        </div>
                                                        {{-- </div>--}}
                                                    </div><!-- card -->
                                                </div>
                                                                    @if(check_permission('show-balance'))
                                                <a href="{{ route('admin.user.trade.toggle', $user->id) }}" class="btn  {{ $user->can_trade ? 'btn-danger' : 'btn-success' }}">{{ $user->can_trade ? 'Disable' : 'Enable' }} Trade</a>

                                                <a href="{{ route('admin.user.allowTrade.toggle', $user->id) }}" class="btn  {{ $user->allow_trade ? 'btn-danger' : 'btn-success' }} mt-2">{{ $user->allow_trade ? 'Disable' : 'Enable' }} Allow Trade After Close Trade</a>
@endif
                                                                </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <hr>
                            <div>
                                <div>
                                    <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-13 mg-b-25">Profile Information</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div>
                                                <div class="d-flex">
                                                    <div class="w-50">
                                                        <p class="tx-14 tx-capitalize tx-black tx-mont tx-spacing-1 mg-b-2">First Name</p>
                                                    </div>
                                                    <div class="w-50">
                                                        <p class="tx-info mg-b-25">{{ $user->first_name }}</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="w-50">
                                                        <p class="tx-14 tx-capitalize tx-black tx-mont tx-spacing-1 mg-b-2">Last Name</p>
                                                    </div>
                                                    <div class="w-50">
                                                        <p class="tx-info mg-b-25">{{ $user->last_name }}</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="w-50">

                                                        <p class="tx-14 tx-capitalize tx-black tx-mont tx-spacing-1 mg-b-2">Phone Number</p>
                                                    </div>
                                                    <div class="w-50">
                                                        <p class="tx-info mg-b-25"><a href="tel:{{ $user->phone }}"> {{$user->phone_code}} {{ $user->phone }}</a></p>
                                                    </div>
                                                </div>

                                                <div class="d-flex">
                                                    <div class="w-50">
                                                        <p class="tx-14 tx-capitalize tx-black tx-mont tx-spacing-1 mg-b-2">Country</p>
                                                    </div>
                                                    <div class="w-50">
                                                        <p class="tx-info mg-b-25">  <image id="flag-icon" src="{{ $user->iso }}" width="32" height="22" style="margin-right:4px; backgroud-color:#e9ecef;"/> {{$user->country}}</p>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div>
                                                <div class="d-flex">
                                                    <div class="w-50">
                                                        <p class="tx-14 tx-capitalize tx-black tx-mont tx-spacing-1 mg-b-2">Email Address</p>
                                                    </div>
                                                    <div class="w-50">
                                                        <p class="tx-inverse">{{ $user->email }}</p>
                                                    </div>
                                                </div>
{{--                                                <div class="d-flex">--}}
{{--                                                    <div class="w-50">--}}
{{--                                                        <p class="tx-14 tx-capitalize tx-black tx-mont tx-spacing-1 mg-b-2">Present Address</p>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="w-50">--}}
{{--                                                        <p class="tx-inverse mg-b-25">{{ $user->address }} </p>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div class="d-flex">--}}
{{--                                                    <div class="w-50">--}}
{{--                                                        <p class="tx-14 tx-capitalize tx-black tx-mont tx-spacing-1 mg-b-2">Permanent Address</p>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="w-50">--}}
{{--                                                        <p class="tx-inverse mg-b-50">{{ $user->permanent_address }}</p>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="tx-gray-800 tx-uppercase tx-semibold tx-13 mg-b-25">KYC Documents</h3>
                                    @if ($user->identify)
                                        <h3>Front</h3>
                                            <img height="100" width="100" src="{{ $user->identify->front }}" />

                                        <h3>Back</h3>
                                        <img height="100" width="100" src="{{ $user->identify->back }}" />

                                        @else
                                        <h6>Not Uploaded</h6>
                                    @endif

                                    <div class="text-center">


                                        @if (check_permission('users-delete'))

                                            <a onclick="event.preventDefault();" data-delete="delete-form" href="{{ route('admin.user.delete', $user->id) }}" data-placement="top" class="btn btn-warning but_delete_action fa fa-trash but_delete_action" title="delete">
                                                <i class="fa fa-trash-alt"></i>
                                                Delete Account
                                            </a>
                                            <form id="delete-form" action="{{ route('admin.user.delete', $user->id) }}" method="POST" style="display: none;">
                                                @method('GET')
                                                @csrf
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                                    @if(setting('joint_account') == 1)
                                    <hr>
                                    <div>
                                        <div>
                                            <h6 class="tx-gray-800 tx-uppercase tx-semibold tx-13 mg-b-25">Joint Account Information</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div>

                                                        <div class="d-flex">
                                                            <div class="w-50">
                                                                <p class="tx-14 tx-capitalize tx-black tx-mont tx-spacing-1 mg-b-2">First Name</p>
                                                            </div>
                                                            <div class="w-50">
                                                                <p class="tx-info mg-b-25">{{ $user->j_first_name }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex">
                                                            <div class="w-50">
                                                                <p class="tx-14 tx-capitalize tx-black tx-mont tx-spacing-1 mg-b-2">Last Name</p>
                                                            </div>
                                                            <div class="w-50">
                                                                <p class="tx-info mg-b-25">{{ $user->j_last_name }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex">
                                                            <div class="w-50">
                                                                <p class="tx-14 tx-capitalize tx-black tx-mont tx-spacing-1 mg-b-2">Phone Number</p>
                                                            </div>
                                                            <div class="w-50">
                                                                <p class="tx-info mg-b-25">{{ $user->j_phone }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div>
                                                        <div class="d-flex">
                                                            <div class="w-50">
                                                                <p class="tx-14 tx-capitalize tx-black tx-mont tx-spacing-1 mg-b-2">Email Address</p>
                                                            </div>
                                                            <div class="w-50">
                                                                <p class="tx-inverse">{{ $user->j_email }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex">
                                                            <div class="w-50">
                                                                <p class="tx-14 tx-capitalize tx-black tx-mont tx-spacing-1 mg-b-2">Country</p>
                                                            </div>
                                                            <div class="w-50">
                                                                <p class="tx-inverse mg-b-25">{{ $user->j_country }} </p>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                        </div><!-- card-body -->
                    </div><!-- card -->

                </div>





                <div class="tab-pane fade show" id="posts">
                    @if(check_permission('add-balance'))
                    <div class="card pd-20 pd-xs-30 shadow-base bd-0 m-2" id="actions-details">
                        <div class="row btn-bloc">

                        <a href="" data-toggle="modal" data-target="#fundBonus" class="btn btn-primary mb-2 ml-2">Add / Substract Bonus</a>

                        <a href="" data-toggle="modal" data-target="#fundBalance" class="btn btn-success mb-2 ml-2">Add / Substract Balance</a>

                        </div>
                    </div>
                    @endif
                    <div class="card pd-20 pd-xs-30 shadow-base bd-0 mg-t-30">

                        <h3>All Deposits</h3>

                            <div class="table-wrapper">
                            <a href="{{ route('admin.users.deposits.create', $user) }}">
                                <button class="btn btn-primary mb-2 pull-right">Add Deposit</button>
                            </a>
                                <table class="datatable1 table table-bordered display responsive nowrap" style="width: 100%;">
                                    <thead>
                                    <tr>
                                        <th class="wd-5p">S/N</th>
                                        <th class="wd-15p">Action</th>
                                        <th class="wd-15p">Date</th>
                                        <th class="wd-15p">Deposited Amount</th>
                                        <th class="wd-10p">Proof</th>
                                        <th class="wd-10p">Status</th>
                                        {{--                                <th class="wd-10p">Action</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @php
                                        $count = 1;
                                    @endphp
                                    @foreach ($deposits as $item)
                                        <tr>
                                            <td>{{ $count++ }}</td>
                                            <td>
                                                <a href="{{ route('admin.users.deposits.edit', $item) }}" class="btn btn-primary" title="View User"><em class="fa fa-edit"></em></a>
                                            </td>
                                            {{--                                    <td>{{ optional($item->user)->name }}</td>--}}
                                            <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                            <td>{{ number_format($item->amount) }}</td>
                                            <td>
                                                @if ($item->proof)
                                                    <a target="_blank" href="{{ $item->proof }}">View Proof</a>
                                                @else
                                                    Not Uploaded
                                                @endif
                                            </td>
                                            <td>
                                            @if($item->status)
                                                <p class="badge badge-success">Approved</p>
                                                @else
                                                <p class="badge badge-danger">Pending</p>
                                                @endif
                                            </td>
                                            {{--                                    <td>--}}
                                            {{--                                        <a href="{{ route('admin.deposit.approve', $item->id) }}" class="{{ $item->status ? 'btn-danger' : 'btn-success' }} btn " data-toggle="tooltip" data-placement="top">{{ $item->status ? 'Un Approve': 'Approve' }}</a>--}}
                                            {{--                                        <a href="{{ route('admin.deposit.destroy', $item) }}" onclick="return confirm(&quot;Click Ok to delete Deposit.&quot;)" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Delete"><em class="fa fa-trash"></em>--}}
                                            {{--                                        </a>--}}
                                            {{--                                    </td>--}}

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                        </div><!-- table-wrapper -->
                    </div>
                </div><!-- tab-pane -->

                <div class="tab-pane fade show" id="login">

                    <div class="card pd-20 pd-xs-30 shadow-base bd-0 mg-t-30">

                        <h3>All Activities</h3>


                                <div class="table-wrapper">
                                    <table  class="table display table-bordered responsive nowrap">
                                        <thead>
                                        <tr>
                                            <th class="wd-10p">S/N</th>
                                            <th  class="wd-15p">Ip Address</th>
                                            <th width="45%" class="wd-45p">Device Details</th>
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
                                                <td width="40%">{{ $item->user_agent }}</td>
                                                <td>{{ $item->login_at }}</td>
                                                <td>{{ $item->logout_at }}</td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div><!-- table-wrapper -->


                    </div>
                </div><!-- tab-pane -->

                <div class="tab-pane fade show" id="activities">



                    <div class="card pd-20 pd-xs-30 shadow-base bd-0 mg-t-30">

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

                        <h3>All Activities</h3>



                                <div class="table-wrapper">
                                    <table  class="table display table-bordered responsive nowrap">
                                        <thead>
                                        <tr>
                                            <th class="wd-10p">S/N</th>
                                            <th  class="wd-15p">Login At</th>
                                            <th  class="wd-15p">Logout At</th>
                                            <th  class="wd-50">Description </th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @php
                                            $count = 1;
                                        @endphp
                                        @foreach ($details as $item)
                                            <tr>
                                                <td>{{ $count++ }}</td>
                                                <td>{{ $item->login_at }}</td>
                                                <td>{{ $item->logout_at }}</td>
                                                <td width="40%">{{ $item->user_agent }}</td>
                    {{--                                                <td>{{ $item->logout_at }}</td>--}}
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div><!-- table-wrapper -->


                    </div>
                </div><!-- tab-pane -->
 <div class="tab-pane fade show" id="roles">



                    <div class="card pd-20 pd-xs-30 shadow-base bd-0 mg-t-30">

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

                        <h3>Update Role</h3>



                                <div class="table-wrapper">
                                    <form method="post" action="{{route('admin.change_role_user')}}">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{$user['id']}}">
                                        <div class="form-group">
                                            <label class="col-form-label">Permission</label>
                                            <br>
                                            <select class="form-control select2" style="width: 100%"  name="permission[]" multiple id="permission">
                                                <option disabled> select Permission</option>
                                                @foreach($roles as $role)

                                                    <option {{in_array($role['id'],$roles_id)?'selected':''}} value="{{$role['id']}}">{{$role['name']}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="form-group"><button class="btn btn-primary">Save Role</button></div>

                                    </form>
                               </div><!-- table-wrapper -->


                    </div>
                </div><!-- tab-pane -->

                                            @if(check_permission('view-transaction'))
                    <div class="tab-pane fade show" id="trans">
                        <div class="row ">
                            <div class="col-md-12">
                                <div class="card pd-20 pd-xs-30 shadow-base bd-0 mg-t-30">

                                    <div class="tabl-wrapper table-responsive">

                                        <form action="{{ route('admin.users.transactions.delete') }}" method="POST" id="deleteTransactionForm">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-danger mb-2 delete-multiple-transactions" style="display: none;">Delete Selected</button>
                                            <table class="datatable1 table display table-bordered responsive nowrap" style="width: 100%">
                                                <thead>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <th>S/N</th>
                                                    @if (check_permission('users-update'))
                                                    <th class="wd-15p">Action</th>
                                                    @endif
                                                    <th class="wd-15p">Date / Time</th>
                                                    <th class="wd-15p">Amount</th>
                                                    <th class="wd-15p">Source</th>
                                                    <th class="wd-10p">Fund Type</th>
                                                    <th class="wd-10p">Account</th>
                                                    <th class="wd-40p">Description</th>
                                                    {{--                                <th class="wd-10p">Action</th>--}}
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @php
                                                    $count = 1;
                                                @endphp
                                                @foreach ($trans as $item)
                                                    <tr>
                                                        <td><center><input type="checkbox" name="delete_transaction_ids[]" value="{{ $item->id }}" onchange="toggleTransactionToDelete(this)"></center></td>
                                                        <td>{{ $count++ }}</td>
                                                        @if (check_permission('users-update'))
                                                        <td>
                                                            <a href="{{ route('admin.users.transactions.edit', $item) }}" class="btn btn-primary" title="View User"><em class="fa fa-edit"></em></a>
                                                            @if(check_permission('delete-transaction'))
                                                            <a href="#" onclick="deleteTransactions({{ $item->id}})" class="btn btn-danger delete-transaction-btn" title="View User"><em class="fa fa-trash"></em></a>
                                                       @endif
                                                        </td>
                                                        @endif
                                                        <td>{{ $item->created_at }}</td>
                                                        <td>{{ amt($item->amount) }}</td>
                                                        <td>{{ $item->source }}</td>
                                                        <td>{{ $item->type }}</td>
                                                        <td>{{ $item->account_type }}</td>
                                                        <td>{{ $item->note }}</td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                        <button class="btn btn-danger delete-multiple-transactions" style="display: none;">Delete Selected</button>
                                    </form>
                                    </div><!-- table-wrapper -->
                                </div>
                            </div>



                        </div><!-- row -->
                    </div><!-- tab-pane -->
                @endif




                <div class="tab-pane fade {{ request()->get('asset') ? 'active show' : '' }}" id="photos">

                    @section('hide')

                        <div class="card pd-20 pd-xs-30 shadow-base bd-0 m-2" id="actions-details">

                            <div class="row btn-bloc">
                                <a href="{{ route('admin.trades.index') }}?user={{$user->id}}" class="btn btn-success mb-2 ml-2">Trade For {{ $user->first_name }}</a>

                                <a href="{{ route('admin.user.trade.toggle', $user->id) }}" class="btn  {{ $user->can_trade ? 'btn-danger' : 'btn-success' }} mb-2 ml-2">{{ $user->can_trade ? 'Disable' : 'Enable' }} Trade</a>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-wrapper">
                                    <div class="card pd-20 pd-xs-30 shadow-base bd-0 mg-t-30 table-responsive">
                                        <table class="table datatabl table-bordered table-condensed display responsive nowrap">

                                            <thead>
                                            <tr>
                                                <th>Opened at</th>
                                                <th>Currency / Asset</th>
                                                <th>Amount</th>
                                                <th>Qty</th>
                                                <th>Direction</th>
                                                <th>Status</th>
                                                <th>Result</th>
                                                <th>PNL</th>
                                                <th>Edit</th>
                                                {{--                                            <th></th>--}}
                                            </tr>
                                            </thead>
                                            @if (count($trades) < 1)
                                                <tbody>
                                                <tr>
                                                    <td colspan="100%">No results found</td>
                                                </tr>
                                                </tbody>
                                            @else
                                                <tbody>
                                                @foreach($trades as $item)
                                                    <tr>
                                                        <td>{{ $item->open_at }}</td>
                                                        <td>{{ optional($item->currency)->name }}</td>
                                                        <td>{{ $item->traded_amount }} USD</td>
                                                        <td>{{ $item->qty }} {{ optional($item->currency)->sym }}</td>

                                                        <td>
                                                            @if ($item->trade_type == 'buy')
                                                                <span class="badge badge-success p-2">Buy</span>
                                                            @else
                                                                <span class="badge badge-warning p-2">Sell</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($item->status == 0)
                                                                <span class="badge badge-warning p-2">Running</span>
                                                            @else
                                                                <span class="badge badge-danger p-2">Closed</span>
                                                            @endif
                                                        </td>
                                                        @if ($item->status == 1)
                                                            <td>
                                                                @if ($item->result === 2)
                                                                    <span class="badge badge-danger p-2">loss</span>
                                                                @elseif ($item->result === 1)
                                                                    <span class="badge badge-success p-2">Won</span>
                                                                @elseif ($item->result === 3)
                                                                    <span class="badge badge-warning p-2">Draw</span>
                                                                @endif
                                                            </td>
                                                        @else
                                                            <td>
                                                                <button @click="closeOrder(i.id)" class="btn btn-danger">Close Trade</button>
                                                            </td>
                                                        @endif
                                                        <td>
                                                            @if ($item->profit)
                                                                {{ amt($item->profit) }}
                                                            @else
                                                                <span class="badge badge-danger p-2">Running</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($item->status === 0)
                                                                <a href="" data-toggle="modal" data-target="#editTrade{{ $item->id }}" class="btn btn-primary">Edit</a>
                                                            @else
                                                                <a href="" disabled="" data-toggle="modal" class="btn btn-warning">Closed</a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            @endif

                                            @section('hide')
                                                <thead>
                                                <tr>
                                                    <th>Opened at</th>
                                                    <th>Currency / Asset</th>
                                                    <th>Amount</th>
                                                    <th>Qty</th>
                                                    <th>Opening Price</th>
                                                    <th>Closing Price</th>
                                                    <th>Direction</th>
                                                    <th>Status</th>
                                                    <th>Result</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                @if(count($trades) < 1)
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="100%">No results found</td>
                                                    </tr>
                                                    </tbody>
                                                @else
                                                    <tbody >
                                                    @foreach($trades as $i)
                                                        <tr>
                                                            <td>{{ $i->open_at }}</td>
                                                            <td>{{ optional($i->currency)->name }}</td>
                                                            <td>{{ $i->traded_amount }} USD</td>
                                                            <td>{{ $i->qty }} {{ optional($i->currency)->sym }}</td>
                                                            <td>{{ $i->opening_price }}USD</td>
                                                            <td>
                                                                @if ($i->closing_price)
                                                                    {{ $i->closing_price }} USD
                                                                @else
                                                                    <span class="badge badge-success p-2">Running</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($i->trade_type == 'buy')
                                                                    <span class="badge badge-success p-2">Buy</span>
                                                                @else
                                                                    <span v-else class="badge badge-warning p-2">Sell</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($i->status == 0)
                                                                    <span class="badge badge-success p-2">Running</span>
                                                                @else
                                                                    <span class="badge badge-warning p-2">Closed</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($i->result == 2)
                                                                    <span class="badge badge-danger p-2">loss</span>
                                                                @elseif($i->result == 1)
                                                                    <span class="badge badge-success p-2">Won</span>
                                                                @else
                                                                    <span class="badge badge-warning p-2">Draw</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($i->status == 0)
                                                                    <a  href="{{ route('trade.close', $i->id) }}" class="btn btn-danger">Close Trade</a>
                                                                @else
                                                                    <button class="btn btn-warning" disabled>Closed</button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                @endif
                                            @endsection

                                        </table>
                                    </div>
                                </div><!-- table-wrapper -->
                            </div>
                        </div>
                    @endsection




                            <p>
                                @if (check_permission('update-recent-trade'))
                                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                    Add New Trade
                                </button>
                                @endif
                                @if(check_permission('delete-recent-trade'))

                                <a href="{{ route('admin.user.trade.toggle', $user->id) }}" class="btn  {{ $user->can_trade ? 'btn-danger' : 'btn-success' }} mb-2 ml-2 float-right">{{ $user->can_trade ? 'Disable' : 'Enable' }} Trade</a>
@endif
                            </p>


                            <div class="collapse {{ request()->get('asset') ? 'show' : '' }}" id="collapseExample">
                                <div class="br-section-wrapper">
                                    <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10">Add Trade [increase or decrease opening rate to alter profit]</h6>

                                    <form action="{{ route('admin.trades.store') }}" method="POST">
                                        @csrf
                                        <div class="form-layout form-layout-1">
                                            <div class="row mg-b-25">

                                                <input name="user_id" type="hidden" value="{{ $user->id }}">

                                                <div class="col-md-3">
                                                    <div class="form-group mg-b-10-force">
                                                        <label class="form-control-label">Select Asset Type: <span class="tx-danger">*</span></label>
                                                        <select v-model="data.a_type" id="asset"  name="type" required class="form-control">
                                                            @foreach( ['crypto','stocks','forex','indices','commodities'] as $item)
                                                                <option  value="{{ $item }}">{{ $item }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div><!-- col-8 -->
                                                <div class="col-md-3">
                                                    <div class="form-group mg-b-10-force">
                                                        <label class="form-control-label">Select Asset : <span class="tx-danger">*</span></label>
                                                        <select v-model="data.coin_id" id="asset" @change="updateStatus(data.coin_id)" name="coin_id" required class="form-control">
                                                            @foreach(\App\Models\CurrencyPair::whereType($c_type)->where('rate','!=',0.0000)->get() as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div><!-- col-8 -->
                                                <div class="col-md-3">
                                                    <div class="form-group mg-b-10-force">
                                                        <label class="form-control-label text text-capitalize"> Opening Rate [@{{ coinPrice }}]: <span class="tx-danger">*</span></label>
                                                        <input v-model="data.value" disabled class="form-control" min="1" step="any" :value="data.value" required type="number" name="value" placeholder="Asset Value">
                                                        <input v-model="data.value" :value="data.value" required type="hidden" name="value">
                                                    </div>
                                                </div><!-- col-8 -->

                                                <div class="col-md-3">
                                                    <div class="form-group mg-b-10-force">
                                                        <label class="form-control-label text text-capitalize">Traded Volume : <span class="tx-danger">*</span></label>
                                                        <input v-model="data.volume" class="form-control" step="any" required type="number" name="amt" placeholder="Traded Volume">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group mg-b-10-force">
                                                        <label class="form-control-label text text-capitalize">Amount in USD : <span class="tx-danger">*</span></label>
                                                        <input v-model="data.amount"  class="form-control" step="any" required type="number" name="amount" placeholder="Traded Amount">
                                                    </div>
                                                </div>


                                                <div class="col-md-3">
                                                    <div class="form-group mg-b-10-force">
                                                        <label class="form-control-label text text-capitalize">Leverage : <span class="tx-danger">*</span></label>
                                                        <input v-model="data.leverage" disabled class="form-control" step="any" placeholder="leverage">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group mg-b-10-force">
                                                        <label class="form-control-label">Trade Type: <span class="tx-danger">*</span></label>
                                                        <select id="type" required class="form-control" name="type">
                                                            <option value="buy">Buy</option>
                                                            <option value="sell">Sell</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mg-b-10-force">
                                                        <label class="form-control-label">Take Profit: <span class="tx-danger">*</span></label>
                                                        <select v-model="data.is_take_profit" required class="form-control" name="is_take_profit">
                                                            <option value="1">Enable</option>
                                                            <option value="0">Disable</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group mg-b-10-force">
                                                        <label class="form-control-label">Stop loss: <span class="tx-danger">*</span></label>
                                                        <select v-model="data.is_stop_loss" id="trade_type" required class="form-control" name="is_stop_loss">
                                                            <option value="1">Enable</option>
                                                            <option value="0">Disable</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3" v-if="data.is_take_profit > 0">
                                                    <div class="form-group mg-b-10-force">
                                                        <label class="form-control-label text text-capitalize">Take Profit : <span class="tx-danger">*</span></label>
                                                        <input v-model="data.take_profit" :value="data.take_profit" class="form-control" step="any" required type="number" name="take_profit" placeholder="Take Profit">
                                                    </div>
                                                </div>

                                                <div class="col-md-3" v-if="data.is_stop_loss > 0">
                                                    <div class="form-group mg-b-10-force">
                                                        <label class="form-control-label text text-capitalize">Stop loss : <span class="tx-danger">*</span></label>
                                                        <input :value="data.stop_loss" v-model="data.stop_loss" class="form-control" step="any" required type="number" name="stop_loss" placeholder="stop_loss">
                                                    </div>
                                                </div>






                                            </div><!-- row -->

                                            <div class="form-layout-footer">
                                                <button class="btn btn-primary" type="submit">Submit Trade</button>
                                            </div><!-- form-layout-footer -->
                                        </div><!-- form-layout -->
                                    </form>
                                </div><!-- br-section-wrapper -->
                            </div>


                        <div class="br-section-wrapper mb-2" v-if="all_trades.length > 0">
                            <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10"> Open Trades</h6>

                            <div class="table-wrapper" v-cloak>

                                <table class="table table-bordered table-condensed display responsive">
                                    <thead>
                                    <tr>
                                        <th>Opened at</th>
                                        <th>Currency / Asset</th>
                                        <th>Leverage</th>
                                        <th>Amount</th>
                                        <th>Opening Value</th>
                                        <th>Current Value</th>
                                        <th>Qty</th>
                                        <th>By Admin</th>
                                        <th>Direction</th>
                                        <th>Status</th>
                                        {{--                                <th>Result</th>--}}
                                        <th>PNL</th>
                                        <th>Edit</th>
                                        <th>Close</th>
                                        @if (check_permission('users-delete'))
                                        <th>Delete</th>
                                        @endif
                                        {{--                                            <th></th>--}}
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <tr v-for="item in all_trades">
                                        <td>@{{ item.open_at }}</td>
                                        <td>@{{ item.currency ? item.currency.name : '' }}</td>
                                        <td>@{{ item.leverage }} </td>
                                        <td>@{{ item.traded_amount }} USD</td>
                                        <td>@{{ item.opening_price }} USD</td>
                                        <td>@{{ item.closing_price }} USD</td>
                                        <td>@{{ item.qty }} @{{ item.currency ? item.currency.sym : '' }}</td>

                                        <td>
                                            <span v-if="item.by_admin" class="badge badge-success p-2">Yes</span>
                                            <span v-else  class="badge badge-warning p-2">No</span>
                                        </td>
                                        <td>
                                            <span v-if="item.trade_type === 'buy'" class="badge badge-success p-2">Buy</span>
                                            <span v-else  class="badge badge-warning p-2">Sell</span>
                                        </td>
                                        <td>
                                            <span v-if="item.status < 1" class="badge badge-warning p-2">Running</span>
                                            <span v-else class="badge badge-danger p-2">Closed</span>
                                        </td>

                                        {{--                                        @if ($item->status == 1)--}}
                                        {{--                                            <td>--}}
                                        {{--                                                @if ($item->result === 2)--}}
                                        {{--                                                    <span class="badge badge-danger p-2">loss</span>--}}
                                        {{--                                                @elseif ($item->result === 1)--}}
                                        {{--                                                    <span class="badge badge-success p-2">Won</span>--}}
                                        {{--                                                @elseif ($item->result === 3)--}}
                                        {{--                                                    <span class="badge badge-warning p-2">Draw</span>--}}
                                        {{--                                                @endif--}}
                                        {{--                                            </td>--}}
                                        {{--                                        @else--}}
                                        {{--                                            <td>--}}
                                        {{--                                                <a  href="{{ route('trade.manual_close', $item->id) }}" class="btn btn-danger">Close Trade</a>--}}
                                        {{--                                            </td>--}}
                                        {{--                                        @endif--}}
                                        <td>
                                            @{{ item.profit }}
                                        </td>
                                        <td>

                                            <button v-if="item.status == 0"  @click="editTrade(item)" class="btn btn-primary">Edit</button>
                                            {{--                                    <button v-if="item.status == 0"  data-toggle="modal" :data-target="'#editTrade'+item.id" class="btn btn-primary">Edit</button>--}}
                                            <a v-else href="" disabled="" data-toggle="modal" class="btn btn-warning">Closed</a>

                                        </td>
                                        <td>
                                            <a  :href="'/close/trade/'+item.id+'/trades'" class="btn btn-danger">Close</a>
                                        </td>
                                        @if (check_permission('users-delete'))
                                        <td class="text-center">
                                            {{--                                            {!! route('admin.trades.destroy', $item->id) !!}--}}

                                            {{-- <form method="POST" :action="'/admin'" accept-charset="UTF-8">
                                                <input name="_method" value="DELETE" type="hidden">
                                                {{ csrf_field() }}

                                                <div class="btn-group justify-center" role="group">
                                                    <button type="submit" class="btn btn-danger" title="Delete Job" onclick="return confirm(&quot;Click Ok to delete Record.&quot;)">
                                                        <span class="fa fa-trash" aria-hidden="true"></span>
                                                    </button>
                                                </div>

                                            </form> --}}

                                            <div class="btn-group justify-center" role="group">
                                                <a :href="'/admin/users/trades/'+item.id+'/delete'" class="btn btn-danger" title="Delete Job" onclick="return confirm(&quot;Click Ok to delete Record.&quot;)">
                                                    <span class="fa fa-trash" aria-hidden="true"></span>
                                                </a>
                                            </div>

                                        </td>
                                        @endif
                                    </tr>
                                    </tbody>


                                </table>

                            </div><!-- table-wrapper -->


                        </div>


                        <div class="br-section-wrapper">
                            <h6 class="tx-gray-800 tx-uppercase tx-bold tx-14 mg-b-10"> Closed Trades</h6>




                            <div class="table-wrapper">
                                <form action="{{ route('admin.users.trades.delete') }}" method="POST" id="deleteTradeForm">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger mb-2 delete-multiple-trades" style="display: none;">Delete Selected</button>
                                    <table  class="table table-bordered table-condensed display responsive">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Opened at</th>
                                            <th>Currency / Asset</th>
                                            <th>Amount</th>
                                            <th>Opening Value</th>
                                            <th>Current Rate</th>
                                            <th>Qty</th>
                                            <th>Direction</th>
                                            <th>Status</th>
                                            {{--                                <th>Result</th>--}}
                                            <th>PNL</th>
                                            <th>Edit</th>
                                            @if (check_permission('delete-recent-trade'))
                                            <th>Delete</th>
                                            @endif
                                            {{--                                            <th></th>--}}
                                        </tr>
                                        </thead>
                                        @if (count($trades) < 1)
                                            <tbody>
                                            <tr>
                                                <td colspan="100%">No results found</td>
                                            </tr>
                                            </tbody>
                                        @else
                                            <tbody>
                                            @foreach($trades as $item)
                                                <tr>
                                                    <td><center><input type="checkbox" name="delete_trade_ids[]" value="{{ $item->id }}" onchange="toggleTradeToDelete(this)"></center></td>
                                                    <td>{{ $item->open_at }}</td>
                                                    <td>{{ optional($item->currency)->name }}</td>
                                                    <td>{{ $item->traded_amount }} USD</td>
                                                    <td>{{ $item->opening_price }} USD</td>
                                                    <td>{{ $item->closing_price }} USD</td>
                                                    <td>{{ $item->qty }} {{ optional($item->currency)->sym }}</td>

                                                    <td>
                                                        @if ($item->trade_type == 'buy')
                                                            <span class="badge badge-success p-2">Buy</span>
                                                        @else
                                                            <span class="badge badge-warning p-2">Sell</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($item->status == 0)
                                                            <span class="badge badge-warning p-2">Running</span>
                                                        @else
                                                            <span class="badge badge-danger p-2">Closed</span>
                                                        @endif
                                                    </td>
                                                    {{--                                        @if ($item->status == 1)--}}
                                                    {{--                                            <td>--}}
                                                    {{--                                                @if ($item->result === 2)--}}
                                                    {{--                                                    <span class="badge badge-danger p-2">loss</span>--}}
                                                    {{--                                                @elseif ($item->result === 1)--}}
                                                    {{--                                                    <span class="badge badge-success p-2">Won</span>--}}
                                                    {{--                                                @elseif ($item->result === 3)--}}
                                                    {{--                                                    <span class="badge badge-warning p-2">Draw</span>--}}
                                                    {{--                                                @endif--}}
                                                    {{--                                            </td>--}}
                                                    {{--                                        @else--}}
                                                    {{--                                            <td>--}}
                                                    {{--                                                <a  href="{{ route('trade.manual_close', $item->id) }}" class="btn btn-danger">Close Trade</a>--}}
                                                    {{--                                            </td>--}}
                                                    {{--                                        @endif--}}
                                                    <td>
                                                        {{--                                            @if ($item->profit)--}}
                                                        {{ amt($item->profit) }}
                                                        {{--                                            @else--}}
                                                        {{--                                                <span class="badge badge-danger p-2">Running</span>--}}
                                                        {{--                                            @endif--}}
                                                    </td>
                                                    <td>
                                                        @if($item->status === 0)
                                                            <a href="" data-toggle="modal" data-target="#editTrade{{ $item->id }}" class="btn btn-primary">Edit</a>
                                                        @else
                                                            <a href="" disabled="" data-toggle="modal" class="btn btn-warning">Closed</a>
                                                        @endif
                                                    </td>
                                                    @if (check_permission('delete-recent-trade'))
                                                    <td class="text-center">
                                                        {{-- <form method="POST" action="{!! route('admin.trades.destroy', $item->id) !!}" accept-charset="UTF-8">
                                                            <input name="_method" value="DELETE" type="hidden">
                                                            {{ csrf_field() }}

                                                            <div class="btn-group justify-center" role="group">
                                                                <button type="submit" class="btn btn-danger" title="Delete Job" onclick="return confirm(&quot;Click Ok to delete Record.&quot;)">
                                                                    <span class="fa fa-trash" aria-hidden="true"></span>
                                                                </button>
                                                            </div>

                                                        </form> --}}

                                                        <div class="btn-group justify-center" role="group">
                                                            <a href="{{ route('admin.users.trades.delete-one', $item->id) }}" class="btn btn-danger" title="Delete Job" onclick="return confirm(&quot;Click Ok to delete Record.&quot;)">
                                                                <span class="fa fa-trash" aria-hidden="true"></span>
                                                            </a>
                                                        </div>

                                                    </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        @endif


                                    </table>
                                </form>
                            </div><!-- table-wrapper -->

                            @foreach($p_trade as $item)
                                <div id="editTrade{{$item->id}}" class="modal fade">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content tx-size-sm">
                                            <div class="modal-header pd-x-20">
                                                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"> Modify trade opening rate to alter profit</h6>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('admin.user.updateTrade') }}" method="POST">

                                                <div class="modal-body pd-20">

                                                    @csrf
                                                    <div class="form-layout form-layout-1">
                                                        <div class="row mg-b-25">

                                                            <input name="id" type="hidden" value="{{ $item->id }}">

                                                            <div class="col-md-12">
                                                                <div class="form-group mg-b-10-force">
                                                                    <label class="form-control-label"> Current Opening Rate : [{{ $item->opening_price }}]</label>
                                                                    <input  value="{{ $item->opening_price }}" class="form-control" required type="number" step="any" name="opening_price" placeholder="opening_price">
                                                                </div>
                                                            </div><!-- col-8 -->
                                                            <div class="col-md-12">
                                                                <div class="form-group mg-b-10-force">
                                                                    <label class="form-control-label"> Current PNL: <span class="tx-danger">*</span></label>
                                                                    <input disabled value="{{ $item->profit }}" class="form-control" required type="number" step="any" name="profit" placeholder="PNL">
                                                                </div>
                                                            </div><!-- col-8 -->
                                                        </div>
                                                    </div>

                                                </div><!-- modal-body -->
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary tx-size-xs">Save</button>
                                                    <button type="button" class="btn btn-secondary tx-size-xs" data-dismiss="modal">Close</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div id="editTrade" ref="modal" class="modal fade">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content tx-size-sm">
                                        <div class="modal-header pd-x-20">
                                            <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"> Modify trade opening rate to alter profit</h6>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.user.updateTrade') }}" method="POST">

                                            <div class="modal-body pd-20">

                                                @csrf
                                                <div class="form-layout form-layout-1">
                                                    <div class="row mg-b-25" v-if="form">

                                                        <input name="id" type="hidden" :value="eId">

                                                        <div class="col-md-12">
                                                            <div class="form-group mg-b-10-force">
                                                                <label class="form-control-label"> Current Opening Rate : [@{{ form.opening_price }}]</label>
                                                                <input v-model="eOP"  :value="eOP" class="form-control" required type="number" step="any" name="opening_price" placeholder="opening_price">
                                                            </div>
                                                        </div><!-- col-8 -->

                                                        <div class="col-md-12">
                                                            <div class="form-group mg-b-10-force">
                                                                <label class="form-control-label"> Current PNL: <span class="tx-danger">*</span></label>
                                                                <input disabled v-model="form.profit" :value="form.profit" class="form-control" required type="number" step="any" name="profit" placeholder="PNL">
                                                                <em v-if="calculating">Calculating pnl .............</em>

                                                            </div>
                                                        </div><!-- col-8 -->
                                                    </div>
                                                </div>

                                            </div><!-- modal-body -->
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary tx-size-xs">Save</button>
                                                <button type="button" class="btn btn-secondary tx-size-xs" data-dismiss="modal">Close</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>


                        </div>






                </div><!-- tab-pane -->


                @if (check_permission('view-withdrawals'))
                    <div class="tab-pane fade" id="withdrawals">
                        <div class="card pd-20 pd-xs-30 shadow-base bd-0 m-2" id="actions-details">
                            <div class="row btn-bloc">
                        <a href="{{ route('admin.user.withdraw.toggle', $user->id) }}" class="btn {{ $user->can_withdraw ? 'btn-danger' : 'btn-success' }} mb-2">{{ $user->can_withdraw ? 'Disable' : 'Enable' }} Withdraw</a>
                        <a href="" data-toggle="modal" data-target="#fundWithdrawable" class="btn btn-info mb-2">Add Withdraw</a>
                            </div>
                        </div>

                        <div class="col-md-12">
                                <div class="card pd-20 pd-xs-30 shadow-base bd-0 mg-t-30 ">
                                    <div class="table-wrapper table-responsive">

                                    <table class="table datatabl table-bordered table-condensed display responsive nowrap">
                                        <thead>
                                            <tr>
                                                {{-- <th class="wd-5p">S.No</th> --}}
                                                {{-- <th class="wd-10p">User</th>--}}
                                                <th class="wd-20p">Order Type / Item</th>
                                                <th class="wd-10p">Currency Pair</th>
                                                <th class="wd-10p"> Buy at</th>
                                                <th class="wd-10p">Sell at</th>
                                                <th class="wd-10p">Item Price</th>
                                                <th class="wd-10p">Opening Price</th>
                                                <th class="wd-10p">Closing Price</th>
                                                <th class="wd-10p">Profit</th>
                                                {{-- <th></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($trades as $trade)
                                            <tr>
                                                {{-- <td>{{ $trade->id }}</td>--}}
                                                {{-- <td class="text-capitalize"><a href="{{ route('admin.users.show',$trade->user->username) }}"> {{ $trade->user->name }}</a></td>--}}
                                                <td>{{ $trade->order_type }}</td>
                                                <td>{{ $trade->currency_pair }}</td>
                                                <td>{{ $trade->buy_at }}</td>
                                                <td>{{ $trade->sell_at }}</td>
                                                <td>{{ $trade->item_price }}</td>
                                                <td>{{ $trade->opening_price }}</td>
                                                <td>{{ $trade->closing_price }}</td>
                                                <td>{{ $trade->profit }}</td>

                                                {{-- <td class="text-center">--}}
                                                {{-- <form method="POST" action="{!! route('admin.trades.destroy', $trade->id) !!}" accept-charset="UTF-8">--}}
                                                {{-- <input name="_method" value="DELETE" type="hidden">--}}
                                                {{-- {{ csrf_field() }}--}}

                                                {{-- <div class="btn-group justify-center" role="group">--}}
                                                {{-- <button type="submit" class="btn btn-danger" title="Delete Job" onclick="return confirm(&quot;Click Ok to delete Record.&quot;)">--}}
                                                {{-- <span class="fa fa-trash" aria-hidden="true"></span>--}}
                                                {{-- </button>--}}
                                                {{-- </div>--}}

                                                {{-- </form>--}}

                                                {{-- </td>--}}
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- table-wrapper -->
                        </div><!-- row -->
                    </div><!-- tab-pane -->
                @endif


            </div><!-- br-pagebody -->

            <div id="setNotification" class="modal fade">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content tx-size-sm">
                        <div class="modal-header pd-x-20">
                            <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Change Admin Notifications for {{ $user->name }}</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('admin.user.notifications') }}" method="POST">

                            <div class="modal-body pd-20">
                                <input name="user_id" type="hidden" value="{{ $user->id }}">
                                @csrf
                                <div class="form-layout form-layout-1">
                                    <div class="form-check">
                                        <input class="form-check-input" name="admin_notifications[]" type="checkbox" value="login" id="login" @if(in_array('login', $user->admin_notifications)) checked  @endif>
                                        <label class="form-check-label" for="login">
                                          Login
                                        </label>
                                      </div>
                                      <div class="form-check">
                                        <input class="form-check-input" name="admin_notifications[]" type="checkbox" value="open-trade" id="open-trade" @if(in_array('open-trade', $user->admin_notifications)) checked  @endif>
                                        <label class="form-check-label" for="open-trade">
                                          open trade
                                        </label>
                                      </div>
                                      <div class="form-check">
                                        <input class="form-check-input" name="admin_notifications[]" type="checkbox" value="close-trade" id="close-trade" @if(in_array('close-trade', $user->admin_notifications)) checked  @endif>
                                        <label class="form-check-label" for="close-trade">
                                          close trade
                                        </label>
                                      </div>
                                </div>

                            </div><!-- modal-body -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary tx-size-xs">Submit</button>
                                <button type="button" class="btn btn-secondary tx-size-xs" data-dismiss="modal">Close</button>
                            </div>
                        </form>

                    </div>
                </div><!-- modal-dialog -->
            </div><!-- modal -->

            <div id="fundWithdrawable" class="modal fade">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content tx-size-sm">
                        <div class="modal-header pd-x-20">
                            <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Update  {{ $user->name }} Withdrawable</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('admin.user.updatewithdrawable') }}" method="POST">

                            <div class="modal-body pd-20">

                                @csrf
                                <div class="form-layout form-layout-1">
                                    <div class="row mg-b-25">

                                        <input name="user_id" type="hidden" value="{{ $user->id }}">

                                        <div class="col-md-12">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label"> Withdrawable Amount: <span class="tx-danger">*</span></label>
                                                <input value="{{ $user->userInfo->withdrawable }}" class="form-control" required type="number" step="any" name="withdrawable" placeholder="Amount">
                                            </div>
                                        </div><!-- col-8 -->
                                    </div>
                                </div>

                            </div><!-- modal-body -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary tx-size-xs">Submit</button>
                                <button type="button" class="btn btn-secondary tx-size-xs" data-dismiss="modal">Close</button>
                            </div>
                        </form>

                    </div>
                </div><!-- modal-dialog -->
            </div><!-- modal -->

            <div id="fundBalance" class="modal fade">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content tx-size-sm">
                        <div class="modal-header pd-x-20">
                            <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Make Deposit / Subtract From Balance</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('admin.user.fundaccount') }}" method="POST">

                            <div class="modal-body pd-20">

                                @csrf
                                <div class="form-layout form-layout-1">
                                    <div class="row mg-b-25">

                                        <input name="user_id" type="hidden" value="{{ $user->id }}">
                {{--                                        <input name="type" type="hidden" value="deposit">--}}

                                        <div class="col-md-6">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label"> Fund Type: <span class="tx-danger">*</span></label>
                                                <select name="type" class="form-control" required>
                                                    <option value="deposit">Deposit</option>
                                                    <option value="bonus">Bonus</option>
                                                    <option value="withdrawal">Withdrawal</option>
                                                </select>
                                            </div>
                                        </div><!-- col-8 -->
                                        <div class="col-md-6">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label"> Notify Use Via Email:</label>
                                                <select name="notify" class="form-control" required>
                                                    <option value="0">No</option>
                                                    <option value="1">Yes</option>
                                                </select>
                                            </div>
                                        </div><!-- col-8 -->
                                        <div class="col-md-6">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label"> Amount: <span class="tx-danger">*</span></label>
                                                <input class="form-control" required type="number" step="any" name="amount" placeholder="Amount">
                                            </div>
                                        </div><!-- col-8 -->
                                        <div class="col-md-6">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label"> Source: <span class="tx-danger">*</span></label>
                                                <input class="form-control" required type="text" name="source" placeholder="Source">
                                            </div>
                                        </div><!-- col-8 -->
                                        <div class="col-md-12">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label"> Note [pls specify a note or default will be used] : <span class="tx-danger">*</span></label>
                                                <textarea class="form-control" type="text" name="note"></textarea>
                                            </div>
                                        </div><!-- col-8 -->
                                    </div>
                                </div>

                            </div><!-- modal-body -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary tx-size-xs">Submit</button>
                                <button type="button" class="btn btn-secondary tx-size-xs" data-dismiss="modal">Close</button>
                            </div>
                        </form>

                    </div>
                </div><!-- modal-dialog -->
            </div><!-- modal -->

            <div id="autoPL" class="modal fade">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content tx-size-sm">
                        <div class="modal-header pd-x-20">
                            <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Set Auto P/L for {{ $user->name }}</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('admin.user.modify_autopnl') }}" method="POST">

                            <div class="modal-body pd-20">

                                @csrf
                                <div class="form-layout form-layout-1">
                                    <div class="row mg-b-25">

                                        <input name="user_id" type="hidden" value="{{ $user->id }}">
                {{--                                        <input name="type" type="hidden" value="deposit">--}}

                                        <div class="col-md-6">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label">Status: <span class="tx-danger">*</span></label>
                                                <select name="status" class="form-control" required>
                                                    <option {{ $autoPNL->status == 1 ? 'selected' : '' }} value="1">Enable </option>
                                                    <option {{ $autoPNL->status == 0 ? 'selected' : '' }} value="0">Disable</option>
                                                </select>
                                            </div>
                                        </div><!-- col-8 -->
                                        <div class="col-md-6">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label">Profit / Loss</label>
                                                <select name="pnl" class="form-control" required>
                                                    <option {{ $autoPNL->pnl == 1 ? 'selected' : '' }} value="1">Profit</option>
                                                    <option {{ $autoPNL->pnl == 0 ? 'selected' : '' }} value="0">Loss</option>
                                                </select>
                                            </div>
                                        </div><!-- col-8 -->
                                        <div class="col-md-6">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label">Interval Type</label>
                                                <select name="interval_type" class="form-control" required>
                                                    <option {{ $autoPNL->interval_type == 'hour' ? 'selected' : '' }} value="hour">Hour</option>
                                                    <option {{ $autoPNL->interval_type == 'day' ? 'selected' : '' }} value="day">Daily</option>
                                                    <option {{ $autoPNL->interval_type == 'week' ? 'selected' : '' }} value="week">Weekly</option>
                                                    <option {{ $autoPNL->interval_type == 'month' ? 'selected' : '' }} value="month">Monthly</option>
                                                </select>
                                            </div>
                                        </div><!-- col-8 -->
                                        <div class="col-md-6">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label">Interval </label>
                                                <input class="form-control" value="{{ $autoPNL->interval }}" required type="number" step="any" name="interval" placeholder="Interval">
                                            </div>
                                        </div><!-- col-8 -->
                                        <div class="col-md-6">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label">Fixed / Percentage</label>
                                                <select name="fixed" class="form-control" required>
                                                    <option {{ $autoPNL->fixed == 'fixed' ? 'selected' : '' }} value="fixed">Fixed</option>
                                                    <option {{ $autoPNL->fixed == 'percent' ? 'selected' : '' }} value="percent">Balance Percent</option>
                                                </select>
                                            </div>
                                        </div><!-- col-8 -->
                                        <div class="col-md-6">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label"> Amount: <span class="tx-danger">*</span></label>
                                                <input class="form-control" value="{{ $autoPNL->amount }}" required type="number" step="any" name="amount" placeholder="Amount">
                                            </div>
                                        </div><!-- col-8 -->

                                        <div class="col-md-6">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label">Record Activity</label>
                                                <select name="record" class="form-control" required>
                                                    <option {{ $autoPNL->record == 1 ? 'selected' : '' }} value="1">Yes</option>
                                                    <option {{ $autoPNL->record == 0 ? 'selected' : '' }} value="0">No</option>
                                                </select>
                                            </div>
                                        </div><!-- col-8 -->


                                    </div>
                                </div>

                            </div><!-- modal-body -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary tx-size-xs">Submit</button>
                                <button type="button" class="btn btn-secondary tx-size-xs" data-dismiss="modal">Close</button>
                            </div>
                        </form>

                    </div>
                </div><!-- modal-dialog -->
            </div><!-- modal -->

            <div id="fundBonus" class="modal fade">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content tx-size-sm">
                        <div class="modal-header pd-x-20">
                            <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Add / Subtract {{ $user->name }} Account Bonus</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('admin.user.fundbonus') }}" method="POST">

                            <div class="modal-body pd-20">

                                @csrf
                                <div class="form-layout form-layout-1">
                                    <div class="row mg-b-25">

                                        <input name="account_type" type="hidden" value="bonus">
                                        <input name="user_id" type="hidden" value="{{ $user->id }}">

                                        <div class="col-md-6">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label"> Fund Type: <span class="tx-danger">*</span></label>
                                                <select name="type" class="form-control" required>
                                                    <option value="credit">Add Money</option>
                                                    <option value="debit">Subtract Money</option>
                                                </select>
                                            </div>
                                        </div><!-- col-8 -->
                                        <div class="col-md-6">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label"> Amount: <span class="tx-danger">*</span></label>
                                                <input class="form-control" required type="number" step="any" name="amount" placeholder="Amount">
                                            </div>
                                        </div><!-- col-8 -->
                                        <div class="col-md-12">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label"> Note : <span class="tx-danger">*</span></label>
                                                <textarea class="form-control" type="text" name="note"></textarea>
                                            </div>
                                        </div><!-- col-8 -->
                                    </div>
                                </div>

                            </div><!-- modal-body -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary tx-size-xs">Submit</button>
                                <button type="button" class="btn btn-secondary tx-size-xs" data-dismiss="modal">Close</button>
                            </div>
                        </form>

                    </div>
                </div><!-- modal-dialog -->
            </div><!-- modal -->

            <div id="planUpgrade" class="modal fade">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content tx-size-sm">
                        <div class="modal-header pd-x-20">
                            <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Upgrade {{ $user->name }} Account Plan</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('admin.user.upgradeplan') }}" method="POST">
                            <div class="modal-body pd-20">
                                @csrf
                                <div class="form-layout form-layout-1">
                                    <div class="row mg-b-25">

                                        <input name="user_id" type="hidden" value="{{ $user->id }}">

                                        <div class="col-md-12">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label"> Current Plan : <span class="tx-danger">*</span></label>
                                                <input class="form-control" type="text" value="{{ $user->plan }}" disabled>
                                            </div>
                                        </div><!-- col-8 -->

                                        <div class="col-md-12">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label"> Select Plan: <span class="tx-danger">*</span></label>
                                                <select name="plan" class="form-control" required>
                                                    @foreach(\App\Models\Package::all() as $item)
                                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div><!-- col-8 -->



                                    </div>
                                </div>

                            </div><!-- modal-body -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary tx-size-xs">Submit</button>
                                <button type="button" class="btn btn-secondary tx-size-xs" data-dismiss="modal">Close</button>
                            </div>
                        </form>

                    </div>
                </div><!-- modal-dialog -->
            </div><!-- modal -->

            <div id="connectTrader" class="modal fade">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content tx-size-sm">
                        <div class="modal-header pd-x-20">
                            <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Send {{ $user->name }} Autotrader request</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('admin.connect.account') }}" method="POST">
                            <div class="modal-body pd-20">
                                @csrf
                                <div class="form-layout form-layout-1">
                                    <div class="row mg-b-25">

                                        <input name="user_id" type="hidden" value="{{ $user->id }}">

                                        <div class="col-md-12">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label"> Select Autotraders: <span class="tx-danger">*</span></label>
                                                <select name="trader_id" class="form-control" required>
                                                    @foreach(\App\Models\User::whereRoleIs('autotrader')->latest()->get() as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div><!-- col-8 -->
                                        <div class="col-md-12">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label"> Send Email to user: <span class="tx-danger">*</span></label>
                                                <select name="sendmail" class="form-control" required>
                                                    <option value="0">No</option>
                                                    <option value="1">Yes</option>
                                                </select>
                                            </div>
                                        </div><!-- col-8 -->

                                        <div class="col-md-12">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label"> Message [change admin to auto trader name] : <span class="tx-danger">*</span></label>
                                                <textarea class="form-control" type="text" name="message">Admin is trying to connect to your account, approve to grant access, once you accept this, Admin will trade on your behave</textarea>
                                            </div>
                                        </div><!-- col-8 -->

                                    </div>
                                </div>

                            </div><!-- modal-body -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary tx-size-xs">Send </button>
                                <button type="button" class="btn btn-secondary tx-size-xs" data-dismiss="modal">Close</button>
                            </div>
                        </form>

                    </div>
                </div><!-- modal-dialog -->
            </div><!-- modal -->


            @foreach($trades as $item)
            <div id="editTrade{{$item->id}}" class="modal fade">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content tx-size-sm">
                        <div class="modal-header pd-x-20">
                            <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold"> Modify trade {{ $item->id }} => {{ amt($item->traded_amount) }}</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('admin.user.updateTrade') }}" method="POST">

                            <div class="modal-body pd-20">

                                @csrf
                                <div class="form-layout form-layout-1">
                                    <div class="row mg-b-25">

                                        <input name="id" type="hidden" value="{{ $item->id }}">

                                        <div class="col-md-12">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label"> Outcome: <span class="tx-danger">*</span></label>
                                                <select name="outcome" class="form-control" required>
                                                    <option value="1">Won</option>
                                                    <option value="0">Lost</option>
                                                </select>
                                            </div>
                                        </div><!-- col-8 -->
                                        <div class="col-md-12">
                                            <div class="form-group mg-b-10-force">
                                                <label class="form-control-label"> PNL: <span class="tx-danger">*</span></label>
                                                <input value="{{ $item->profit }}" class="form-control" required type="number" step="any" name="profit" placeholder="PNL">
                                            </div>
                                        </div><!-- col-8 -->
                                    </div>
                                </div>

                            </div><!-- modal-body -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary tx-size-xs">Save</button>
                                <button type="button" class="btn btn-secondary tx-size-xs" data-dismiss="modal">Close</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div><!-- br-mainpanel -->
                </div>
            </div>
        </div>
    </div>

@endsection


@section('js')
    @include('admin.partials.dt-js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var transactionsToDelete = [];
        function toggleTransactionToDelete(obj) {
            if (obj.checked) transactionsToDelete.push(obj.value)
            else transactionsToDelete.splice(transactionsToDelete.indexOf(obj.value), 1);

            var transactionCount = transactionsToDelete.length;
            if (transactionCount > 0) {
                // show delete multiple button
                $('.delete-multiple-transactions').show().html(`Delete ${transactionsToDelete.length} ${transactionCount > 1 ? 'Transactions' : 'Transaction'}`);
                $('.delete-transaction-btn').hide();
            } else {
                $('.delete-multiple-transactions').hide();
                $('.delete-transaction-btn').show();

            }
        }
         function select_tap(){

         }




        $('.delete-multiple-transactions').click(function (e) {
            e.preventDefault();
            deleteTransactions()
        });

        function deleteTransactions (id = null) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log(id)
                    if (id == null) {
                        $('#deleteTransactionForm').submit();
                        console.log('delete multiple')
                    } else {
                        // delete single transaction
                        // var transactionId = 2
                        $('#deleteTransactionForm').attr('action', `/admin/users/transactions/${id}/delete`).submit();
                        console.log('single multiple')
                    }
                }
            })
        }


        $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
            $('.show  table').DataTable().destroy();

            $.fn.dataTable.moment( 'DD.MM.YYYY' );
            $('.show  table').DataTable({
                iDisplayLength : 10,
                responsive: true,
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: '',
                    lengthMenu: '_MENU_ items/page',
                },
                columnDefs: [
                    {
                        "orderSequence": ["desc", "asc"],
                        type: 'numeric-comma', targets: 0
                    }
                ],
            });
        })

    </script>









    <script>
        var tradesToDelete = [];
        function toggleTradeToDelete(obj) {
            if (obj.checked) tradesToDelete.push(obj.value)
            else tradesToDelete.splice(tradesToDelete.indexOf(obj.value), 1);

            var tradeCount = tradesToDelete.length;
            if (tradeCount > 0) {
                // show delete multiple button
                $('.delete-multiple-trades').show().html(`Delete ${tradesToDelete.length} ${tradeCount > 1 ? 'Trades' : 'Trade'}`);
                $('.delete-trade-btn').hide();
            } else {
                $('.delete-multiple-trades').hide();
                $('.delete-trade-btn').show();

            }
        }

        $('.delete-multiple-trades').click(function (e) {
            e.preventDefault();
            deleteTrades()
        });

        function deleteTrades (id = null) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log(id)
                    if (id == null) {
                        $('#deleteTradeForm').submit();
                        console.log('delete multiple')
                    } else {
                        // delete single trade
                        // var tradeId = 2
                        $('#deleteTradeForm').attr('action', `/admin/users/trades/${id}/delete`).submit();
                        console.log('single multiple')
                    }
                }
            })
        }
    </script>


    <script>
         function show_section(element){
             $($(element).attr('href')).addClass('show active')
             $($(element).attr('href')).siblings().removeClass('show active')

         }
        {{--let checkGameUrl = "{{route('user.api.practice.trade.check')}}";--}}
        {{--let gsettings =  "{{ $gameSettings }}";--}}
        let coin = '@json(App\Models\CurrencyPair::first()->id)'
        let userId = "{{ $user->id }}"

        let c_type = "{{ $c_type }}"

        let updateTrade = "{{ route('admin.user.updateTrade') }}"

        let currentUrl = "{{ request()->url() }}?user={{ $user->id }}"

        let int = "{{ setting('api_interval',1000) }}"

        {{--let store_url = {{ route('admin.trades.store') }}--}}
        {{--let demoGameResultUrl = "{{ route('user.demo.game.result') }}";--}}
        document.addEventListener('DOMContentLoaded', function () {
            new Vue({
                el: '#photos',
                data() {
                    return {
                        name : 'crypo',
                        asset : '',
                        eId : null,
                        calculating : false,
                        eOP:null,
                        interval : 10000,
                        form : null,
                        all_trades : [],
                        data : {
                            coin_id : '',
                            a_type : c_type,
                            value : 0,
                            volume : 0,
                            amount : 0,
                            type : 'buy',
                            leverage : 0,
                            is_stop_loss:0,
                            is_take_profit:0,
                            stop_loss:100,
                            take_profit:100,
                            user_id : userId,
                        },
                        coinPrice : 0,
                        currency : null
                    }
                },
                mounted() {
                    this.data.coin_id = coin;
                    this.interval = parseInt(int) + 1000;
                    this.getCoin(coin);
                    this.getAllTrades();
                },
                methods : {
                    updateStatus(item){
                        this.getCoin(item)
                    },
                    editTrade(item){
                        this.edit = true;
                        this.calculating = false;
                        this.eId = item.id;
                        this.eOP = item.opening_price;
                        this.form = item
                        let element = this.$refs.modal
                        $(element).modal('show');
                    },
                    startUpdate(){
                        console.log('updating interval .... ='+this.interval)
                        this.updateProfits();
                        this.timer = setInterval(() => {
                            this.updateProfits();
                        }, this.interval)
                    },

                    updateProfits(){
                        axios.get('/admin/update/profit/'+userId).then((res) => {
                            this.all_trades = res.data;
                        })
                    },
                    updateTrade(){
                        this.calculating = true
                        axios.post(updateTrade, { id : this.eId, opening_price : this.eOP, api : true}).then((res) => {
                            this.form.profit = res.data.profit;
                            this.calculating = false
                        }).catch(()=>{
                            this.calculating = false
                        })
                    },
                    getCurPrice(){
                        axios.get('/api/cur/rate/'+this.currency.sym+'/'+this.currency.base+'/'+this.currency.type).then((res) =>{
                            this.coinPrice = res.data;
                            this.data.value = this.coinPrice;
                        })
                    },

                    getAllTrades(){
                        axios.get('/admin/get/all_trades/'+userId).then((res) =>{
                            this.all_trades = res.data;
                            if(this.all_trades.length > 0){
                                this.startUpdate();
                            }
                        })
                    },
                    getCoin(coin){
                        axios.get('/admin/get/coin/'+coin).then((res) =>{
                            this.coinPrice = res.data.rate;
                            this.data.value = res.data.rate;
                            this.currency = res.data;
                        })
                    },

                },

                computed : {
                    assets(){

                    },
                    aType(){
                        return this.data.a_type;
                    },
                    vVolume(){
                        return this.data.volume;
                    },
                    openingRate(){
                        if(this.form){
                            return this.form.opening_price;
                        }
                    },
                    amount(){
                        return this.data.amount;
                    }
                },

                watch: {
                    eOP(){
                        this.updateTrade();
                    },
                    vVolume(){
                        this.data.amount = (this.data.volume * this.coinPrice) / this.currency.leverage;
                    },
                    // amount(){
                    //     this.data.volume = this.data.amount / this.coinPrice;
                    // },
                    aType(){
                        let new_url = currentUrl+'&asset='+this.data.a_type
                        window.location = new_url;
                    },
                    openingRate(){
                        console.log(this.form)
                    },
                    currency() {
                        this.data.leverage = this.currency.leverage;
                        this.getCurPrice();
                    },
                }
            })
        })
    </script>

<script>
    $(document).ready(function() {
        $('.but_delete_action').click(function( event ) {
            if (confirm('Are you sure you want to delete the item?')) {
                var deleteId = $(this).data('delete');
                document.getElementById(deleteId).submit();
            }
        });
    });
</script>

@endsection
