@extends('back/individual/layouts/master')

@section('title')
	@lang('individual.createloanpackage')
@endsection

@section('one-step')
    / Loan Packages / Edit
@endsection

@section('content')
	<div class="row">
		<div class="col-md-4">
			<div class="card">
				<div class="card-header">
					<h5>@lang('individual.anoteonpackages') </h5>
				</div>
				<div class="accordion card-body" id="accordionExample">
				  <div class="card-body">
					 <p>
					 	Package Name: {{ $package->name }}
					 </p>
					 <p>
					 	Currency: {{ $package->currency }}
					 </p>
					 <p>
					 	Borrowers: {{ count($datas) }}
					 </p>
					 <p>
					 	Repayment Plan: {{ $package->repayment_plan }}
					 </p>
					 <p>
					 	Status: @if($package->status)
											Active
										@else
											Inactive
										@endif
					 </p>

					 <p>
						Minimum Credit Score: {{ $package->min_credit_score }}
					 </p>
					 <p>
					 	Interest Rate: {{ $package->interest_rate }}% per annum
					 </p>
					 <p>
					 	Insured? @if($package->insured)
											Yes
										@else
											No
										@endif
					 </p>

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
                    <h4>@lang('individual.updateloanpackage')</h4>
                </div>
                <div class="card-body">
                	<form action="{{ route('user-packages.store', $package->id) }}" method="POST">
          @method('PATCH')
					@csrf
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="">@lang('individual.packagename') <span class="important">*</span></label>
								<input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ $package->name }}" name="name" value="{{ old('name') }}">
								@if($errors->has('name'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('name') }}</strong>
									</span>
								@endif
                        </div>
                        <!-- <div class="form-group col-md-6">
                            <label for="">@lang('individual.repaymentplan')<span class="important">*</span></label>
								<select name="repayment_plan" class="form-control{{ $errors->has('repayment_plan') ? ' invalid-feddback' : '' }}">
                  <option value="">{{ $package->name }}</option>
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
                        </div> -->
                       <!--  <div class="form-group col-md-6">
                            <label for="">@lang('individual.minimumcreditscore') <span class="important">*</span></label>
                            	<select name="min_score" class="form-control{{ $errors->has('min_score') ? ' is-invalid' : '' }}">
																<option value="" disabled="" selected="">Set Min Credit Score is {{ $package->min_credit_score }}</option>
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
									<option value="" disabled="" selected="">Selected {{ $package->insured }}</option>
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
                        </div> -->
                        <div class="form-group col-md-6">
                            <label for="">@lang('individual.minimumamount')<span class="important">*</span></label>
								<input type="text" id="min" class="form-control{{ $errors->has('min_amount') ? ' is-invalid' : '' }}" placeholder="{{ $package->min_amount }}" name="min_amount" value="{{ old('min_amount') }}" oninput="restrictMinAmount()">
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
								<input type="text" id="max" class="form-control{{ $errors->has('max_amount') ? ' is-invalid' : '' }}" placeholder="{{ $package->max_amount }}" name="{{ $package->max_amount }}" value="{{ $walletBalance->balance }}" oninput="restrictMaxAmount()">
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
                        <!-- <div class="form-group col-md-6">
                            <label for="">@lang('individual.currency') <span class="important">*</span></label>
								<select name="currency" id="" class="form-control{{ $errors->has('currency') ? ' is-invalid' : '' }}">
									<option value="" disabled="" selected="">Selected {{ $package->currency }}</option>
									@foreach($currencies as $currency)
										<option value="{{ $currency->name }}">{{ $currency->name }}</option>
									@endforeach
								</select>
								@if($errors->has('currency'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('currency') }}</strong>
									</span>
								@endif
                        </div> -->
                        <!-- <div class="form-group col-md-6">
                            <label for="">@lang('individual.interestrate') <span class="important">*</span></label>
								<input type="text" id="rate" class="form-control{{ $errors->has('interest') ? ' is-invalid' : '' }}" placeholder="{{ $package->interest_rate }}" name="interest" value="{{ old('interest') }}" oninput="restrictInterestRate()">
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
                        </div> -->
                        <div class="form-group mb-0 col-md-12">
                            <label for="description">@lang('individual.description') <span class="important">*</span></label>
								<textarea name="description" id="" cols="30" rows="5" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ $package->description }}"></textarea>
								@if($errors->has('description'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('description') }}</strong>
									</span>
								@endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block">@lang('individual.updateloanpackage')</button>
                </div>
            </form>
            </div>
		</div>
	</div>
	<script type="text/javascript">
	  // var wallet = <?php echo $walletBalance->balance; ?>

		var wallet = {!! json_encode($walletBalance->balance, JSON_HEX_TAG) !!};

		console.log(wallet);
		function restrictInterestRate() {
			var interestRate = document.getElementById('rate').value;
			if (interestRate < 1) {
				document.getElementById('rate').value = 1;
			}
			else if (interestRate > 100) {
				document.getElementById('rate').value = 100;
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

		function restrictMaxAmount() {
			var maxAmount = document.getElementById('max').value;
			if (maxAmount < 1) {
				document.getElementById('max').value = 1;
			}
			else if (maxAmount > wallet) {
				document.getElementById('max').value = wallet;
			}
		}
	</script>
@endsection
