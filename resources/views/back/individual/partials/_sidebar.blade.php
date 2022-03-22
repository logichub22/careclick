<div class="site-overlay"></div>
<div class="site-sidebar">
	<div class="custom-scroll custom-scroll-dark">
		<ul class="sidebar-menu">
			<li class="menu-title">@lang('individual.main')</li>
			<li>
				<a href="{{ route('user.dashboard') }}" class="waves-effect  waves-light">
					<i class="fas fa-tachometer-alt"></i>
					<span class="s-text">@lang('individual.overview')</span>
				</a>
			</li>
			<li class="menu-title">@lang('individual.personalinformation')</li>
			<li>
				<a href="{{ route('user.profile') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-user"></i></span>
					<span class="s-text">@lang('individual.profile')</span>
				</a>
			</li>
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="fas fa-user-friends"></i></span>
					<span class="s-text">@lang('individual.groups')</span>
				</a>
				<ul>
					<li><a href="{{ route('user-groups.index') }}">@lang('individual.mygroups')</a></li>
					<li><a href="{{ route('user-groups.create') }}">@lang('individual.createnewgroup')</a></li>
					{{-- <li><a href="{{ route('user.addmeeting') }}">@lang('individual.addameeting')</a></li> --}}
				</ul>
			</li>
			<li class="menu-title">
				Peer to Peer
			</li>
			{{-- <li>
				<a href="{{ route('payment.index') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-money-check-alt"></i></span>
					<span class="s-text">@lang('individual.addpaymentdetails')</span>
				</a>
			</li> --}}
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="far fa-credit-card"></i></span>
					<span class="s-text">@lang('individual.lending')</span>
				</a>
				<ul>
					<li><a href="{{ route('user-packages.index') }}">@lang('individual.mypackages')</a></li>
					<li><a href="{{ route('user-packages.create') }}">@lang('individual.createpackage')</a></li>
				</ul>
			</li>

			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="far fa-credit-card"></i></i></span>
					<span class="s-text">@lang('individual.borrowing')</span>
				</a>
				<ul>
					<li><a href="{{ route('user.browseloans') }}">@lang('individual.borrow')</a></li>
					<li><a href="{{ route('user-loans.index') }}">@lang('individual.myloans')</a></li>
				</ul>
			</li>

			<li>
				<a href="{{ route('userwallet') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-wallet"></i></span>
					<span class="s-text">@lang('individual.wallet')</span>
				</a>
			</li>
			<li>
				<a href="{{ route('user.transactions') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="far fa-money-bill-alt"></i></span>
					<span class="s-text">@lang('individual.transactions')</span>
				</a>
			</li>

			<li class="menu-title">@lang('individual.analyticsandreporting')</li>
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="fas fa-chart-bar"></i></span>
					<span class="s-text">@lang('individual.graphicalanalysis')</span>
				</a>
				<ul>
					<li><a href="{{ route('user.loancollectionschart') }}">@lang('individual.loancollectionschart')</a></li>
					<li><a href="{{ route('user.loanmaturitychart') }}">@lang('individual.loanmaturitychart')</a></li>
					<li><a href="{{ route('user.loanreleasedchart') }}">@lang('individual.loanreleasedchart')</a></li>
					<li><a href="{{ route('user.genderchart') }}">@lang('individual.genderchart')</a></li>
					<li><a href="{{ route('user.balancechart') }}">@lang('individual.balancechart')</a></li>
					<li><a href="{{ route('user.averageloantenurechart') }}">@lang('individual.averageloantenurechart')</a></li>
					<li><a href="{{ route('user.savingschart') }}">@lang('individual.savingschart')</a></li>
				</ul>
			</li>
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="fas fa-file-pdf"></i></span>
					<span class="s-text">@lang('individual.reports')</span>
				</a>
				<ul>
					<li><a href="{{ route('user.reportw') }}">@lang('individual.reportw')</a></li>
					<li><a href="{{ route('user.reportb') }}">@lang('individual.reportb')</a></li>
					<li><a href="{{ route('user.reportl') }}">@lang('individual.reportl')</a></li>
					<li><a href="{{ route('user.reportt') }}">@lang('individual.reportt')</a></li>
					<li><a href="{{ route('user.reportt') }}">@lang('individual.reportgroup')</a></li>
					<li><a href="{{ route('user.reportcashflow') }}">@lang('individual.reportcashflow')</a></li>
					<li><a href="{{ route('user.reportdisbursement') }}">@lang('individual.reportdisbursement')</a></li>
					<li><a href="{{ route('user.reportprofitloss') }}">@lang('individual.reportprofitloss')</a></li>
					<li><a href="{{ route('user.reportpendingdues') }}">@lang('individual.reportpendingdues')</a></li>
				</ul>
			</li>

			<li class="menu-title">@lang('individual.settingsandconfigurations')</li>
			<li>
				<a href="{{ route('user.preferences') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="fas fa-cogs"></i></span>
					<span class="s-text">@lang('individual.preferences')</span>
				</a>
			</li>
		</ul>
	</div>
</div>
