@extends('back/organization/layouts/master')

@section('title')
	Create a New Group
@endsection

@section('page-nav')
	<h4>New Group</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="#">Groups</a></li>
		<li class="breadcrumb-item"><a href="{{ route('groups.index') }}">My Groups</a></li>
		<li class="breadcrumb-item active">Create New group</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<div class="box box-block bg-white">
				<h5>New group</h5>
				<form action="{{ route('groups.store') }}" method="POST">
					{{ csrf_field() }}
					<input type="hidden" value="{{ $organization->id }}" name="org_id">
					<div class="form-group">
						<label for="name">Group Name <span class="important">*</span></label>
						<input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Name of your group" value="{{ old('name') }}" maxlength="100">
						@if($errors->has('name'))
							<span class="invalid-feedback" role="alert">
								<strong>{{ $errors->first('name') }}</strong>
							</span>
						@endif
					</div>
					<div class="form-group">
						<label for="account_no">Account</label>
						<input type="text" name="account_no" class="form-control{{ $errors->has('account_no') ? ' is-invalid' : '' }}" placeholder="Account Number" value="{{ old('account_no') }}" maxlength="100">
						@if($errors->has('account_no'))
							<span class="invalid-feedback" role="alert">
								<strong>{{ $errors->first('account_no') }}</strong>
							</span>
						@endif
					</div>
					<div class="form-group">
						<label for="comment">Comment <span class="important">*</span></label>
						<textarea name="comment" class="form-control{{ $errors->has('comment') ? ' is-invalid' : '' }}" rows="6" placeholder="Additional comments about this group"></textarea>
						@if($errors->has('comment'))
							<span class="invalid-feedback" role="alert">
								{{ $errors->first('comment') }}
							</span>
						@endif
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary btn-block">Create Group</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection