@extends('back/superadmin/layouts/master')

@section('title')
	Profile
@endsection

@section('one-step')
	/ Profile
@endsection

@push('styles')
	<style>
		label{
			font-weight: bold;
		}
	</style>
@endpush

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<a href="#changeAvatar" data-toggle="modal" class="btn btn-primary"><i class="fas fa-user-tie"></i> &nbsp;Change Avatar</a>
				{{-- &nbsp; &nbsp; <a href="#profile" class="btn btn-primary"><i class="fas fa-pencil-alt"></i> &nbsp;Edit Profile</a> --}}
				<h5 class="mb-1 mt-1">Account Information</h5>
				<div class="row">
					<div class="col-md-3">
						<p><strong>Full Names</strong></p>
						<p>{{ $user->name . ' ' . $user->other_names }}</p>
					</div>
					<div class="col-md-3">
						<p><strong>Email Address</strong></p>
						<p>{{ $user->email }}</p>
					</div>
					<div class="col-md-3">
						<p><strong>Date Joined</strong></p>
						<p>{{ $user->created_at }}</p>
					</div>
					<div class="col-md-3">
						<p><strong>Account Number</strong></p>
						<p>
							@if(is_null($user->account_no))
								Not Set
							@else
								{{ $user->account_no }}
							@endif
						</p>
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
					<h5 class="mb-1" id="profile">Update your details</h5>
				</div>
				<div class="card-body">
					<form action="{{ route('super.updateprofile') }}" method="POST">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-md-4">
							<label for="name">First Name <span class="important">*</span></label>
							<input type="text" name="name" class="form-control" value="{{ $user->name }}">
						</div>
						<div class="col-md-4">
							<label for="other_names">Other Names <span class="important">*</span></label>
							<input type="text" name="other_names" class="form-control" value="{{ $user->other_names }}">
						</div>
						<div class="col-md-4">
							<label for="email">Email <span class="important">*</span></label>
							<input type="text" name="email" class="form-control" value="{{ $user->email }}">
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-4">
							<label for="msisdn">Phone Number <span class="important">*</span></label>
							<input type="text" name="msisdn" class="form-control" value="{{ $user->msisdn }}">
						</div>
						<div class="col-md-4">
							<label for="password">New Password</label>
							<input type="password" name="password" class="form-control" placeholder="New Password">
						</div>
						<div class="col-md-4">
							<label for="password_confirmation">Confirm New Password</label>
							<input type="password" name="password_confirmation" class="form-control" placeholder="Confirm New Password">
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-6 offset-md-3">
							<div class="form-group">
								<button class="btn btn-primary btn-block">Update Profile</button>
							</div>
						</div>
					</div>
				</form>
				</div>
			</div>
		</div>
		
	</div>
</div>
@endsection
<!-- Change Avatar Modal -->
	<div class="modal fade" id="changeAvatar" tabindex="-1" role="dialog" aria-labelledby="changeAvatar" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form action="{{ route('super.picchange') }}" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12 text-center">
								<h5>Current Avatar</h5>
								<img src="{{ asset('img/avatars/' . Auth::user()->avatar) }}" alt="Current Avatar">
							</div>
						</div>
						<br>
						<div class="form-group">
							<label for="recipient-name" class="form-control-label">Choose Pic:</label>
							<input type="file" class="form-control{{ $errors->has('avatar') ? ' is-invalid' : ''}}" name="avatar">
							@if($errors->has('avatar'))
								<span class="invalid-feedback" role="alert">
									 <strong>{{ $errors->first('avatar') }}</strong>
								</span>
							@endif
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Update Avatar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!--// End Change AVatar Modal //-->