<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('super.dashboard') }}"> <img alt="image" src="{{ asset('assets/img/logo.png') }}"
                                                    class="header-logo"/>
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown">
                <a href="{{ route('super.dashboard') }}" class="nav-link"><i
                        class="fas fa-laptop"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">Peer to Peer</li>
            <li><a class="nav-link" href="{{ route('organizations.index') }}"><i class="fas fa-building"></i><span>Organizations</span></a></li>
            <li><a class="nav-link" href="{{ route('all-groups.index') }}"><i class="fas fa-user-friends"></i><span>Groups</span></a></li>
           <!--  <li><a class="nav-link" href="#"><i class="fas fa-chart-line"></i><span>Loans</span></a></li> -->
            <li><a class="nav-link" href="{{ route('packages.index') }}"><i class="fas fa-align-justify"></i><span>Loan Packages</span></a></li>
            <li><a class="nav-link" href="{{ route('super.transactions') }}"><i class="fas fa-credit-card"></i><span>Transactions</span></a></li>
            <!-- <li class="menu-header">Analytics, Reporting &amp Statements</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i class="fas fa-chart-pie"></i><span>Graphical Analysis</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('super.graphs') }}">Loan Collections</a></li>
                    <li><a class="nav-link" href="{{ route('super.graphs') }}">Loan Maturity</a></li>
                    <li><a class="nav-link" href="{{ route('super.graphs') }}">Loan Released</a></li>
                    <li><a class="nav-link" href="{{ route('super.graphs') }}">Gender Chart</a></li>
                    <li><a class="nav-link" href="{{ route('super.graphs') }}">Balance &amp;   Payroll Chart</a></li>
                    <li><a class="nav-link" href="{{ route('super.graphs') }}">Average Loan Tenure</a></li>
                    <li><a class="nav-link" href="{{ route('super.graphs') }}">Savings Chart</a></li>
                </ul>
            </li> -->
            <!-- <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        class="fas fa-file-excel"></i><span>Reports</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('super.reports') }}">Credit &amp; Debit Report</a></li>
                    <li><a class="nav-link" href="{{ route('super.reports') }}">Borrowing Report</a></li>
                    <li><a class="nav-link" href="{{ route('super.reports') }}">Lending Report</a></li>
                    <li><a class="nav-link" href="{{ route('super.reports') }}">Transaction Report</a></li>
                    <li><a class="nav-link" href="{{ route('super.reports') }}">Group Report</a></li>
                    <li><a class="nav-link" href="{{ route('super.reports') }}">Cash Flow Report</a></li>
                    <li><a class="nav-link" href="{{ route('super.reports') }}">Disbursement Report</a></li>
                    <li><a class="nav-link" href="{{ route('super.reports') }}">Profit &amp; Loss Report</a></li>
                    <li><a class="nav-link" href="{{ route('super.reports') }}">Pending Dues Report</a></li>
                </ul>
            </li> -->
            <li class="menu-header">Settings & Preferences</li>
                    <li>
                        <a href="{{ route('all-users.index') }}" class="nav-link">
                            <i class="fas fa-user-friends"></i><span>User Management</span>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                class="fas fa-lock"></i><span>Access Control</span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('roles.index') }}" class="nav-link">
                                    <i class="fas fa-user-tag"></i><span>Roles</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- <li>
                        <a href="{{ route('settings.index') }}" class="nav-link">
                            <i class="fas fa-cog"></i><span>Manage Settings</span>
                        </a>
                    </li> -->
                    <li>
                        <a href="{{ route('configs.index') }}" class="nav-link">
                            <i class="fas fa-file"></i><span>System Config</span>
                        </a>
                    </li>
                    <!-- <li>
                        <a href="{{ url('superadmin/metrics') }}" class="nav-link">
                            <i class="fas fa-chart-bar"></i><span>Metrics</span>
                        </a>
                    </li> -->
                    <li>
                        <a href="{{ route('super.profile') }}" class="nav-link">
                            <i class="far fa-user"></i><span>Profile</span>
                        </a>
                    </li>
             <li class="menu-header">Security</li>
             <li><a class="nav-link" href="{{ route('logs.all') }}">Access Logs</a></li>
        </ul>
    </aside>
</div>
