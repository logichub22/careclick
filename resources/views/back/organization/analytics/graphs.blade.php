@extends('back/organization/layouts/master')

@section('title')
	Graphical Analysis
@endsection

@section('one-step')
    / Analytics
@endsection

@push('scripts')
	<script src="{{ asset('plugins/chartjs/Chart.bundle.min.js') }}"></script>
@endpush

@section('page-nav')
	<h4>@lang('layout.graphicalanalysis')</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">@lang('layout.home')</a></li>
		<li class="breadcrumb-item active">@lang('layout.graphicalanalysis')</li>
	</ol>
@endsection

@section('content')
	<div class="box box-block bg-white">
		<div class="row">
			<div class="col-md-12 mb-1 mb-md-0">
				<h5 class="mb-1">@lang('layout.usersummary')</h5>
				<form action="">
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<input class="form-control" type="text" name="daterange" value="01/01/2015 - 01/31/2015">
							</div>
						</div>
						<div class="col-md-4">
							<button type="submit" class="btn btn-primary btn-block">@lang('layout.applyfilter')</button>	
						</div>
					</div>
				</form>
				<canvas id="line" class="chart-container" height="100"></canvas>
			</div>
			{{-- <div class="col-md-6">
				<h5 class="mb-1">@lang('layout.loanssummary')</h5>
				<form action="">
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<input class="form-control" type="text" name="daterange" value="01/01/2015 - 01/31/2015">
							</div>
						</div>
						<div class="col-md-4">
							<button type="submit" class="btn btn-primary btn-block">@lang('layout.applyfilter')</button>	
						</div>
					</div>
				</form>
				<canvas id="bar" class="chart-container"></canvas>
			</div> --}}
		</div>
	</div>
	{{-- <div class="box box-block bg-white">
		<div class="row">
			<div class="col-md-6 mb-1 mb-md-0">
				<h5 class="mb-1">@lang('layout.organizationusers')</h5>
				<canvas id="pie" class="chart-container"></canvas>
			</div>
			<div class="col-md-6">
				<h5 class="mb-1">@lang('layout.groupssummary')</h5>
				<canvas id="doughnut" class="chart-container"></canvas>
			</div>
		</div>
	</div> --}}
@endsection

@push('scripts')
	<script src="{{ asset('js/back/graphs.js') }}"></script>
@endpush