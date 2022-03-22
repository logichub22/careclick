@extends('back/individual/layouts/master')

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

@section('one-step')
    / Loan
@endsection

@section('content')
	<div class="row">
		<div class="col-md-4">
			@if($loan->status == 1)
			<a href="#loanSchedule" class="btn btn-primary btn-block" id="showModal" data-toggle="modal">Show Schedule</a>
			@endif
			<!-- <button id="generateSchedule" class="btn btn-primary btn-block">View Schedule</button> <br> -->
			<a href="{{ route('user-loans.index') }}" class="btn btn-success btn-block">Back to my loans</a> <br>
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">Loan Details</h5>
				</div>
				<div class="card-body">
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
						@if($detail->balance > 0)
						<li class="nav-item">
							<a class="nav-link">
								<strong>Next Payment Date:</strong> {{ $detail->next_payment_date }}
							</a>
						</li>
						@endif

						<li class="nav-item">
							<a class="nav-link">
								<strong>Borrowed On:</strong> {{ date('M j, Y', strtotime($loan->created_at)) . ' at ' . date('H:i', strtotime($loan->created_at)) }}
							</a>
						</li>
					</ul>
					@if($loan->status == 0)
						<button class="btn btn-danger btn-block" disabled>Loan Request Pending</button>
					@elseif($detail->balance > 0)
						<a href="#loanRepayment" class="btn btn-primary btn-block" id="showModal" data-toggle="modal">Repay Loan</a>
					@endif
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">
					<h5>Additional Information</h5>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-4">
							<p><i class="far fa-money-bill-alt"></i> Amount Borrowed</p>
							<p>{{ $detail->currency }} {{ number_format($detail->principal_due, 2) }}</p>
						</div>
						<div class="col-md-4">
							<p><i class="far fa-money-bill-alt"></i> Total Payable Amount</p>
							<p>{{ $detail->currency }} {{ number_format($detail->amount_payable, 2) }}</p>
						</div>
						<div class="col-md-4">
							<p><i class="far fa-money-bill-alt"></i> Balance</p>
							<p>{{ $detail->currency }} {{ number_format($detail->balance, 2) }}</p>
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
							<p>{{ $detail->currency }} {{ number_format($detail->charge_per_installment, 2) }}</p>
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
							<p>{{ $detail->currency }} {{ $detail->penalty_due }}</p>
						</div>
						<div class="col-md-4">
							<p><i class="far fa-money-bill-alt"></i> Interest Charged</p>
							<p>{{ $detail->currency }} {{ number_format($detail->interest_due) }}</p>
						</div>
						<div class="col-md-4">
							<p><i class="fa fa-user"></i> Name of Loan Borrowed</p>
							<p>{{ $detail->package_name }}</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">Loan Deductions</h5>
				</div>
				<div class="card-body">
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
	</div>
@endsection

