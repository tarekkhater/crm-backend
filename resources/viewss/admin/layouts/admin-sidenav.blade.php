
<!-- ########## START: LEFT PANEL ########## -->
<div class="br-logo"><a  href="{{ url('/') }}" target="_blank"><span> </span>CRM <span></span></a></div>
<div class="br-sideleft overflow-y-auto">
    <div>
        <a href="{{ route('admin.dashboard') }}" class="br-menu-link {{ active('admin.dashboard') }}">
            <div class="br-menu-item">
                <i class="menu-item-icon icon ion-ios-home-outline tx-22"></i>
                <span class="menu-item-label">Dashboard</span>
            </div><!-- menu-item -->
        </a><!-- br-menu-link -->


        @if (check_permission('view-settings,update-settings'))
            <a href="#" class="br-menu-link {{ active(['admin.settings.*','admin.overnights','admin.margin.call'], 'active show-sub') }}">
                <div class="br-menu-item">
                    <i class="menu-item-fa fa fa-cogs tx-18"></i>
                    <span class="menu-item-label"> Settings</span>
                    <i class="menu-item-arrow fa fa fa-angle-down"></i>
                </div><!-- menu-item -->
            </a><!-- br-menu-link -->
            <ul class="br-menu-sub nav flex-column">

                <li class="nav-item"><a href="{{ route('admin.overnights') }}" class="nav-link {{ active('admin.overnights') }}">Overnight Commissions</a></li>

                <li class="nav-item"><a href="{{ route('admin.settings.fees') }}" class="nav-link {{ active('admin.settings.fees') }}">Fees Setting</a></li>
                <li class="nav-item"><a href="{{ route('admin.settings.site_settings') }}" class="nav-link {{ active('admin.settings.site_settings') }}">Site Setting</a></li>
                <li class="nav-item"><a href="{{ route('admin.margin.call') }}" class="nav-link {{ active('admin.margin.call') }}">Margin Call</a></li>
                <li class="nav-item"><a href="{{ route('admin.settings.withdrawals') }}" class="nav-link {{ active('admin.settings.withdrawals') }}">User Withdrawal Setting</a></li>
              <!--  <li class="nav-item"><a href="{{ route('admin.faqs.index') }}" class="nav-link {{ active('admin.faqs') }}">Faqs</a></li>-->
                <li class="nav-item"><a href="{{ route('admin.settings.payment_methods') }}" class="nav-link {{ active('admin.settings.payment_methods') }}">Payment Methods</a></li>
                <li class="nav-item"><a href="{{ route('admin.p_methods.index') }}" class="nav-link {{ active('admin.p_methods.*') }}">Crypto Payment Methods</a></li>
                <li class="nav-item"><a href="{{ route('admin.settings.c_payment') }}" class="nav-link {{ active('admin.settings.c_payment') }}">Custom Payment Link</a></li>

                <li class="nav-item"><a href="{{ route('admin.settings.lead.config') }}" class="nav-link {{ active('admin.settings.lead.config') }}">Lead Config</a></li>

            </ul>
        @endif

        @if (check_permission('view-dev-settings,update-dev-settings'))
            <a href="#" class="br-menu-link {{ active(['admin.settings.*'], 'active show-sub') }}">
                <div class="br-menu-item">
                    <i class="menu-item-fa fa fa-cogs tx-18"></i>
                    <span class="menu-item-label">Dev Settings</span>
                    <i class="menu-item-arrow fa fa fa-angle-down"></i>
                </div><!-- menu-item -->
            </a><!-- br-menu-link -->
            <ul class="br-menu-sub nav flex-column">

                    <li class="nav-item"><a href="{{ route('admin.settings.smtp') }}" class="nav-link {{ active('admin.settings.smtp') }}">SMTP Settings</a></li>
                    <li class="nav-item"><a href="{{ route('admin.settings.crm') }}" class="nav-link {{ active('admin.settings.crm') }}">CRM Settings</a></li>
                    <li class="nav-item"><a href="{{ route('admin.settings.modules') }}" class="nav-link {{ active('admin.settings.modules') }}">Modules Settings</a></li>
                    <li class="nav-item"><a href="{{ route('admin.settings.index') }}" class="nav-link {{ active('admin.settings.index') }}">General Settings</a></li>
                    <li class="nav-item"><a href="{{ route('admin.settings.mails') }}" class="nav-link {{ active('admin.settings.mails') }}">Mail Setting</a></li>
                    <li class="nav-item"><a href="{{ route('admin.settings.apis') }}" class="nav-link {{ active('admin.settings.apis') }}">APIs Setting</a></li>

                    <li class="nav-item"><a href="{{ route('admin.settings.pages') }}" class="nav-link {{ active('admin.settings.pages') }}">Pages Setting</a></li>
                    {{--            <li class="nav-item"><a href="{{ route('admin.settings.twak_io') }}" class="nav-link {{ active('admin.settings.twak_io') }}">Twak_io Setting</a></li>--}}

            </ul>
        @endif


        @if (check_permission('view-leads,create-leads,update-leads,create-source-leads,create-status-leads,import-leads,assign-manager-leads'))
            <a href="{{ route('admin.users.leads') }}" class="br-menu-link  {{ active('admin.users.leads') }}">
                <div class="br-menu-item">
                    <i class="menu-item-fa fa fa-phone tx-22"></i>
                    <span class="menu-item-label"> Leads</span>
                </div><!-- menu-item -->
            </a><!-- br-menu-link -->
        @endif

        @if (check_permission('view-customers,create-customers,update-customers,assign-manager-customers,assign-retention-customers'))

        <a href="{{ route('admin.users.index') }}" class="br-menu-link  {{ active('admin.users.index') }}">
            <div class="br-menu-item">
                <i class="menu-item-fa fa fa-users tx-22"></i>
                <span class="menu-item-label"> Customers</span>
            </div><!-- menu-item -->
        </a><!-- br-menu-link -->
