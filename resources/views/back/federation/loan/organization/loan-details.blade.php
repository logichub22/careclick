@extends('back/organization/layouts/master')

@section('title')
	View Loan Details
@endsection

@push('styles')
	<style>
		.dark-header{
			background-color: #1b2b45 !important;
			color: white;
		}
	</style>
@endpush

@section('page-nav')
	<h4>Loan Details</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="#">Borrowing</a></li>
		<li class="breadcrumb-item"><a href="{{ route('org-loans.index') }}">My Loans</a></li>
		<li class="breadcrumb-item active">Loan Details</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-4">
			<a href="#loanSchedule" class="btn btn-primary btn-block" id="showModal" data-toggle="modal" style="display: none;">Show Schedule</a>
			<button id="generateSchedule" class="btn btn-primary btn-block">View Schedule</button> <br>
			<a href="{{ route('org-loans.index') }}" class="btn btn-success btn-block">Back to my loans</a> <br>
			<div class="box box-block bg-white">
				<h5 class="mb-1">Loan Details</h5>
				<form style="display: none;">
					<input type="hidden" id="loanID" value="{{ $loan->id }}">
				</form>
				<ul class="nav nav-4">
					<li class="nav-item">
						<a class="nav-link">
							<strong>Loan Name:</strong> {{ $loan->loan_title }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link">
							<strong>Payback Date:</strong> {{ $detail->payback_date }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link">
							<strong>Next Payment Date:</strong> {{ $detail->next_payment_date }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link">
							<strong>Borrowed On:</strong> {{ date('M j, Y', strtotime($loan->created_at)) . ' at ' . date('H:i', strtotime($loan->created_at)) }}
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-md-8">
			<div class="box box-block bg-white">
				<h5>Additional Information</h5>
				<div class="row">
					<div class="col-md-4">
						<p><i class="far fa-money-bill-alt"></i> Amount Borrowed</p>
						<p>&#8358; {{ number_format($detail->principal_due) }}</p>
					</div>
					<div class="col-md-4">
						<p><i class="far fa-money-bill-alt"></i> Total Payable Amount</p>
						<p>&#8358; {{ number_format($detail->amount_payable) }}</p>
					</div>	
					<div class="col-md-4">
						<p><i class="far fa-money-bill-alt"></i> Balance</p>
						<p>&#8358; {{ number_format($detail->balance) }}</p>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-4">
						<p><i class="far fa-money-bill-alt"></i> No. Of Installments</p>
						<p>{{ $detail->no_of_installments }}</p>
					</div>	
					<div class="col-md-4">
						<p><i class="far fa-money-bill-alt"></i> Charge Per Installment</p>
						<p>&#8358; {{ number_format($detail->charge_per_installment) }}</p>
					</div>
					<div class="col-md-4">
						<p><i class="far fa-money-bill-alt"></i> Interest Charge Frequency</p>
						<p>{{ $detail->interest_charge_frequency }}</p>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-4">
						<p><i class="far fa-money-bill-alt"></i> Penalties Charged</p>
						<p>&#8358; {{ $detail->penalty_due }}</p>
					</div>	
					<div class="col-md-4">
						<p><i class="far fa-money-bill-alt"></i> Interest Charged</p>
						<p>&#8358; {{ number_format($detail->interest_due) }}</p>
					</div>
					<div class="col-md-4">
						<p><i class="fa fa-user"></i> Name of Loan Borrowed</p>
						<p>{{ $detail->package_name }}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="box box-block bg-white">
				<h5 class="mb-1">Loan Deductions</h5>
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-2">
						<thead>
							<th>Date</th>
							<th>Amount Deducted</th>
							<th>Status</th>
							<th>Pending Balance</th>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Loan Schedule Modal -->
	<div class="modal fade" id="loanSchedule" tabindex="-1" role="dialog" aria-labelledby="loanSchedule" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					{{-- <h4 class="modal-title">Loan Summary</h4> --}}
				</div>
				<div class="modal-body">
					<div class="card">
						<div class="card-header clearfix">
							<h5 class="float-xs-left mb-0">Payment Schedule <span id="score"></span></h5>
							<div class="float-xs-right">{{ date('F' . ' ' . 'd' . ',' . ' ' . 'Y') }}</div>
						</div>
						<div class="card-block">
							<div class="row mb-2">
								<div class="col-sm-12">
									<h5>Loan Details:</h5> 
									<div class="clearfix mb-0-25">
										<span class="float-xs-left">Amount Borrowed:</span>
										<span class="float-xs-right" id="append-principal"></span>
									</div>
									<div class="clearfix mb-0-25">
										<span class="float-xs-left">Total Amount Payable:</span>
										<span class="float-xs-right" id="append-payable"></span>
									</div>
									<div class="clearfix mb-0-25">
										<span class="float-xs-left">Interest Type:</span>
										<span class="float-xs-right">STRAIGHT LINE</span>
									</div>
									<div class="clearfix mb-0-25">
										<span class="float-xs-left">No. Of Installments:</span>
										<span class="float-xs-right" id="append-installments"></span>
									</div>
									<div class="clearfix mb-0-25">
										<span class="float-xs-left">Payment Frequency:</span>
										<span class="float-xs-right" id="payment-frequency"></span>
									</div>
									<div class="clearfix mb-0-25">
										<span class="float-xs-left">Start Date:</span>
										<span class="float-xs-right" id="start-date"></span>
									</div>
									<div class="clearfix mb-0-25">
										<span class="float-xs-left">Payback Date:</span>
										<span class="float-xs-right" id="payback-date"></span>
									</div>
								</div>
							</div>
							<table class="table table-bordered table-striped mb-2">
								<thead class="dark-header">
									<tr>
										<th>Payment Date</th>
										<th>Amount</th>
										<th>Balance</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody id="scheduleBody">

								</tbody>
								<tfoot class="dark-header">
									<tr>
										<th>Totals</th>
										<th id="total"></th>
										<th id="total-balance"></th>
										<th>Completed</th>
									</tr>
								</tfoot>
							</table>
						</div>
						<div class="card-footer clearfix">
							<button type="button" class="btn btn-primary label-left float-xs-right" id="printSchedule">
								<span class="btn-label"><i class="fas fa-print"></i></span>
								Print
							</button>
							<button type="button" class="btn btn-info label-left float-xs-right mr-0-5" data-dismiss="modal" id="cancel">
								<span class="btn-label"><i class="fas fa-ban"></i></span>
								Dismiss
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script>
		$(document).ready(function () {
			$('#generateSchedule').click(function (e) {
				event.preventDefault();
				var id = $('#loanID').val();
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

				$.ajax({
					url: "{{ url('organization/org-schedule') }}"+ "/" +id,
					method: 'GET',
					success: function(data) {
						//console.log(data);
						var dates = data.dates;
						var amounts = data.amounts;
						var balances = data.balances;
						var last_index = dates.length - 1;
						$('#append-principal').text(data.principal);
						$('#append-installments').text(data.installments);
						$('#start-date').text(dates[0]);
						$('#payback-date').text(dates[last_index]);
						$('#append-payable').text(data.amount_to_pay);
						$('#payment-frequency').text(data.payment_frequency);
						$('#total').text(data.amount_to_pay);
						$('#total-balance').text(0);
						// Append rows to table
						$.each(dates, function(index, value){
							$("#scheduleBody").append('<tr><td>' + value + '</td>' + '<td>' + amounts[index] + '</td>' + '<td>' + balances[index] + '</td>' + '<td>Unpaid</td>' + '</tr>');
						});
						$('#showModal').trigger('click');
					}
				});
			});
		});
	</script>
@endpush