@extends('back/superadmin/layouts/master')

@section('title')
	System Roles
@endsection

@section('one-step')
	/ Roles
@endsection

@section('spec-styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/prism/prism.css') }}">

@section('content')
	<div class="row">
        <div class="w-100">
            <div class="card">
                <div class="card-header">
                    <h4>All Roles</h4>
                </div>
                <div class="card-body">
                	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#roleModal">New Role</button>
                      <hr>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-left" id="tableExport" style="width:100%;">
                            <thead>
								<tr>
									<th>Role Name</th>
									<th>Role Description</th>
									<th>Users Assigned</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($roles as $role)
									<tr>
										<td>{{ $role->name }}</td>
										<td>{{ $role->description }}</td>
										<td>{{ count($role->users) }}</td>
										<td class="text-center">
											<a href="{{ route('roles.show', $role->id) }}" class="btn btn-sm btn-primary" title="View Role">View</i></a> &nbsp;
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

@section('spec-styles')
    <script src="{{ asset('assets/bundles/prism/prism.js') }}"></script>
    <!-- MODAL -->
    <div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="formModal"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="formModal">Add a system role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{ route('roles.store') }}" method="POST">
					{{ csrf_field() }}
					<div class="modal-body">
						<div class="form-group">
							<label for="name" class="form-control-label">Role Name <span class="important">*</span></label>
							<input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : ''}}" name="name" value="{{ old($role->name) }}">
							@if($errors->has('name'))
								<span class="invalid-feedback" role="alert">
									 <strong>{{ $errors->first('name') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group">
							<label for="description" class="form-control-label">Role Description <span class="important">*</span></label>
							<textarea class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description">{{ old($role->description) }}</textarea>
							@if($errors->has('description'))
								<span class="invalid-feedback" role="alert">
									 <strong>{{ $errors->first('description') }}</strong>
								</span>
							@endif
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Add Role</button>
					</div>
				</form>
              </div>
            </div>
          </div>
        </div>
@endsection