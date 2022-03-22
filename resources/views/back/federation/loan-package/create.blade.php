@extends('back/organization/layouts/master')

@section('title')
	Create Loan Package
@endsection

@section('page-nav')
	<h4>Create Loan Package</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="#">Lending</a></li>
		<li class="breadcrumb-item active">Create Package</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-4">
			<div class="box box-block bg-white">
				<h5 class="mb-1">A Note on Packages</h5>
				<div class="accordion" id="accordionExample">
				  <div class="card">
					    <div class="card-header" id="headingOne">
					      <h5 class="mb-0">
					        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#creditScore" aria-expanded="true" aria-controls="creditScore">
					          Setting the credit score
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
					          Setting the interest rate
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
					          Insuring your loan
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
					          Min and Max Amount
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
			<div class="box box-block bg-white">
				<h5 class="mb-1">New loan Package</h5>
				<form action="{{ route('org-packages.store') }}" method="POST">
					@csrf
					<input type="hidden" name="balance" value="{{ $walletBalance->balance }}">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="">Package Name <span class="important">*</span></label>
								<input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Loan Package Name" name="name" value="{{ old('name') }}">
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
								<select name="repayment_plan" class="form-control{{ $errors->has('repayment_plan') ? ' invalid-feddback' : '' }}">
									<option value="" disabled selected>Repayment Plan</option>
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
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="">Minimum Credit Score <span class="important">*</span></label>
								<input type="text" class="form-control{{ $errors->has('min_score') ? ' is-invalid' : '' }}" placeholder="Minimum Credit Score" name="min_score" value="{{ old('min_score') }}">
								@if($errors->has('min_score'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('min_score') }}</strong>
									</span>
								@endif
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="">Insure Loan</label>
								<select name="insured" id="" class="form-control{{ $errors->has('insured') ? ' is-invalid' : '' }}">
									<option value="" disabled="" selected="">Insure Loan Package?</option>
									<option value="1">Yes</option>
									<option value="0">No</option>
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
								<input type="text" class="form-control{{ $errors->has('min_amount') ? ' is-invalid' : '' }}" placeholder="Minimum Amount" name="min_amount" value="{{ old('min_amount') }}">
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
								<input type="text" class="form-control{{ $errors->has('max_amount') ? ' is-invalid' : '' }}" placeholder="Maximum Amount" name="max_amount" value="{{ $walletBalance->balance }}">
								@if($errors->has('max_amount'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('max_amount') }}</strong>
									</span>
								@endif
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="">Currency</label>
								<select name="currency" id="" class="form-control{{ $errors->has('currency') ? ' is-invalid' : '' }}">
									<option value="" disabled="" selected="">Select Currency</option>
									@foreach($currencies as $currency)
										<option value="{{ $currency->name }}">{{ $currency->name }}</option>
									@endforeach
								</select>
								@if($errors->has('currency'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('currency') }}</strong>
									</span>
								@endif
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="">Interest Rate <span class="important">*</span></label>
								<input type="text" class="form-control{{ $errors->has('interest') ? ' is-invalid' : '' }}" placeholder="Interest Rate" name="interest" value="{{ old('interest') }}">
								@if($errors->has('interest'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('interest') }}</strong>
									</span>
								@endif
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="description">Description <span class="important">*</span></label>
								<textarea name="description" id="" cols="30" rows="5" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="A brief description of this loan"></textarea>
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
								<button type="submit" class="btn btn-primary btn-block">Create Package</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection