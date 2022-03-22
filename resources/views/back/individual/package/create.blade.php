@extends('back/individual/layouts/master')

@section('title')
	@lang('individual.createloanpackage')
@endsection

@section('one-step')
/ Loan Packages / Create
@endsection

@section('content')
	<div class="row">
		<div class="col-md-4">
			<div class="card">
				<h5 class="card-header">@lang('individual.anoteonpackages')</h5>
				<div class="accordion card-body" id="accordionExample">
				  <div class="card">
					    <div class="card-header" id="headingOne">
					      <h5 class="mb-0">
					        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#creditScore" aria-expanded="true" aria-controls="creditScore">
					          @lang('individual.settingthecreditscore')
					        </button>
					      </h5>
					    </div>

					    <div id="creditScore" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
					      <div class="card-body text-justify" style="padding: 15px;">
					        The minimum credit score field should be a number between <strong>1</strong> and <strong>10</strong>. That is the minimum score that a borrower must have, meaning an equal of that (the score) or higher would warrant loan qualification. {{-- This is the score with which our platform will determine whether the <strong>borrower</strong> qualifies for your loan or not. --}}
					      </div>
					    </div>
				  </div>
				  <div class="card">
					    <div class="card-header" id="headingTwo">
					      <h5 class="mb-0">
					        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#interestRate" aria-expanded="true" aria-controls="interestRate">
					          @lang('individual.settingtheinterestrate')
					        </button>
					      </h5>
					    </div>

					    <div id="interestRate" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionExample">
					      <div class="card-body text-justify" style="padding: 15px;">
					        The interest rate field should have a value between <strong>1</strong> and <strong>100</strong>. You can have decimal values as well, so an interest rate of, say <strong>12.5</strong>, is still valid. By default, the interest is calculated <strong>per annum</strong>. All values will be subsequently converted to percentages. So, 10 will translate to 10% per annum.
					      </div>
					    </div>
				  </div>
				  <div class="card">
					    <div class="card-header" id="headingThree">
					      <h5 class="mb-0">
					        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#insurance" aria-expanded="true" aria-controls="insurance">
					          @lang('individual.insuringyourloan')
					        </button>
					      </h5>
					    </div>

					    <div id="insurance" class="collapse show" aria-labelledby="headingThree" data-parent="#accordionExample">
					      <div class="card-body text-justify" style="padding: 15px;">
					        You can choose to either have your loan insured or not. While this is an optional field, we highly encourage you to select <strong>Yes</strong> from the dropdown list on this field to mitigate the risk associated with your loan. By default, all loans <strong>have no</strong> insurance.
					      </div>
					    </div>
				  </div>
				  <div class="card">
					    <div class="card-header" id="headingFour">
					      <h5 class="mb-0">
					        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#currency" aria-expanded="true" aria-controls="currecny">
					          @lang('individual.minandmaxamount')
					        </button>
					      </h5>
					    </div>

					    <div id="currency" class="collapse show" aria-labelledby="headingFour" data-parent="#accordionExample">
					      <div class="card-body text-justify" style="padding: 15px;">
					        The <strong>minimum amount</strong> must be <strong>less than or equal to</strong> your wallet balance. By default, the <strong>maximum amount</strong> is autofilled with your wallet balance. This, the max amount, should be <strong>less than or equal to</strong> your wallet balance.
					      </div>
					    </div>
				  </div>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="card">
                <div class="card-header">
                    <h4>@lang('individual.createnewloanpackage')</h4>
                </div>
                <div class="card-body">
                	<form action="{{ route('user-packages.store') }}" method="POST">
					@csrf
					<input type="hidden" name="balance" value="{{ $walletBalance->balance }}">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="">@lang('individual.packagename') <span class="important">*</span></label>
								<input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Loan Package Name" name="name" value="{{ old('name') }}">
								@if($errors->has('name'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('name') }}</strong>
									</span>
								@endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">@lang('individual.repaymentplan')<span class="important">*</span></label>
								<select name="repayment_plan" class="form-control{{ $errors->has('repayment_plan') ? ' invalid-feddback' : '' }}">
									<option value="" disabled selected>@lang('individual.repaymentplan')</option>
									<option value="weekly">Weekly</option>
									<option value="bi-weekly">Bi-weekly</option>
									<option value="monthly">Monthly</option>
								</select>
								@if($errors->has('repayment_plan'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('repayment_plan') }}</strong>
									</span>
								@endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">@lang('individual.minimumcreditscore') <span class="important">*</span></label>
                            	<select name="min_score" class="form-control{{ $errors->has('min_score') ? ' is-invalid' : '' }}">
									<option value="" disabled selected>@lang('individual.minimumcreditscore')</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
									<option value="8">8</option>
									<option value="9">9</option>
									<option value="10">10</option>
								</select>
								<!-- <input type="text" class="form-control{{ $errors->has('min_score') ? ' is-invalid' : '' }}" placeholder="Minimum Credit Score" name="min_score" value="{{ old('min_score') }}"> -->
								@if($errors->has('min_score'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('min_score') }}</strong>
									</span>
								@endif
                            <div class="input-desc">
                                <p class="m-0">
                                    The minimum credit score field should be a number between 1 and 10.
                                    That is the minimum score that a borrower must have, meaning an equal of that (the
                                    score) or higher would warrant loan qualification.
                                </p>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">@lang('individual.insureloan')<span class="important">*</span></label>
								<select name="insured" id="" class="form-control{{ $errors->has('insured') ? ' is-invalid' : '' }}">
									<option value="" disabled="" selected="">Insure Loan Package?</option>
									<option value="1">@lang('individual.yes')</option>
									<option value="0">@lang('individual.no')</option>
								</select>
								@if($errors->has('insured'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('insured') }}</strong>
									</span>
								@endif
                            <div class="input-desc">
                                <p class="m-0">
                                    You can choose to either have your loan insured or not.
                                    While this is an optional field, we highly encourage you to select Yes from the
                                    dropdown list on this field to mitigate the risk associated with your loan. By
                                    default, all loans have no insurance.
                                </p>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">@lang('individual.minimumamount')<span class="important">*</span></label>
								<input type="text" class="form-control{{ $errors->has('min_amount') ? ' is-invalid' : '' }}" placeholder="Minimum Amount" name="min_amount" value="{{ old('min_amount') }}" id="min" oninput="restrictMinAmount()">
								@if($errors->has('min_amount'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('min_amount') }}</strong>
									</span>
								@endif
                            <div class="input-desc">
                                <p class="m-0">The minimum amount must be less than or equal to your wallet balance</p>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">@lang('individual.maximumamount')<span class="important">*</span></label>
								<input type="text" class="form-control{{ $errors->has('max_amount') ? ' is-invalid' : '' }}" placeholder="Maximum Amount" name="max_amount" value="{{ $walletBalance->balance }}" oninput="restrictMaxAmount()">
								@if($errors->has('max_amount'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('max_amount') }}</strong>
									</span>
								@endif
                            <div class="input-desc">
                                <p class="m-0">
                                    By default, the maximum amount is autofilled with your wallet balance. This, the max
                                    amount, should be less than or equal to your wallet balance.
                                </p>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">@lang('individual.currency') <span class="important">*</span></label>
								<select name="currency" id="" class="form-control{{ $errors->has('currency') ? ' is-invalid' : '' }}">
									<option value="" disabled="" selected="">Select Currency</option>
									@foreach($currencies as $currency)
										<option value="{{ $currency->name }}">{{ $currency->prefix }}, {{ $currency->name }}</option>
									@endforeach
								</select>
								@if($errors->has('currency'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('currency') }}</strong>
									</span>
								@endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">@lang('individual.interestrate') <span class="important">*</span></label>
								<input type="text" id="rate" class="form-control{{ $errors->has('interest') ? ' is-invalid' : '' }}" placeholder="Interest Rate" name="interest" value="{{ old('interest') }}" oninput="restrictInterestRate()">
								@if($errors->has('interest'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('interest') }}</strong>
									</span>
								@endif
                            <div class="input-desc">
                                <p class="m-0">
                                    The interest rate field should have a value between 1 and 100
                                    You can have decimal values as well, so an interest rate of, say 12.5, is still
                                    valid. By default, the interest is calculated per annum. All values will be
                                    subsequently converted to percentages. So, 10 will translate to 10% per annum.
                                </p>
                            </div>
                        </div>
                        <div class="form-group mb-0 col-md-12">
                            <label for="description">@lang('individual.description') <span class="important">*</span></label>
								<textarea name="description" id="" cols="30" rows="5" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="A brief description of this loan"></textarea>
								@if($errors->has('description'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('description') }}</strong>
									</span>
								@endif
                        </div>
                    </div>
                    <div class="card-footer">
                    	<button type="submit" class="btn btn-primary btn-block">@lang('individual.createpackage')</button>
                    </div>
                    </form>
                </div>
            </div>
            <div class="row">
            	<div class="col-md-12">
	            	<div class="card">
		            	<div class="card-header">
		            		<h4>Interest Rates and Credit Scores Chart</h4>
		            	</div>
		            	<div class="card-body">
		            		<canvas id="myChart3" height="180"></canvas>
		            	</div>
		            </div>
	            </div>
            </div>
    </div>
            
	<script type="text/javascript">
		var wallet = {!! json_encode($walletBalance->balance, JSON_HEX_TAG) !!};

		function restrictInterestRate() {
			var interestRate = document.getElementById('rate').value;
			if (interestRate < 1) {
				document.getElementById('rate').value = 1;
			}
			else if (interestRate > 100) {
				document.getElementById('rate').value = 100;
			}
		}

		function restrictMaxAmount() {
			var maxAmount = document.getElementById('max').value;
			if (maxAmount < 1) {
				document.getElementById('max').value = 1;
			}
			else if (maxAmount > wallet) {
				document.getElementById('max').value = wallet;
			}
		}

		function restrictMinAmount() {
			var minAmount = document.getElementById('min').value;
			if (minAmount < 1) {
				document.getElementById('min').value = 1;
			}
			else if (minAmount > wallet) {
				document.getElementById('min').value = wallet;
			}
		}
	</script>