@endif

        @if (check_permission('view-roles,create-roles,update-roles'))
        <a href="{{ route('admin.roles.index') }}" class="br-menu-link  {{ active('admin.roles.index') }}">
            <div class="br-menu-item">
                <i class="menu-item-fa fa fa-users tx-22"></i>
                <span class="menu-item-label"> Manage Roles</span>
            </div><!-- menu-item -->
        </a><!-- br-menu-link -->
        @endif

        @if (check_permission('view-trading-hours,edit-trading-hours'))
        <a href="{{ route('admin.trading_hours.index') }}" class="br-menu-link  {{ active('admin.trading_hours.index') }}">
            <div class="br-menu-item">
                <i class="menu-item-fa fa fa-users tx-22"></i>
                <span class="menu-item-label"> Manage Trading Hours</span>
            </div><!-- menu-item -->
        </a><!-- br-menu-link -->
        @endif

        @if (check_permission('view-active-customers,create-active-customers,update-active-customersm,assign-manager-active-customers,assign-retention-active-customers,set-active-users-as-free,set-active-users-as-archieve'))
        <a href="{{ route('admin.users.activ-users') }}" class="br-menu-link  {{ active('admin.users.activ-users') }}">
            <div class="br-menu-item">
                <i class="menu-item-fa fa fa-users tx-22"></i>
                <span class="menu-item-label">Activ Customers</span>
            </div><!-- menu-item -->
        </a><!-- br-menu-link -->
        @endif
        @if (check_permission('view-archieve-customers,create-archieve-customers,update-archieve-customers,assign-manager-archieve-customers,assign-retention-archieve-customers,set-archieve-users-as-free,set-archieve-users-as-active'))
        <a href="{{ route('admin.users.archieve-users') }}" class="br-menu-link  {{ active('admin.users.archieve-users') }}">
            <div class="br-menu-item">
                <i class="menu-item-fa fa fa-users tx-22"></i>
                <span class="menu-item-label">Archieve Customers</span>
            </div><!-- menu-item -->
        </a><!-- br-menu-link -->
        @endif
        @if (check_permission('view-assets,create-currency-pair,update-currency-pair,delete-currency-pair,update-leverage,update-buy,update-sell'))


            <a href="{{ route('admin.currencies.index') }}" class="br-menu-link  {{ active('admin.currencies.index') }}">
                <div class="br-menu-item">
                    <i class="menu-item-fa fa fa-list-ul tx-22"></i>
                    <span class="menu-item-label"> Assets</span>
                </div><!-- menu-item -->
            </a><!-- br-menu-link -->
            @endif

            {{-- <a href="{{ route('admin.users.marketers') }}" class="br-menu-link  {{ active('admin.users.marketers') }}">
                <div class="br-menu-item">
                    <i class="menu-item-fa fa fa-users tx-22"></i>
                    <span class="menu-item-label"> Affiliate Marketer</span>
                </div><!-- menu-item -->
            </a><!-- br-menu-link --> --}}

           @if(check_permission('view-conversion-agents,create-manager,assign-user-to-manager,update-manager,delete-manager,login-as-manager'))
            <a href="{{ route('admin.users.managers') }}" class="br-menu-link {{ active('admin.users.managers') }}">
            <div class="br-menu-item">
                <i class="menu-item-fa fa fa-user-md tx-22"></i>
                <span class="menu-item-label">Conversion Agents</span>
            </div><!-- menu-item -->
        </a><!-- br-menu-link -->
