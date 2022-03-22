<div class="row">
	<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
		<div class="box box-block tile tile-2 bg-danger mb-2">
			<div class="t-icon right"><i class="fa fa-users"></i></div>
			<div class="t-content">
				<h1 class="mb-1">{{ count($users) }}</h1>
				<h6 class="text-uppercase">@lang('layout.users')</h6>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
		<div class="box box-block tile tile-2 bg-success mb-2">
			<div class="t-icon right"><i class="far fa-money-bill-alt"></i></div>
			<div class="t-content">
				<h1 class="mb-1">{{ number_format($wallet->balance) }}</h1>
				<h6 class="text-uppercase">@lang('layout.balance')</h6>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
		<div class="box box-block tile tile-2 bg-primary mb-2">
			<div class="t-icon right"><i class="fas fa-chart-line"></i></div>
			<div class="t-content">
				<h1 class="mb-1">{{ count($groups) }}</h1>
				<h6 class="text-uppercase">@lang('layout.Groups')</h6>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
		<div class="box box-block tile tile-2 bg-warning mb-2">
			<div class="t-icon right"><i class="fas fa-chart-pie"></i></div>
			<div class="t-content">
				<h1 class="mb-1">0</h1>
				<h6 class="text-uppercase">@lang('layout.totalmoneylendout')</h6>
			</div>
		</div>
	</div>
</div>

<div class="row row-md mb-1">
	<div class="col-md-4">
		<div class="box bg-white user-1">
			<div class="u-img img-cover" style="background-image: url(../img/front/landing/home-banner.png);"></div>
			<div class="u-content">
				<div class="avatar box-64">
					<img class="b-a-radius-circle shadow-white" src="{{ asset('img/back/main/team.png') }}" alt="">
					<i class="status bg-success bottom right"></i>
				</div>
				<h5><a class="text-black" href="#">{{-- {{ $organization->name }} --}}</a></h5>
				<p class="text-muted pb-0-5">{{-- {{ $organization->address }} --}}</p>
				<div class="text-xs-center pb-0-5">
					<a href="" class="btn btn-outline-primary btn-rounded mr-0-5">@lang('layout.newuser') <i class="ti-plus ml-0-5"></i></a>
					<a href="{{ route('groups.create') }}" class="btn btn-primary btn-rounded">@lang('layout.newgroup') <i class="ti-plus ml-0-5"></i></a>
				</div>
			</div>
			<div class="u-counters">
				<div class="row no-gutter">
					<div class="col-xs-6 uc-item">
						<a class="text-black">
							<strong>0</strong>
							<span>@lang('layout.users')</span>
						</a>
					</div>
					<div class="col-xs-6 uc-item">
						<a class="text-black">
							<strong>0</strong>
							<span>@lang('layout.savingsgroup')</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="box box-block bg-white">
			<div class="clearfix mb-1">
				<h5 class="float-xs-left">@lang('layout.financialstatistics')</h5>
				<div class="float-xs-right">
					<button class="btn btn-link btn-sm text-muted" type="button"><i class="ti-angle-down"></i></button>
					<button class="btn btn-link btn-sm text-muted" type="button"><i class="ti-reload"></i></button>
					<button class="btn btn-link btn-sm text-muted" type="button"><i class="ti-close"></i></button>
				</div>
			</div>
			<div id="advanced" class="chart-container mb-1" style="height: 295px;"></div>
			<div class="text-xs-center">
				<span class="mx-1"><i class="fa fa-circle text-success"></i> @lang('layout.deposits')</span>
				<span class="mx-1"><i class="fa fa-circle text-warning"></i> @lang('layout.withdrawals')</span>
				<span class="mx-1"><i class="fa fa-circle text-danger"></i> @lang('layout.loansborrowed')</span>
				<span class="mx-1"><i class="fa fa-circle text-primary"></i> @lang('layout.loanslendout')</span>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<div class="card text-xs-center">
			<div class="card-header text-uppercase"><b>@lang('layout.latestusers')</b></div>
			<div class="card-block">
				<div class="avatars">
					@foreach($users as $user)
						<a href="{{ route('users.show', $user->id) }}">
							<div class="avatar box-48 mr-0-75">
								<img class="b-a-radius-circle" src="{{ asset('img/avatars/' . Auth::user()->avatar) }}" alt="" title="{{ $user->name . ' ' . $user->other_names }}">
							</div>
						</a>
					@endforeach
				</div>
			</div>
			<div class="card-footer text-muted">
				<a href="{{ route('users.index') }}">@lang('layout.viewallusers')</a>
			</div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="box box-block bg-white">
			<h5 class="mb-1">@lang('layout.latesttransactions')</h5>
			<div class="table-responsive">
				<table class="table table-striped table-hover table-bordered table-2">
					<thead>
						<tr>
							<th>@lang('layout.amount')</th>
							<th>@lang('layout.transactiondate')</th>
							<th>@lang('layout.paymentmethod')</th>
							<th>@lang('layout.status')</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
