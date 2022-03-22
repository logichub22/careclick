@extends('back/organization/layouts/master')

@section('title')
	My Loan Packages
@endsection

@section('page-nav')
	<h4>Loan Packages</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="#">Lending</a></li>
		<li class="breadcrumb-item active">My Packages</li>
	</ol>
@endsection

@section('content')
	<div class="box box-block bg-white">
		<h5 class="mb-1">Loan Packages</h5>
		<div class="table-responsive">
			<table class="table table-hover table-bordered table-striped table-2">
				<thead>
					<tr>
						<th>Name</th>
						<th>Repayment Plan</th>
						<th>Interest Rate</th>
						<th>Currency</th>
						<th>Created On</th>
						<th>Action</th>
					</tr>
				</thead>

				<tbody>
					@foreach($packages as $package)
						<tr>
							<td>{{ $package->name }}</td>
							<td>{{ $package->repayment_plan }}</td>
							<td>{{ $package->interest_rate }}</td>
							<td>{{ $package->currency }}</td>
							<td>{{ $package->created_at }}</td>
							<td>
								<a href="{{ route('org-packages.show', $package->id) }}" class="btn btn-sm btn-primary" title="View Package"><i class="far fa-eye"></i></a> &nbsp;
								<a href="{{ route('org-packages.edit', $package->id) }}" class="btn btn-sm btn-success" title="Edit Package"><i class="fas fa-pencil-alt"></i></a> &nbsp;
								<a href="" class="btn btn-sm btn-danger" title="Delete Package"><i class="fa fa-trash"></i></a> &nbsp;
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection