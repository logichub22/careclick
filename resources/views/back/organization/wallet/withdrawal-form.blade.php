@extends('back/organization/layouts/master')

@section('title')
	Withdrawal
@endsection

@section('one-step')
    / Make Payment
@endsection

@section('content')
	<div class="row">
		<div class="col-md-4">
			<div class="card">
				<h5 class="card-header">Information</h5>
				<div class="card-body">
                    Please provide the details for your withdrawal destination. Ensure that your details are correct, as the transaction may be irreversible.
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0 mt-2">Enter the Account Details</h5>
				</div>
				<div class="card-body">
					{{-- <script src="https://checkout.flutterwave.com/v3.js"></script> --}}

					<form action="{{ route('org.process-withdrawal') }}" method="POST">
						@csrf
                            <div class="form-group">
                                <label for="bank">Bank/Network<span class="important">*</span></label>
                                <select name="bank" id="bank" class="form-control" required>
                                    <option value="" disabled="" selected="">Select Bank</option>
                                    @foreach($banks as $bank)
                                        <option value="{{$bank->code}}">{{$bank->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Account Number<span class="important">*</span></label>
                                <input type="number" id="account_number" name="account_number" class="form-control" placeholder="Enter you bank account number" required>
                            </div>

                            <div class="form-group">
                                <label for="">Amount <span class="important">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ $currency }}</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Maximum withdrawable amount: {{ $wallet_balance }}" name="amount" id="amount" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="currency" id="currency" value="{{ $currency }}">
                                <button type="button" id="continue" class="btn btn-primary">Continue</button>
                            </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('spec-scripts')
    <!-- Loan Repayment Modal -->
    <div class="modal fade" id="withdrawal" tabindex="-1" role="dialog" aria-labelledby="withdrawal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header clearfix">
                            <h5 class="float-xs-left mb-0">Confirm Withdrawal</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('org.process-withdrawal') }}">
                                @csrf
                                <div class="col-md-12" id="withdrawal-modal-content">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- End Modal -->

	<script>
		$(document).ready(function () {
			$('#continue').click(function (e) {
				e.preventDefault();
                $('#continue').text('Please wait...');


                let amount = $('#amount').val(),
                    currency = $('#currency').val(),
                    account_number = $('#account_number').val(),
                    bank = $('#bank').val()

                if(amount == ''){
                    //error - pop up error modal
                    console.log('enter an amount')
                    $('#continue').text('Continue');
                }
                else{
                    $.ajax({
                        url: "{{ route('common.verify-bank') }}",
                        method: 'GET',
                        data: {
                            account_number: account_number,
                            bank: bank,
                            amount: amount
                        },
                        success: function(response_data) {
                            response_data = JSON.parse(response_data);
                            response = response_data.fw_response
                            if(response.status == 'success'){
                                let account_details = response.data
                                let account_name = account_details.account_name

                                let text = `
                                    <div class="form-group">
                                        You are about to transfer <strong>${currency} ${amount}</strong> to <strong>${account_name}</strong>. You will be charged a withdrawal fee of <strong>${currency} ${response_data.charges}. To confirm this transaction, please enter your password below
                                    </div>

                                    <div class="form-group">
                                        <input type="hidden" name="bank" value="${bank}">
                                        <input type="hidden" name="account_number" value="${account_number}">
                                        <input type="hidden" name="amount" value="${amount}">
                                        <input type="hidden" name="amount_with_charges" value="${response_data.total}">
                                        <input type="hidden" name="currency" value="${currency}">
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Process Withdrawal</button>
                                `;

                                $('#withdrawal-modal-content').html(text);
                                $('#withdrawal').modal('show');

                            }
                            else{
                                console.log('error')

                            }

                        },
                        complete: function() {
                            $('#continue').text('Continue');
                        }
                    });
                }
			});
		});
	</script>
@endsection
