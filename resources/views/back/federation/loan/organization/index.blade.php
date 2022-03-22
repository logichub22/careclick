@extends('back/organization/layouts/master')

@section('title')
	My Loans
@endsection

@section('page-nav')
	<h4>Loans</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="#">Borrowing</a></li>
		<li class="breadcrumb-item active">My Loans</li>
	</ol>
@endsection

@section('content')
	<div class="box box-block bg-white">
		<h5 class="mb-1">All Your Loans</h5>
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered table-2">
				<thead>
					<tr>
						<th>Title of Loan</th>
						<th>Amount</th>
						<th>Status</th>
						<th>Borrowed On</th>
						<th>Action</th>
					</tr>
				</thead>

				<!-- Fake Data -->
				<tbody>
					@foreach($loans as $loan)
						<tr>
							<td>{{ $loan->loan_title }}</td>
							<td>&#8358; {{ number_format($loan->amount) }}</td>
							<td>
								@if($loan->status == 0)
									Pending
								@elseif($loan->status == 1)
									Approved
								@elseif($loan->status == 2)
									Declined
								@elseif($loan->status == 3)
									Paid
								@else
									Defaulted
								@endif
							</td>
							<td>{{ date('M j, Y', strtotime($loan->created_at)) . ' at ' . date('H:i', strtotime($loan->created_at)) }}</td>
							<td>
								<a href="{{ route('org-loans.show', $loan->id) }}" class="btn btn-sm btn-primary" title="View Loan"><i class="far fa-eye"></i></a> &nbsp;
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection