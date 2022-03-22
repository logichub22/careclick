@extends('back/superadmin/layouts/master')

@section('title')
	Graphical Analysis
@endsection

@push('scripts')
	<script src="{{ asset('plugins/chartjs/Chart.bundle.min.js') }}"></script>
@endpush

@section('page-nav')
	<h4>Graphical Analysis</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('super.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item active">Graphical Analysis</li>
	</ol>
@endsection

@section('content')
	<div class="box box-block bg-white">
		<div class="row">
			<div class="col-md-12 mb-1 mb-md-0">
				<h5 class="mb-1">User Analysis</h5>
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
				<canvas id="line" class="chart-container" height="80"></canvas>
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
	{{-- <script src="{{ asset('js/back/supergraph.js') }}"></script> --}}
	<script>
		$(document).ready(function() {
			var request = $.ajax({
				url: "{{ route('superchart.userdata') }}",
				method: 'GET'
			});

			request.done(function (response) {
				console.log(response);
				var ctx = document.getElementById("line").getContext('2d');
				var myChart = new Chart(ctx, {
					type: 'bar',
					data: {
						labels: response.months,
						datasets: [
							{
								label: "All",
								fill: false,
								lineTension: 0.0,
								backgroundColor: "#ccc", //#ccc
								borderColor: "#ccc",
								borderCapStyle: 'butt',
								borderDash: [],
								borderDashOffset: 0.0,
								borderJoinStyle: 'miter',
								pointBorderColor: "#ccc",
								pointBackgroundColor: "#fff",
								pointBorderWidth: 1,
								pointHoverRadius: 5,
								pointHoverBackgroundColor: "#ccc",
								pointHoverBorderColor: "#fff",
								pointHoverBorderWidth: 2,
								pointRadius: 1,
								pointHitRadius: 10,
								data: response.user_count_data,
								spanGaps: false,
							}
						]
					},
					options: {
						scales: {
							yAxes: [{
								ticks: {
									beginAtZero:true,
									max: response.max
								}
							}]
						}
					}
				});
			});

			
		});
	</script>
@endpush