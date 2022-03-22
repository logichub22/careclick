@extends('back/superadmin/layouts/master')

@section('title')
	View Organization
@endsection

@section('one-step')
	/ Organization Details
@endsection

@section('spec-styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-4 col-md-3">
			<div class="card">
				<div class="card-body">
					<ul class="nav nav-4">
						<li class="nav-item">
							<a class="nav-link" href="#">
								Date Created: {{ $organization->created_at }} 
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">
								Organization Status: 
								@if($organization->status)
									Active
								@else
									Inactive
								@endif
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">
								Members: {{ count($members) }} 
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
			<!-- <div>
			@if($organization->status)
				{{-- <a href="#" class="btn btn-danger btn-block" id="cancel">Cancel Membership</a> --}}
				{!! Form::open(['route' => ['organization.deactivate', $organization->org_id],'method' => 'POST', 'style' => 'display: inline-block', 'id' => 'deactivateForm']) !!}  
				<input type="hidden" name="organization" value="{{ $organization->name }}" id="name">                        
				<input type="hidden" name="org_id" value="{{ $organization->org_id }}"></input>
				{{Form::button('Deactivate', array('id'=> 'deactivate', 'class' => 'btn btn-block btn-danger', 'title' => 'Deactivate Organization'))}}
				{!! Form::close() !!}
			@else
				{!! Form::open(['route' => ['organization.activate', $organization->org_id],'method' => 'POST', 'style' => 'display: inline-block', 'id' => 'activateForm']) !!} 
				<input type="hidden" name="organization" value="{{ $organization->name }}" id="name">    
				<input type="hidden" name="org_id" value="{{ $organization->org_id }}"></input>      
				{{Form::button('Activate', array('id'=> 'activate', 'class' => 'btn btn-block btn-success', 'title' => 'Activate Organization'))}}
				{!! Form::close() !!}
			@endif
		</div> -->
		<div class="col-sm-8 col-md-9">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">Organization Details</h5>
				</div>
				<div class="card-body">
					<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<strong>Organization Name</strong>
							<p>{{ $organization->name }}</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<strong>Address</strong>
							<p>{{ $organization->address }}</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<strong>Domain</strong>
							<p><a href="{{ $organization->domain }}">{{ $organization->domain }}</a></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<strong>Organization Email</strong>
							<p>{{ $organization->org_email }}</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<strong>Organization Phone Number</strong>
							<p>+{{ $organization->org_msisdn }}</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<strong>Type of Organization</strong>
							<p>
								@if($organization->is_financial)
									Financial
								@else
									Service Provider
								@endif
							</p>
						</div>
					</div>
				</div>
				<hr>
				<h5 class="mb-1">Admin Details</h5>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<strong>Manager</strong>
							<p>{{ $organization->fname . ' ' . $organization->lname }}</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<strong>Admin Phone Number</strong>
							<p>+{{ $organization->userphone }}</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<strong>Admin Email</strong>
							<p>
								{{ $organization->useremail }}
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">Organization Users</h5>
				</div>
				<div class="card-body">
					<div class="table-responsive">
                        <table class="table table-striped table-hover text-left" id="tableExport" style="width:100%;">
                            <thead>
							<th>Name</th>
							<th>Email</th>
							<th>Phone Number</th>
							<th>Account Number</th>
							<th>Action</th>
						</thead>
						<tbody>
							@foreach ($members as $member)
								<tr>
									<td>{{ $member->name . ' ' . $member->other_names }}</td>
									<td>{{ $member->email }}</td>
									<td>{{ $member->msisdn }}</td>
									<td>
										@if(is_null($member->account_no))
											Not Set
										@else
											{{ $member->account_no }}
										@endif
									</td>
									<td class="text-center">
										<a href="{{ route('all-users.show', $member->id) }}" class="btn btn-sm btn-primary" title="View Member">View</i></a> &nbsp;
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
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script>
		$(document).ready(function() {
			// Cancel Membership
			$('#deactivate').click(function(event) {
				event.preventDefault();
				var name = $('#name').val();
				swal({
				  title: "Are you sure?",
				  text: "By clicking OK, " + name + " will be deactivated and all its members",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {
				    $('#deactivateForm').submit();
				  } else {
				 //    swal({
					//   title: "Are you sure?",
					//   text: "By clicking OK, " + name + "'s membership will be cancelled",
					//   icon: "warning",
					//   buttons: true,
					//   dangerMode: true,
					// })
				  }
				});   
			});

			// Renew Membership
			$('#activate').click(function(event) {
				event.preventDefault();
				var name = $('#name').val();
				swal({
				  title: "Are you sure?",
				  text: "By clicking OK, " + name + " will be activated",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {
				    $('#activateForm').submit();
				  } else {
				 //    swal({
					//   title: "Are you sure?",
					//   text: "By clicking OK, " + name + "'s membership will be cancelled",
					//   icon: "warning",
					//   buttons: true,
					//   dangerMode: true,
					// })
				  }
				});   
			});
		})
	</script>

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