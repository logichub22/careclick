@extends('back/federation/layouts/master')

@section('title')
    Add Trainer
@endsection

@section('page-nav')
	<h4>Add Trainer</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('federation.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item active">Trainers Management</li>
	</ol>
@endsection

@push('styles')
    <style>
        .invalid-feedback{
            color: #E33545 !important
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-sm-4 col-md-3">
			{{-- <a href="{{ route('org.generate') }}" class="btn btn-primary btn-block">Download CSV Template</a> <br> --}}
			<div class="box bg-white">
				<ul class="nav nav-4">
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fa fa-globe"></i>Federation: {{ $organization->detail->name }} 
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fa fa-calendar"></i>Created: {{ $organization->created_at }} 
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fa fa-users"></i>Total:  {{ count($trainers) }} 
						</a>
					</li>
				</ul>
			</div>
        </div>
        <div class="col-sm-8 col-md-9">
			<div class="box box-block bg-white">
				<h5 class="mb-1">Add a new trainer</h5>
				<form action="{{ route('trainers.store') }}" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<input type="hidden" name="org_id" value="{{ $organization->id }}">
					<input type="hidden" name="fedname" value="{{ $organization->detail->name }}">
					<input type="hidden" name="country" value="{{ $organization->detail->country }}">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group{{ $errors->has('name') ? ' is-invalid' : '' }}">
								<label>First Name <span class="important">*</span></label>
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
								<label>Other Names <span class="important">*</span></label>
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
								<label>Email Address <span class="important">*</span></label>
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
								<label>Gender <span class="important">*</span></label>
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
                            <select class="signup-input{{ $errors->has('country') ? ' is-invalid' : '' }} form-control" id="countryName" disabled>
                                <option value="" disabled="" selected="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ $country->id == $organization->detail->country ? 'selected' : '' }}>{{ $country->name }}</option>
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
								<label>Phone Number <span class="important">*</span></label>
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
                                <input type="text" class="signup-input{{ $errors->has('doc_no') ? ' is-invalid' : '' }} form-control" name="doc_no" value="{{ old('doc_no') }}" placeholder="eg Document Number">
                                @if ($errors->has('doc_no'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('doc_no') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
								<label for="identification_document">Upload Document </label>
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
						<div class="col-md-12">
							<button type="submit" class="btn btn-primary btn-block">Create Trainer</button>
						</div>
					</div>
				</form>
			</div>
		</div>
    </div>
@endsection