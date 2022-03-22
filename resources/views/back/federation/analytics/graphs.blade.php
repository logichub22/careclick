@extends('back/federation/layouts/master')

@section('title')
	Graphical Analysis
@endsection

@push('scripts')
	<script src="{{ asset('plugins/chartjs/Chart.bundle.min.js') }}"></script>
@endpush

@section('page-nav')
	<h4>Graphical Analysis</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('federation.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item active">Graphical Analysis</li>
	</ol>
@endsection

@section('content')
	<div class="box box-block bg-white">
		<div class="row">
			<div class="col-md-12 mb-1 mb-md-0">
				<h5 class="mb-1">User Summary</h5>
				<form action="">
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<input class="form-control" type="text" name="daterange" value="01/01/2015 - 01/31/2015">
							</div>
						</div>
						<div class="col-md-4">
							<button type="submit" class="btn btn-primary btn-block">Apply Filter</button>	
						</div>
					</div>
				</form>
				<canvas id="line" class="chart-container" height="100"></canvas>
			</div>
			{{-- <div class="col-md-6">
				<h5 class="mb-1">Loans Summary</h5>
				<form action="">
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<input class="form-control" type="text" name="daterange" value="01/01/2015 - 01/31/2015">
							</div>
						</div>
						<div class="col-md-4">
							<button type="submit" class="btn btn-primary btn-block">Apply Filter</button>	
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
				<h5 class="mb-1">Organization Users</h5>
				<canvas id="pie" class="chart-container"></canvas>
			</div>
			<div class="col-md-6">
				<h5 class="mb-1">Groups Summary</h5>
				<canvas id="doughnut" class="chart-container"></canvas>
			</div>
		</div>
	</div> --}}
@endsection

@push('scripts')
	<script src="{{ asset('js/back/graphs.js') }}"></script>
@endpush