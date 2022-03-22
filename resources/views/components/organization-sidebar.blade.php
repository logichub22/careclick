<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('organization.dashboard') }}"> <img alt="image" src="{{ asset('assets/img/logo.png') }}"
                                                    class="header-logo"/>
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown">
                <a href="{{ route('organization.dashboard') }}" class="nav-link"><i
                        class="fas fa-laptop"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">Organization Information</li>

            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        class="fas fa-credit-card"></i><span>Lending</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('org-packages.index') }}">My Packages</a></li>
                    <li><a class="nav-link" href="{{ route('org-packages.create') }}">Create Packages</a></li>
                    <li><a class="nav-link" href="{{ route('organization.requests') }}">Loan Requests</a></li>
                    @if ($user->isFirstSource())
                        <li><a class="nav-link" href="{{ route('organization.loans') }}">Approved Loans</a></li>
                    @endif
                </ul>
            </li>

            <li><a class="nav-link" href="{{ route('services.index') }}"><i class="fas fa-cog"></i><span>Services</span></a>
            </li>

            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        class="fas fa-credit-card"></i><span>Borrowing</span></a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('organization.browseloans') }}">@lang('layout.borrow')</a></li>
                    <li><a href="{{ route('org-loans.index') }}">@lang('layout.myloans')</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        class="fas fa-users"></i><span>Groups</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('groups.index') }}">My Groups</a></li>
                    <li><a class="nav-link" href="{{ route('groups.create') }}">Create New Group</a></li>
                    <!-- <li><a class="nav-link" href="{{ route('orggroup.contributions') }}">Group Contributions</a></li> -->
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i class="far fa-credit-card"></i><span>Wallet</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('orgwallet.personal') }}">Personal</a></li>
                    <li><a class="nav-link" href="{{ route('organization.wallet') }}">Organization</a></li>
                </ul>
            </li>
            <li><a class="nav-link" href="{{ route('org.transactions') }}"><i class="fas  fa-money-check"></i><span>Transactions</span></a>
            </li>
            <li><a class="nav-link" href="{{ route('orgsavings') }}"><i class="fas  fa-piggy-bank"></i><span>Savings</span></a>
            </li>
            <li class="menu-header">Analytics, Reporting &amp Statements</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i class="fas fa-chart-pie"></i><span>Graphical Analysis</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('organization.loancollectionschart') }}">Loan Collections</a></li>
                    <!-- <li><a class="nav-link" href="{{ route('organization.loanmaturitychart') }}">Loan Maturity</a></li> -->
                    <li><a class="nav-link" href="{{ route('organization.loanreleasedchart') }}">Loan Released</a></li>
                    <li><a class="nav-link" href="{{ route('organization.genderchart') }}">Gender Chart</a></li>
                    <!-- <li><a class="nav-link" href="{{ route('organization.balancechart') }}">Balance &amp;   Payroll Chart</a></li> -->
                    <!-- <li><a class="nav-link" href="{{ route('organization.averageloantenurechart') }}">Average Loan Tenure</a></li> -->
                    <!-- <li><a class="nav-link" href="{{ route('organization.savingschart') }}">Savings Chart</a></li> -->
                </ul>
            </li>
             <li><a class="nav-link" href="{{ route('organization.reportw') }}"><i class="fas  fa-money-check"></i><span>Reports</span></a></li>
            <!-- <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        class="fas fa-file-excel"></i><span>Reports</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('organization.reportw') }}">Credit &amp; Debit Report</a></li>
                    <li><a class="nav-link" href="{{ route('organization.reportb') }}">Borrowing Report</a></li>
                    <li><a class="nav-link" href="{{ route('organization.reportl') }}">Lending Report</a></li>
                    <li><a class="nav-link" href="{{ route('organization.reportt') }}">Transaction Report</a></li>
                    <li><a class="nav-link" href="{{ route('organization.reportgroup') }}">Group Report</a></li>
                    <li><a class="nav-link" href="{{ route('organization.reportcashflow') }}">Cash Flow Report</a></li>
                    <li><a class="nav-link" href="{{ route('organization.reportdisbursement') }}">Disbursement Report</a></li>
                    <li><a class="nav-link" href="{{ route('organization.reportprofitloss') }}">Profit &amp; Loss Report</a></li>
                    <li><a class="nav-link" href="{{ route('organization.reportpendingdues') }}">Pending Dues Report</a></li>
                </ul>
            </li> -->
            <!-- <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        class="fas fa-file-excel"></i><span>Reports</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('organization.reportw') }}">Credit &amp; Debit Report</a></li>
                    <li><a class="nav-link" href="{{ route('organization.reportb') }}">Borrowing Report</a></li>
                    <li><a class="nav-link" href="{{ route('organization.reportl') }}">Lending Report</a></li>
                    <li><a class="nav-link" href="{{ route('organization.reportt') }}">Transaction Report</a></li>
                    <li><a class="nav-link" href="{{ route('organization.reportgroup') }}">Group Report</a></li>
                    <li><a class="nav-link" href="{{ route('organization.reportcashflow') }}">Cash Flow Report</a></li>
                    <li><a class="nav-link" href="{{ route('organization.reportdisbursement') }}">Disbursement Report</a></li>
                    <li><a class="nav-link" href="{{ route('organization.reportprofitloss') }}">Profit &amp; Loss Report</a></li>
                    <li><a class="nav-link" href="{{ route('organization.reportpendingdues') }}">Pending Dues Report</a></li>
                </ul>
            </li> -->
            <li class="menu-header">Settings & Preferences</li>
                <li class="dropdown">
                    <a href="{{ route('users.index') }}" class="nav-link"><i
                            class="fas fa-laptop"></i><span>User Management</span></a>
                </li>
                <li>
                    <a href="{{ route('organization.profile') }}" class="nav-link">
                        <i class="far fa-user"></i><span>Profile</span>
                    </a>
                </li>
                <!-- <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown"><i
                            class="fas fa-lock"></i><span>Access</span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="#">Roles</a></li>
                        <li><a class="nav-link" href="#">Permissions</a></li>
                    </ul>
                </li> -->
            </ul>
        </ul>
    </aside>
</div>
