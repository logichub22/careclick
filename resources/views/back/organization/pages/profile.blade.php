@extends('back/organization/layouts/master')

@section('title')
	@lang('layout.profile')
@endsection

@section('one-step')
    / Profile
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-4 col-md-3">
			<div class="box bg-white">
				<ul class="nav nav-4">
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fa fa-home"></i> {{ $organization->name }} 
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fa fa-globe"></i> 
							@if(!is_null($organization->domain))
								{{ $organization->domain }}
							@else
								@lang('layout.notavailable')
							@endif
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fa fa-home"></i>	{{ $organization->address }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fa fa-phone"></i> 
							@if(!is_null($organization->org_msisdn))
								{{ $organization->org_msisdn }}
							@else
								@lang('layout.notavailable')
							@endif
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="far fa-calendar-alt"></i> Created {{ $organization->created_at }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fa fa-envelope"></i>
							@if(!is_null($organization->org_email))
								{{ $organization->org_email }}
							@else
								@lang('layout.notavalable')
							@endif
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fa fa-users"></i> @lang('layout.members')
							<div class="tag tag-primary float-xs-right">0</div>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#changeAvatar" data-toggle="modal">
							<i class="fas fa-pencil-alt"></i> @lang('layout.changeavatar')
						</a>
					</li>
				</ul>
			</div>
			<div class="box bg-white">
				<ul class="nav nav-4">
					<li class="nav-item">
						<a class="nav-link" href="{{ route('streampermit') }}">
							<i class="fas fa-file-pdf"></i> @lang('layout.viewpermitfile')
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('streamtax') }}">
							<i class="fas fa-file-pdf"></i> @lang('layout.viewtaxcertificate') 
						</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="col-sm-8 col-md-9">
			<div class="card">
				<div class="card-body">
				<h5 class="mb-1">@lang('layout.updateyourprofile')</h5>
				<form action="{{ route('organization.update') }}" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-md-4">
							<div class="form-group{{ $errors->has('name') ? ' is-invalid' : '' }}">
								<label>@lang('layout.organizationname')</label>
				                <input type="text" name="name" placeholder="Organization Name" class="form-control" readonly value="{{ $organization->name }}" />
				                @if ($errors->has('name'))
				                    <span class="invalid-feedback" role="alert">
				                        <strong>{{ $errors->first('name') }}</strong>
				                    </span>
				                @endif
				            </div>
						</div>
						<div class="col-md-4">
							<div class="form-group{{ $errors->has('org_msisdn') ? ' is-invalid' : '' }}">
								<label>@lang('layout.businessphonenumber')</label>
				                <input type="text" name="org_msisdn" placeholder="Phone Number" class="form-control" value="{{ $organization->org_msisdn }}" />
				                @if ($errors->has('org_msisdn'))
				                    <span class="invalid-feedback" role="alert">
				                        <strong>{{ $errors->first('org_msisdn') }}</strong>
				                    </span>
				                @endif
				            </div>
						</div>
						<div class="col-md-4">
							<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
								<label>@lang('layout.address')</label>
				                <input type="text" name="address" placeholder="Physical Address" class="form-control" id="address" value="{{ $organization->address }}" />
				                @if ($errors->has('address'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('address') }}</strong>
				                    </span>
				                @endif
				            </div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group{{ $errors->has('domain') ? ' has-error' : '' }}">
								<label>@lang('layout.domain')</label>
				            	<input type="text" name="domain" placeholder="Domain" class="form-control" value="{{ $organization->domain }}" />
				                @if ($errors->has('domain'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('domain') }}</strong>
				                    </span>
				                @endif
				            </div>
						</div>
						<div class="col-md-6">
							<div class="form-group{{ $errors->has('org_email') ? ' has-error' : '' }}">
								<label>@lang('layout.organizationemail')</label>
				            	<input type="text" name="org_email" placeholder="Organization Email" class="form-control" value="{{ $organization->org_email }}" />
				                @if ($errors->has('org_email'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('org_email') }}</strong>
				                    </span>
				                @endif
				            </div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
								<label>@lang('layout.firstname')</label>
		                        <input type="text" name="first_name" placeholder="Your Name" class="form-control" value="{{ $user->name }}" />
		                         @if ($errors->has('first_name'))
		                            <span class="help-block">
		                                <strong>{{ $errors->first('first_name') }}</strong>
		                            </span>
		                        @endif
		                    </div>
						</div>
						<div class="col-md-6">
							<div class="form-group{{ $errors->has('other_names') ? ' has-error' : '' }}">
								<label>@lang('layout.othernames')</label>
		                        <input type="text" name="other_names" placeholder="Other Names" class="form-control" value="{{ $user->other_names }}" />
		                         @if ($errors->has('user_name'))
		                            <span class="help-block">
		                                <strong>{{ $errors->first('user_name') }}</strong>
		                            </span>
		                        @endif
		                    </div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
								<label>@lang('layout.emailaddress')</label>
				                <input type="email" name="email" placeholder="Email Address" class="form-control" value="{{ $user->email }}" />
				                @if ($errors->has('email'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('email') }}</strong>
				                    </span>
				                @endif
				            </div>
						</div>
						<div class="col-md-6">
							<div class="form-group{{ $errors->has('msisdn') ? ' has-error' : '' }}">
								<label>@lang('layout.personalphonenumber')</label>
				                <input type="text" name="msisdn" placeholder="Personal Phone Number" class="form-control" value="{{ $user->msisdn }}" />
				                @if ($errors->has('msisdn'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('msisdn') }}</strong>
				                    </span>
				                @endif
				            </div>
						</div>
					</div>
					<hr>
					<div class="row">
						{{-- <div class="col-md-4">
							<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
								<label>@lang('layout.oldpassword')</label>
				                <input type="password" name="old_password" placeholder="Password" class="form-control" />
				                @if ($errors->has('old_password'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('old_password') }}</strong>
				                    </span>
				                @endif
				            </div>
						</div> --}}
						<div class="col-md-6">
							<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
								<label>@lang('layout.newpassword')</label>
				                <input type="password" name="password" placeholder="Password" class="form-control" />
				                @if ($errors->has('password'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('password') }}</strong>
				                    </span>
				                @endif
				            </div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>@lang('layout.confirmnewpassword')</label>
				                <input type="password" name="password_confirmation" placeholder="Confirm Password" class="form-control" />
				            </div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-6">
							<label>@lang('layout.changepermitfile')</label>
							<div class="form-group{{ $errors->has('permit') ? ' has-error' : '' }}">
								<input type="file" name="permit" placeholder="Choose Avatar" class="form-control" />
								@if ($errors->has('permit'))
			                        <span class="help-block">
			                            <strong>{{ $errors->first('permit') }}</strong>
			                        </span>
			                    @endif
							</div>
						</div>
						<div class="col-md-6">
							<label>@lang('layout.changetaxcertificate')</label>
							<div class="form-group{{ $errors->has('tax') ? ' has-error' : '' }}">
								<input type="file" name="tax" placeholder="Choose Avatar" class="form-control" />
								@if ($errors->has('tax'))
			                        <span class="help-block">
			                            <strong>{{ $errors->first('tax') }}</strong>
			                        </span>
			                    @endif
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-12">
						<button type="submit" class="btn btn-primary btn-block">@lang('layout.updatedetails')</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		</div>
	</div>
@endsection

@section('spec-scripts')
	<!-- Change Avatar Modal -->
	<div class="modal fade" id="changeAvatar" tabindex="-1" role="dialog" aria-labelledby="changeAvatar" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form action="{{ route('avatar.change') }}" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12 text-center">
								<h5>@lang('layout.currentavatar')</h5>
								<img src="{{ asset('img/avatars/' . Auth::user()->avatar) }}" alt="Current Avatar">
							</div>
						</div>
						<br>
						<div class="form-group">
							<label for="recipient-name" class="form-control-label">@lang('layout.changepic'):</label>
							<input type="file" class="form-control{{ $errors->has('avatar') ? ' is-invalid' : ''}}" name="avatar">
							@if($errors->has('avatar'))
								<span class="invalid-feedback" role="alert">
									 <strong>{{ $errors->first('avatar') }}</strong>
								</span>
							@endif
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('layout.close')</button>
						<button type="submit" class="btn btn-primary">@lang('layout.updateavatar')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!--// End Change AVatar Modal //-->
@endsection