@endsection
@section('spec-scripts')
	<script src="{{ asset('assets/bundles/chartjs/chart.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('assets/bundles/jqvmap/dist/maps/jquery.vmap.indonesia.js')}} "></script>
	<script type="text/javascript">
        
		var interest_rates = {!! json_encode($interest_rates) !!};
        var interest_rates = Object.values(interest_rates);

        var credit_scores = {!! json_encode($credit_scores) !!};
        var credit_scores = Object.values(credit_scores);

        var months = {!! json_encode($months) !!}
        var months = Object.keys(months);
        console.log(months);

        "use strict";

		var sparkline_values = [10, 7, 4, 8, 5, 8, 6, 5, 2, 4, 7, 4, 9, 6, 5, 9];
		var sparkline_values_chart = [2, 6, 4, 8, 3, 5, 2, 7];
		var sparkline_values_bar = [10, 7, 4, 8, 5, 8, 6, 5, 2, 4, 7, 4, 9, 10, 7, 4, 8, 5, 8, 6, 5, 2, 4, 7, 4, 9, 8, 6, 5, 2, 4, 7, 4, 9, 10, 2, 4, 7, 4, 9, 7, 4, 8, 5, 8, 6, 5];

		$('.sparkline-inline').sparkline(sparkline_values, {
		  type: 'line',
		  width: '100%',
		  height: '32',
		  lineWidth: 3,
		  lineColor: 'rgba(87,75,144,.1)',
		  fillColor: 'rgba(87,75,144,.25)',
		  highlightSpotColor: 'rgba(87,75,144,.1)',
		  highlightLineColor: 'rgba(87,75,144,.1)',
		  spotRadius: 3,
		});

		$('.sparkline-line').sparkline(sparkline_values, {
		  type: 'line',
		  width: '100%',
		  height: '32',
		  lineWidth: 3,
		  lineColor: 'rgba(63, 82, 227, .5)',
		  fillColor: 'transparent',
		  highlightSpotColor: 'rgba(63, 82, 227, .5)',
		  highlightLineColor: 'rgba(63, 82, 227, .5)',
		  spotRadius: 3,
		});

		$('.sparkline-line-chart').sparkline(sparkline_values_chart, {
		  type: 'line',
		  width: '100%',
		  height: '32',
		  lineWidth: 2,
		  lineColor: 'rgba(63, 82, 227, .5)',
		  fillColor: 'transparent',
		  highlightSpotColor: 'rgba(63, 82, 227, .5)',
		  highlightLineColor: 'rgba(63, 82, 227, .5)',
		  spotRadius: 2,
		});
		$('.sparkline-line-chart2').sparkline(sparkline_values_chart, {
		  type: "line",
		  width: "100%",
		  height: "100",
		  lineWidth: 3,
		  lineColor: "white",
		  fillColor: "transparent",
		  highlightSpotColor: "rgba(63,82,227,.1)",
		  highlightLineColor: "rgba(63,82,227,.1)",
		  spotRadius: 3
		});

		$(".sparkline-bar").sparkline(sparkline_values_bar, {
		  type: "bar",
		  width: "100%",
		  height: "100",
		  barColor: "white",
		  barWidth: 2
		});

		var ctx = document.getElementById("myChart3").getContext('2d');
		var myChart = new Chart(ctx, {
		  type: 'line',
		  data: {
		    labels: months,
		    datasets: [{
		      label: 'Interest Rate',
		      data: interest_rate,
		      borderWidth: 2,
		      backgroundColor: 'transparent',
		      borderColor: 'rgba(254,86,83,.7)',
		      borderWidth: 2.5,
		      pointBackgroundColor: 'transparent',
		      pointBorderColor: 'transparent',
		      pointRadius: 4
		    },
		    {
		      label: 'Credit Score',
		      data: credit_score,
		      borderWidth: 2,
		      backgroundColor: 'transparent',
		      borderColor: 'rgba(63,82,227,.8)',
		      borderWidth: 0,
		      pointBackgroundColor: 'transparent',
		      pointBorderColor: 'transparent',
		      pointRadius: 4
		    },
		    ]
		  },
		  options: {
		    legend: {
		      display: false
		    },
		    scales: {
		      yAxes: [{
		        gridLines: {
		          drawBorder: false,
		          color: '#f2f2f2',
		        },
		        ticks: {
		          beginAtZero: true,
		          stepSize: 200,
		          fontColor: "#9aa0ac", // Font Color
		        }
		      }],
		      xAxes: [{
		        gridLines: {
		          display: false
		        },
		        ticks: {
		          fontColor: "#9aa0ac", // Font Color
		        }
		      }]
		    },
		  }
		});

		$('#visitorMap4').vectorMap(
		  {
		    map: 'world_en',
		    backgroundColor: '#ffffff',
		    borderColor: '#F5AE46',
		    borderOpacity: .8,
		    borderWidth: 1,
		    hoverColor: '#000',
		    hoverOpacity: .8,
		    color: '#ddd',
		    normalizeFunction: 'linear',
		    selectedRegions: false,
		    showTooltip: true,
		    pins: {
		      id: '<div class="jqvmap-circle"></div>',
		      my: '<div class="jqvmap-circle"></div>',
		      th: '<div class="jqvmap-circle"></div>',
		      sy: '<div class="jqvmap-circle"></div>',
		      eg: '<div class="jqvmap-circle"></div>',
		      ae: '<div class="jqvmap-circle"></div>',
		      nz: '<div class="jqvmap-circle"></div>',
		      tl: '<div class="jqvmap-circle"></div>',
		      ng: '<div class="jqvmap-circle"></div>',
		      si: '<div class="jqvmap-circle"></div>',
		      pa: '<div class="jqvmap-circle"></div>',
		      au: '<div class="jqvmap-circle"></div>',
		      ca: '<div class="jqvmap-circle"></div>',
		      tr: '<div class="jqvmap-circle"></div>',
		    },
		  });

		/* chart shadow */
		var draw = Chart.controllers.line.prototype.draw;
		Chart.controllers.lineShadow = Chart.controllers.line.extend({
		  draw: function () {
		    draw.apply(this, arguments);
		    var ctx = this.chart.chart.ctx;
		    var _stroke = ctx.stroke;
		    ctx.stroke = function () {
		      ctx.save();
		      ctx.shadowColor = '#00000075';
		      ctx.shadowBlur = 10;
		      ctx.shadowOffsetX = 8;
		      ctx.shadowOffsetY = 8;
		      _stroke.apply(this, arguments)
		      ctx.restore();
		    }
		  }
		});

		var chart = new ApexCharts(document.querySelector("#revenue"), options);

		chart.render();


    </script>
@endsection
