@extends('back/organization/layouts/master')

@section('title')
	Organization Ledger
@endsection

@section('one-step')
    / Organization Ledger
@endsection

@section('page-nav')
	<h4>Ledger</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a>Transactions</a></li>
        <li class="breadcrumb-item"><a href="{{ route('org.transactions') }}">All</a></li>
        <li class="breadcrumb-item active">Ledger</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-block bg-white">
				<h5 class="mb-1">Accounting ledger for your organization</h5>
				<div class="table-responsive">
					<table class="table table-hover table-striped table-2 table-bordered">
						 <thead>
							<tr>
								<th>Debits</th>
								<th>Amount</th>
								<th>Credits</th>
								<th>Amount</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection