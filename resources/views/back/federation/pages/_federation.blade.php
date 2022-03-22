<div class="row">
	<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
		<div class="box box-block tile tile-2 bg-danger mb-2">
			<div class="t-icon right"><i class="fa fa-users"></i></div>
			<div class="t-content">
				<h1 class="mb-1">{{ count($users) }}</h1>
				<h6 class="text-uppercase">Users</h6>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
		<div class="box box-block tile tile-2 bg-success mb-2">
			<div class="t-icon right"><i class="far fa-money-bill-alt"></i></div>
			<div class="t-content">
				<h1 class="mb-1">{{ number_format($wallet->balance) }}</h1>
				<h6 class="text-uppercase">Balance</h6>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
		<div class="box box-block tile tile-2 bg-primary mb-2">
			<div class="t-icon right"><i class="fas fa-warehouse"></i></div>
			<div class="t-content">
				<h1 class="mb-1">{{ count($associations) }}</h1>
				<h6 class="text-uppercase">Associations</h6>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
		<div class="box box-block tile tile-2 bg-warning mb-2">
			<div class="t-icon right"><i class="fas fa-store-alt"></i></div>
			<div class="t-content">
				<h1 class="mb-1">{{ count($groups) }}</h1>
				<h6 class="text-uppercase">Groups</h6>
			</div>
		</div>
	</div>
</div>

<div class="row row-md mb-1">
	<div class="col-md-12">
		<div class="box box-block bg-white">
			<div class="clearfix mb-1">
				<h5 class="float-xs-left">Financial statistics</h5>
				<div class="float-xs-right">
					<button class="btn btn-link btn-sm text-muted" type="button"><i class="ti-angle-down"></i></button>
					<button class="btn btn-link btn-sm text-muted" type="button"><i class="ti-reload"></i></button>
					<button class="btn btn-link btn-sm text-muted" type="button"><i class="ti-close"></i></button>
				</div>
			</div>
			<div id="advanced" class="chart-container mb-1" style="height: 295px;"></div>
			<div class="text-xs-center">
				<span class="mx-1"><i class="fa fa-circle text-success"></i> Deposits</span>
				<span class="mx-1"><i class="fa fa-circle text-warning"></i> Withdrawals</span>
				<span class="mx-1"><i class="fa fa-circle text-danger"></i> Loans Borrowed</span>
				<span class="mx-1"><i class="fa fa-circle text-primary"></i> Loans Lend Out</span>
			</div>
		</div>
	</div>
</div>

<div class="row">
	{{-- <div class="col-md-4">
		<div class="card text-xs-center">
			<div class="card-header text-uppercase"><b>Latest Users</b></div>
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
				<a href="{{ route('users.index') }}">View All Users</a>
			</div>
		</div>
	</div> --}}
	<div class="col-md-12">
		<div class="box box-block bg-white">
			<h5 class="mb-1">Latest Transactions</h5>
			<div class="table-responsive">
				<table class="table table-striped table-hover table-bordered table-2">
					<thead>
						<tr>
							<th>Amount</th>
							<th>Transaction Date</th>
							<th>Payment Method</th>
							<th>Status</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