@endif
@if(check_permission('view-retention-agents,create-retention,update-retention,delete-retention,login-as-retention'))
        <a href="{{ route('admin.users.retentions') }}" class="br-menu-link {{ active('admin.users.retentions') }}">
            <div class="br-menu-item">
                <i class="menu-item-fa fa fa-user-circle-o tx-22"></i>
                <span class="menu-item-label">Retention Agents</span>
            </div><!-- menu-item -->
        </a><!-- br-menu-link -->
@endif
        @if(check_permission('view-admins,create-admin,update-admin,delete-admin,login-as-admin'))

        <a href="{{ route('admin.users.admins') }}" class="br-menu-link ">
            <div class="br-menu-item">
                <i class="menu-item-fa fa fa-universal-access tx-22"></i>
                <span class="menu-item-label"> Admins</span>
            </div><!-- menu-item -->
        </a><!-- br-menu-link -->
@endif

{{--        <a href="#" class="br-menu-link  {{ active(['admin.users.index','admin.users.*'], 'active show-sub') }}">--}}
{{--            <div class="br-menu-item">--}}
{{--                <i class="menu-item-fa fa fa-user tx-18"></i>--}}
{{--                <span class="menu-item-label"> Manage Customers</span>--}}
{{--                <i class="menu-item-arrow fa fa fa-angle-down"></i>--}}
{{--            </div><!-- menu-item -->--}}
{{--        </a><!-- br-menu-link -->--}}

{{--        <ul class="br-menu-sub nav flex-column">--}}
{{--            <li class="nav-item"><a href="{{ route('admin.users.index') }}" class="nav-link {{ active('admin.users.index') }}">Users</a></li>--}}
{{--            @if (auth()->user()->hasRole(['superadmin','admin']))--}}
{{--            <li class="nav-item"><a href="{{ route('admin.users.admins') }}" class="nav-link {{ active('admin.users.admins') }}">Admins</a></li>--}}
{{--            <li class="nav-item"><a href="{{ route('admin.users.managers') }}" class="nav-link {{ active('admin.users.managers') }}">Managers</a></li>--}}
{{--                @if (setting('autotrader'))--}}
{{--            <li class="nav-item"><a href="{{ route('admin.users.autotraders') }}" class="nav-link {{ active('admin.users.autotraders') }}">Auto Traders</a></li>--}}
{{--                    @endif--}}
{{--            @endif--}}
{{--            <li class="nav-item"><a href="" class="nav-link">Banned Accounts</a></li>--}}
{{--        </ul>--}}



        @if (check_permission('view-packages'))


            @if (setting('invest'))
        <a href="{{ route('admin.packages.index') }}" class="br-menu-link  {{ active('admin.packages.index') }}">
            <div class="br-menu-item">
                <i class="menu-item-fa fa fa-shopping-bag tx-22"></i>
                <span class="menu-item-label"> Packages</span>
            </div><!-- menu-item -->
        </a><!-- br-menu-link -->
            @endif



