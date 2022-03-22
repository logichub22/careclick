@extends('back/individual/layouts/master')

@section('title')
	Withdrawal
@endsection

@section('one-step')
    / Make Withdrawal
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0 mt-2">Withdrawal</h5>
				</div>
				<div class="card-body">
					<script src="https://checkout.flutterwave.com/v3.js"></script>

					<form action="{{ route('user.process-withdrawal') }}" method="POST">
						{{ csrf_field() }}

						<input type="hidden" name="tx_ref" id="tx_ref" value="{{ strtoupper(str_random(10)) }}">
						<!-- <input type="hidden" name="payment_options" id="payment_options" value="{{ $paymentMethod }}"> -->
						<input type="hidden" name="redirect_url" id="redirect_url" value="{{ route('organization.wallet') }}">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Name<span class="important">*</span></label>
									<input type="text" id="name" name="name" class="form-control" placeholder="Enter Full Name" value='{{ $user->name }}'>
								</div>
							</div>
                            <div class="col-md-6">
								<div class="form-group">
									<label>Bank/Network<span class="important">*</span></label>
									<select name="bank_name" id="bank_name" class="form-control{{ $errors->has('payment_method') ? ' is-invalid' : '' }}">
										<option value="" disabled="" selected="">Select Bank</option>
                                            @foreach($banks as $bank)
                                            <option value="{{$bank->code}}">{{$bank->name}}</option>
                                            @endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Account No/Mobile Number<span class="important">*</span></label>
									<input type="text" id="account_no" name="account_no" class="form-control" placeholder="Enter Account No Or Mobile Number">
								</div>
							</div>

                            <div class="col-md-6">
								<div class="form-group">
									<label>Withdrawal Narration<span class="important">*</span></label>
									<input type="text" id="narration" name="narration" class="form-control" placeholder="Narration">
								</div>
							</div>

						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Currency<span class="important">*</span></label>
									<input type="hidden" name="currency" value="{{ $currency }}">
									<input type="text" class="form-control" value="{{ $currency }}" disabled>
									{{-- <select name="currency" id="currency" class="form-control{{ $errors->has('currency') ? ' is-invalid' : '' }}">
										<option value="" disabled="" selected="">Select Currency</option>
										@foreach($currencies as $currency)
											<option value="{{ $currency }}">{{ $currency }}, {{ $currency->name }}</option>
										@endforeach
									</select> --}}
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
									<button type="submit" class="btn btn-primary btn-block">Submit</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
