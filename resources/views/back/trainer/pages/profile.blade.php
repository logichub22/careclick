@extends('back/trainer/layouts/master')

@section('title')
	Profile
@endsection

@push('styles')
	<style>
		label{
			font-weight: bold;
		}
	</style>
@endpush

@section('page-nav')
	<h4>My Profile</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item active">Profile</li>
	</ol>
@endsection

@section('content')
	@if ($errors->any())
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif
	<div class="row">
		<div class="col-md-12">
			<div class="box box-block bg-white">
				<a href="#profile" class="btn btn-primary"><i class="fas fa-pencil-alt"></i> &nbsp;Edit Profile</a>
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
				<hr>
				<div class="row">
					<div class="col-md-3">
						<p><strong>Occupation</strong></p>
						<p>{{ $detail->occupation }}</p>
					</div>
					<div class="col-md-3">
						<p><strong>Income Class</strong></p>
						<p>{{ $income }}</p>
					</div>
					<div class="col-md-3">
						<p><strong>Residence</strong></p>
						<p>{{ $residence }}</p>
					</div>
					<div class="col-md-3">
						<p><strong>Marital Status</strong></p>
						<p>
							{{ $marital }}
						</p>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-3">
						<p><strong>Gender</strong></p>
						<p>{{ $gender }}</p>
					</div>
					<div class="col-md-3">
						<p><strong>Country</strong></p>
						<p>
							{{ $country }}
						</p>
					</div>
					<div class="col-md-3">
						<p><strong>Address</strong></p>
						<p>{{ $detail->address }}</p>
					</div>
					<div class="col-md-3">
						<p><strong>City</strong></p>
						<p>{{ $detail->city }}</p>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<p><strong>Organization</strong></p>
						<p>
							@if(is_null($organization))
								None
							@else
								{{ $organization->name }}
							@endif
						</p>
					</div>
					<div class="col-md-4">
						<p><strong>Account Balance</strong></p>
						<p>{{ number_format($wallet->balance) }}</p>
					</div>
					
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="box box-block bg-white">
				<h5 class="mb-1" id="profile">Update your details</h5>
				<form action="{{ route('trainerupdateprofile') }}" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-md-3">
                            <div class="form-group">
                                <label for="name">First Name <span class="important">*</span></label>
                                <input type="text" class="signup-input form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $user->name }}">
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						<div class="col-md-3">
							<div class="form-group{{ $errors->has('other_names') ? ' has-error' : '' }}">
								<label>Other Names <span class="important">*</span></label>
		                        <input type="text" name="other_names" placeholder="Other Names" class="form-control" value="{{ $user->other_names }}" />
		                         @if ($errors->has('user_name'))
		                            <span class="help-block">
		                                <strong>{{ $errors->first('user_name') }}</strong>
		                            </span>
		                        @endif
		                    </div>
						</div>
						<div class="col-md-3">
							<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
								<label>Email Address <span class="important">*</span></label>
				                <input type="email" name="email" placeholder="Email Address" class="form-control" value="{{ $user->email }}" />
				                @if ($errors->has('email'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('email') }}</strong>
				                    </span>
				                @endif
				            </div>
						</div>
						<div class="col-md-3">
							<div class="form-group{{ $errors->has('msisdn') ? ' has-error' : '' }}">
								<label>Phone Number <span class="important">*</span></label>
				                <input type="text" name="msisdn" placeholder="Personal Phone Number" class="form-control" value="{{ $user->msisdn }}" />
				                @if ($errors->has('msisdn'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('msisdn') }}</strong>
				                    </span>
				                @endif
				            </div>
						</div>
					</div>
					<div class="row">
                        <div class="col-md-4">
	                        <div class="form-group">
	                            <label for="state">Account No. <span class="important">*</span></label>
	                            <input type="text" class="signup-input form-control{{ $errors->has('account_no') ? ' is-invalid' : '' }}" name="account_no" value="{{ $user->account_no }}" placeholder="Bank Account Number">
	                            @if ($errors->has('account_no'))
	                                <span class="invalid-feedback" role="alert">
	                                    <strong>{{ $errors->first('account_no') }}</strong>
	                                </span>
	                            @endif
	                        </div>
	                    </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="occupation">Occupation <span class="important">*</span></label>
                                <input type="text" class="signup-input form-control{{ $errors->has('occupation') ? ' is-invalid' : '' }}" name="occupation" value="{{ $detail->occupation }}" placeholder="Occupation">
                                @if ($errors->has('occupation'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('occupation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="income">How much is your yearly income? <span class="important">*</span></label>
                                <select name="income" class="signup-input{{ $errors->has('income') ? ' is-invalid' : '' }} form-control">
									<option value="" disabled="" selected="">Select Income Class</option>
									@foreach($incomes as $income)
										<option value="{{ $income->id }}">{{ $income->name }}</option>
									@endforeach
								</select>
                            </div>
                        </div>
					</div>
					<div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="doc_type">Document of Identification <span class="important">*</span></label>
								<select name="doc_type" class="form-control{{ $errors->has('doc_type') ? ' is-invalid' : '' }} signup-input" value="{{ old('doc_type') }}">
									<option value="" disabled selected>Select Type of Document</option>
                                    @foreach($documents as $document)
                                        <option value="{{ $document->id }}">{{ $document->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('doc_type'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('doc_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label for="doc_no">Document Number <span class="important">*</span></label>
                                <input type="text" class="signup-input{{ $errors->has('doc_no') ? ' is-invalid' : '' }} form-control" name="doc_no" value="{{ $detail->doc_no }}">
                                @if ($errors->has('doc_no'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('doc_no') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-group">
									<label for="identification_document">
										@if(is_null($user->identification_document))
											Upload Document
										@else 
											Change Document File
										@endif <span class="important">*</span></label>
									<input type="file" name="identification_document" class="form-control signup-input">
									@if ($errors->has('identification_document'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('identification_document') }}</strong>
										</span>
									@endif
								</div>
                            </div>
                        </div>
					</div>
					<hr>
					<div class="row">
	                    <div class="col-md-6">
	                        <div class="form-group">
	                            <label for="city">City <span class="important">*</span></label>
	                            <input type="text" class="signup-input form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ $detail->city }}" placeholder="City">
	                            @if ($errors->has('city'))
	                                <span class="invalid-feedback" role="alert">
	                                    <strong>{{ $errors->first('city') }}</strong>
	                                </span>
	                            @endif
	                        </div>
	                    </div>
	                    <div class="col-md-6">
	                        <div class="form-group">
	                            <label for="state">State of Residence <span class="important">*</span></label>
	                            <input type="text" class="signup-input form-control{{ $errors->has('state') ? ' is-invalid' : '' }}" name="state" value="{{ $detail->state }}" placeholder="State of Residence">
	                            @if ($errors->has('state'))
	                                <span class="invalid-feedback" role="alert">
	                                    <strong>{{ $errors->first('state') }}</strong>
	                                </span>
	                            @endif
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
	                	<div class="col-md-6">
							<div class="form-group">
								<label for="postal_code">Postal Code <span class="important">*</span></label>
								<input type="text" class="signup-input form-control{{ $errors->has('postal_code') ? ' is-invalid' : '' }}" name="postal_code" placeholder="e.g. 28301" value="{{ $detail->postal_code }}" id="postal_code">
								@if ($errors->has('postal_code'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('postal_code') }}</strong>
									</span>
								@endif
							</div>
	                    </div>
	                    <div class="col-md-6">
							<div class="form-group">
								<label for="address">Permanent Address <span class="important">*</span></label>
								<input type="text" class="signup-input form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ $detail->address }}" id="address">
								@if ($errors->has('address'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('address') }}</strong>
									</span>
								@endif
							</div>
                        </div>
	                </div> 
	                <hr>
	                <div class="row">
						<div class="col-md-6">
							<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
								<label>New Password</label>
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
								<label>Confirm New Password</label>
				                <input type="password" name="password_confirmation" placeholder="Confirm Password" class="form-control" />
				            </div>
						</div>
	                </div>
					<div class="row">
						<div class="col-md-6 offset-md-3">
							<button type="submit" class="btn btn-primary btn-block">Update Profile</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		
	</div>

	<!-- Change Avatar Modal -->
	<div class="modal fade" id="changeAvatar" tabindex="-1" role="dialog" aria-labelledby="changeAvatar" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Change Avatar</h4>
				</div>
				<form action="{{ route('pic.change') }}" method="POST" enctype="multipart/form-data">
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
@endsection