{{--            <a href="{{ route('admin.plans.index') }}" class="br-menu-link  {{ active('admin.plans.index') }}">--}}
{{--                <div class="br-menu-item">--}}
{{--                    <i class="menu-item-fa fa fa-cubes tx-22"></i>--}}
{{--                    <span class="menu-item-label"> Plans</span>--}}
{{--                </div><!-- menu-item -->--}}
{{--            </a><!-- br-menu-link -->--}}


{{--                <a href="{{ route('admin.users.ids') }}" class="br-menu-link">--}}
{{--                    <div class="br-menu-item">--}}
{{--                        <i class="menu-item-fa fa fa-id-card tx-22"></i>--}}
{{--                        <span class="menu-item-label">KYC Verifications</span>--}}
{{--                    </div><!-- menu-item -->--}}
{{--                </a>--}}


            @if (setting('invest'))
        <a href="{{ route('admin.investments') }}" class="br-menu-link  {{ active('admin.investments') }}">
            <div class="br-menu-item">
                <i class="menu-item-fa fa fa-money tx-22"></i>
                <span class="menu-item-label"> Investments</span>
            </div><!-- menu-item -->
        </a><!-- br-menu-link -->
                @endif
        @endif

{{--        <a href="{{ route('admin.currencies.index') }}" class="br-menu-link  {{ active('admin.currencies.index') }}">--}}
{{--            <div class="br-menu-item">--}}
{{--                <i class="menu-item-fa fa fa-shopping-bag tx-22"></i>--}}
{{--                <span class="menu-item-label">Currencies</span>--}}
{{--            </div><!-- menu-item -->--}}
{{--        </a><!-- br-menu-link -->--}}



{{--        <a href="#" class="br-menu-link {{ active(['admin.deposits.index','admin.withdrawals.index'], 'active show-sub') }}">--}}
{{--            <div class="br-menu-item">--}}
{{--                <i class="menu-item-fa fa fa-money tx-18"></i>--}}
{{--                <span class="menu-item-label"> Deposit / Withdrawals</span>--}}
{{--                <i class="menu-item-arrow fa fa fa-angle-down"></i>--}}
{{--            </div><!-- menu-item -->--}}
{{--        </a><!-- br-menu-link -->--}}
{{--        <ul class="br-menu-sub nav flex-column">--}}
{{--            <li class="nav-item"><a href="{{ route('admin.deposits.index') }}" class="nav-link {{ active('admin.deposits.index') }}">All Deposits</a></li>--}}
{{--            <li class="nav-item"><a href="{{ route('admin.withdrawals.index') }}" class="nav-link {{ active('admin.withdrawals.index') }}">Withdrawals</a></li>--}}
{{--        </ul>--}}
{{--        --}}


 @if(check_permission('view-recent-login,view-recent-trade,update-recent-trade_delete-recent-trade,view-transaction,delete-transaction'))

        <a href="#" class="br-menu-link {{ active(['admin.users.active.plans','admin.trades.*'], 'active show-sub') }}">
            <div class="br-menu-item">
                <i class="menu-item-fa fa fa-fire tx-18"></i>
                <span class="menu-item-label"> Activities</span>
                <i class="menu-item-arrow fa fa fa-angle-down"></i>
            </div><!-- menu-item -->
        </a><!-- br-menu-link -->
        <ul class="br-menu-sub nav flex-column">
{{--@if(check_permission('view-recent-login'))--}}
{{--     <li class="nav-item"><a href="{{route('admin.user.recent.login')}}" class="nav-link ">Recent Logins</a></li>--}}
{{--            @endif--}}
    @if(check_permission('view-recent-trade'))
     <li class="nav-item"><a href="{{route('admin.all.trades')}}" class="nav-link ">Recent Trades</a></li>
     @endif
    @if(check_permission('view-transaction'))
     <li class="nav-item"><a href="{{ route('admin.transactions') }}" class="nav-link ">Transactions</a></li>
     @endif
