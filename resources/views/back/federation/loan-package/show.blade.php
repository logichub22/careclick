@extends('back/organization/layouts/master')

@section('title')
	View Package
@endsection

@section('page-nav')
	<h4>View Package</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="#">Lending</a></li>
		<li class="breadcrumb-item"><a href="{{ route('org-packages.index') }}">My Packages</a></li>
		<li class="breadcrumb-item active">View Package</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-5 col-md-4">
			<div class="box bg-white">
				<ul class="nav nav-4">
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fa fa-home"></i> {{ $package->name }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="far fa-money-bill-alt"></i>  Currency
							<span class="float-xs-right">
								{{ $package->currency }}
							</span>
							{{-- <div class="tag tag-warning float-xs-right">&#8358;</div> --}}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fa fa-users"></i> Borrowers
							<div class="float-xs-right">0</div>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fas fa-calendar"></i> Repayment Plan
							<span class="float-xs-right">
								{{ $package->repayment_plan }}
							</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fas fa-retweet"></i> Loan Requests
							<div class="float-xs-right">0</div>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fas fa-laptop"></i> Status
							<div class="float-xs-right">
								@if($package->status)
									Active
								@else
									Inactive
								@endif
							</div>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fas fa-percent"></i>Minimum Credit Score
							<div class="float-xs-right">{{ $package->min_credit_score }}</div>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fa fa-balance-scale"></i> Interest Rate?
							<div class="float-xs-right">{{ $package->interest_rate }}% per annum</div>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fas fa-shield-alt"></i> Insured?
							<div class="float-xs-right">
								@if($package->insured)
									Yes
								@else
									No
								@endif
							</div>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-sm-7 col-md-8">
			<div class="box box-block bg-white">
				<h5 class="mb-1">Loans borrowed from this package</h5>
				<div class="table-responsive">
					<table class="table-striped table table-hover table-bordered table-2">
						<thead>
							<tr>
								<th>Borrower</th>
								<th>Amount</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($datas as $data)
								<tr>
									<td>{{ $data->name . ' ' . $data->other_names }}</td>
									<td>{{ number_format($data->amount) }}</td>
									<td>
										@if($data->status == 0)
											Pending
										@elseif($data->status == 1)
											Approved
										@elseif($data->status == 2)
											Declined
										@elseif($data->status == 3)
											Paid
										@else
											Defaulted
										@endif
									</td>
									<td><a href="{{ route('borrower.detail', $data->id) }}">More Details</a></td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection