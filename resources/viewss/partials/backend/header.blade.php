<!-- START: Header-->

<div id="header-fix" class="header fixed-top">
{{--    @if(auth()->user()['new_password']==1)--}}
{{--        <div style="background: #FF7700" class="text-center text-white py-2">{{setting('message_new_password')}}</div>--}}
{{--    @endif--}}
    <nav class="navbar navbar-expand-xl p-0">
        <div class="navbar-header h4 mb-0 align-self-center d-flex">
            <a href="#" class="sidebarCollapse ml-2 " id="collapse"><i
                    class="icon-menu body-color"></i></a>
             <a href="{{ env('SITE_URL') }}" class="horizontal-logo align-self-center d-flex ">
             <img src="{{ setting('logo','asset/images/logosm.png') }}" alt="logo" width="85px" class="img-fluid"/>
             </a>
        </div>


        <div class="d-inline-block position-relative">
            <button data-toggle="modal" data-target=".bd-example-modal-lg"
                class="btn btn-primary p-2  mx-3 h6 mb-0 line-height-1 d-none d-lg-block">
                <span class="text-white font-weight-bold h6">DEPOSIT</span>
            </button>
        </div>

        <!-- START: Logo-->
        <a href="{{ env('SITE_URL') }}" class="sidebar-logo d-none d-md-block d-flex ml-3">
        <img src="{{ setting('logo','/asset/images/logosm.png') }}" alt="logo" width="70" class="m-1 p-0 img-fluid main-logo"/>
        {{-- <span class="h5 align-self-center mb-0">360Invest</span> --}}
        </a>
        <!-- {{-- <span class="h5 align-self-center m-0">
           <img src="{{ setting('logo','asset/images/logosm.png') }}" alt="" width="50" class="m-1 p-0 img-fluid">
       </span> --}} -->



        <!-- END: Logo-->

        <div class="row crypto-row align-items-center pr-3 pl-2 ml-0">

            <div class="modal fade" id="showwatchlist" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">


                    @php
                        $assets_id = auth()->user()->watchlists()->get()->pluck('currency_pair_id')->toarray();

                    $assets = \App\Models\CurrencyPair::where('rate', '!=', 0.0)
                                ->wherein("id",$assets_id)
                                ->where('disabled', 0)
                                ->get();
                    @endphp

                    <all_assets tradestation="{{ route('backend.tradestation') }}" :key="watchlist" id="watchlist" i="watchlist"
                                :asset='@json($assets)' path="{{asset('icon/')}}" my_asset="{{json_encode($assets_id)}}" url="{{ route('backend.add_new_wach.store') }}" types="{{ json_encode($types) }}" ></all_assets>




                </div>


            </div>




            @foreach ($types as $i)
                <div class="asset-item d-none">
                    {{-- <div class="card-body" @click="loadCur({{$item}})"> --}}
                    <a href="#" data-toggle="modal" data-target="#show{{ $i }}">
                        {{-- <a href="#"> --}}
                        {{-- <img style="max-height: 20px; max-width: 20px" src="" alt="account balance" class="float-left " /> --}}
                        {{ $i }} &nbsp;
                        <i class="fa fa-angle-down hidden-sm"
                           style="color: rgba(255, 255, 255, 0.7; font-weight: 600;"></i>
                    </a>
                </div>

                <div class="modal fade" id="show{{ $i }}" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

                        @php
                            $assets = \App\Models\CurrencyPair::where('rate', '!=', 0.0)
                                ->where('type', $i)
                               // ->where('disabled', 0)
                                ->get();
                        @endphp

                                <all_assets tradestation="{{ route('backend.tradestation') }}" url="{{ route('backend.add_new_wach.store') }}"  :key="{{ $i }}" id="{{ $i }}" i="{{ $i }}"
                                            :asset='@json($assets)' path="{{asset('icon/')}}" my_asset="{{json_encode($assets_id)}}" types="{{ json_encode($types) }}" ></all_assets>




                    </div>


                </div>

            @endforeach
        </div>


        <form class="float-left d-none d-lg-block search-form">
            <div class="form-group mb-0 position-relative">
                <input type="text" class="form-control border-0 rounded bg-search pl-5"
                    placeholder="Search anything...">
                <div class="btn-search position-absolute top-0">
                    <a href="#"><i class="h5 icon-magnifier body-color"></i></a>
                </div>
                <a href="#" class="position-absolute close-button mobilesearch d-lg-none" data-toggle="dropdown"
                    aria-expanded="false"><i class="icon-close h5"></i>
                </a>

            </div>
        </form>
        <div class="dropdown">
  <button class=" asset_list " type="button" id="dropdownMenuButton"  data-toggle="modal" data-target="#showcrypto">
      Market List
      <i class="fa fa-caret-down"></i>
  </button>
{{--  <div class="dropdown-menu asset_asset_list" aria-labelledby="dropdownMenuButton">--}}


