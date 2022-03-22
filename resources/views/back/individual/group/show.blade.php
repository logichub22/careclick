@extends('back/individual/layouts/master')

@section('title')
	View Group
@endsection

@section('one-step')
    / Group / Detail
@endsection

@push('styles')
	<link rel="stylesheet" href="{{ asset('css/main/loader.css') }}">
@endpush

@section('page-nav')
	<h4>Group Dashboard</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="#">Groups</a></li>
		<li class="breadcrumb-item"><a href="{{ route('user-groups.index') }}">My Groups</a></li>
		<li class="breadcrumb-item active">View Group</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-6">
			<div class="errorbox box box-block bg-white" style="display: none;">
				<h5 class="">Email Import Errors</h5>
				<ul id="errorList">

				</ul>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="phone-errorbox box box-block bg-white" style="display: none;">
				<h5 class="">Phone Import Errors</h5>
				<ul id="phoneErrorList">

				</ul>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="row">
				<div class="col-lg-3 col-md-6 col-sm-6 col-12">
		            <div class="card card-statistic-1">
		                <div class="card-icon l-bg-purple">
		                    <i class="fas fa-wallet"></i>
		                </div>
		                <div class="card-wrap">
		                    <div class="padding-20">
		                        <div class="text-right">
		                            <h3 class="font-light mb-0">
		                                <sub>{{ $currency->prefix }}</sub>
		                                <i class="ti-arrow-up text-success"></i>
		                                {{ number_format($wallet->balance) }}
		                            </h3>
		                            <span class="text-muted">@lang('individual.balance')</span>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
		            <div class="card card-statistic-1">
		                <div class="card-icon l-bg-cyan">
		                    <i class="fas fa-credit-card"></i>
		                </div>
		                <div class="card-wrap">
		                    <div class="padding-20">
		                        <div class="text-right">
		                            <h3 class="font-light mb-0">
		                                <i class="ti-arrow-up text-success"></i>
		                                0
		                            </h3>
		                            <span class="text-muted">Contributions</span>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
		            <div class="card card-statistic-1">
		                <div class="card-icon l-bg-green">
		                    <i class="fas fa-users"></i>
		                </div>
		                <div class="card-wrap">
		                    <div class="padding-20">
		                        <div class="text-right">
		                            <h3 class="font-light mb-0">
		                                <i class="ti-arrow-up text-success"></i>
		                                {{ count($members) }}
		                            </h3>
		                            <span class="text-muted">Members</span>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
		            <div class="card card-statistic-1">
		                <div class="card-icon l-bg-orange">
		                    <i class="fas fa-share"></i>
		                </div>
		                <div class="card-wrap">
		                    <div class="padding-20">
		                        <div class="text-right">
		                            <h3 class="font-light mb-0">
		                                <i class="ti-arrow-up text-success"></i>
		                                0
		                            </h3>
		                            <span class="text-muted">Shares</span>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
				<div class="row">
					@if(Auth::user()->hasRole('group-admin'))
						<div class="col-md-6">
							<a href="{{ route('usergroup.addmember', $group->id) }}" class="btn btn-block btn-primary">Add Members</a>
						</div>
					@endif
				</div>

				</button>
				<br />
				<div class="card">
					<div class="card-body">
						<ul class="nav nav-4">
							<li class="nav-item">
								<a class="nav-link" href="#">
									<i class="fa fa-home"></i>Name: {{ $group->name }}
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">
									<i class="fa fa-home"></i>Association: {{ $group->association_id == null ? ' ' : implode(\App\Models\Organization\OrganizationDetail::where('org_id', $group->association_id)->pluck('name')->toArray()) }}
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">
									<i class="far fa-money-bill-alt"></i> Balance
									<div class="float-xs-right">{{ number_format($wallet->balance) }}</div>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">
									<i class="fa fa-users"></i> Members
									<div class="float-xs-right">{{ count($members) }}</div>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">
									<i class="far fa-calendar-alt"></i> Created {{ date('M j, Y', strtotime($group->created_at)) . ' at ' . date('H:i', strtotime($group->created_at)) }}
								</a>
							</li>
						</ul>
					</div>
			</div>
				@if(Auth::user()->hasRole('group-admin'))
					<div class="row">
						<!-- <div class="col-md-6">
							<button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#editGroup" >Edit Group  Info</button>
						</div> -->
						<div class="col-md-6">
							<a href="{{ route('usergroupsettings', $group->id) }}" class="btn btn-success btn-block" >Group Settings</a>
						</div>
					</div>
				@endif
		</div>
		<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<h5 class="mb-1">Group Details</h5>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<strong>Group Name</strong>
									<p>{{ $group->name }}</p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<strong>Account Number</strong>
									<p>{{ $group->account_no == null ? 'Not Available' : $group->account_no }}</p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<strong>Bank Account?</strong>
									<p>{{ $group->bank == true ? 'Yes' : 'No' }}</p>
								</div>
							</div>
						</div>
						<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<strong>Account Number</strong>
										<p>{{ $group->account_no == null ? 'Not Available' : $group->account_no }}</p>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<strong>Bank Name</strong>
										<p>{{ $group->bank_name == null ? 'Not Available' : $group->bank_name }}</p>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<strong>Bank branch</strong>
										<p>{{ $group->bank_branch == null ? 'Not Available' : $group->bank_branch }}</p>
									</div>
								</div>
							</div>
						<hr>
						<h5 class="mb-1">Coordinator Details</h5>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<strong>Name</strong>
									<p>{{ $coordinator->name . ' ' . $coordinator->other_names }}</p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<strong>Phone Number</strong>
									<p>+{{ $coordinator->msisdn }}</p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<strong>Email</strong>
									<p>
										{{ $coordinator->email }}
									</p>
								</div>
							</div>
						</div>
						<hr>
						<h5 class="mb-1">Trainer Details</h5>
						@if(is_null($trainingofficer))
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<p>No trainer assigned</p>
									</div>
								</div>
							</div>
						@else
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<strong>Name</strong>
										<p>{{ $trainingofficer->name . ' ' . $trainingofficer->other_names }}</p>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<strong>Phone Number</strong>
										<p>+{{ $trainingofficer->msisdn }}</p>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<strong>Email</strong>
										<p>
											{{ $trainingofficer->email }}
										</p>
									</div>
								</div>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid">
		<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h5 class="mb-1">Group Members</h5>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered table-hover table-2">
									<thead>
										<th>Name</th>
										<th>Email</th>
										<th>Role</th>
										<th>Phone Number</th>
										<th>Member Since</th>
										@if(Auth::user()->hasRole('group-admin'))
											<th>Action</th>
										@endif
									</thead>
									<tbody>
										@foreach ($members as $member)
											<tr>
												<td>{{ $member->name . ' ' . $member->other_names }}</td>
												<td>{{ $member->email }}</td>
												<td>
													@if($member->admin)
														<span class="badge badge-primary">Coordinator</span>
													@else
														<span class="label label-primary">Member</span>
													@endif
												</td>
												<td>{{ $member->msisdn }}</td>
												<td>{{ date('M j, Y', strtotime($member->membercreated)) . ' at ' . date('H:i', strtotime($member->membercreated)) }}</td>
												@if(Auth::user()->hasRole('group-admin'))
													<td>
														<a href="{{ route('usergroup.viewmember', $member->memberid) }}" class="btn btn-primary btn-sm"><i class="far fa-eye"></i></a>
													</td>
												@endif
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
				</div>
			</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">Transactions</h5>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover table-2">
							<thead>
								<th>Transaction Code</th>
								<th>Amount</th>
								<th>Type of Transaction</th>
								<th>Group Member</th>
								<th>Transaction Date</th>
							</thead>
							<tbody>
								@foreach($transactions as $transaction)
										<tr>
											<td>{{ $transaction->txn_code }}</td>
											<td>{{ number_format($transaction->amount) }}</td>
											<td>{{ $transaction->type }}</td>
											<td>{{ $transaction->name . ' ' . $transaction->other_names }}</td>
											<td>{{ date('M j, Y', strtotime($transaction->created_at)) . ' at ' . date('H:i', strtotime($transaction->created_at)) }}</td>
										</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
@endsection
@section('spec-scripts')
	<!-- Edit Group Modal -->
	<div class="modal fade" id="editGroup" tabindex="-1" role="dialog" aria-labelledby="editGroup" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<!-- <h4 class="modal-title">Edit {{ $group->name }} Group</h4> -->
				</div>
				<form action="{{ route('user-groups.update', $group->id) }}" method="POST">
					{{ csrf_field() }}
					{{ method_field('PATCH') }}
					<input type="hidden" name="association" value="{{ $group->association_id }}">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="recipient-name" class="form-control-label">Group Name:</label>
									<input type="text" class="form-control" name="name" value="{{ $group->name }}">
									@if($errors->has('name'))
										<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('name') }}</strong>
										</span>
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="name">Association</label>
									<select class="form-control" id="" disabled>
										<option value="" disabled selected>Select Association</option>
										@foreach($associations as $association)
											<option value="{{ $association->id }}" {{ $group->association_id == $association->id ? ' selected' : ''}}>{{ $association->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Group Document {{ $group->group_certficate == null ? ' ' : '( change existing one )' }}</label>
									<input type="file" name="group_certificate" class="form-control{{ $errors->has('group_certificate') ? ' is-invalid' : '' }}">
									@if ($errors->has('group_certificate'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('group_certificate') }}</strong>
										</span>
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Do you have a bank account? <span class="important">*</span></label>
									<select name="bank" class="form-control{{ $errors->has('bank') ? ' is-invalid' : '' }}" id="bank_select">
										<option value="" disabled selected>Do you have a bank account?</option>
										<option value="1" {{ $group->bank == true ? ' selected' : '' }}>Yes</option>
										<option value="0" {{ $group->bank == false ? ' selected' : '' }}>No</option>
									</select>
									@if ($errors->has('bank'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('bank') }}</strong>
										</span>
									@endif
								</div>
							</div>
						</div>
						<div class="row" style="{{ $group->bank == true ? '' : 'display: none'}}" id="bank_row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="">Bank Name <span class="important">*</span></label>
									<input type="text" name="bank_name" class="form-control{{ $errors->has('bank_name') ? ' is-invalid' : '' }}" placeholder="Name of your bank" value="{{ old('bank_name') }}" maxlength="100" id="bank_name">
									@if($errors->has('bank_name'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('bank_name') }}</strong>
										</span>
									@endif
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="">Branch <span class="important">*</span></label>
									<input type="text" name="bank_branch" class="form-control{{ $errors->has('bank_branch') ? ' is-invalid' : '' }}" placeholder="Branch" value="{{ $group->bank_branch }}" maxlength="100" id="bank_branch">
									@if($errors->has('bank_branch'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('bank_branch') }}</strong>
										</span>
									@endif
								</div>
							</div>
							<div class="col-md-4">
								<label for="account_no">Account Number <span class="important">*</span></label>
								<input type="text" name="account_no" class="form-control{{ $errors->has('account_no') ? ' is-invalid' : '' }}" placeholder="Account Number" value="{{ old('account_no') }}" maxlength="100" id="account_no">
								@if($errors->has('account_no'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('account_no') }}</strong>
									</span>
								@endif
							</div>
						</div>
						<div class="row">
								@if(count($regions_arr) === 4)
									@foreach($regions_arr as $key => $value)
										<div class="col-md-3">
											<label for="">{{ $value }}</label>
											@if($key == 0)
												<select name="level_one" id="levelOneID" class="form-control{{ $errors->has('level_one') ? ' is-invalid' : '' }}">
													<option value="" disabled selected>@lang('individual.select') {{ $value }}</option>
													@foreach($level_ones as $level_one)
														<option value="{{ $level_one->id }}">{{ $level_one->name }}</option>
													@endforeach
												</select>
												@if ($errors->has('level_one'))
													<span class="invalid-feedback" role="alert">
														<strong>{{ $errors->first('level_one') }}</strong>
													</span>
												@endif
											@elseif($key == 1)
												<select name="level_two" id="levelTwoID" class="form-control{{ $errors->has('level_two') ? ' is-invalid' : '' }}">
													<option value="" disabled selected>Select {{ $value }}</option>

												</select>
												@if ($errors->has('level_two'))
													<span class="invalid-feedback" role="alert">
														<strong>{{ $errors->first('level_two') }}</strong>
													</span>
												@endif
											@elseif($key == 2)
												<select name="level_three" id="levelThreeID" class="form-control{{ $errors->has('level_three') ? ' is-invalid' : '' }}">
													<option value="" disabled selected>Select {{ $value }}</option>

												</select>
												@if ($errors->has('level_three'))
													<span class="invalid-feedback" role="alert">
														<strong>{{ $errors->first('level_three') }}</strong>
													</span>
												@endif
											@elseif($key == 3)
												<input type="text" class="form-control{{ $errors->has('level_four') ? ' is-invalid' : '' }}" name="level_four" placeholder="{{ $value }}">
												@if ($errors->has('level_four'))
													<span class="invalid-feedback" role="alert">
														<strong>{{ $errors->first('level_four') }}</strong>
													</span>
												@endif
											@endif
										</div>
									@endforeach
								@elseif(count($regions_arr) === 3)
									@foreach($regions_arr as $key => $value)
										<div class="col-md-4">
											<label for="">{{ $value }}</label>
											@if($key == 0)
												<select name="level_one" id="levelOneID" class="form-control{{ $errors->has('level_one') ? ' is-invalid' : '' }}">
													<option value="" disabled selected>@lang('individual.select') {{ $value }}</option>
													@foreach($level_ones as $level_one)
														<option value="{{ $level_one->id }}">{{ $level_one->name }}</option>
													@endforeach
												</select>
												@if ($errors->has('level_one'))
													<span class="invalid-feedback" role="alert">
														<strong>{{ $errors->first('level_one') }}</strong>
													</span>
												@endif
											@elseif($key == 1)
												<select name="level_two" id="levelTwoID" class="form-control{{ $errors->has('level_two') ? ' is-invalid' : '' }}">
													<option value="" disabled selected>Select {{ $value }}</option>

												</select>
												@if ($errors->has('level_two'))
													<span class="invalid-feedback" role="alert">
														<strong>{{ $errors->first('level_two') }}</strong>
													</span>
												@endif
											@elseif($key == 2)
												<select name="level_three" id="levelThreeID" class="form-control{{ $errors->has('level_three') ? ' is-invalid' : '' }}">
													<option value="" disabled selected>Select {{ $value }}</option>

												</select>
												@if ($errors->has('level_three'))
													<span class="invalid-feedback" role="alert">
														<strong>{{ $errors->first('level_three') }}</strong>
													</span>
												@endif
											@endif
										</div>
									@endforeach
								@elseif(count($regions_arr) == 2)
									@foreach ($regions_arr as $key => $value)
									<div class="col-md-6">
											<label for="">{{ $value }}</label>
											@if($key == 0)
												<select name="level_one" id="levelOneID" class="form-control{{ $errors->has('level_one') ? ' is-invalid' : '' }}">
													<option value="" disabled selected>Select {{ $value }}</option>

												</select>
												@if ($errors->has('level_one'))
													<span class="invalid-feedback" role="alert">
														<strong>{{ $errors->first('level_one') }}</strong>
													</span>
												@endif
											@elseif($key == 1)
												<select name="level_two" id="levelTwoID" class="form-control{{ $errors->has('level_two') ? ' is-invalid' : '' }}">
													<option value="" disabled selected>@lang('individual.select') {{ $value }}</option>

												</select>
												@if ($errors->has('level_two'))
													<span class="invalid-feedback" role="alert">
														<strong>{{ $errors->first('level_two') }}</strong>
													</span>
												@endif
											@endif
										</div>
									@endforeach
								@endif
							</div>
						<br />
						<div class="form-group">
							<label for="message-text" class="form-control-label">Comments:</label>
							<textarea class="form-control">{{ $group->comment }}</textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Update Group</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!--// End Edit Group Modal //-->

	<!-- Import Users Modal -->
	<div class="modal fade" id="importMembers" tabindex="-1" role="dialog" aria-labelledby="editGroup" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Import Users</h4>
				</div>
				<form id="uploadForm" enctype="multipart/form-data">
					{{-- <input type="hidden" name="org_id" value="{{ $organization->id }}">
					<input type="hidden" name="organization" value="{{ $organization->name }}"> --}}
					<input type="hidden" name="grp_name" id="grp_name" value="{{ $group->name }}">
					<input type="hidden" name="group_id" id="group_id" value="{{ $group->id }}">
					<div class="modal-body">
						<div class="form-group">
							<label for="file" class="form-control-label">File Name:</label>
							<input type="file" class="form-control" name="file" required id="file">
							{{-- @if($errors->has('file'))
								<span class="invalid-feedback" role="alert">
									 <strong>{{ $errors->first('file') }}</strong>
								</span>
							@endif --}}
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeImport">Close</button>
						<button type="submit" class="btn btn-primary" id="importButton">Import</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!--// End Edit Group Modal //-->

	<div class="modal fade" id="instructions" tabindex="-1" role="dialog" aria-labelledby="instructions" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">User Upload Instructions</h4>
				</div>
				<div class="modal-body">
					<div>
						<h5 class="mb-1">1. File Format</h5>
						The file format of the file uploaded should be a csv file with a <strong>.csv</strong> file extension. For example, the file should have a name such as <strong>test.csv</strong>
					</div>
					<br>
					<div>
						<h5 class="mb-1">2. Emails</h5>
						All emails for users should be unique. If the system identifies any email that is duplicated or already exists in the database, the upload operation will be unsuccessful and <strong>NO</strong> records will be saved. You will be notified of the emails that contain errors.
					</div>
					<br>
					<div>
						<h5 class="mb-1">3. Gender</h5>
						The two genders, male and female, are represented as either <strong>1</strong> or <strong>2</strong>. Ideally, you should key in 1 or 2 to represent the relevant gender.
						<p>Male - <strong>1</strong> and Female - <strong>2</strong></p>
					</div>
					<br>
					<div>
						<h5 class="mb-1">4. Country</h5>
						The seven countries (for now) are all represented by numbers. This works the same way as the gender column.
						<p>Botswana - <strong>25</strong>, Kenya - <strong>93</strong>, Liberia - <strong>101</strong>, Nigeria - <strong>131</strong>, Sierra Leone - <strong>159</strong>, Tanzania - <strong>177</strong> and Uganda - <strong>186</strong></p>
					</div>
					<br>
					<div>
						<h5 class="mb-1">5. Document of Identification</h5>
						The document of identification can either be a National ID, Passport, Driving License or Voter ID. All these are represented by numbers 1, 2, 3 or 4
						<p>National ID - <strong>1</strong>, Passport - <strong>2</strong>, Driving License - <strong>3</strong> and Voter ID - <strong>4</strong></p>
					</div>
					<br>
					<div>
						<h5 class="mb-1">6. Date of Birth</h5>
						The date of birth format should be month/day/year. Eg, For a user born on 20th October 1980 the date would be entered as 10/20/1980. The forward slashes are necessary.
					</div>
					<br>
					<div>
						<h5 class="mb-1">7. Approximate Annual Income</h5>
						The approximate annual income is represented by various classes. The income currency is Naira.
						<p>Below 50,000 - <strong>1</strong>, 50,001 to 250,000 - <strong>2</strong>, 250,001 to 500,000 <strong>3</strong>, 500,001 to Less than 1 million - <strong>4</strong>, 1 million to Less than 5 million - <strong>5</strong>, 5 million to Less than 10 million - <strong>6</strong>, Above 20 million - <strong>7</strong> and Not Specified - <strong>8</strong></p>
					</div>
					<br>
					<div>
						<h5 class="mb-1">8. Resident Type</h5>
						Residential - <strong>1</strong>, Non Residential - <strong>2</strong> and Foreign National - <strong>3</strong>
					</div>
					<br>
					<div>
						<h5 class="mb-1">9. Marital Status</h5>
						Married - <strong>1</strong> and  Single - <strong>2</strong>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Download Instructions</button>
				</div>
			</div>
		</div>
	</div>
@endsection
@push('scripts')
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script src="{{ asset('js/main/loader.js') }}"></script>

	<script>
		$(document).ready(function () {
			$('#bank_select').on('change', function () {
				// localStorage.setItem("bank", $(this).val());
				if ($(this).val() == 1) {
					$('#bank_row').show();
				} else {
					$('#bank_row').hide();
					$('#bank_name').val();
					$('#bank_branch').val();
					$('#account_no').val();
				}
			});
			$('#uploadForm').submit(function(e) {
				e.preventDefault();
				var emails = [];
				var spinner = '<i class="fas fa-spinner fa-spin"></i> Importing...';
				var file_data = $('#file').prop('files')[0];
				var grp_name = $('#grp_name').val();
				var group_id = $('#group_id').val();
				var form_data = new FormData();
				form_data.append('file', file_data);
				form_data.append('grp_name', grp_name);
				form_data.append('group_id', group_id);
				$(document).ajaxStart(function(){
					appLoading.start();
					$('#importButton').html(spinner);
                });

                $(document).ajaxComplete(function(){
					appLoading.stop();
					$('#importButton').html('<span>Import</span>');
                });
                $.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

				$.ajax({
					url: "{{ route('usergroup.import') }}",
					data: form_data,
					method: 'POST',
					contentType: false,
					cache: false,
					processData: false,
					success: function (data) {
						$('#closeImport').trigger('click');
						if(data.errors) {
							// if (value.message === 'Phone number exists') {
							// 	$('.phone-errorbox').show();
							// }
							// if (value.message === 'Email exists') {
							// 	$('.errorbox').show();
							// }
							$.each(data.errors, function(index, value){
								if (value.message === 'Phone number exists') {
									$('#phoneErrorList').append('<li>' + value.msisdn + ' ' + '-' + ' ' + value.message + '</li>');
								}
								if (value.message === 'Email exists') {
									$('#errorList').append('<li>' + value.email + ' ' + '-' + ' ' + value.message + '</li>');
								}
							});
							swal({
                                title: "Upload Error",
                                text: data.uploaderror,
                                icon: "warning",
                                dangerMode: true,
                            })
						} else {
							$('#errorList').empty();
							$('.errorbox').hide();
							swal({
                                title: "Congrats",
                                text: data.success,
                                icon: "success"
                            })
						}
					}
				})

				$('#levelOneID').on('change', function(e){
				//console.log(e);
				var level_id = e.target.value;
				console.log(level_id);
				$.get('{{ url('load/level_two/') }}'+'/'+level_id, function() {
					//alert( "success" );
				})
				.done(function( data ) {
				//alert( "Data Loaded:");
				$('#levelTwoID').empty();
					if (Object.keys(data).length > 0) {
						$.each(data, function(key, value) {
							$('#levelTwoID').append('<option value="'+key+'">'+ value +'</option>');
						});
					} else {
						$('#levelTwoID').append('<option value="" disabled selected>No data found</option>')
					}

				})
				.fail(function(error) {
					console.log(JSON.stringify(error));
				});
			});

			$('#levelTwoID').on('change', function(e){
				//console.log(e);
				var level_id = e.target.value;

				$.get('{{ url('load/level_three/') }}'+'/'+level_id, function() {
					//alert( "success" );
				})
				.done(function( data ) {
				//alert( "Data Loaded:");
				$('#levelThreeID').empty();
					if (Object.keys(data).length > 0) {
						$.each(data, function(key, value) {
							$('#levelThreeID').append('<option value="'+key+'">'+ value +'</option>');
						});
					} else {
						$('#levelThreeID').append('<option value="" disabled selected>No data found</option>')
					}

				})
				.fail(function(error) {
					console.log(JSON.stringify(error));
				});
			});
			})
		})
	</script>
@endpush