</ul>

@endif


@section('hide')
@if (check_permission('view-settings'))
<a href="#" class="br-menu-link {{ active(['admin.settings.*'], 'active show-sub') }}">
 <div class="br-menu-item">
     <i class="menu-item-fa fa fa-cogs tx-18"></i>
     <span class="menu-item-label"> Settings</span>
     <i class="menu-item-arrow fa fa fa-angle-down"></i>
 </div><!-- menu-item -->
</a><!-- br-menu-link -->
<ul class="br-menu-sub nav flex-column">
 @if (check_permission('view-settings'))
 <li class="nav-item"><a href="{{ route('admin.settings.smtp') }}" class="nav-link {{ active('admin.settings.smtp') }}">SMTP Settings</a></li>
 <li class="nav-item"><a href="{{ route('admin.settings.crm') }}" class="nav-link {{ active('admin.settings.crm') }}">CRM Settings</a></li>
 <li class="nav-item"><a href="{{ route('admin.settings.modules') }}" class="nav-link {{ active('admin.settings.modules') }}">Modules Settings</a></li>
 <li class="nav-item"><a href="{{ route('admin.settings.index') }}" class="nav-link {{ active('admin.settings.index') }}">General Settings</a></li>
 <li class="nav-item"><a href="{{ route('admin.settings.mails') }}" class="nav-link {{ active('admin.settings.mails') }}">Mail Setting</a></li>
 <li class="nav-item"><a href="{{ route('admin.settings.notifications') }}" class="nav-link {{ active('admin.settings.notifications') }}">Notification Settings</a></li>
 <li class="nav-item"><a href="{{ route('admin.settings.apis') }}" class="nav-link {{ active('admin.settings.apis') }}">APIs Setting</a></li>
 <li class="nav-item"><a href="{{ route('admin.settings.pages') }}" class="nav-link {{ active('admin.settings.pages') }}">Pages Setting</a></li>
{{--            <li class="nav-item"><a href="{{ route('admin.settings.twak_io') }}" class="nav-link {{ active('admin.settings.twak_io') }}">Twak_io Setting</a></li>--}}
 @endif
 <li class="nav-item"><a href="{{ route('admin.settings.fees') }}" class="nav-link {{ active('admin.settings.fees') }}">Fees Setting</a></li>
 <li class="nav-item"><a href="{{ route('admin.settings.site_settings') }}" class="nav-link {{ active('admin.settings.site_settings') }}">Site Setting</a></li>
 <li class="nav-item"><a href="{{ route('admin.settings.withdrawals') }}" class="nav-link {{ active('admin.settings.withdrawals') }}">User Withdrawal Setting</a></li>
 <li class="nav-item"><a href="{{ route('admin.faqs.index') }}" class="nav-link {{ active('admin.faqs') }}">Faqs</a></li>
 <li class="nav-item"><a href="{{ route('admin.settings.payment_methods') }}" class="nav-link {{ active('admin.settings.payment_methods') }}">Payment Methods</a></li>
 <li class="nav-item"><a href="{{ route('admin.p_methods.index') }}" class="nav-link {{ active('admin.p_methods.*') }}">Crypto Payment Methods</a></li>
 <li class="nav-item"><a href="{{ route('admin.settings.c_payment') }}" class="nav-link {{ active('admin.settings.c_payment') }}">Custom Payment Link</a></li>



 <!-- br-menu-link -->

