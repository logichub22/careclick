@extends('back/federation/layouts/master')

@section('title')
	All Trainers
@endsection

@section('one-step')
	/ Trainers
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
						All Trainers
					</h5>
				</div>
				<div class="card-body">
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
									<a href="{{ route('trainers.show', $user->id) }}" class="btn btn-sm btn-primary" title="View User">View</a> &nbsp;
									{{-- {!! Form::open(['route' => ['trainers.destroy', $user->id], 'method' => 'DELETE', 'style' => 'display: inline-block']) !!}                                
									   {{Form::button('<i class="fas fa-trash"></i>', array('type' => 'submit', 'class' => 'btn btn-sm btn-danger', 'title' => 'Delete'))}}
									{!! Form::close() !!} &nbsp;&nbsp; --}}
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