@extends('back/organization/layouts/master')

@section('title')
	Approved Loans
@endsection

@section('one-step')
    / Loans
@endsection

@section('spec-styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
	<div class="card">
		<div class="card-header">
			<h4 class="mb-1">All Your Approved Loans</h4>
            <div class="card-header-action">
                <form action="{{ route('organization.generate_loans_csv') }}" method="post" target="_blank">
                @csrf
                <button type="submit" class="btn btn-info btn-icon icon-right">Generate CSV<i class="fas fa-download"></i></button>
                </form>
            </div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-striped table-hover table-bordered table-2 datatable"> {{-- sid="tableExport" --}}
					@if ($isFirstSource)
					<thead>
						<tr>
                            <th>S/N</th>
							<th>Applicant's Name</th>
							<th>Disbursement Date</th>
							<th>Principal</th>
							<th>Interest</th>
							<th>No. of Installments</th>
							<th>Total Expected Payment</th>
							<th>Amount Repaid</th>
							<th>Outstanding Amount</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
                        @php
                            $sn = 1;
                        @endphp
						@foreach($loans as $loan)
							@php
                                $interest = 0.00;
                                foreach ($loan->repayment_schedules as $schedule) {
                                    $interest += floatval($schedule->interest);
                                }
                                $total = $loan->application->amount + $interest;

                                $outstanding = $total;
                                $repaid = 0.00;
                                foreach ($loan->repayments as $repayment){
                                    $outstanding -= $repayment->actual_amount;
                                    $repaid += $repayment->actual_amount;
                                }
							@endphp
							<tr>
                                <td>{{ $sn++ }}</td>
								<td>{{ $loan->application->inputs->first_name . ' ' . $loan->application->inputs->last_name }}</td>
								<td>{{ date('M j, Y', strtotime($loan->created_at)) }}</td>
								{{-- <td>{{ date('M j, Y', strtotime($loan->created_at)) . ' at ' . date('H:i', strtotime($loan->created_at)) }}</td> --}}
								<td>{{ $loan->application->loan_type->currency }} {{ number_format($loan->application->amount, 2) }}</td>
								<td>{{ $loan->application->loan_type->currency }} {{ number_format($interest, 2) }}</td>
								<td>{{ count($loan->repayment_schedules) . ' (' . strtolower($loan->metric->repayment_frequency) . ')' }}</td>
								<td>{{ $loan->application->loan_type->currency }} {{ number_format($loan->expected_amount, 2) }}</td>
								<td>{{ $loan->application->loan_type->currency }} {{ number_format($repaid, 2) }}</td>
								<td>{{ $loan->application->loan_type->currency }} {{ number_format($outstanding, 2) }}</td>
								<td>
									<a href="{{ route('organization.loan_details', $loan->id) }}" class="btn btn-sm btn-primary" title="View Loan">View</a> &nbsp;
								</td>
							</tr>
						@endforeach
					</tbody>
					@else
					<thead>
						<tr>
							<th>Title of Loan</th>
							<th>Amount</th>
							<th>Status</th>
							<th>Borrowed On</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
						@foreach($loans as $loan)
							<tr>
								<td>{{ $loan->loan_title }}</td>
								<td>{{ $loan->currency }} {{ number_format($loan->amount) }}</td>
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
									<a href="{{ route('org-loans.show', $loan->id) }}" class="btn btn-sm btn-primary" title="View Loan">View</a> &nbsp;
								</td>
							</tr>
						@endforeach
					</tbody>
					@endif
				</table>
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