{{--        <a href="{{ route('admin.faqs.index') }}" class="br-menu-link {{ active('admin.faqs.*') }}">--}}
{{--            <div class="br-menu-item">--}}
{{--                <i class="menu-item-fa fa fa-question tx-22"></i>--}}
{{--                <span class="menu-item-label">Faqs</span>--}}
{{--            </div><!-- menu-item -->--}}
{{--        </a><!-- br-menu-link -->--}}
@endif
     @endsection
<br>
<a  href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="br-menu-link">

 <div class="br-menu-item">
     <i class="menu-item-icon icon ion-power tx-18"></i>
     <span class="menu-item-label">Logout</span>
 </div><!-- menu-item -->
</a><!-- br-menu-link -->
<a  href="#" class="br-menu-link">

 <div class="br-menu-item">
     <i class="menu-item-icon icon ion-clock tx-18"></i>
     <span class="menu-item-label">{!! \Carbon\Carbon::now()->toDateTimeString()!!} </span>
 </div><!-- menu-item -->
</a><!-- br-menu-link -->
</div><!-- br-sideleft-menu -->





<br>
</div><!-- br-sideleft -->
<!-- ########## END: LEFT PANEL ########## -->

<!-- ########## START: HEAD PANEL ########## -->
<div class="br-header">
<div class="br-header-left">
<div class="navicon-left hidden-md-down"><a id="btnLeftMenu" href="#"><i class="icon ion-navicon-round"></i></a></div>
<div class="navicon-left hidden-lg-up"><a id="btnLeftMenuMobile" href="#"><i class="icon ion-navicon-round"></i></a></div>
<div class="input-group hidden-xs-down text-white transition">
{{--            <input id="searchbox" disabled type="text" class="form-control" placeholder="{{ setting('site_name') }}">--}}

 <span class="input-group-btn">
 <button class="btn btn-secondary" type="button">{{ setting('site_name') }} </button>
</span>

</div><!-- input-group -->

