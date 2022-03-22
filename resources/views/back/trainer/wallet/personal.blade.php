@extends('back/organization/layouts/master')

@section('title')
	Personal Wallet
@endsection

@section('page-nav')
	<h4>Wallet</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="#">Wallet</a></li>
		<li class="breadcrumb-item active">Personal</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-6 col-md-5">
			<div class="card text-xs-center">
				<div class="card-header">
					Receive Funds
				</div>
				<div class="card-block">
					<h4 class="card-title"><i class="fa fa-qrcode fa-3x" aria-hidden="true"></i></h4>
					<p class="card-text">{{ str_random(35) }}</p>
					<a href="#" class="btn btn-primary">Path: url/goes/here</a>
				</div>
			</div>
		</div>
		<div class="col-sm-6 col-md-7">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="box box-block bg-white tile tile-1 mb-2">
						<div class="t-icon right"><span class="bg-danger"></span><i class="fa fa-money"></i></div>
						<div class="t-content">
							<h6 class="text-uppercase mb-1">Balance</h6>
							<h1 class="mb-1">{{ $bal }}</h1>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="box box-block bg-white tile tile-1 mb-2">
						<div class="t-icon right"><span class="bg-success"></span><i class="fa fa-credit-card"></i></div>
						<div class="t-content">
							<h6 class="text-uppercase mb-1">Withdrawals</h6>
							<h1 class="mb-1">0</h1>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="box box-block bg-white tile tile-1 mb-2">
						<div class="t-icon right"><span class="bg-primary"></span><i class="fa fa-credit-card-alt"></i></div>
						<div class="t-content">
							<h6 class="text-uppercase mb-1">Deposits</h6>
							<h1 class="mb-1">&#8358; 0</h1>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<div class="box box-block bg-white">
				<h5 class="mb-1">All Your Deposits</h5>
				<div class="table-responsive">
					<table class="table table-striped table-hover table-bordered table-2">
						<thead>
							<tr>
								<th>Amount</th>
								<th>Transaction Date</th>
								<th>Payment Method</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="box box-block bg-white">
				<h5 class="mb-1">All Your Withdrawals</h5>
				<div class="table-responsive">
					<table class="table table-striped table-hover table-bordered table-2">
						<thead>
							<tr>
								<th>Amount</th>
								<th>Transaction Date</th>
								<th>Payment Method</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection