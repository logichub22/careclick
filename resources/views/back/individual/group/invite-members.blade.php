@extends('back/individual/layouts/master')

@section('title')
	Add Members
@endsection

@section('one-step')
    / Group / Add Member
@endsection

@section('page-nav')
	<h4>Group Members</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="{{ route('user-groups.index') }}"></a>Groups</li>
		<li class="breadcrumb-item active">Add Member</li>
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
		<div class="col-sm-4 col-md-3">
			<a href="#importMembers" class="btn btn-success btn-block" data-toggle="modal">Import Group Members</a> <br>
			<a href="{{ route('user.generate') }}" class="btn btn-primary btn-block">Download CSV Template</a> <br>
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
								<i class="fa fa-user"></i>Members {{ count($members) }}
							</a>
						</li>
						<!-- <li class="nav-item" title="Customize your group database">
							<a class="nav-link" href="#addMoreFields" data-toggle="modal">
								<i class="fas fa-cogs"></i> Add more fields
							</a>
						</li> -->
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
					<form action="{{ route('ind.savemember') }}" method="POST" enctype="multipart/form-data">
						{{ csrf_field() }}
						<input type="hidden" name="group_id" value="{{ $group->id }}">
						<input type="hidden" name="group_name" value="{{ $group->name }}">
						<input type="hidden" name="country" value="{{ $user->detail->country }}">
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
	                            <select name="" class="signup-input{{ $errors->has('country') ? ' is-invalid' : '' }} form-control" id="countryName" disabled>
	                                <option value="" disabled="" selected="">Select Country</option>
	                                @foreach($countries as $country)
	                                    <option value="{{ $country->id }}" {{ $user->detail->country == $country->id ? ' selected' : '' }}>{{ $country->name }}</option>
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
							<div class="col-md-6">
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
							<div class="col-md-6">
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
						</div>
						@if(!empty($fields))
							@foreach($fields as $key => $value)
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="{{ preg_replace('/\s+/', '_', strtolower($key)) }}">{!! $key !!} <span class="important">*</span></label>
											<a href="" class="btn btn-sm btn-danger" style="float: right !important">Remove Field</a>
											<input type="hidden" name="member_field" value="{!! $key !!}">
											@if($value === "boolean")
												<select name="fields[]" class="form-control">
													<option value="" disabled selected>Select Option</option>
													<option value="1">Yes</option>
													<option value="0">No</option>
												</select>
											@elseif($value === "text")
												<textarea name="fields[{{ strtolower(preg_replace('/\s+/', '_', $key)) }}][]" cols="30" rows="5" class="form-control" placeholder="{!! $key !!}"></textarea>
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
			<div class="box box-block bg-white">
				<h5 class="mb-1">Members</h5>
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
									<td>
										<a href="{{ route('usergroup.viewmember', $member->memberid) }}" class="btn btn-sm btn-primary" title="View User"><i class="far fa-eye"></i></a> &nbsp;
									</td>
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
	<!-- Import Users Modal -->
	<div class="modal fade" id="importMembers" tabindex="-1" role="dialog" aria-labelledby="editGroup" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form id="uploadForm" enctype="multipart/form-data">
						<input type="hidden" name="grp_name" id="grp_name" value="{{ $group->name }}">
						<input type="hidden" name="group_id" id="group_id" value="{{ $group->id }}">
						<div class="modal-body">
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
							<div class="form-group">
								<label for="file" class="form-control-label">File Name:</label>
								<input type="file" class="form-control" name="file" required id="file">
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

		<!-- Import Users Modal -->
	<div class="modal fade" id="addMoreFields" tabindex="-1" role="dialog" aria-labelledby="editGroup" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title">Import Users</h4>
					</div>
					<div class="modal-body">
							<form action="{{ route('usergroup.storedata') }}" method="POST">
									{{ csrf_field() }}
									<input type="hidden" name="group" value="{{ $group->id }}">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="field">Display Text</label>
												<input type="text" class="form-control" placeholder="Field Name" name="field">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="value">Data Type</label>
												<select id="" class="form-control" name="datatype">
													<option value="" disabled="" selected="">Select data type</option>
													<option value="string">STRING</option>
													<option value="text">TEXT</option>
													<option value="integer">INTEGER</option>
													<option value="date">DATE</option>
													<option value="float">FLOAT</option>
													<option value="double">DOUBLE</option>
													<option value="boolean">BOOLEAN</option>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-8 offset-md-2">
											<button type="submit" class="btn btn-primary btn-block">Add Data</button>
										</div>
									</div>
								</form>
					</div>
				</div>
			</div>
		</div>
		<!--// End Edit Group Modal //-->
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script src="{{ asset('js/main/loader.js') }}"></script>
	<script type="text/javascript">
        $(document).ready(function () {
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
		});
    </script>
@endsection
