@extends('back/individual/layouts/master')

@section('title')
	Add Money via {{ $paymentMethodText }}
@endsection

@section('one-step')
    / Make bills payment	
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0 mt-2">Bill Services</h5>
				</div>
				<div class="card-body">
					<script src="https://checkout.flutterwave.com/v3.js"></script>

					<form action="{{ route('user.proccess-bills-payment') }}" method="POST">
						{{ csrf_field() }}

						<input type="hidden" name="tx_ref" id="tx_ref" value="{{ strtoupper(str_random(10)) }}">
						<!-- <input type="hidden" name="payment_options" id="payment_options" value="{{ $paymentMethod }}"> -->
						<input type="hidden" name="redirect_url" id="redirect_url" value="{{ route('organization.wallet') }}">
						<div class="row">
        
                            <div class="col-md-12">
								<div class="form-group">
									<label>Bill Services<span class="important">*</span></label>
									<select name="bill_type" id="bill_type" class="form-control{{ $errors->has('payment_method') ? ' is-invalid' : '' }}">
										<option value="" disabled="" selected="">Select Bill type</option>
                                        <option value="airtime">Airtime  (All networks)</option>
                                        <option value="dstv">DStv payment Nigeria(also known as DStv box)</option>
                                        <option value="lcc">LCC Lekki & Ikoyi</option>
                                        <option value="disco">Eko Disco Post & Prepaid</option>
	                                        <option value="data">Data bundles all network (Nigeria & Uganda)</option>
                                        <option value="remita">Remita government bill payments.</option>
									</select>
									
								</div>
							</div>
						</div>
						
						<div class=" airtime" style="display: none">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Phone Number<span class="important">*</span></label>
										<input type="text" id="phone_no" name="phone_no" class="form-control" placeholder="Mobile No">
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group row">
									<label>Select Network<span class="important">*</span></label>

										<div class="col-md-3">
										<div class="form-check mt-2">
											<input class="form-check-input" type="checkbox" value="airtel" name="network" id="flexCheckChecked" >
											<label class="form-check-label" for="flexCheckChecked">
											<img wifth="50" height="50" src="/img/airtel.jpg">

											</label>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-check mt-2">
											<input class="form-check-input" type="checkbox" value="9mobile"  name="network" id="flexCheckChecked1" >
											<label class="form-check-label" for="flexCheckChecked1">
												<img wifth="50" height="50" src="/img/9mobile.png">

											</label>
											</div>
										</div>
										
										<div class="col-md-3">
										<div class="form-check mt-2">
										<input class="form-check-input" type="checkbox" value="mtn" name="network" id="flexCheckChecked3" >
										<label class="form-check-label" for="flexCheckChecked3">
										<img wifth="50" height="50" src="/img/mtn.png">

										</label>
										</div>
										</div>
										<div class="col-md-3">
										<div class="form-check mt-2">
										<input class="form-check-input" type="checkbox" value="glo" name="network" id="flexCheckChecked2" >
										<label class="form-check-label" for="flexCheckChecked2">
										<img wifth="50" height="50" src="/img/glo.jpg">

										</label>
										</div>
										</div>
									</div>
								</div>
							</div>
							
						
							<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Currency<span class="important">*</span></label>
									<input type="hidden" name="currency" value="{{ $currency->prefix }}">
									<input type="text" class="form-control" value="{{ $currency->prefix }}" disabled>
									{{-- <select name="currency" id="currency" class="form-control{{ $errors->has('currency') ? ' is-invalid' : '' }}">
										<option value="" disabled="" selected="">Select Currency</option>
										@foreach($currencies as $currency)
											<option value="{{ $currency->prefix }}">{{ $currency->prefix }}, {{ $currency->name }}</option>
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
						
						</div>
						<!-- <div class="row">
							
                            
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
									<input type="hidden" name="currency" value="{{ $currency->prefix }}">
									<input type="text" class="form-control" value="{{ $currency->prefix }}" disabled>
									{{-- <select name="currency" id="currency" class="form-control{{ $errors->has('currency') ? ' is-invalid' : '' }}">
										<option value="" disabled="" selected="">Select Currency</option>
										@foreach($currencies as $currency)
											<option value="{{ $currency->prefix }}">{{ $currency->prefix }}, {{ $currency->name }}</option>
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
						</div> -->
						
					</form>
				</div>
			</div>
		</div>
	</div>
	
	
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

	<script>
	$(document).ready(function(){
		$("select").change(function(){
			$(this).find("option:selected").each(function(){
				var optionValue = $(this).attr("value");
				if(optionValue == "airtime"){
					$("." + optionValue).show();
				} else{
					$(".airtime").hide();
				}
			});
		}).change();
	});
</script>
@endsection