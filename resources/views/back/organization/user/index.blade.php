@extends('back/organization/layouts/master')

@section('title')
	Organization Users
@endsection

@section('one-step')
    / All Users
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
                    All Your Users
                </div>
                <div class="card-body">
                	<a href="{{ route('users.create') }}" class="mr-auto btn btn-primary">New user</a>
                	<a href="#uploadCsv" class="mr-auto btn btn-success" data-toggle="modal">Upload CSV</a>
                	<a href="{{ route('download-user-csv-template') }}" class="mr-auto btn btn-warning">Download CSV Template</a>
                	<hr>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-left" id="tableExport" style="width:100%;">
                            <thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>Phone Number</th>
									<th>Status</th>
									<th>Date Added</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($users as $user)
									<tr>
										<td>{{ $user->name }}</td>
										<td>{{ $user->other_names }}</td>
										<td>{{ $user->msisdn }}</td>
										<td>
											@if($user->status)
												Active
											@else
												Inactive
											@endif
										</td>
										<td>{{ date('M j, Y', strtotime($user->created_at)) . ' at ' . date('H:i', strtotime($user->created_at)) }}</td>
										<td>
											<a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-primary" title="View User">View</a> &nbsp;
											{!! Form::open(['route' => ['users.delete', $user->id], 'method' => 'DELETE', 'style' => 'display: inline-block']) !!}
			                                   {{Form::button('Delete', array('type' => 'submit', 'class' => 'btn btn-sm btn-danger', 'title' => 'Delete'))}}
			                                {!! Form::close() !!} &nbsp;&nbsp;
			                                {{-- @if($user->status)
			                                    {!! Form::open(['route' => ['user.deactivate', $user->id], 'method' => 'POST', 'style' => 'display: inline-block']) !!}
			                                       {{Form::button('Deactivate', array('type' => 'submit', 'class' => 'btn btn-sm btn-danger', 'title' => 'Deactivate'))}}
			                                    {!! Form::close() !!} &nbsp;&nbsp;
			                                @else
												{!! Form::open(['route' => ['user.activate', $user->id], 'method' => 'POST', 'style' => 'display: inline-block']) !!}
			                                       {{Form::button('Activate', array('type' => 'submit', 'class' => 'btn btn-sm btn-primary', 'title' => 'Activate'))}}
			                                    {!! Form::close() !!} &nbsp;&nbsp;
			                                @endif --}}
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
	<!-- Change Avatar Modal -->
	<div class="modal fade" id="uploadCsv" tabindex="-1" role="dialog" aria-labelledby="changeAvatar" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form action="{{ route('orgusers.import') }}" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<input type="hidden" name="org_id" value="{{ $organization->id }}">

					<div class="modal-body">
						<div class="row">
							<div class="col-md-12 text-center">
								<h5>Upload CSV</h5>
							</div>
						</div>
						<br>
						<div class="form-group">
							<label for="recipient-name" class="form-control-label">Upload CSV</label>
							<input type="file" class="form-control" name="file">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('layout.close')</button>
						<button type="submit" class="btn btn-primary">Import Members</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!--// End Change AVatar Modal //-->

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
