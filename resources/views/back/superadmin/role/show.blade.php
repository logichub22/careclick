@extends('back/superadmin/layouts/master')

@section('title')
	View Role
@endsection

@section('one-step')
  / Role Detail
@endsection

@section('content')
	<div class="row">
        <div class="col-sm-4 col-md-3">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-4">
                        <li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fa fa-home"></i> {{ $role->name }}
						</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">
								<i class="far fa-money-bill-alt"></i> Users
								<div class="float-xs-right">{{ count($role->users) }}</div>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">
								<i class="fa fa-users"></i> Description 
								<div class="px-1">{{ $role->description }}</div>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">
								<i class="far fa-calendar-alt"></i> Added On {{ date('M j, Y', strtotime($role->created_at)) . ' at ' . date('H:i', strtotime($role->created_at)) }}
							</a>
						</li>
                    </ul>
                </div>
                <a href="#editRole" data-toggle="modal" class="btn btn-primary">Edit Role</a>
            </div>
        </div>
        <div class="col-sm-8 col-md-9">
             <div class="card">
                <div class="card-header">
                    <h4 class="mb-1">Users with role</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-left" id="tableExport" style="width:100%;">
                            <thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>Phone Number</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($users as $user)
									<tr>
										<td>{{ $user->name . ' ' . $user->other_names }}</td>
										<td>{{ $user->email }}</td>
										<td>{{ $user->msisdn }}</td>
										<td>
											<a href="{{ route('all-users.show', $user->id) }}" class="btn btn-primary btn-sm">View User</a>
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
    <!-- Edit Role Modal -->
  <div class="modal fade" id="editRole" tabindex="-1" role="dialog" aria-labelledby="editRole" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
          {{ csrf_field() }}
          {{ method_field('PATCH') }}
          <div class="modal-body">
            <div class="form-group">
              <label for="name" class="form-control-label">Role Name <span class="important">*</span></label>
              <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : ''}}" name="name" value="{{ $role->name }}" readonly="">
              @if($errors->has('name'))
                <span class="invalid-feedback" role="alert">
                   <strong>{{ $errors->first('name') }}</strong>
                </span>
              @endif
            </div>
            <div class="form-group">
              <label for="description" class="form-control-label">Description <span class="important">*</span></label>
              <textarea class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description">{{ $role->description }}</textarea>
              @if($errors->has('description'))
                <span class="invalid-feedback" role="alert">
                   <strong>{{ $errors->first('description') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update Role</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<!--// End Edit Role Modal //-->
@endsection