{{--    @foreach ($types as $i)--}}

{{--                    <a href="#" class="dropdown-item" data-toggle="modal" data-target="#show{{ $i }}">--}}
{{--                        --}}{{-- <a href="#"> --}}
{{--                        --}}{{-- <img style="max-height: 20px; max-width: 20px" src="" alt="account balance" class="float-left " /> --}}
{{--                        {{ $i }} &nbsp;--}}
{{--                        <i class="fa fa-angle-down hidden-sm"--}}
{{--                           style="color: rgba(255, 255, 255, 0.7; font-weight: 600;"></i>--}}
{{--                    </a>--}}
{{--                    @endforeach--}}



{{--  </div>--}}
        </div>

        <div class="dropdown d-sm-none d-md-none d-lg-none" style="display: none">
            <a href="#" aria-expanded="false" data-toggle="modal" data-target=".bd-example-modal-lg">
                <h5 class="ml-3 mb-0" style="color: #27B254; font-weight: bold; font-family:'Trebuchet MS';">
                    {{ amt(auth()->user()->balance) }}</h5>
            </a>
            <div class="dropdown-menu">
                <a href="#" class="dropdown-item px-2 align-self-center btn_orang d-flex d-md-none d-lg-none" data-toggle="modal"
                    data-target=".bd-example-modal-lg">
                    Deposit
                </a>
            </div>
        </div>

        <div class="navbar-right ml-auto">
            <ul class="ml-auto p-0 m-0 list-unstyled d-flex align-items-center">
                <!-- <li class="dropdown hidden-sm">
                    <a
                        href="#"
                        class="nav-link px-2 ml-3 mb-0"
                        data-toggle="dropdown"
                        aria-expanded="false"
                        style="color: #27B254; font-weight: bold; font-size: 1.25rem;"
                    >
                        {{-- {{ amt(auth()->user()->balance) }} --}}
                        <i class="fa fa-angle-down" style="color: #215f4e; font-weight: 600;"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-right border py-0">
                        <li><a class="dropdown-item text-center py-2" href="{{ route('backend.deposits.index') }}">Deposit</a></li>
                        <li><a class="dropdown-item text-center py-2" href="{{ route('backend.withdraw.select') }}">Withdrawal</a></li>
                    </ul>
                </li> -->



                    @auth()
                        @if (check_permission('login-manger'))
                            <li class="hidden-sm">
                                <a target="_blank" href="{{ route('admin.dashboard') }}"
                                   class="btn btn-warning   mx-3 h6 mb-0 line-height-1 text-black-50" style="padding: 5px 20px">
                                    Switch to CRM
                                </a>
                            </li>
                        @endif
                    @endauth
                @if (setting('kyc_verify_button') && auth()->user()->identity->status != 'approved')
                    <li class="hidden-sm">
                        <a href="{{ route('backend.profile.upload.id') }}"
                            class="btn btn-outline-danger text-white mx-3 h6 mb-0 line-height-1" style="padding: 10px 20px;">
                            @if(!auth()->user()->identity->front && auth()->user()->identity->status == 'pending')
                                Verify Your Account
                            @elseif(auth()->user()->identity->status == 'pending')
                                Pending Approval
                            @elseif(auth()->user()->identity->status == 'disapproved')
                                DISAPPROVED!
                            @endif
                        </a>
                    </li>
                @endif

                @if (setting('autotrader'))
                    <li class="hidden-sm">

                        @if (auth()->user()->trader_request == 'accepted' && auth()->user()->manager_id)
                            <a href="{{ route('backend.profile.view', auth()->user()->manager_id) }}"
                                class="btn text-white btn-primary btn-deposit mx-3 h6 mb-0 line-height-1"
                                style="padding: 10px 20px;">
                                <i class="fa fa-user"></i> &nbsp; {{ auth()->user()->account_officer }}
                            </a>
                        @else
                            <button class="btn btn-primary btn-deposit mx-3 h6 mb-0 line-height-1"
                                style="padding: 10px 20px;">
                                <i class="fa fa-user"></i> &nbsp; {{ auth()->user()->account_officer }}
                            </button>
                        @endif
                    </li>
                @endif
{{--                <li>--}}
{{--                    <div id="google_translate_element"></div>--}}
{{--                </li>--}}
                    <li class="dropdown user-profile d-inline-block py-1 mr-2">
                        <a href="#" class="nav-link px-2 py-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="media">
                                <div class="media-body align-self-center d-none d-sm-block mr-2">
                                    <p class="mb-0 text-uppercase line-height-1 text-capitalize">
                                        <b>{{ auth()->user()->first_name }}</b><br /><span> </span></p>

                                </div>
                                <img src="{{ auth()->user()->avatar }}" alt="" class="d-flex img-fluid rounded-circle"
                                     style="width: 47px;height: 47px">
                                <span class="d-sm-none d-md-inline-block" style="font-size: 16px; color: #fff;margin: 10px;">
                                    {{ auth()->user()->first_name ." ". auth()->user()->last_name }}
                                    <i class="fa fa-caret-down"></i>

                                </span>


                            </div>
                        </a>

                        <div class="dropdown-menu  dropdown-menu-right p-0">
                            <a href="{{ route('backend.withdraw.select') }}"
                               class="dropdown-item px-2 align-self-center d-flex">
                                <span class="icon-minus mr-2 h6 mb-0"></span> Withdraw</a>

                        <!--<a href="{{ route('backend.profile.edit') }}"-->
                            <!--    class="dropdown-item px-2 align-self-center d-flex">-->
                            <!--    <span class="icon-pencil mr-2 h6 mb-0"></span> Edit Profile</a>-->

                            <a href="{{ route('backend.profile.edit') }}"
                               class="dropdown-item px-2 align-self-center d-flex">
                                <span class="icon-pencil mr-2 h6 mb-0"></span> Edit Profile</a>
                            <a href="{{ route('backend.account.overview') }}"
                               class="dropdown-item px-2 align-self-center d-flex">
                                <span class="icon-user mr-2 h6 mb-0"></span> View Profile</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class=" dropdown-item px-2 align-self-center d-flex d-md-none d-lg-none"
                               data-toggle="modal" data-target=".bd-example-modal-lg">
                                Deposit
                            </a>
                        <!-- <a href="{{ setting('help_url', '#') }}" class="dropdown-item px-2 align-self-center d-flex">
                            <span class="icon-support mr-2 h6  mb-0"></span> Help Center</a>-->
                            <a href="{{ route('backend.account.security') }}"
                               class="dropdown-item px-2 align-self-center d-flex">
                                <span class="icon-settings mr-2 h6 mb-0"></span> KYC </a>
                            <a data-toggle="modal" data-target="#update_bassword_model"
                               class="dropdown-item px-2 align-self-center d-flex">
                                <span class="icon-settings mr-2 h6 mb-0"></span> Change Password</a>
                            <div class="dropdown-divider"></div>

                            <a onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                               href="{{ route('logout') }}"
                               class="dropdown-item px-2 text-danger align-self-center d-flex">
                                <span class="icon-logout mr-2 h6  mb-0"></span> Sign Out
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                                @isset($type)
                                    <input type="text" hidden name="logoutType" id="logoutType" value="{{ $type }}">
                                    <input type="text" hidden name="adminID" id="adminID" value="{{ $admin_id }}">
                                @endisset
                            </form>
                        </div>

                    </li>

                <li class="dropdown align-self-center mr-1 d-inline-block">
                    <a href="#" class="nav-link px-2" data-toggle="dropdown" aria-expanded="false">
                        <img src="{{asset('icon/Group5620.svg')}}">
                         <span class="badge badge-default"> <span class="ring">
                            </span><span class="ring-point">
                            </span> </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right border   py-0">
                        <li><a class="dropdown-item text-center py-2" href="#"> <strong>No Notification </strong></a>
                        </li>
                    </ul>
                </li>


                    <li class="py-3 hidden-sm text-success bal text-right d-sm-none d-md-inline-block" style="font-size: 17px; letter-spacing: 1px;">