</div><!-- br-header-left -->
<div class="br-header-right">
<nav class="nav">
 @section('hide')
 <div class="dropdown">
     <a href="#" class="nav-link pd-x-7 pos-relative" data-toggle="dropdown">
         <i class="icon ion-ios-email-outline tx-18"></i>
         <!-- start: if statement -->
         <span class="square-8 bg-danger pos-absolute t-15 r-0 rounded-circle"></span>
         <!-- end: if statement -->
     </a>
     <div class="dropdown-menu dropdown-menu-header wd-300 pd-0-force">
         <div class="d-flex align-items-center justify-content-between pd-y-10 pd-x-20 bd-b bd-gray-200">
             <label class="tx-12 tx-info tx-uppercase tx-semibold tx-spacing-2 mg-b-0">Messages</label>
             <a href="#" class="tx-11">+ Add New Message</a>
         </div><!-- d-flex -->


         <div class="media-list">
             <!-- loop starts here -->
             <a href="#" class="media-list-link">
                 <div class="media pd-x-20 pd-y-15">
                     <img src="http://localhost/afiaanyi-logistics/img/img3.jpg" class="wd-40 rounded-circle" alt="">

                     <div class="media-body">
                         <div class="d-flex align-items-center justify-content-between mg-b-5">
                             <p class="mg-b-0 tx-medium tx-gray-800 tx-14">Donna Seay</p>
                             <span class="tx-11 tx-gray-500">2 minutes ago</span>
                         </div><!-- d-flex -->
                         <p class="tx-12 mg-b-0">A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring.</p>
                     </div>
                 </div><!-- media -->
             </a>
             <!-- loop ends here -->

             <a href="#" class="media-list-link read">
                 <div class="media pd-x-20 pd-y-15">
                     <img src="http://localhost/afiaanyi-logistics/img/img4.jpg" class="wd-40 rounded-circle" alt="">
                     <div class="media-body">
                         <div class="d-flex align-items-center justify-content-between mg-b-5">
                             <p class="mg-b-0 tx-medium tx-gray-800 tx-14">Samantha Francis</p>
                             <span class="tx-11 tx-gray-500">3 hours ago</span>
                         </div><!-- d-flex -->
                         <p class="tx-12 mg-b-0">My entire soul, like these sweet mornings of spring.</p>
                     </div>
                 </div><!-- media -->
             </a>
             <a href="#" class="media-list-link read">
                 <div class="media pd-x-20 pd-y-15">
                     <img src="http://localhost/afiaanyi-logistics/img/img7.jpg" class="wd-40 rounded-circle" alt="">
                     <div class="media-body">
                         <div class="d-flex align-items-center justify-content-between mg-b-5">
                             <p class="mg-b-0 tx-medium tx-gray-800 tx-14">Robert Walker</p>
                             <span class="tx-11 tx-gray-500">5 hours ago</span>
                         </div><!-- d-flex -->
                         <p class="tx-12 mg-b-0">I should be incapable of drawing a single stroke at the present moment...</p>
                     </div>
                 </div><!-- media -->
             </a>
             <a href="#" class="media-list-link read">
                 <div class="media pd-x-20 pd-y-15">
                     <img src="http://localhost/afiaanyi-logistics/img/img5.jpg" class="wd-40 rounded-circle" alt="">
                     <div class="media-body">
                         <div class="d-flex align-items-center justify-content-between mg-b-5">
                             <p class="mg-b-0 tx-medium tx-gray-800 tx-14">Larry Smith</p>
                             <span class="tx-11 tx-gray-500">Yesterday</span>
                         </div><!-- d-flex -->
                         <p class="tx-12 mg-b-0">When, while the lovely valley teems with vapour around me, and the meridian sun strikes...</p>
                     </div>
                 </div><!-- media -->
             </a>
             <div class="pd-y-10 tx-center bd-t">
                 <a href="#" class="tx-12"><i class="fa fa-angle-down mg-r-5"></i> Show All Messages</a>
             </div>
         </div><!-- media-list -->
     </div><!-- dropdown-menu -->

 </div><!-- dropdown -->
 <div class="dropdown">
     <a href="#" class="nav-link pd-x-7 pos-relative" data-toggle="dropdown">
         <i class="icon ion-ios-bell-outline tx-18"></i>
         <!-- start: if statement -->
         <span class="square-8 bg-danger pos-absolute t-15 r-5 rounded-circle"></span>
         <!-- end: if statement -->
     </a>
     <div class="dropdown-menu dropdown-menu-header wd-300 pd-0-force">
         <div class="d-flex align-items-center justify-content-between pd-y-10 pd-x-20 bd-b bd-gray-200">
             <label class="tx-12 tx-info tx-uppercase tx-semibold tx-spacing-2 mg-b-0">Notifications</label>
             <a href="#" class="tx-11">Mark All as Read</a>
         </div><!-- d-flex -->

         <div class="media-list">
             <!-- loop starts here -->
             <a href="#" class="media-list-link read">
                 <div class="media pd-x-20 pd-y-15">
                     <img src="http://localhost/afiaanyi-logistics/img/img8.jpg" class="wd-40 rounded-circle" alt="">
                     <div class="media-body">
                         <p class="tx-13 mg-b-0 tx-gray-700"><strong class="tx-medium tx-gray-800">Suzzeth Bungaos</strong> tagged you and 18 others in a post.</p>
                         <span class="tx-12">October 03, 2017 8:45am</span>
                     </div>
                 </div><!-- media -->
             </a>
             <!-- loop ends here -->
             <a href="#" class="media-list-link read">
                 <div class="media pd-x-20 pd-y-15">
                     <img src="http://localhost/afiaanyi-logistics/img/img9.jpg" class="wd-40 rounded-circle" alt="">
                     <div class="media-body">
                         <p class="tx-13 mg-b-0 tx-gray-700"><strong class="tx-medium tx-gray-800">Mellisa Brown</strong> appreciated your work <strong class="tx-medium tx-gray-800">The Social Network</strong></p>
                         <span class="tx-12">October 02, 2017 12:44am</span>
                     </div>
                 </div><!-- media -->
             </a>
             <a href="#" class="media-list-link read">
                 <div class="media pd-x-20 pd-y-15">
                     <img src="http://localhost/afiaanyi-logistics/img/img10.jpg" class="wd-40 rounded-circle" alt="">
                     <div class="media-body">
                         <p class="tx-13 mg-b-0 tx-gray-700">20+ new items added are for sale in your <strong class="tx-medium tx-gray-800">Sale Group</strong></p>
                         <span class="tx-12">October 01, 2017 10:20pm</span>
                     </div>
                 </div><!-- media -->
             </a>
             <a href="#" class="media-list-link read">
                 <div class="media pd-x-20 pd-y-15">
                     <img src="http://localhost/afiaanyi-logistics/img/img5.jpg" class="wd-40 rounded-circle" alt="">
                     <div class="media-body">
                         <p class="tx-13 mg-b-0 tx-gray-700"><strong class="tx-medium tx-gray-800">Julius Erving</strong> wants to connect with you on your conversation with <strong class="tx-medium tx-gray-800">Ronnie Mara</strong></p>
                         <span class="tx-12">October 01, 2017 6:08pm</span>
                     </div>
                 </div><!-- media -->
             </a>
             <div class="pd-y-10 tx-center bd-t">
                 <a href="#" class="tx-12"><i class="fa fa-angle-down mg-r-5"></i> Show All Notifications</a>
             </div>
         </div><!-- media-list -->
     </div><!-- dropdown-menu -->
 </div><!-- dropdown -->
 @endsection

     <a class="btn btn-success text-white" target="_blank" href="{{ route('backend.dashboard') }}">Switch to TRADE ROOM</a>

     <div class="dropdown">


     <a href="#" class="nav-link nav-link-profile" data-toggle="dropdown">
         <span class="logged-name hidden-md-down">{{ auth()->user()->first_name }}</span>
         <img src="{{ asset(auth()->user()->avatar) }}) }}" class="wd-32 rounded-circle" alt="">
         <span class="square-10 bg-success"></span>
     </a>
     <div class="dropdown-menu dropdown-menu-header wd-200">
         <ul class="list-unstyled user-profile-nav">
             <li><a href="{{ route('admin.profile.change-password') }}"><i class="icon ion-ios-person"></i> Change Password</a></li>
           <!--  <li><a href="{{ route('admin.settings.index') }}"><i class="icon ion-ios-gear"></i> Settings</a></li>-->
{{--                        <li><a href="#"><i class="icon ion-ios-download"></i> Downloads</a></li>--}}
{{--                        <li><a href="#"><i class="icon ion-ios-star"></i> Favorites</a></li>--}}
{{--                        <li><a href="#"><i class="icon ion-ios-folder"></i> Collections</a></li>--}}
             <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="icon ion-power"></i> Sign Out</a></li>

             <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                 @csrf
             </form>
         </ul>
     </div><!-- dropdown-menu -->
 </div><!-- dropdown -->
</nav>
</div><!-- br-header-right -->
</div><!-- br-header -->
<!-- ########## END: HEAD PANEL ########## -->

<!-- ########## START: RIGHT PANEL ########## -->
<div class="br-sideright">


<!-- Tab panes -->

</div><!-- br-sideright -->
<!-- ########## END: RIGHT PANEL ########## --->
