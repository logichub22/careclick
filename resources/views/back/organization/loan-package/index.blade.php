@extends('back/organization/layouts/master')

@section('title')
	My Loan Packages
@endsection

@section('one-step')
    / Loan Packages
@endsection

@section('spec-styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
	<div class="card">
		<div class="card-header">
			<h5 class="mb-1">Loan Packages</h5>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-hover table-bordered table-striped table-2" id="tableExport">
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
									<a href="{{ route('org-packages.show', $package->id) }}" class="btn btn-sm btn-primary" title="View Package">View</a> &nbsp;
									<a href="{{ route('org-packages.edit', $package->id) }}" class="btn btn-sm btn-success" title="Edit Package">Edit</a> &nbsp;
									<a href="{{ route('org-packages.delete', $package->id) }}" data-toggle="modal" data-target="#delete-package" data-id="{{ $package->id }}" class="btn btn-sm btn-danger delete-btn" title="Delete Package">Delete</a> &nbsp;
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('spec-scripts')
		
		@if(count($packages) > 0 )
			<div class="modal fade" id="delete-package" tabindex="-1" role="dialog" aria-labelledby="delete-package" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Delete Loan Package?</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
								</button>
						</div>
						<div class="modal-body">
							<p>Are you sure you want to delete this loan package? Please note that you cannot undo this action!</p>
						</div>
						<div class="modal-footer">
							<a class="btn btn-danger" id="delete-confirm-btn">Yes, Continue</a>
							<button class="btn btn-secondary" data-dismiss="modal">No</button>
						</div>
					</div>
				</div>
			</div>
		@endif

    <script src="{{ asset('assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/datatables.js') }}"></script>

		<script>
			$(document).ready(function(){
				let deleteBtn = $(".delete-btn");
				if(deleteBtn.length > 0){
					deleteBtn.on('click', function(e){
						let deleteUrl = $(this).attr('href');
						$("#delete-confirm-btn").attr('href', deleteUrl);
					});
				}
			});
		</script>
@endsection