{{--                        <button class="btn btn-primary mx-3 h6 mb-0 line-height-1" data-toggle="modal"--}}
{{--                                data-target=".bd-example-modal-lg" style="padding: 10px 20px;">--}}

                        <span class="bonus"> <span style="font-size: 15px;color: #9a9a9a;font-weight: 400;">Bonus</span> :
                            <span class="bonus_fig" >{{ amt(auth()->user()->bonus) }}</span></span><br/>
                          <img src="{{ setting('site_logo') }}" /> <span style="font-size: 18px">
                            {{ amt(auth()->user()->balance) }}
                        </span>
{{--                        </button>--}}
                    </li>

                    <li class="hidden-sm d-sm-none d-md-inline-block">
                        <button class="btn btn-primary mx-3 h6 mb-0 line-height-1 btn_orang" data-toggle="modal"
                                data-target=".bd-example-modal-lg" style="padding: 5px 20px !important;">
                           <img src="{{asset('icon/refund-2.svg')}}">
                            Deposit
                        </button>
                    </li>


            </ul>
        </div>
    </nav>
    <div class="d-sm-block d-md-none border-bottom" style="padding: 7px">
        <div class="row">
            <div class=" col text-success bal text-center" style="font-size: 17px; letter-spacing: 1px; padding: 5px">

                {{--                        <button class="btn btn-primary mx-3 h6 mb-0 line-height-1" data-toggle="modal"--}}
                {{--                                data-target=".bd-example-modal-lg" style="padding: 10px 20px;">--}}

                <span class="bonus" style="float: none"> <span style="font-size: 15px; color: #9A9A9A;font-weight: 600;">Bonus</span> :
                    <span class="bonus_fig">{{ amt(auth()->user()->bonus) }}</span></span><br/>
                <img src="{{ setting('site_logo') }}" /> <span style="font-size: 18px">
                    {{ amt(auth()->user()->balance) }}
                </span>
                {{--                        </button>--}}
            </div>

            <div class="col text-center">
                <button class="btn btn-primary mx-3 h6 mb-0 line-height-1 btn_orang" data-toggle="modal"
                        data-target=".bd-example-modal-lg" style="padding: 9px 52px; !important;">
                    <img src="{{asset('icon/refund-2.svg')}}">
                    Deposit
                </button>
            </div>
        </div>



    </div>
