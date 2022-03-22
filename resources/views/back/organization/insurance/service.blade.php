@extends('back/organization/layouts/master')

@section('title')
	Service Section
@endsection

@section('one-step')
    / Service Section
@endsection

@section('page-nav')
	<h4>@lang('layout.servicesection')</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item active">@lang('layout.myservice')</li>
	</ol>
@endsection

@section('content')
	@if(is_null($service))
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-danger">
					{{-- <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> --}}
					<strong>@lang('layout.note'):</strong> @lang('layout.addservice').
				</div>
			</div>
		</div>
	@endif

	<div class="row">
		<div class="col-sm-5 col-md-4">
			<div class="box box-block bg-white">
				<h5 class="mb-1">@lang('layout.serviceprovidernote')</h5>
				<p class="text-justify">
					@lang('layout.serviceprovidernote')
				</p>
			</div>
		</div>
		<div class="col-sm-7 col-md-8">
			<div class="box box-block bg-white">
				<h5 class="mb-1">@lang('layout.servicedetails')</h5>
				<form action="{{ route('service.choose') }}" method="POST">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="service">@lang('layout.whatservice')</label>
								<select name="service" id="" class="form-control">
									<option value="" disabled="" selected="">@lang('layout.selectservice')</option>
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
								<button class="btn btn-primary btn-block">@lang('layout.create')</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection