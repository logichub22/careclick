@extends('back/organization/layouts/master')

@section('title')
	Add New Group Member
@endsection

@section('one-step')
    / Add New Group Member
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
		<div class="col-sm-4 col-md-3">
			<a href="{{ route('org.generate') }}" class="btn btn-primary btn-block">Download CSV Template</a> <br>
			<div class="card">
				<div class="card-body">
					<ul class="nav nav-4">
						<li class="nav-item">
							<a class="nav-link" href="#">
								<i class="fa fa-globe"></i> {{ $group->name }} 
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">
								<i class="fa fa-home"></i> {{ $group->created_at }} 
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="">
								<i class="fa fa-user"></i>Members {{ \App\Models\General\GroupMember::where('group_id', $group->id)->count() }}
							</a>
						</li>
						<li class="nav-item" title="Create multiple group member accounts by importing a csv file">
							<a class="nav-link" href="#importMembers" data-toggle="modal" id="clickable" data-orgid="{{ $organization->id }}" data-grpname="{{ $group->name }}" data-grpid="{{ $group->id }}">
								<i class="fa fa-users"></i> Import Members 
							</a>
						</li>
						<li class="nav-item" title="Customize your group database">
							<a class="nav-link" href="{{ route('orggroupsets', $group->id) }}">
								<i class="fas fa-cogs"></i> Group Settings
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-sm-8 col-md-9">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">Add a new group member</h5>
				</div>
				<div class="card-body">
					<form action="{{ route('addmember') }}" method="POST" enctype="multipart/form-data">
						{{ csrf_field() }}
						<input type="hidden" name="org_id" value="{{ $organization->id }}">
						<input type="hidden" name="group_id" value="{{ $group->id }}">
						<input type="hidden" name="group_name" value="{{ $group->name }}">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group{{ $errors->has('name') ? ' is-invalid' : '' }}">
									<label>First Name <span class="important"></span></label>
			                        <input type="text" name="name" placeholder="First Name" class="form-control" value="{{ old('name') }}" />
			                         @if ($errors->has('name'))
			                            <span class="invalid-feedback" role="alert">
			                                <strong>{{ $errors->first('name') }}</strong>
			                            </span>
			                        @endif
			                    </div>
							</div>
							<div class="col-md-4">
								<div class="form-group{{ $errors->has('other_names') ? ' is-invalid' : '' }}">
									<label>Other Names <span class="important"></span></label>
			                        <input type="text" name="other_names" placeholder="Other Names" class="form-control" value="{{ old('other_names') }}" />
			                         @if ($errors->has('user_name'))
			                            <span class="invalid-feedback" role="alert">
			                                <strong>{{ $errors->first('user_name') }}</strong>
			                            </span>
			                        @endif
			                    </div>
							</div>
							<div class="col-md-4">
								<div class="form-group{{ $errors->has('email') ? ' is-invalid' : '' }}">
									<label>Email Address <span class="important"></span></label>
					                <input type="email" name="email" placeholder="Email Address" class="form-control" value="{{ old('email') }}" />
					                @if ($errors->has('email'))
					                    <span class="invalid-feedback" role="alert">
					                        <strong>{{ $errors->first('email') }}</strong>
					                    </span>
					                @endif
					            </div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group{{ $errors->has('gender') ? ' is-invalid' : '' }}">
									<label>Gender <span class="important"></span></label>
					                <select name="gender" class="form-control{{ $errors->has('gender') ? ' is-invalid' : '' }} signup-input" id="gender">
										<option value="" disabled selected>Select Gender</option>
										@foreach($genders as $gender)
											<option value="{{ $gender->id }}">{{ $gender->name }}</option>
										@endforeach
									</select>
	                                @if ($errors->has('gender'))
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $errors->first('gender') }}</strong>
	                                    </span>
	                                @endif
					            </div>
							</div>
							<div class="col-md-4">
								<label for="country">Country <span class="important">*</span></label>
	                            <select name="country" class="signup-input{{ $errors->has('country') ? ' is-invalid' : '' }} form-control" id="countryName">
	                                <option value="" disabled="" selected="">Select Country</option>
	                                @foreach($countries as $country)
	                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
	                                @endforeach
	                            </select>
	                            @if ($errors->has('country'))
	                                <span class="invalid-feedback" role="alert">
	                                    <strong>{{ $errors->first('country') }}</strong>
	                                </span>
	                            @endif
							</div>
							<div class="col-md-4">
								<div class="form-group{{ $errors->has('msisdn') ? ' is-invalid' : '' }}">
									<label>Phone Number <span class="important"></span></label>
					                <input type="text" name="msisdn" placeholder="Phone Number" class="form-control" value="{{ old('msisdn') }}" id="phone-input" />
					                @if ($errors->has('msisdn'))
					                    <span class="invalid-feedback" role="alert">
					                        <strong>{{ $errors->first('msisdn') }}</strong>
					                    </span>
					                @endif
					            </div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
	                            <div class="form-group">
	                                <label for="doc_type">Document of Identification <span class="important">*</span></label>
	                                <select name="doc_type" class="form-control{{ $errors->has('doc_type') ? ' is-invalid' : '' }} signup-input" value="{{ old('doc_type') }}">
	                                    <option value="" disabled="" selected="">Document of Identification</option>
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
	                                <input type="text" class="signup-input{{ $errors->has('doc_no') ? ' is-invalid' : '' }} form-control" name="doc_no" value="{{ old('doc_no') }}">
	                                @if ($errors->has('doc_no'))
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $errors->first('doc_no') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>
	                        <div class="col-md-4">
	                            <div class="form-group">
									<label for="identification_document">Upload Document <span class="important">*</span></label>
									<input type="file" name="identification_document" class="form-control signup-input">
									@if ($errors->has('identification_document'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('identification_document') }}</strong>
										</span>
									@endif
								</div>
	                        </div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="dob">Date of Birth (dd/mm/yyyy) <span class="important">*</span></label>
									<input type="date" class="signup-input{{ $errors->has('dob') ? ' is-invalid' : '' }} form-control" name="dob" value="{{ old('dob') }}" id="dob">
									@if ($errors->has('dob'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('dob') }}</strong>
										</span>
									@endif
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
	                                <label for="occupation">User Occupation <span class="important">*</span></label>
	                                <input type="text" class="signup-input form-control{{ $errors->has('occupation') ? ' is-invalid' : '' }}" name="occupation" value="{{ old('occupation') }}" placeholder="Occupation">
	                                @if ($errors->has('occupation'))
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $errors->first('occupation') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
							</div>
							<div class="col-md-4">
	                            <div class="form-group">
	                                <label for="income">Approximate annual income? <span class="important">*</span></label>
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
									<label for="residence">Resident Type <span class="important">*</span></label>
									<select name="residence" class="form-control{{ $errors->has('residence') ? ' is-invalid' : '' }} signup-input" id="residence">
										<option value="" disabled selected>Select Resident Type</option>
										@foreach($residents as $resident)
											<option value="{{ $resident->id }}">{{ $resident->name }}</option>
										@endforeach
									</select>
									@if ($errors->has('residence'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('residence') }}</strong>
										</span>
									@endif
								</div>
							</div>
							<div class="col-md-4">
	                        	<div class="form-group">
	                                <label for="address">Physical Address <span class="important">*</span></label>
	                                <input type="text" class="signup-input form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ old('address') }}" placeholder="Your physical address">
	                                @if ($errors->has('address'))
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $errors->first('address') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
							</div>
							<div class="col-md-4">
	                            <div class="form-group">
	                                <label for="city">City <span class="important">*</span></label>
	                                <input type="text" class="signup-input form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ old('city') }}" placeholder="Your city">
	                                @if ($errors->has('city'))
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $errors->first('city') }}</strong>
	                                    </span>
	                                @endif
	                            </div>
	                        </div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="postal_code">Postal Code <span class="important">*</span></label>
									<input type="text" class="signup-input form-control{{ $errors->has('postal_code') ? ' is-invalid' : '' }}" name="postal_code" placeholder="e.g. 28301" value="{{ old('postal_code') }}" id="postal_code">
									@if ($errors->has('postal_code'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('postal_code') }}</strong>
										</span>
									@endif
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="marital_status">Marital Status <span class="important">*</span></label>
									<select name="marital_status" class="form-control{{ $errors->has('marital_status') ? ' is-invalid' : '' }} signup-input" id="marital_status">
											<option value="" disabled selected>Select Marital Status</option>
											@foreach($maritals as $marital)
												<option value="{{ $marital->id }}">{{ $marital->name }}</option>
											@endforeach
									</select>
									@if ($errors->has('marital_status'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('marital_status') }}</strong>
										</span>
									@endif
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="marital_status">Role <span class="important">*</span></label>
									<select name="role" class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }} signup-input" id="role">
											<option value="" disabled selected>Select Role</option>
											@foreach($roles as $role)
												<option value="{{ $role->id }}">{{ $role->name }}</option>
											@endforeach
									</select>
									@if ($errors->has('role'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('role') }}</strong>
										</span>
									@endif
								</div>
							</div>
						</div>
						@if(!empty($fields))
							@foreach($fields as $key => $value)
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="{{ preg_replace('/\s+/', '_', strtolower($key)) }}">{!! $key !!} <span class="important">*</span></label>
											<input type="hidden" name="member_field" value="{!! $key !!}">
											@if($value === "boolean")
												<select name="fields[]" class="form-control">
													<option value="" disabled selected>Select Option</option>
													<option value="1">Yes</option>
													<option value="0">No</option>
												</select>
											@elseif($value === "text")
												<textarea name="fields[]" cols="30" rows="5" class="form-control" placeholder="{!! $key !!}"></textarea>
											@elseif($value === "date")
												<input type="date" name="fields[]" class="form-control">
											@else
												<input type="text" name="fields[]" class="form-control" placeholder="{!! $key !!}">
											@endif
										</div>
									</div>
								</div>
							@endforeach
						@endif
						<div class="row">
							<div class="col-md-12">
								<button type="submit" class="btn btn-primary btn-block">Create Member</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">Members</h5>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover table-2">
							<thead>
								<th>Name</th>
								<th>Email Address</th>
								<th>Phone Number</th>
								<th>Group Account Status</th>
								{{-- <th>Invitation Status</th> --}}
								<th>Action</th>
							</thead>
							<tbody>
								@foreach($members as $member)
									<tr>
										<td>{{ $member->name . ' ' . $member->other_names }}</td>
										<td>{{ $member->email }}</td>
										<td>{{ $member->msisdn }}</td>
										<td>
											@if($member->memberstatus)
												Active
											@else
												Inactive
											@endif
										</td>
										{{-- <td>
											@if($member->invitestatus)
												Accepted
											@else
												Pending Acceptance
											@endif
										</td> --}}
										<td>
											<a href="{{ route('group.viewmember', $member->memberid) }}" class="btn btn-sm btn-primary" title="View User">View</a> &nbsp;
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

	

	@if($errors = Session::get('error-rows'))
		<div class="row">
			<div class="col-md-12">
				<div class="box box-block bg-white">
					<h5 class="mb-1">Import Errors</h5>
					<ul>
						@foreach($errors as $error)
							<li>{{ $error['email'] }} - {{ $error['message'] }}</li>	
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	@endif