</div>
<!-- END: Header-->
@if(auth()->user()['new_password']==1)
<div class="modal fade show"  style="display: block" id="new_password" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLongTitle1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="card">
                    <div class="card-body" style="margin-top: 10px; padding : 5px!important">

                        <div class="mx-3">
                            <h5 class="modal-title text-center" id="exampleModalLongTitle2"> Welcome {{ auth()->user()['name'] }}
                                In {{setting('site_name')}}
                            </h5>


<h5 class="text-center">{{setting('message_new_password')}}</h5>

                            <div class="id_info">
                                <form method="POST" action="{{route('backend.update_pass')}}">
                                 @csrf



                                    <div class="form-group">
                                        <input id="password" type="hidden" value="{{setting('new_password')}}" required="" class="form-control" name="current_password" placeholder="Current Password" autocomplete="current-password">
                                    </div>




                                    <div class="form-group">
                                        <input id="new_password" type="password" required="" class="form-control" placeholder="New Password" name="new_password" autocomplete="current-password">
                                    </div>



                                    <div class=" form-group">
                                        <input id="new_confirm_password" required="" type="password" placeholder="New Confirm Password" class="form-control" name="new_confirm_password" autocomplete="current-password">
                                    </div>


                                    <div class="form-group row mb-0">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary" style="width: 100%">
                                                Change Password
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
