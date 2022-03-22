@extends('back/organization/layouts/master')

@section('title')
	View Package
@endsection

@section('one-step')
    / View Package Detail
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-5 col-md-4">
			<div class="card">
				<div class="card-body">
					<a href="{{ route('org-packages.edit', $package->id) }}" class="btn btn-primary btn-block" id="edit-package">Edit Loan Package</a>
					{{-- <button href="#loanSchedule" class="btn btn-danger btn-block" id="delete-package" data-toggle="modal">Delete Loan Package</button> --}}
					<br>
					<p>
				 	Package Name: {{ $package->name }}
				 </p>
				 <p>
				 	Currency: {{ $package->currency }}
				 </p>
				 <p>
				 	Borrowers: {{ count($datas) }}
				 </p>
				 <!-- <p>
				 	Loan Requests
				 </p> -->
				 <p>
				 	Repayment Plan: {{ $isFirstSource ? $package->repayment_frequency : $package->repayment_plan }}
				 </p>
				 <p>
				 	Status: @if((!$isFirstSource && $package->status) || ($isFirstSource && $package->enabled))
										Active
									@else
										Inactive
									@endif
				 </p>
				 @if (!$isFirstSource)
				 <p>
					Minimum Credit Score: {{ $package->min_credit_score }}
				 </p>
				 <p>
				 	Daily Interest Rate: {{ round($daily_interest, 2) }}% per day
				 </p>
				 <p>
				 	Weekly Interest Rate: {{ round($weekly_interest, 2) }}% per week
				 </p>
				 <p>
				 	Monthly Interest Rate: {{ round($monthly_interest, 2) }}% per month
				 </p>
				 <p>
				 	Interest Rate: {{ $package->interest_rate }}% per annum
				 </p>
				 @else
				 <p>Interest Rate: {{ $package->interest_rate }}% per month</p>
				 @endif
				 @if (!$isFirstSource)
				 <p>
				 	Insured? @if($package->insured)
										Yes
									@else
										No
									@endif
				 </p>
				 <a href="{{ route('org-packages.edit', $package->id) }}" class="btn btn-info mr-1">Edit</a></a> &nbsp;
				 @endif
				</div>
			</div>
		</div>
		<div class="col-sm-7 col-md-8">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">Loans borrowed from this package</h5>
				</div>
				<div class="card-body">
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
	</div>
@endsection