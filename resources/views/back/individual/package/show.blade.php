@extends('back/individual/layouts/master')

@section('title')
	View Package
@endsection


@section('one-step')
/ Loan Packages / View
@endsection

@section('spec-styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-5 col-md-4">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">Package Details</h5>
				</div>
				<div class="card-body">
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
				 	Repayment Plan: {{ $package->repayment_plan }}
				 </p>
				 <p>
				 	Status: @if($package->status)
										Active
									@else
										Inactive
									@endif
				 </p>

				 <p>
					Minimum Credit Score: {{ $package->min_credit_score }}
				 </p>
				 <p>
				 	Interest Rate: {{ $package->interest_rate }}% per annum
				 </p>
				 <p>
				 	Insured? @if($package->insured)
										Yes
									@else
										No
									@endif
				 </p>
				 <a href="{{ route('user-packages.edit', $package->id) }}" class="btn btn-info mr-1">Edit</a></a> &nbsp;

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
					<table class="table-striped table table-hover table-bordered table-2" id="tableExport">
						<thead>
							<tr>
								<th>User</th>
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
									<td><a href="#">More Details</a></td>
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
@section('spec-scripts')
    <script src="{{ asset('assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/datatables.js') }}"></script>
@endsection
