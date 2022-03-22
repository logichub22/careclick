@extends('back/individual/layouts/master')

@section('title')
	Personal Wallet
@endsection

@section('one-step')
    / Make Payment
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0">Add Money via {{ $paymentMethodText }}</h5>
				</div>
				<div class="card-body">
					<script src="https://checkout.flutterwave.com/v3.js"></script>

					<form action="{{ route('user.process-payment') }}" method="POST">
						{{ csrf_field() }}

						<input type="hidden" name="tx_ref" id="tx_ref" value="{{ strtoupper(str_random(10)) }}">
						<!-- <input type="hidden" name="payment_options" id="payment_options" value="{{ $paymentMethod }}"> -->
						<input type="hidden" name="redirect_url" id="redirect_url" value="{{ route('organization.wallet') }}">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Name<span class="important">*</span></label>
									<input type="text" id="name" name="name" class="form-control" placeholder="Enter Full Name" value='{{ "{$user->name} {$user->other_names}" }}'>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Email<span class="important">*</span></label>
								<input type="text" id="email" name="email" class="form-control" placeholder="johndoe@example.com" value="{{ $user->email }}">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Phone Number<span class="important">*</span></label>
									<input type="text" id="phonenumber" name="phonenumber" class="form-control" placeholder="Enter Phone Number" value="{{ $user->msisdn }}">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Payment Method<span class="important">*</span></label>
									<select name="payment_method" id="payment_options" class="form-control{{ $errors->has('payment_method') ? ' is-invalid' : '' }}">
										<option value="" disabled="" selected="">Select Payment Method</option>
											<option value="card">Card</option>
											<option value="account">Bank Account</option>
											<option value="banktransfer">Bank Transfer</option>
											<option value="ussd">USSD</option>
											<option value="mobilemoneyghana">Ghana Mobile Money</option>
											<option value="mobilemoneyrwanda">Rwanda Mobile Money</option>
											<option value="mobilemoneyuganda">Uganda Mobile Money</option>
											<option value="mobilemoneyzambia">Zambia Mobile Money</option>
											<option value="mpesa">Mpesa</option>
											<option value="francophone">Francophone</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Currency<span class="important">*</span></label>
									<input type="hidden" name="currency" value="{{ $currency->prefix }}">
									<input type="text" class="form-control" value="{{ $currency->prefix }}" disabled>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Amount<span class="important">*</span></label>
									<input type="text" id="amount" name="amount" class="form-control" placeholder="Amount">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<button type="submit" class="btn btn-primary btn-block" onclick="makePayment()">Pay Now</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection