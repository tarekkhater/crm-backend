<div class="col-xl-12">
    <div class="card sub-menu">
        <div class="card-body">
            <ul class="d-flex">
                <li class="nav-item">
                    <a href="{{ route('backend.dashboard') }}" class="nav-link">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backend.account.overview') }}" class="nav-link">
                        <i class="mdi mdi-bullseye"></i>
                        <span>Overview</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backend.profile.edit') }}" class="nav-link">
                        <i class="mdi mdi-account-settings-variant"></i>
                        <span>Edit Profile</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{ route('deposit.create') }}" class="nav-link">
                        <i class="mdi mdi-heart"></i>
                        <span>Deposit</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backend.withdrawals.index') }}" class="nav-link">
                        <i class="mdi mdi-pentagon"></i>
                        <span>Withdrawals</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backend.trades.index') }}" class="nav-link">
                        <i class="mdi mdi-database"></i>
                        <span>Trades</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backend.account.security') }}" class="nav-link">
                        <i class="mdi mdi-settings"></i>
                        <span>Security Settings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backend.transactions') }}" class="nav-link">
                        <i class="mdi mdi-history"></i>
                        <span>Transaction History</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
