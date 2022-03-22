<div class="site-overlay"></div>
<div class="site-sidebar">
	<div class="custom-scroll custom-scroll-dark">
		<ul class="sidebar-menu">
			<li class="menu-title">@lang('layout.main')</li>
			<li>
				<a href="{{ route('organization.dashboard') }}" class="waves-effect  waves-light">
					<i class="fas fa-tachometer-alt"></i>
					<span class="s-text">@lang('layout.Overview')</span>
				</a>
			</li>
			<li class="menu-title">Organization Information</li>
			<li>
				<a href="{{ route('organization.profile') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-user"></i></span>
					<span class="s-text">@lang('layout.Profile')</span>
				</a>
			</li>
			{{-- <li>
				<a href="#" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-user"></i></span>
					<span class="s-text">@lang('layout.profile')</span>
				</a>
			</li> --}}
			@role('admin')
				<li class="with-sub">
					<a href="#" class="waves-effect  waves-light">
						<span class="s-caret"><i class="fa fa-angle-down"></i></span>
						<span class="s-icon"><i class="fas fa-user-friends"></i></span>
						<span class="s-text">@lang('layout.Groups')</span>
					</a>
					<ul>
						<li><a href="{{ route('groups.index') }}">@lang('layout.mygroup')</a></li>
						{{-- <li><a href="">@lang('layout.newgrouprequests')</a></li> --}}
					</ul>
				</li>
			@endrole
			{{-- <li class="menu-title">
				@if(Auth::user()->hasRole('admin'))
					@lang('layout.p2p')
				@else
					@lang('layout.serviceprovision')
				@endif
			</li> --}}
			{{-- <li>
				<a href="{{ route('payment.index') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-money-check-alt"></i></span>
					<span class="s-text">@lang('layout.addpaymentdetails')</span>
				</a>
			</li> --}}
			{{-- <li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="far fa-credit-card"></i></span>
					<span class="s-text">@lang('layout.Lending')</span>
				</a>
				<ul>
					<li><a href="{{ route('org-packages.index') }}">@lang('layout.mypackages')</a></li>
					<li><a href="{{ route('org-packages.create') }}">@lang('layout.createpackage')</a></li>
				</ul>
			</li> --}}
			{{-- <li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="far fa-credit-card"></i></i></span>
					<span class="s-text">@lang('layout.Borrowing')</span>
				</a>
				<ul>
					<li><a href="{{ route('organization.browseloans') }}">@lang('layout.borrow')</a></li>
					<li><a href="{{ route('org-loans.index') }}">@lang('layout.myloans')</a></li>
				</ul>
			</li> --}}
			@role('service-provider')
				<li>
					<a href="{{ route('services.index') }}" class="waves-effect  waves-light">
						<span class="s-icon"><i class="fas fa-hands-helping"></i></span>
						<span class="s-text">@lang('layout.services')</span>
					</a>
				</li>
			@endrole
			@role('service-provider')
				<li>
					<a href="{{ route('organization.wallet') }}" class="waves-effect  waves-light">
						<span class="s-icon"><i class="fas fa-wallet"></i></span>
						<span class="s-text">@lang('layout.wallet')</span>
					</a>
				</li>
			@endrole
			@role('admin')
				<li class="with-sub">
					<a href="#" class="waves-effect  waves-light">
						<span class="s-caret"><i class="fa fa-angle-down"></i></span>
						<span class="s-icon"><i class="fas fa-wallet"></i></span>
						<span class="s-text">@lang('layout.wallet')</span>
					</a>
					<ul>
						<li><a href="{{ route('orgwallet.personal') }}">@lang('layout.personal')</a></li>
						<li><a href="{{ route('organization.wallet') }}">@lang('layout.organization')</a></li>
					</ul>
				</li>
				<li>
					<a href="{{ route('org.transactions') }}" class="waves-effect  waves-light">
						<span class="s-icon"><i class="far fa-money-bill-alt"></i></span>
						<span class="s-text">@lang('layout.transactions')</span>
					</a>
				</li>
			@endrole

			<li class="menu-title">@lang('individual.analyticsandreporting')</li>
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="fas fa-chart-bar"></i></span>
					<span class="s-text">@lang('individual.graphicalanalysis')</span>
				</a>
				<ul>
					<li><a href="{{ route('organization.loancollectionschart') }}">@lang('individual.loancollectionschart')</a></li>
					<li><a href="{{ route('organization.loanmaturitychart') }}">@lang('individual.loanmaturitychart')</a></li>
					<li><a href="{{ route('organization.loanreleasedchart') }}">@lang('individual.loanreleasedchart')</a></li>
					<li><a href="{{ route('organization.genderchart') }}">@lang('individual.genderchart')</a></li>
					<li><a href="{{ route('organization.balancechart') }}">@lang('individual.balancechart')</a></li>
					<li><a href="{{ route('organization.averageloantenurechart') }}">@lang('individual.averageloantenurechart')</a></li>
					<li><a href="{{ route('organization.savingschart') }}">@lang('individual.savingschart')</a></li>
				</ul>
			</li>
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="fas fa-file-pdf"></i></span>
					<span class="s-text">@lang('individual.reports')</span>
				</a>
				<ul>
					<li><a href="{{ route('organization.reportw') }}">@lang('individual.reportw')</a></li>
					<li><a href="{{ route('organization.reportb') }}">@lang('individual.reportb')</a></li>
					<li><a href="{{ route('organization.reportl') }}">@lang('individual.reportl')</a></li>
					<li><a href="{{ route('organization.reportt') }}">@lang('individual.reportt')</a></li>
					<li><a href="{{ route('organization.reportt') }}">@lang('individual.reportgroup')</a></li>
					<li><a href="{{ route('organization.reportcashflow') }}">@lang('individual.reportcashflow')</a></li>
					<li><a href="{{ route('organization.reportdisbursement') }}">@lang('individual.reportdisbursement')</a></li>
					<li><a href="{{ route('organization.reportprofitloss') }}">@lang('individual.reportprofitloss')</a></li>
					<li><a href="{{ route('organization.reportpendingdues') }}">@lang('individual.reportpendingdues')</a></li>
				</ul>
			</li>

			<li class="menu-title">@lang('layout.settingsandconfiguration')</li>
			@role('admin')
			<li>
				<a href="{{ route('users.index') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-users-cog"></i></span>
					<span class="s-text">@lang('layout.usermanagement')</span>
				</a>
			</li>
			@endrole
			@role('service-provider')
			<li>
				<a href="{{ route('users.index') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-users-cog"></i></span>
					<span class="s-text">@lang('layout.clientmanagement')</span>
				</a>
			</li>
			@endrole
			@role('admin')
				<li class="with-sub">
					<a href="#" class="waves-effect  waves-light">
						<span class="s-caret"><i class="fa fa-angle-down"></i></span>
						<span class="s-icon"><i class="fas fa-lock"></i></span>
						<span class="s-text">@lang('layout.accesscontrol')</span>
					</a>
					<ul>
						<li><a href="#">@lang('layout.roles')</a></li>
						<li><a href="#">@lang('layout.permissions')</a></li>
					</ul>
				</li>
			@endrole
			{{-- <li>
				<a href="#" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-cogs"></i></span>
					<span class="s-text">@lang('layout.themecustomization')</span>
				</a>
			</li> --}}
			<li class="menu-title">@lang('layout.security')</li>
			<li>
				<a href="{{ route('logs.org') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-file"></i></span>
					<span class="s-text">@lang('layout.accesslogs')</span>
				</a>
			</li>
		</ul>
	</div>
</div>
