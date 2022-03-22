@extends('back/organization/layouts/master')

@section('title')
	Add New user
@endsection

@section('one-step')
    / Add New User
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
        <div class="w-100">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-1">Create a new user</h5>
					<form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<input type="hidden" name="org_id" value="{{ $organization->id }}">
					<input type="hidden" name="organization" value="{{ $organization->name }}">
					<input type="hidden" name="country_id" value="{{ $organization->country }}">
                </div>
                <div class="card-body">
                    <div class="form-row">
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
									 @if ($errors->has('other_names'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('other_names') }}</strong>
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
								<select name="country" id="countryName" class="signup-input{{ $errors->has('country') ? ' is-invalid' : '' }} form-control" >
									<option value="" disabled="" selected="">Select Country</option>
									@foreach($countries as $country)
										<option value="{{ $country->name }}" {{ $country->id === $organization->country ? 'selected' : '' }}>{{ $country->name }}</option>
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
									<input type="text" class="signup-input{{ $errors->has('doc_no') ? ' is-invalid' : '' }} form-control" name="doc_no" value="{{ old('doc_no') }}" placeholder="Document Number">
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
									<label for="postal_code">Select Role <span class="important">*</span></label>
									<select name="role" id="" class="form-control" required>
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
							<div class="row">
								<div class="col-md-12">
									<button type="submit" class="btn btn-primary btn-block">Create User</button>
								</div>
							</div>
						</form>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('spec-styles')
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script src="{{ asset('js/main/loader.js') }}"></script>

	{{-- <script type="text/javascript">
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
	</script> --}}

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
@endsection
