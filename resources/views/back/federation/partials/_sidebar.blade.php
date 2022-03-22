<div class="site-overlay"></div>
<div class="site-sidebar">
	<div class="custom-scroll custom-scroll-dark">
		<ul class="sidebar-menu">
			<li class="menu-title">Main</li>
			<li>
				<a href="{{ route('federation.dashboard') }}" class="waves-effect  waves-light">
					<i class="fas fa-tachometer-alt"></i>
					<span class="s-text">Overview</span>
				</a>
			</li>
			<li class="menu-title">Organization Information</li>
			<li>
				<a href="{{ route('federation.profile') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-user"></i></span>
					<span class="s-text">Profile</span>
				</a>
			</li>
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="fas fa-warehouse"></i></span>
					<span class="s-text">Associations</span>
				</a>
				<ul>
					<li><a href="{{ route('associations.index') }}">My Associations</a></li>
					<li><a href="{{ route('associations.create') }}">Add Association</a></li>
				</ul>
			</li>
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="fas fa-users"></i></span>
					<span class="s-text">Trainers</span>
				</a>
				<ul>
					<li><a href="{{ route('trainers.index') }}">Trainers</a></li>
					<li><a href="{{ route('trainers.create') }}">Add Trainer</a></li>
				</ul>
			</li>
			{{-- <li>
				<a href="{{ route('federation.dashboard') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-users"></i></span>
					<span class="s-text">Trainers</span>
				</a>
			</li> --}}
			{{-- <li>
				<a href="#" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-user"></i></span>
					<span class="s-text">Profile</span>
				</a>
			</li> --}}
			{{-- @role('super-organization-admin')
				<li class="with-sub">
					<a href="#" class="waves-effect  waves-light">
						<span class="s-caret"><i class="fa fa-angle-down"></i></span>
						<span class="s-icon"><i class="fas fa-user-friends"></i></span>
						<span class="s-text">Groups</span>
					</a>
					<ul>
						<li><a href="{{ route('groups.index') }}">My Groups</a></li>
						<li><a href="{{ route('groups.create') }}">Create New Group</a></li>
					</ul>
				</li>
			@endrole --}}
			{{-- <li class="menu-title">
				@if(Auth::user()->hasRole('super-organization-admin') || Auth::user()->hasRole('super-organization-super-organization-admin'))
					Peer to Peer
				@else
					Service Provision
				@endif
			</li> --}}
			{{-- <li>
				<a href="{{ route('payment.index') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-money-check-alt"></i></span>
					<span class="s-text">Add Payment Details</span>
				</a>
			</li> --}}
			{{-- <li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="far fa-credit-card"></i></span>
					<span class="s-text">Lending</span>
				</a>
				<ul>
					<li><a href="{{ route('org-packages.index') }}">My Packages</a></li>
					<li><a href="{{ route('org-packages.create') }}">Create Package</a></li>
				</ul>
			</li> --}}
			{{-- <li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="far fa-credit-card"></i></i></span>
					<span class="s-text">Borrowing</span>
				</a>
				<ul>
					<li><a href="{{ route('organization.browseloans') }}">Borrow</a></li>
					<li><a href="{{ route('org-loans.index') }}">My Loans</a></li>
				</ul>
			</li> --}}
			{{-- @role('service-provider')
				<li>
					<a href="{{ route('services.index') }}" class="waves-effect  waves-light">
						<span class="s-icon"><i class="fas fa-hands-helping"></i></span>
						<span class="s-text">Services</span>
					</a>
				</li>
			@endrole
			@role('service-provider')
				<li>
					<a href="{{ route('organization.wallet') }}" class="waves-effect  waves-light">
						<span class="s-icon"><i class="fas fa-wallet"></i></span>
						<span class="s-text">Wallet</span>
					</a>
				</li>
			@endrole --}}
			{{-- @role('super-organization-admin')
				<li class="with-sub">
					<a href="#" class="waves-effect  waves-light">
						<span class="s-caret"><i class="fa fa-angle-down"></i></span>
						<span class="s-icon"><i class="fas fa-wallet"></i></span>
						<span class="s-text">Wallet</span>
					</a>
					<ul>
						<li><a href="{{ route('fedwallet.personal') }}">Personal</a></li>
						<li><a href="{{ route('fed.wallet') }}">Organization</a></li>
					</ul>
				</li>
				<li>
					<a href="{{ route('fed.transactions') }}" class="waves-effect  waves-light">
						<span class="s-icon"><i class="far fa-money-bill-alt"></i></span>
						<span class="s-text">Transactions</span>
					</a>
				</li>
			@endrole --}}

			{{-- <li class="menu-title">Analytics and Reporting</li>
			<li>
				<a href="{{ route('fed.graphs') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-chart-bar"></i></span>
					<span class="s-text">Graphical Analysis</span>
				</a>
			</li>
			<li>
				<a href="{{ route('fed.reports') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-file-pdf"></i></span>
					<span class="s-text">Reports</span>
				</a>
			</li>
			<li class="menu-title">Settings &amp; Configuration</li>
			@role('super-organization-admin')
			<li>
				<a href="{{ route('users.index') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-users-cog"></i></span>
					<span class="s-text">User Management</span>
				</a>
			</li>
			@endrole
			@role('service-provider')
			<li>
				<a href="{{ route('users.index') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-users-cog"></i></span>
					<span class="s-text">Client Management</span>
				</a>
			</li>
			@endrole
			@role('super-organization-admin')
				<li class="with-sub">
					<a href="#" class="waves-effect  waves-light">
						<span class="s-caret"><i class="fa fa-angle-down"></i></span>
						<span class="s-icon"><i class="fas fa-lock"></i></span>
						<span class="s-text">Access Control</span>
					</a>
					<ul>
						<li><a href="#">Roles</a></li>
						<li><a href="#">Permissions</a></li>
					</ul>
				</li>
			@endrole --}}
			{{-- <li>
				<a href="#" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-cogs"></i></span>
					<span class="s-text">Theme Customization</span>
				</a>
			</li> --}}
			{{-- <li class="menu-title">Security</li>
			<li>
				<a href="{{ route('logs.org') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-file"></i></span>
					<span class="s-text">Access Logs</span>
				</a>
			</li> --}}
		</ul>
	</div>
</div>
