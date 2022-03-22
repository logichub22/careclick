@extends('back/organization/layouts/master')

@section('title')
	Browse Loans
@endsection

@section('page-nav')
	<h4>Available Loans</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="#">Borrowing</a></li>
		<li class="breadcrumb-item active">Available Loans</li>
	</ol>
@endsection

@section('content')
	<div class="box box-block bg-white">
		<h5 class="mb-1">Filter Loans</h5>
		<form id="filterForm" method="" action="">
			{{ csrf_field() }}
			<div class="row">
				<div class="col-md-4">
					<label>Repayment Plan</label>
					<select name="repayment_plan" id="repayment_plan" class="form-control" required>
						<option value="" disabled="" selected="">Select Plan</option>
						<option value="weekly">Weekly</option>
						<option value="bi-weekly">Bi-Weekly</option>
						<option value="monthly">Monthly</option>
					</select>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Minimum Interest Rate</label>
						<div class="form-group">
							<input type="text" class="form-control" name="min_interest" required placeholder="minimum interest">
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>Maximum Interest Rate</label>
						<div class="form-group">
							<input type="text" class="form-control" name="max_interest" required placeholder="maximum interest">
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Has Insurance?</label>
						<select name="insured" id="insured" class="form-control" required>
							<option value="" disabled="" selected="">Has Insurance?</option>
							<option value="1">Yes</option>
							<option value="0">No</option>
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<label>Min Amount</label>
					<input type="text" class="form-control" name="min_amount" required placeholder="minimum amount">
				</div>
				<div class="col-md-4">
					<label>Max Amount</label>
					<input type="text" class="form-control" name="max_amount" required placeholder="maximum amount">
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-12">
					<button type="submit" class="btn btn-primary btn-block">Apply Filter</button>
				</div>
			</div>
		</form>
	</div>
	<div class="box box-block bg-white">
		<h5 class="mb-1">Available Loans</h5>
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered table-2">
				<thead>
					<tr>
						<th>Loan Name</th>
						<th>Minimum Amount</th>
						<th>Maximum Amount</th>
						<th>Interest Per Annum</th>
						<th>Repayment Plan</th>
						<th>Insured</th>
						<th>Action</th>
					</tr>
				</thead>

				<tbody>
					@foreach($packages as $package)
						<tr>
							<td>{{ $package->name }}</td>
							<td>{{ $package->min_amount }}</td>
							<td>{{ $package->max_amount }}</td>
							<td>{{ $package->interest_rate }} %</td>
							<td>{{ $package->repayment_plan }}</td>
							<td>
								@if($package->insured)
									Yes
								@else
									No
								@endif
							</td>
							<td>
								<a href="{{ route('organization.applyloan', $package->id) }}" class="btn btn-sm btn-primary">Apply</a>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection

{{-- @push('scripts')
	<script>
		$(document).ready(function (e) {
			var form = $('#filterForm');
			form.submit(function(e) {
				event.preventDefault();
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.ajax({
					type: 'POST',
					url: '{{ URL::to('organization/filter-loans') }}',
	                dataType: "json",
	                success: function(data) {
	                	$('tbody').empty();
	                	$('tbody').html(data);
	                }
				});
			});
		})
	</script>
@endpush --}}