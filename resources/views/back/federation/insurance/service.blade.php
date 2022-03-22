@extends('back/organization/layouts/master')

@section('title')
	Service Sesction
@endsection

@section('page-nav')
	<h4>Service Section</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item active">My Service</li>
	</ol>
@endsection

@section('content')
	@if(is_null($service))
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-danger">
					{{-- <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> --}}
					<strong>Note:</strong> Please add a service to access all features in the system.
				</div>
			</div>
		</div>
	@endif

	<div class="row">
		<div class="col-sm-5 col-md-4">
			<div class="box box-block bg-white">
				<h5 class="mb-1">A Note On Service Providers</h5>
				<p class="text-justify">
					Please note, as a service provider you can either offer insurance or tangible products as your service. Once you have added what kind of service you offer, you will not be allowed to add any other service. For tangible products, you can go further and specify what kind of product you specifically deal with. This could be things such as land, farm produce etc.
				</p>
			</div>
		</div>
		<div class="col-sm-7 col-md-8">
			<div class="box box-block bg-white">
				<h5 class="mb-1">Service Details</h5>
				<form action="{{ route('service.choose') }}" method="POST">
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
@endsection