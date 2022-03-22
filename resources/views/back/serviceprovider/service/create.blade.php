@extends('back/organization/layouts/master')

@section('title')
	Add a Service
@endsection

@section('page-nav')
	<h4>Add Service</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item active">Add Service</li>
	</ol>
@endsection

@section('content')
	{{-- @if(is_null($service))
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-danger">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>Note:</strong> Please add a service to access all features in the system.
				</div>
			</div>
		</div>
	@endif --}}

	<div class="row">
		<div class="col-sm-5 col-md-4">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">A Note On Service Providers</h5>
				</div>
				<div class="card-body">
					<p class="text-justify">
						Please note, as a service provider you can either offer insurance or tangible products as your service. Once you have added what kind of service you offer, clients will be able to view your services and can apply. For a case of insurance service, you need to go further and add the various insurance products that you are offering.
					</p>
				</div>
			</div>
		</div>
		<div class="col-sm-7 col-md-8">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">Service Details</h5>
				</div>
				<div class="card-body">
					<form action="{{ route('services.store') }}" method="POST">
						{{ csrf_field() }}
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="service">What service do you offer?</label>
									<select name="service" id="" class="form-control">
										<option value="" disabled="" selected="">Select Service</option>
										@foreach($services as $service)
											<option value="{{ $service->id }}">{{ $service->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<button class="btn btn-primary btn-block">Create</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection