@extends('back/federation/layouts/master')

@section('title')
	Associations
@endsection

@section('one-step')
	/ Associations
@endsection

@section('spec-styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/bundles/flag-icon-css/css/flag-icon.min.css') }}">
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">
						All Associations
					</h5>
				</div>
				<div class="card-body">
					<div class="table-responsive">
		                <table class="table table-striped table-hover text-left" id="tableExport" style="width:100%;">
		                    <thead>
							<tr>
								<th>Organization Name</th>
								<th>Manager</th>
								<th>Address</th>
								<th>Status</th>
								<th>Created On</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($organizations as $organization)
								<tr>
									<td>{{ $organization->name }}</td>
									<td>{{ $organization->fname . ' ' . $organization->lname }}</td>
									<td>{{ $organization->address }}</td>
									<td>
										@if($organization->status)
											Active
										@else
											Inactive
										@endif
									</td>
									<td>{{ $organization->created_at }}</td>
									<td class="text-center">
										<a href="{{ route('associations.show', $organization->org_id) }}" class="btn btn-sm btn-primary" title="View Organization">View</i></a> &nbsp;
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