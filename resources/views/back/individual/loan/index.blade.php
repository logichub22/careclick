@extends('back/individual/layouts/master')

@section('title')
	My Loans
@endsection

@section('one-step')
    / Loan
@endsection

@section('spec-styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
	<div class="row">
        <div class="w-100">
            <div class="card">
                <div class="card-header">
                    <h4>All Your Loans</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-left" id="tableExport" style="width:100%;">
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
											<a href="{{ route('user-loans.show', $loan->id) }}" class="btn btn-sm btn-primary" title="View Loan">View</a> &nbsp;
										</td>
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