@endsection

@section('spec-scripts')
	<!-- Import Users Modal -->
	<div class="modal fade" id="importMembers" tabindex="-1" role="dialog" aria-labelledby="importMembers" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form action="{{ route('groupmembers.import') }}" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<input type="hidden" name="g_id" value="{{ $organization->id }}">
					<input type="hidden" name="grp_name" value="{{ $group->name }}">
					<input type="hidden" name="group_id" value="{{ $group->id }}">
					<div class="modal-body">
						<div class="form-group">
							<label for="file" class="form-control-label">File Name:</label>
							<input type="file" class="form-control" name="file" required>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Import</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
        $(document).ready(function () {
			$('#countryName').change(function (e) {
                e.preventDefault();
                const name = $('#countryName').val();
                if(name == 131) {
                    $('#phone-input').val(234);
                } else if(name == 93) {
                    $('#phone-input').val(254);
                } else if(name == 101) {
                    $('#phone-input').val(231);
                } else if(name == 186) {
                    $('#phone-input').val(256);
                } else if(name == 177) {
                    $('#phone-input').val(255);
                } else if(name == 25) {
                    $('#phone-input').val(267);
                } else if(name == 159) {
                    $('#phone-input').val(232);
                } else {
                    $('#phone-input').val('');
                }
            });
		});
    </script>
@endsection