<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('user.dashboard') }}"> <img alt="image" src="{{ asset('assets/img/logo.png') }}"
                                                    class="header-logo"/>
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown">
                <a href="{{ route('user.dashboard') }}" class="nav-link"><i
                        class="fas fa-laptop"></i><span>Dashboard</span></a>
            </li>
            <!-- <li class="menu-header">Personal Information</li> -->
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        class="fas fa-users"></i><span>Groups</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('user-groups.index') }}">My Groups</a></li>
                    <li><a class="nav-link" href="{{ route('usergroup.contributions') }}">My Contributions</a></li>
                    <li><a class="nav-link" href="{{ route('usergroup.make-contributions') }}">Make Contributions</a></li>
                </ul>
            </li>
            <li class="menu-header">Peer to Peer</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        class="fas fa-credit-card"></i><span>Lending</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('user-packages.index') }}">My Packages</a></li>
                    <li><a class="nav-link" href="{{ route('user-packages.create') }}">Create Packages</a></li>
                    <li><a class="nav-link" href="{{ route('user.requests') }}">Loan Requests</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i class="far fa-credit-card"></i><span>Borrowing</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('user.browseloans') }}">Borrow</a></li>
                    <li><a class="nav-link" href="{{ route('user-loans.index') }}">My Loans</a></li>
                </ul>
            </li>
            <li><a class="nav-link" href="{{ route('userwallet') }}"><i class="fas fa-wallet"></i><span>Wallet</span></a></li>

            <li><a class="nav-link" href="{{ route('user.transactions') }}"><i class="fas  fa-money-check"></i><span>Transactions</span></a>
            </li>

            <li><a class="nav-link" href="{{ route('usersavings')}}"><i class="fas  fa-piggy-bank"></i><span>Savings</span></a>
            
            <li><a class="nav-link" href="{{ route('user.bills-payment') }}"><i class="fas fa-money-bill"></i><span>Bills Payment</span></a></li>

            </li>
            <li class="menu-header">Analytics, Reporting &amp Statements</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i class="fas fa-chart-pie"></i><span>Graphical Analysis</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('user.loancollectionschart') }}">Loan Collections</a></li>
                    <!-- <li><a class="nav-link" href="{{ route('user.loanmaturitychart') }}">Loan Maturity</a></li> -->
                    <li><a class="nav-link" href="{{ route('user.loanreleasedchart') }}">Loan Released</a></li>
                    <!-- <li><a class="nav-link" href="{{ route('user.genderchart') }}">Gender Chart</a></li> -->
                    <!-- <li><a class="nav-link" href="{{ route('user.balancechart') }}">Balance &amp;   Payroll Chart</a></li> -->
                    <!-- <li><a class="nav-link" href="{{ route('user.averageloantenurechart') }}">Average Loan Tenure</a></li> -->
                    <!-- <li><a class="nav-link" href="{{ route('user.savingschart') }}">Savings Chart</a></li> -->
                </ul>
            </li>

            <li><a class="nav-link" href="{{ route('user.reportw') }}"><i class="fas  fa-money-check"></i><span>Reports</span></a></li>
            <!-- <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        class="fas fa-file-excel"></i><span>Reports</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('user.reportw') }}">Credit &amp; Debit Report</a></li>
                    <li><a class="nav-link" href="{{ route('user.reportb') }}">Borrowing Report</a></li>
                    <li><a class="nav-link" href="{{ route('user.reportl') }}">Lending Report</a></li>
                    <li><a class="nav-link" href="{{ route('user.reportt') }}">Transaction Report</a></li>
                    <li><a class="nav-link" href="{{ route('user.reportgroup') }}">Group Report</a></li>
                    <li><a class="nav-link" href="{{ route('user.reportcashflow') }}">Cash Flow Report</a></li>
                    <li><a class="nav-link" href="{{ route('user.reportdisbursement') }}">Disbursement Report</a></li>
                    <li><a class="nav-link" href="{{ route('user.reportprofitloss') }}">Profit &amp; Loss Report</a></li>
                    <li><a class="nav-link" href="{{ route('user.reportpendingdues') }}">Pending Dues Report</a></li>
                </ul>
            </li> -->
            <li class="menu-header">Settings & Preferences</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                        class="fas fa-cogs"></i><span>Preferences</span></a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('user.profile') }}" class="nav-link">
                            <i class="far fa-user"></i><span>Profile</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </aside>
</div>
