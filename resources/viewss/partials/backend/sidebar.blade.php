
<div class="sidebar">
    <a href="#"
        class="sidebarCollapse float-right h6 dropdown-menu-right mr-2 mt-2 position-absolute d-block d-sm-none d-md-none d-lg-none">
        <i class="icon-close"></i>
    </a>
    <!-- START: Logo-->
    <!-- <a href="{{ env('SITE_URL') }}" class="sidebar-logo d-flex d-sm-none d-md-none d-lg-none">
        <img src="{{ setting('favicon', 'asset/images/logosm.png') }}" alt="logo" width="100" class="img-fluid mr-2"/> -->
    <a href="{{ setting('site_info_url', '/') }}" class="sidebar-logo d-flex d-sm-none d-md-none d-lg-none">
        <img src="{{ setting('logo', '/asset/images/logosm.png') }}" alt="logo" width="100" class="img-fluid mr-2" />
        {{-- <span class="h5 align-self-center mb-0">360Invest</span> --}}
    </a>
    <!-- END: Logo-->

    <!-- START: Menu-->
    <ul id="side-menu" class="sidebar-menu" style="height: auto;margin-bottom: 70px">


        @if (auth()->user()->hasRole('dev'))
            <li class="{{ active('backend.dashboard') }}"><a href="{{ route('backend.dashboard') }}"><i
                        class="icon-speedometer"></i>Dashboard</a></li>

            @if (setting('trade'))
            <li class="{{ active('backend.tradestation') }}"><a href="{{ route('backend.tradestation') }}"><i
                        class="icon-chart"></i>Trade Station</a></li>
            @endif
        @else
            @if (setting('trade'))
            <li class="{{ active('backend.tradestation') }}"><a href="{{ route('backend.tradestation') }}">
                    <img style="margin-bottom: 7px" src="{{asset('icon/Group65.svg')}}">
                    Trade Station</a></li>
        @endif
        @endif

        @if (setting('trade'))
            <li class="{{ active('backend.trades.index') }}">
                @php
                $count = \App\Models\Trade::whereUserId(auth()->id())->whereStatus(0)->count();
                @endphp


                <a href="{{ route('backend.trades.index') }}">
{{--                    <update_user :trades="{{ $count }}"></update_user>--}}

                    <img style="margin-bottom: 7px" src="{{asset('icon/Group67.svg')}}">

                    Order Book

                </a></li>
{{--            <li class="{{ active('backend.finances.index') }}"><a href="{{ route('backend.finances.index') }}"><i--}}
{{--                        class="fa fa-money-bill"></i> Finances</a></li>--}}
            {{-- <li class="{{ active('backend.tradestation') }}"><a href="{{ route('backend.tradestation') }}"><i class="fa fa-mail-bulk"></i>Trade Station</a></li> --}}
            <li class="{{ active('backend.markets') }}"><a href="{{ route('backend.markets') }}">
                    <img style="margin-bottom: 7px" src="{{asset('icon/Group73.svg')}}">
                    Market</a></li>

                <li class="{{ active('backend.trade.plans') }}"><a href="{{ route('backend.trade.plans') }}">
                        <img style="margin-bottom: 7px" src="{{asset('icon/Group71.svg')}}">
                        Trading Plans</a></li>
        @endif
        {{-- <li class=""><a href="#"><i class="icon-grid"></i>Board</a></li> --}}


        @if (setting('exchange'))
                <li class="{{ active('backend.plans') }}"><a href="{{ route('backend.plans') }}"><i
                            class="fa fa-cubes"></i>Crypto Exchange</a></li>
            @endif

        @if (setting('invest'))
                <li class="{{ active('backend.investments') }}"><a href="{{ route('backend.investments') }}"><i
                            class="icon-chart"></i>Investments</a></li>

                <li class="{{ active('backend.plans') }}"><a href="{{ route('backend.plans') }}"><i
                            class="fa fa-cubes"></i>Investment Plans</a></li>


        @endif

            <li class="{{ active('backend.transactions') }}"><a href="{{ route('backend.transactions') }}">
                    <img style="margin-bottom: 7px" src="{{asset('icon/Group78.svg')}}">
                    Transaction History</a></li>


            {{-- <li class=""><a href="{{ route('backend.trades.index') }}"><i class="icon-chart"></i>Trades</a></li> --}}
{{--        <li class="{{ active('backend.withdraw.select') }}"><a href="{{ route('backend.withdraw.select') }}"><i--}}
{{--                    class="icon-support"></i>Withdraw</a></li>--}}
{{--        <li class="{{ active('backend.deposits.index') }}"><a href="{{ route('backend.deposits.index') }}"><i--}}
{{--                    class="far fa-bookmark"></i>Deposits</a></li>--}}
        <!-- <li class="dropdown"><a href="#"><i class="icon-user"></i>Overview</a>
            <div>
                <ul>
                    <li><a href="{{ route('backend.account.overview') }}"><i class="icon-people"></i> Profile Overview </a></li>
                    <li><a href="{{ route('backend.profile.edit') }}"><i class="fas fa-edit"></i> Edit Profile </a></li>
                    <li><a href="{{ route('backend.account.security') }}"><i class="icon-cursor-move"></i> Security Setting</a></li>
                    <li><a href="{{ route('backend.account.overview') }}"><i class="icon-user"></i> Account Plan</a></li>

                </ul>
            </div>
        </li> -->
{{--        <li class=""><a href="#"><i class="icon-envelope"></i>Notifications</a></li>--}}
        @if (setting('autotrader'))
            <li class=""><a href="{{ route('backend.autotraders') }}">
                    <i class="icon-user"></i>Auto Traders</a></li>
        @endif


{{--                <li class=""><a href="{{ route('backend.account.overview') }}">--}}
{{--                        <i class="icon-user"></i>Profile</a></li>--}}


{{--            @if (setting('multi_lang'))--}}
{{--                <li class=""><a href="#">--}}
{{--                        <i class="icon-flag"></i>English</a></li>--}}
{{--            @endif--}}

{{--            @if (setting('referrals'))--}}
{{--                <li class="{{ active('backend.refer') }}"><a href="{{ route('backend.refer') }}">--}}
{{--                        <img style="margin-bottom: 7px" src="{{asset('icon/Group71.svg')}}">Referrals</a></li>--}}
{{--            @endif--}}

    </ul>
    <!-- END: Menu-->
</div>
