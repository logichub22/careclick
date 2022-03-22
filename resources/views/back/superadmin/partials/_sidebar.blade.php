<div class="site-overlay"></div>
<div class="site-sidebar">
	<div class="custom-scroll custom-scroll-dark">
		<ul class="sidebar-menu">
			<li class="menu-title">Main</li>
			<li>
				<a href="{{ route('super.dashboard') }}" class="waves-effect  waves-light">
					<i class="fas fa-tachometer-alt"></i>
					<span class="s-text">Overview</span>
				</a>
			</li>
			<li class="menu-title">Personal Information</li>
			<li>
				<a href="{{ route('super.profile') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-user"></i></span>
					<span class="s-text">Profile</span>
				</a>
			</li>
			<li class="menu-title">
				Peer to Peer
			</li>
			<li>
				<a href="{{ route('organizations.index') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-university"></i></span>
					<span class="s-text">Organizations</span>
				</a>
			</li>
			<li>
				<a href="{{ route('all-groups.index') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-user-friends"></i></span>
					<span class="s-text">Groups</span>
				</a>
			</li>
			<li>
				<a href="#" class="waves-effect waves-light">
					<span class="s-icon"><i class="fas fa-chart-line"></i></span>
					<span class="s-text">Loans</span>
				</a>
			</li>
			<li>
				<a href="{{ route('packages.index') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-align-justify"></i></span>
					<span class="s-text">Loan Packages</span>
				</a>
			</li>
			<li>
				<a href="{{ route('super.transactions') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-credit-card"></i></span>
					<span class="s-text">Transactions</span>
				</a>
			</li>
			<li class="menu-title">Analytics and Reporting</li>
			<li>
				<a href="{{ route('super.graphs') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-chart-bar"></i></span>
					<span class="s-text">Graphical Analysis</span>
				</a>
			</li>
			<li>
				<a href="{{ route('super.reports') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-file-pdf"></i></span>
					<span class="s-text">Reports</span>
				</a>
			</li>
			<li>
				<a href="{{ route('super.graphs') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-chart-area"></i></span>
					<span class="s-text">Google Analytics</span>
				</a>
			</li>
			<li class="menu-title">Settings &amp; Configuration</li>
			<li>
				<a href="{{ route('all-users.index') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-users-cog"></i></span>
					<span class="s-text">User Management</span>
				</a>
			</li>
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="fas fa-lock"></i></span>
					<span class="s-text">Access Control</span>
				</a>
				<ul>
					<li><a href="{{ route('roles.index') }}">Roles</a></li>
					{{-- <li><a href="{{ route('permissions.index') }}">Permissions</a></li> --}}
				</ul>
			</li>
			<li>
				<a href="{{ route('settings.index') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-cog"></i></span>
					<span class="s-text">Manage Settings</span>
				</a>
			</li>
			<li>
				<a href="{{ route('configs.index') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-file"></i></span>
					<span class="s-text">System Configs</span>
				</a>
			</li>
			<li>
				<a href="{{ url('superadmin/metrics') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-chart-bar"></i></span>
					<span class="s-text">Metrics</span>
				</a>
			</li>
			<li class="menu-title">Security</li>
			<li>
				<a href="{{ route('logs.all') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-file"></i></span>
					<span class="s-text">Access Logs</span>
				</a>
			</li>
		</ul>
	</div>
</div>