@section('spec-scripts')
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
							<h5 class="float-xs-left mb-0">Payment Schedule &nbsp; <span id="score"></span></h5>
							<div class="float-xs-right">{{ date('F' . ' ' . 'd' . ',' . ' ' . 'Y') }}</div>
						</div>
						<div class="card-body">
							<div class="row mb-2">
								<div class="col-sm-12">
									<h5>Loan Details:</h5>
									<div class="clearfix mb-0-25">
										<span class="float-xs-left">Amount Borrowed: </span>
										<span class="float-xs-right" id="append-principal"></span>
									</div>
									<div class="clearfix mb-0-25">
										<span class="float-xs-left">Total Amount Payable: </span>
										<span class="float-xs-right" id="append-payable"></span>
									</div>
									<div class="clearfix mb-0-25">
										<span class="float-xs-left">Interest Type: </span>
										<span class="float-xs-right">STRAIGHT LINE</span>
									</div>
									<div class="clearfix mb-0-25">
										<span class="float-xs-left">No. Of Installments: </span>
										<span class="float-xs-right" id="append-installments"></span>
									</div>
									<div class="clearfix mb-0-25">
										<span class="float-xs-left">Payment Frequency: </span>
										<span class="float-xs-right" id="payment-frequency"></span>
									</div>
									<div class="clearfix mb-0-25">
										<span class="float-xs-left">Start Date: </span>
										<span class="float-xs-right" id="start-date"></span>
									</div>
									<div class="clearfix mb-0-25">
										<span class="float-xs-left">Payback Date: </span>
										<span class="float-xs-right" id="payback-date"></span>
									</div>
								</div>
							</div>
							<br>
							<table class="table table-bordered table-striped mb-2">
								<thead class="dark-header">
									<tr>
										<th>Payment Date</th>
										<th>Amount</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody id="scheduleBody">

								</tbody>
								<tfoot class="dark-header">
									<tr>
										<th>Total Balance:</th>
										<th id="total" colspan="2"></th>
										{{-- <th id="completed-status"></th> --}}
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
	<!-- End Modal -->
	<!-- Loan Repayment Modal -->
	<div class="modal fade" id="loanRepayment" tabindex="-1" role="dialog" aria-labelledby="loanRepayment" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					{{-- <h4 class="modal-title">Loan Repayment</h4> --}}
				</div>
				<div class="modal-body">
					<div class="card">
						<div class="card-header clearfix">
							<h5 class="float-xs-left mb-0">Loan Repayment</h5>
						</div>
						<div class="card-body">
							<form action="{{ route('user.repay-loan') }}" method="POST">
								{{ csrf_field() }}
								<input type="hidden" name="loan_id" value="{{ $loan->id }}" id="package_id">
								<input type="hidden" name="total_amount" value="{{ $detail->balance }}" id="total_amount">
								<div class="col-md-12">
									{{-- <div class="form-group">
										<label>Amount</label>
										<input class="form-control" type="text" name="amount" id="repaymentAmount" placeholder="{{ $detail->currency }} {{ $detail->charge_per_installment }}" onblur="restrictMinMaxAmount()" required>
									</div> --}}
									<div class="form-group">
										<label for="repayment_count">Select Number of Payments</label>
										<select name="repayment_count" id="repayment_count" class="form-control">
											<option value="0">Choose</option>
										</select>
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary label-left">
											Make Payment
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- End Modal -->
	<script type="text/javascript">
		var charge_per_installment = {!! json_encode($detail->charge_per_installment, JSON_HEX_TAG) !!};
		var balance = {!! json_encode($detail->balance, JSON_HEX_TAG) !!};
		// console.log(balance);
		// console.log(max_amount);

		function restrictMinMaxAmount() {
			// var amount = document.getElementById('repaymentAmount').value;

			// if (amount < charge_per_installment) {
			// 	document.getElementById('repaymentAmount').value = charge_per_installment;
			// }
			// else if (amount > balance) {
			// 	document.getElementById('repaymentAmount').value = balance;
			// }
		}
	</script>
	<script>
		$(document).ready(function () {
			var id = $('#loanID').val();

			$('#showModal').click(function (e) {
				event.preventDefault();
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				
				$.ajax({
					url: "{{ url('user/loan-scheduler') }}"+ "/" +id,
					method: 'GET',
					success: function(data) {
						var schedule = data.schedule;
						$('#append-principal').text(data.principal);
						$('#append-installments').text(data.installments);
						$('#start-date').text(data.start_date);
						$('#payback-date').text(data.payback_date);
						$('#append-payable').text(data.amount_to_pay);
						$('#payment-frequency').text(data.payment_frequency);
						$('#total').text(data.balance);
						
						// Append rows to table
						var status = function(code){
							let st;
							switch(code){
								case 1:
									st = "Paid";
								break;
								case 2:
									st = "Defaulted";
								break;
								default:
									st = "Unpaid";
							}

							return st
						}

						$("#scheduleBody").html("");
						$.each(schedule, function(index, value){
							if(value.status > 0) {
								balance -= parseFloat(value.amount);
							}
							$("#scheduleBody").append(`
								<tr>
									<td>${value.scheduled_date}</td>
									<td>${value.amount}</td>
									<td>${status(value.status)}</td>
								</tr>`
							);
						});

						$('#total-balance').text(balance);
						var completed_status = balance > 0 ? "Unpaid" : "Paid";
						$("#completed-status").text('')

						$('#loanSchedule').modal('show');
					}
				});
			});


			//Repayment selection
			$.ajax({
				url: "{{ url('user/loan-repay-list') }}"+ "/" +id,
				method: 'GET',
				success: function(data) {
					$("#repayment_count").html(data);
				}
			});
				

		});
	</script>
@endsection
