@extends('back/superadmin/layouts/master')

@section('title')
	All Settings
@endsection

@section('page-nav')
	<h4>Manage Settings</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('super.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item active">Manage Settings</li>
	</ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="">
				<div class="row">
					<div class="col-md-6">
						<div class="box box-block bg-white">
							<h5 class="mb-1">Region Settings</h5>
							<form action="">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="">Level</label>
											<select name="regions_level" id="" class="form-control" required>
												<option value="" disabled selected>Select Level</option>
												<option value="level_one">Level One</option>
												<option value="level_two">Level Two</option>
												<option value="level_three">Level Three</option>
												<option value="level_four">Level Four</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="">Country</label>
											<select name="regions_country" id="" class="form-control">
												<option value="" disabled selected>Select Country</option>
												@foreach($countries as $country)
													<option value="{{ $country->id }}">{{ $country->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="">Level</label>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<button type="submit" class="btn btn-primary btn-block">Submit</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="col-md-6">
						<div class="box box-block bg-white">
							<h5 class="mb-1">Language Settings</h5>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
@endsection