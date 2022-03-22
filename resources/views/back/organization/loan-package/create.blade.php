@extends('back/organization/layouts/master')

@section('title')
	Create Loan Package
@endsection

@section('one-step')
    / Create Loan Package
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
                	<form action="{{ route('org-packages.store') }}" method="POST">
						@csrf
						<input type="hidden" name="balance" value="{{ $walletBalance->balance }}">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Package Name <span class="important">*</span></label>
									<input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Loan Package Name" name="name" value="{{ old('name') }}" required>
									@if($errors->has('name'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('name') }}</strong>
										</span>
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Repayment Plan <span class="important">*</span></label>
									<select name="repayment_plan" class="form-control{{ $errors->has('repayment_plan') ? ' invalid-feddback' : '' }}" required>
										<option value="" disabled selected>Repayment Plan</option>
										@if(!$isFirstSource)
										<option value="weekly">Weekly</option>
										<option value="bi-weekly">Bi-weekly</option>
										@endif
										<option value="monthly">Monthly</option>
									</select>
									@if($errors->has('repayment_plan'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('repayment_plan') }}</strong>
										</span>
									@endif
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Minimum Credit Score <span class="important">*</span></label>
									<select name="min_score" class="form-control{{ $errors->has('min_score') ? ' is-invalid' : '' }}" required>
										<option value="" disabled selected>@lang('individual.minimumcreditscore')</option>
										@for ($i = 1; $i <= 10; $i++)
												<option value="{{$i}}">$i</option>
										@endfor
									</select>
									@if($errors->has('min_score'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('min_score') }}</strong>
										</span>
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Insure Loan Package?</label>
									<select name="insured" id="" class="form-control{{ $errors->has('insured') ? ' is-invalid' : '' }}">
										<option value="1">Yes</option>
										<option value="0" selected>No</option>
									</select>
									@if($errors->has('insured'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('insured') }}</strong>
										</span>
									@endif
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Minimum Amount <span class="important">*</span></label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">{{ $org_currency }}</span>
										</div>
										<input type="text" class="form-control{{ $errors->has('min_amount') ? ' is-invalid' : '' }}" placeholder="1000" name="min_amount" id="min" value="{{ old('min_amount') }}" oninput="restrictMinAmount()" required>
									</div>
									@if($errors->has('min_amount'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('min_amount') }}</strong>
										</span>
									@endif
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Maximum Amount <span class="important">*</span></label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">{{ $org_currency }}</span>
										</div>
										<input type="text" class="form-control{{ $errors->has('max_amount') ? ' is-invalid' : '' }}" placeholder="Maximum Amount" name="max_amount" id="max" oninput="restrictMaxAmount()" required>
									</div>
									@if($errors->has('max_amount'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('max_amount') }}</strong>
										</span>
									@endif
								</div>
							</div>
						</div>
						<div class="row">
							<div class="{{ $isFirstSource ? 'col-md-6' : 'col-md-12' }}">
								<div class="form-group">
									<label for="">Interest Rate <span class="important">*</span></label>
									<div class="input-group">
										<input type="text" class="form-control{{ $errors->has('interest') ? ' is-invalid' : '' }}" id="rate" placeholder="Interest Rate" name="interest" value="{{ old('interest') }}" oninput="restrictInterestRate()" required>
										<div class="input-group-append">
											<span class="input-group-text">%</span>
										</div>
									</div>
									@if($errors->has('interest'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('interest') }}</strong>
										</span>
									@endif
								</div>
							</div>

							@if($isFirstSource)
							<div class="col-md-6">
								<div class="form-group">
									<label for="max_tenure">Maximum Loan Tenure (months) *</label>
									<input type="number" name="max_tenure" id="max_tenure" value="12" placeholder="Maximum length of loans" class="form-control">
								</div>
							</div>
							@endif
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="description">Description <span class="important">*</span></label>
									<textarea name="description" id="" cols="30" rows="5" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="A brief description of this loan" required></textarea>
									@if($errors->has('description'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('description') }}</strong>
										</span>
									@endif
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<input type="hidden" name="currency" value="{{ $org_currency }}">
									<button type="submit" class="btn btn-primary btn-block">Create Package</button>
								</div>
							</div>
						</div>
					</form>
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
			/*
			var maxAmount = document.getElementById('max').value;
			if (maxAmount < 1) {
				document.getElementById('max').value = 1;
			}
			else if (maxAmount > wallet) {
				document.getElementById('max').value = wallet;
			}
			*/
		}

		function restrictMinAmount() {
			/*
			var minAmount = document.getElementById('min').value;
			if (minAmount < 1) {
				document.getElementById('min').value = 1;
			}
			else if (minAmount > wallet) {
				document.getElementById('min').value = wallet;
			}
			*/
		}
	</script>
@endsection
