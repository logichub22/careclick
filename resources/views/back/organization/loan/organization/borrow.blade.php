@extends('back/organization/layouts/master')

@section('title')
	Apply For a Loan
@endsection

@section('one-step')
    / Apply For a Loan
@endsection

@push('styles')
	<style>
		label{
			font-weight: bold;
		}
	</style>
@endpush

@section('content')
	<div class="row">
		<div class="col-md-4">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">How to Apply</h5>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<a href="{{ route('organization.browseloans') }}" class="btn btn-primary btn-block">Back to all packages</a>
						</div>
						<div class="col-md-6">
							<a href="{{ route('org-loans.index') }}" class="btn btn-success btn-block">My Loans</a>	
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">Package Details</h5>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-4">
							<p><i class="far fa-money-bill-alt"></i> Min Amount</p>
							<p>{{ $package->currency . ' ' . number_format($package->min_amount) }}</p>
						</div>	
						<div class="col-md-4">
							<p><i class="far fa-money-bill-alt"></i> Max Amount</p>
							<p>{{ $package->currency. ' ' . number_format($package->max_amount) }}</p>
						</div>
						<div class="col-md-4">
							<p><i class="fa fa-balance-scale"></i> Interest Rate</p>
							<p>{{ $package->interest_rate }} % per annum</p>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-4" title="This means that the deductions will be made {{ $package->repayment_plan }} until the full amount is settled">
							<p><i class="fas fa-calendar"></i> Repayment Plan</p>
							<p>{{ $package->repayment_plan }}</p>
						</div>	
						<div class="col-md-4">
							<p><i class="far fa-money-bill-alt"></i> Loan Package Name</p>
							<p>{{ $package->name }}</p>
						</div>
						<div class="col-md-4">
							<p><i class="fa fa-balance-scale"></i> Insured?</p>
							<p>
								@if($package->insured)
									Yes
								@else
									No
								@endif
							</p>
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
					<h5 class="mb-1">Loan Application Form</h5>
				</div>
				<div class="card-body">
					<form id="borrowForm" method="POST" action="{{ route('org-loans.store') }}">
						{{ csrf_field() }}
						<input type="hidden" name="package_id" value="{{ $package->id }}" id="package_id">
						<input type="hidden" name="package_name" value="{{ $package->name }}" id="package_name">
						<input type="hidden" name="interest_rate" value="{{ $package->interest_rate }}" id="interest_rate">
						<input type="hidden" name="max" value="{{ $package->max_amount }}" id="max_amount">
						<input type="hidden" name="min" value="{{ $package->min_amount }}" id="min_amount">
						<input type="hidden" name="credit_score" value="{{ $package->min_credit_score }}" id="credit_score">
						<input type="hidden" name="wallet_balance" value="{{ $walletBalance->balance }}" id="wallet_balance">
						<input type="hidden" name="organization" value="{{ $organization->id }}">
						{{-- <input type="hidden" name="walletBalance" value="{{ $walletBalance->balance }}" id="walletBalance"> --}}
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="loan_title">Name of loan</label>
									<input type="text" class="form-control{{ $errors->has('loan_title') ? ' is-invalid' : '' }}" name="loan_title" value="{{ old('loan_title') }}" placeholder="How do you want to name your loan?" id="loan_title">
									@if($errors->has('loan_title'))
										<span class="invalid-feedback" role="alert">
											{{ $errors->first('loan_title') }}
										</span>
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="amount">Amount</label>
									<input type="text" class="form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}" name="amount" value="{{ old('amount') }}" placeholder="Min: {{ number_format($package->min_amount) . ' ' . $package->currency . ' ' . ' ' }} Max: {{ number_format($package->max_amount) . ' ' . $package->currency }}" id="amount">
									@if($errors->has('loan_title'))
										<span class="invalid-feedback" role="alert">
											{{ $errors->first('loan_title') }}
										</span>
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="length_of_loan">
										Length of loan in
										@if($package->repayment_plan === "weekly")
											weeks
										@elseif($package->repayment_plan === "monthly")
											months
										@else
											2-week periods
										@endif
									</label>
									@if($package->repayment_plan === "monthly")
										<select name="length_of_loan" class="form-control{{ $errors->has('length_of_loan') ? ' invalid-feddback' : '' }}">
											<option value="" disabled selected>Length of Loan</option>
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
											<option value="11">11</option>
											<option value="12">12</option>
										</select>	
									@else									
										<input type="text" class="form-control{{ $errors->has('length_of_loan') ? ' is-invalid' : '' }}" name="length_of_loan" value="{{ old('length_of_loan') }}" placeholder="Length of loan in number" id="length_of_loan">
										@if($errors->has('length_of_loan'))
											<span class="invalid-feedback" role="alert">
												{{ $errors->first('length_of_loan') }}
											</span>
										@endif
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="payment_frequency">Payment Frequency</label>
									<input type="text" class="form-control{{ $errors->has('payment_frequency') ? ' is-invalid' : '' }}" name="payment_frequency" value="{{ strtoupper($package->repayment_plan) }}" readonly id="payment_frequency">
								</div>
							</div>
							<div class="col-md-8 offset-md-2">
								<a href="#loanSummary" class="btn btn-primary btn-block" id="showModal" data-toggle="modal" style="display: none;">Apply this loan</a>
								<button class="btn btn-primary btn-block" id="sendData" type="submit">Apply</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Loan Summary Modal -->
	<div class="modal fade" id="loanSummary" tabindex="-1" role="dialog" aria-labelledby="loanSummary" aria-hidden="true">
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
								<h5 class="float-xs-left mb-0">Loan Summary <span id="score"></span></h5>
								<div class="float-xs-right">{{ date('F' . ' ' . 'd' . ',' . ' ' . 'Y') }}</div>
							</div>
							<div class="card-block">
								<div class="row mb-2">
									<div class="col-sm-12">
										<h5>Payment Details:</h5>
										<div class="clearfix mb-0-25">
											<span class="float-xs-left">Title of your loan:</span>
											<span class="float-xs-right" id="append-title"></span>
										</div>
										<div class="clearfix mb-0-25">
											<span class="float-xs-left">No. Of Installments:</span>
											<span class="float-xs-right" id="installments"></span>
										</div>
										<div class="clearfix mb-0-25">
											<span class="float-xs-left">Payment Frequency:</span>
											<span class="float-xs-right">{{ strtoupper($package->repayment_plan) }}</span>
										</div>
										<div class="clearfix mb-0-25">
											<span class="float-xs-left">Currency:</span>
											<span class="float-xs-right">
												@if($package->currency === "Naira")
														Naira (&#8358;)
												@elseif($package->currency === "Kenya Shillings")
													Kenya Shillings (Ksh.)
												@elseif($package->currency === "American Dollar")
													American Dollar (&#36;)
												@else
													Sterling Pound (&#163;)
												@endif
											</span>
										</div>
									</div>

								</div>
								<table class="table table-bordered table-striped mb-2">
									<thead>
										<tr>
											<th>
												Description
											</th>
											<th>
												Amount
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Principal Amount</td>
											<td> 
												@if($package->currency === "Naira")
													&#8358;
												@elseif($package->currency === "Kenya Shillings")
													Ksh.
												@elseif($package->currency === "American Dollar")
													&#36;
												@else
													&#163;
												@endif <span id="principal"></span>
											</td>
										</tr>
										<tr>
											<td>Total Interest Amount</td>
											<td>
												@if($package->currency === "Naira")
													&#8358;
												@elseif($package->currency === "Kenya Shillings")
													Ksh.
												@elseif($package->currency === "American Dollar")
													&#36;
												@else
													&#163;
												@endif <span id="interest"></span>
											</td>
										</tr>
										<tr>
											<td>{{ ucfirst($package->repayment_plan) }} Amount Deducted</td>
											<td>
												@if($package->currency === "Naira")
													&#8358;
												@elseif($package->currency === "Kenya Shillings")
													Ksh.
												@elseif($package->currency === "American Dollar")
													&#36;
												@else
													&#163;
												@endif <span id="installment-amount"></span> 
											</td>
										</tr>
										<tr>
											<td>Annual Interest Rate</td>
											<td>{{ $package->interest_rate }}%</td>
										</tr>
									</tbody>
								</table>
								<div class="row">
									<div class="col-lg-6">
										<strong>Additional Information</strong>
										<p class="text-muted mb-0">
											By clicking confirm, a one time fee of 
											@if($package->currency === "Naira")
												&#8358;
											@elseif($package->currency === "Kenya Shillings")
												Ksh.
											@elseif($package->currency === "American Dollar")
												&#36;
											@else
												&#163;
											@endif <span id="fees"></span> 
											will be deducted from your wallet.
										</p>
									</div>
									<div class="col-lg-6">
										<div class="text-xs-right">
											<div class="mb-0-5">Sub Total: 
												<b>
													@if($package->currency === "Naira")
														&#8358;
													@elseif($package->currency === "Kenya Shillings")
														Ksh.
													@elseif($package->currency === "American Dollar")
														&#36;
													@else
														&#163;
													@endif <span id="subtotal"></span>
												</b>
											</div>
											<div class="mb-0-5">Processing Fee: @if($package->currency === "Naira")
													&#8358;
												@elseif($package->currency === "Kenya Shillings")
													Ksh.
												@elseif($package->currency === "American Dollar")
													&#36;
												@else
													&#163;
												@endif <span id="processing-fee"></span></div>
											Grand Total: <strong>@if($package->currency === "Naira")
													&#8358;
												@elseif($package->currency === "Kenya Shillings")
													Ksh.
												@elseif($package->currency === "American Dollar")
													&#36;
												@else
													&#163;
												@endif <span id="total"></span></strong>
										</div>
									</div>
								</div>
							</div>
							<div class="card-footer clearfix">
								<button type="button" class="btn btn-primary label-left float-xs-right" id="confirmLoan">
									<span class="btn-label"><i class="fas fa-check"></i></span>
									Confirm
								</button>
								<button type="button" class="btn btn-info label-left float-xs-right mr-0-5" data-dismiss="modal" id="cancel">
									<span class="btn-label"><i class="fas fa-ban"></i></span>
									Cancel
								</button>
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>
	<!--// End Loan Summart Modal //-->
@endsection

@section('spec-scripts')
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<!-- <script>
		$(document).ready(function () {
			$('#sendData').click(function(e) {
				event.preventDefault();
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.ajax({
					url: "{{ route('loanservice') }}",
					method: 'POST',
					data: {
						amount: $('#amount').val(),
						length_of_loan: $('#length_of_loan').val(),
						payment_frequency: $('#payment_frequency').val(),
						interest_rate: $('#interest_rate').val(),
						loan_title: $('#loan_title').val(),
						length_of_loan: $('#length_of_loan').val(),
						credit_score: $('#credit_score').val(),
						wallet_balance: $('#wallet_balance').val()
					},
					success: function(data) {
						console.log(data);
						$('#append-title').text(data.titleOfLoan);
						$('#installments').text(data.lengthOfLoan);
						$('#principal').text(data.principal);
						$('#interest').text(data.totalInterest);
						$('#installment-amount').text(data.installmentAmount);
						$('#fees').text(data.serviceFee);
						$('#subtotal').text(data.subTotal);
						$('#processing-fee').text(data.serviceFee);
						$('#total').text(data.totalAmount);
						$('#showModal').trigger('click');

						// Send data on confirm button click
						$('#confirmLoan').click(function(e) {
							e.preventDefault();
							let expected_score = $('#credit_score').val();

							console.log(data);

							// Check whether credit score matches
							if (data.score < expected_score) {
								$('#cancel').trigger('click');
								swal({
								  title: "Loan Denied",
								  text: "You do not meet the minimum loan requirements",
								  icon: "warning",
								});
							} else {
								// Check whether user has enough in his wallet for service fee
								if (data.walletBalance >= data.serviceFee) {
									$('#cancel').trigger('click');
									// Store Loan Into Database
									$('#borrowForm').submit();
								} else {
									swal({
									  title: "Insufficient Balance",
									  text: "Your wallet balance is less than the service fee required",
									  icon: "warning",
									});
								}
							}
						});
					}
				});
			});
		})
	</script> -->
@endsection