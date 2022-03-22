<div class="site-overlay"></div>
<div class="site-sidebar">
	<div class="custom-scroll custom-scroll-dark">
		<ul class="sidebar-menu">
			<li class="menu-title">Main</li>
			<li>
				<a href="{{ route('trainer.dashboard') }}" class="waves-effect  waves-light">
					<i class="fas fa-tachometer-alt"></i>
					<span class="s-text">Overview</span>
				</a>
			</li>
			<li class="menu-title">Personal Information</li>
			<li>
				<a href="{{ route('trainer.profile') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-user"></i></span>
					<span class="s-text">Profile</span>
				</a>
			</li>
			{{-- <li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="fas fa-warehouse"></i></span>
					<span class="s-text">Groups</span>
				</a>
				<ul>
					<li><a href="{{ route('associations.index') }}">Groups Assigned</a></li>
				</ul>
			</li> --}}
		</ul>
	</div>
</div>
