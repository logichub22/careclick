@extends('back/organization/layouts/master')

@section('title')
	Add New user
@endsection

@push('styles')
	<link rel="stylesheet" href="{{ asset('css/main/loader.css') }}">
@endpush

@section('page-nav')
	<h4>Organization Users</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="{{ route('users.index') }}"></a>User Management</li>
		<li class="breadcrumb-item active">Add User</li>
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
		<div class="col-sm-4 col-md-3">
			<a href="#instructions" class="btn btn-primary btn-block" data-toggle="modal">Read Import Instructions</a> <br>
			<a href="#importMembers" class="btn btn-success btn-block" data-toggle="modal">Import Organization Members</a> <br>

			<div class="box bg-white">
				<ul class="nav nav-4">
					<li class="nav-item">
						<a class="nav-link" href="{{ $organization->domain }}">
							<i class="fa fa-globe"></i> {{ $organization->name }} 
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fa fa-home"></i> {{ $organization->address }} 
						</a>
					</li>
				</ul>
			</div>
			<a href="{{ route('org_user_file.generate') }}" class="btn btn-primary btn-block">Download CSV Template</a> <br>
		</div>
		<div class="col-sm-8 col-md-9">
			<div class="box box-block bg-white">
				<h5 class="mb-1">Create a new user</h5>
				<form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<input type="hidden" name="org_id" value="{{ $organization->id }}">
					<input type="hidden" name="organization" value="{{ $organization->name }}">
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
									<select name="gender" class="form-control{{ $errors->has('gender') ? ' is-invalid' : '' }}">
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
								<select name="country" id="countryName" class="signup-input{{ $errors->has('country') ? ' is-invalid' : '' }} form-control">
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
									<input type="text" name="msisdn" placeholder="Phone Number" class="form-control" value="{{ old('msisdn') }}" id="phoneInput" />
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
					<div class="row">
						<div class="col-md-12">
							<button type="submit" class="btn btn-primary btn-block">Create User</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	{{-- @if($errors = Session::get('error-rows'))
		<div class="row">
			<div class="col-sm-9">
				<div class="box box-block bg-white">
					<h5 class="mb-1">Import Errors</h5>
					<ul>
						@foreach($errors as $error)
							<li>{{ $error['email'] }}</li>
							<li>{{ $error['message'] }}</li>	
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	@endif --}}
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
					<input type="hidden" name="org_id" value="{{ $organization->id }}">
					<input type="hidden" name="organization" value="{{ $organization->name }}">

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

	<script type="text/javascript">
        $(document).ready(function () {
			$('#countryName').change(function (e) {
                e.preventDefault();
                const name = $('#countryName').val();
                if(name == 131) {
                    $('#phoneInput').val(234);
                } else if(name == 93) {
                    $('#phoneInput').val(254);
                } else if(name == 101) {
                    $('#phoneInput').val(231);
                } else if(name == 186) {
                    $('#phoneInput').val(256);
                } else if(name == 177) {
                    $('#phoneInput').val(255);
                } else if(name == 25) {
                    $('#phoneInput').val(267);
                } else if(name == 159) {
                    $('#phoneInput').val(232);
                } else {
                    $('#phoneInput').val('');
                }
            });
		});
	</script>
	
	<script>
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
					url: "{{ route('orgusers.import') }}",
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
			})
		})
	</script>
@endpush