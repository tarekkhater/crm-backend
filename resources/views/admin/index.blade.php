@extends('admin.layouts.admin-app')

@section('style')
    <link href="{{ asset('lib/rickshaw/rickshaw.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/chartist/chartist.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="br-mainpanel">
        <div class="pd-30">
            {{-- <h4 class="tx-gray-800 mg-b-5">{{ setting('site_name') }}</h4> --}}
            {{-- <p class="mg-b-0">Do big things with Bracket, the responsive bootstrap 4 admin template.</p> --}}
        </div><!-- d-flex -->

        <div class="br-pagebody mg-t-5 pd-x-30">
            <div class="row row-sm">
                @if(check_permission('count-customer'))

                <div class="col-sm-6 col-xl-4 mt-2">
                    <div class="bg-primary rounded overflow-hidden">
                        <a href="@if(check_permission('only-active')){{route('admin.users.activ-users')}} @else {{ route('admin.users.index') }} @endif">

                            <div class="pd-25 d-flex align-items-center">
                                <i class=" fa fa-users tx-60 lh-0 tx-white op-7"></i>
                                <div class="mg-l-20">
                                    <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-10">Customers
                                    </p>
                                    <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1">{{ $users }}</p>
                                    {{-- <span class="tx-11 tx-roboto tx-white-6">Total Job Posted</span> --}}
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                @endif
                @if (check_permission('online-users'))
                <div class="col-sm-6 col-xl-4 mt-2">
                    <div class="bg-success rounded overflow-hidden">
                        <a href="{{ route('admin.online-users') }}">

                            <div class="pd-25 d-flex align-items-center">
                                <i class="ion ion-person-add tx-60 lh-0 tx-white op-7"></i>
                                <div class="mg-l-20">
                                    <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-10">Online
                                        Customers</p>
                                    <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1">{{ $online_users }}</p>
                                    {{-- <span class="tx-11 tx-roboto tx-white-6">Total Job Posted</span> --}}
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                @endif

                @if (check_permission('view-leads'))
                    <div class="col-sm-6 col-xl-4 mt-2">
                        <div class="bg-br-primary rounded overflow-hidden">
                            <a href="{{ route('admin.users.leads') }}">

                                <div class="pd-25 d-flex align-items-center">
                                    <i class="ion ion-ios-telephone-outline tx-60 lh-0 tx-white op-7"></i>
                                    <div class="mg-l-20">
                                        <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-10">Leads
                                        </p>
                                        <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1">{{ $leads }}</p>
                                        {{-- <span class="tx-11 tx-roboto tx-white-6">Total Job Posted</span> --}}
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif

                @if(check_permission('view-withdrawals'))
                    <div class="col-sm-6 col-xl-4 mg-t-20 mt-2">
                        <div class="bg-danger rounded overflow-hidden">
                            <a href="{{ route('admin.withdrawals.index') }}?status=0">
                                <div class="pd-25 d-flex align-items-center">
                                    <i class="ion ion-ios-calculator-outline tx-60 lh-0 tx-white op-7"></i>
                                    <div class="mg-l-20">
                                        <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-10">
                                            Withdrawals [Pending]</p>
                                        <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1">{{ $p_withdrawals }}</p>
                                        {{-- <span class="tx-11 tx-roboto tx-white-6">$390,212 before tax</span> --}}
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div><!-- col-3 -->
@endif
                    @if(check_permission('view-deposits'))
                    <div class="col-sm-6 col-xl-4 mg-t-20 mt-2">
                        <div class="bg-br-primary rounded overflow-hidden">
                            <a href="{{ route('admin.deposits.index') }}?status=0">
                                <div class="pd-25 d-flex align-items-center">
                                    <i class="ion ion-cash tx-60 lh-0 tx-white op-7"></i>
                                    <div class="mg-l-20">
                                        <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-10">
                                            Deposits [Pending]</p>
                                        <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1">{{ $p_deposits }}</p>
                                        {{-- <span class="tx-11 tx-roboto tx-white-6">65.45% on average time</span> --}}
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div><!-- col-3 -->
@endif
                    @if(check_permission('view-KYC-Verifications'))
                    <div class="col-sm-6 col-xl-4 mg-t-20 mt-2">
                        <div class="bg-warning rounded overflow-hidden">
                            <a href="{{ route('admin.users.ids') }}">
                                <div class="pd-25 d-flex align-items-center">
                                    <i class="ion ion-ios-list-outline tx-60 lh-0 tx-white op-7"></i>
                                    <div class="mg-l-20">
                                        <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-10">KYC
                                            Verifications</p>
                                        {{-- <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1">{{ $a_deposits }}</p> --}}
                                        {{-- <span class="tx-11 tx-roboto tx-white-6">23% average duration</span> --}}
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div><!-- col-3 -->
@endif



            </div><!-- row -->

            <div class="row row-sm mg-t-20">

            </div><!-- row -->

        </div><!-- br-pagebody -->
    @endsection

    @section('js')
        <script src="{{ asset('lib/chartist/chartist.js') }}"></script>
        <script src="{{ asset('lib/jquery.sparkline.bower/jquery.sparkline.min.js') }}"></script>
        <script src="{{ asset('lib/d3/d3.js') }}"></script>
        <script src="{{ asset('lib/rickshaw/rickshaw.min.js') }}"></script>
        <script src="{{ asset('js/ResizeSensor.js') }}"></script>
        <script src="{{ asset('js/dashboard.js') }}"></script>
        <script>
            $(function() {
                'use strict'

                // FOR DEMO ONLY
                // menu collapsed by default during first page load or refresh with screen
                // having a size between 992px and 1299px. This is intended on this page only
                // for better viewing of widgets demo.
                $(window).resize(function() {
                    minimizeMenu();
                });

                minimizeMenu();

                function minimizeMenu() {
                    if (window.matchMedia('(min-width: 992px)').matches && window.matchMedia('(max-width: 1299px)')
                        .matches) {
                        // show only the icons and hide left menu label by default
                        $('.menu-item-label,.menu-item-arrow').addClass('op-lg-0-force d-lg-none');
                        $('body').addClass('collapsed-menu');
                        $('.show-sub + .br-menu-sub').slideUp();
                    } else if (window.matchMedia('(min-width: 1300px)').matches && !$('body').hasClass(
                            'collapsed-menu')) {
                        $('.menu-item-label,.menu-item-arrow').removeClass('op-lg-0-force d-lg-none');
                        $('body').removeClass('collapsed-menu');
                        $('.show-sub + .br-menu-sub').slideDown();
                    }
                }
            });
        </script>
    @endsection
