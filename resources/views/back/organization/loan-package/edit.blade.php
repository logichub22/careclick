@extends('back/organization/layouts/master')

@section('title')
	Edit Loan Package
@endsection

@section('one-step')
    / Edit Loan Package
@endsection

@section('content')
	<div class="row">
		<div class="col-md-4">
			<div class="card">
				<div class="card-header">
					<h5>@lang('individual.anoteonpackages') </h5>
				</div>
				<div class="accordion card-body" id="accordionExample">
				  @if(!$isFirstSource)
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
							Status: {{ $package->status ? "Active" : "Inactive" }}
						</p>
						<p>
							Minimum Credit Score: {{ $package->min_credit_score }}
						</p>
						<p>
							Interest Rate: {{ $package->interest_rate }}% per annum
						</p>
						<p>
							Insured? {{ $package->insured ? "Yes" : "No" }}
						</p>
					</div>
					@endif
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

				<form action="{{ route('org-packages.update', $package->id) }}" method="POST">
					<div class="card-body">
						@method('PATCH')
						@csrf

						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="name">@lang('individual.packagename') <span class="important">*</span></label>
								<input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ $package->name }}" name="name">
								@if($errors->has('name'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('name') }}</strong>
									</span>
								@endif
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label for="">Interest Rate <span class="important">*</span></label>
									<div class="input-group">
										<input type="text" class="form-control{{ $errors->has('interest_rate') ? ' is-invalid' : '' }}" id="rate" placeholder="Interest Rate" name="interest_rate" value="{{ $package->interest_rate }}" required>
										<div class="input-group-append">
											<span class="input-group-text">%</span>
										</div>
									</div>
									@if($errors->has('interest_rate'))
										<span class="invalid-feedback" role="alert">
											<strong>{{ $errors->first('interest_rate') }}</strong>
										</span>
									@endif
								</div>
							</div>

							<div class="form-group col-md-6">
								<label for="">@lang('individual.minimumamount')<span class="important">*</span></label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">{{ $package->currency }}</span>
									</div>
									<input type="text" id="min" class="form-control{{ $errors->has('min_amount') ? ' is-invalid' : '' }}" value="{{ $package->min_amount }}" name="min_amount">
								</div>
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
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">{{ $package->currency }}</span>
									</div>
									<input type="text" id="max" class="form-control{{ $errors->has('max_amount') ? ' is-invalid' : '' }}" name="max_amount" value="{{ $package->max_amount }}">
								</div>
								@if($errors->has('max_amount'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('max_amount') }}</strong>
									</span>
								@endif
								<div class="input-desc">
									<p class="m-0">
										The maximum amount should be less than or equal to your wallet balance.
									</p>
								</div>
							</div>

							@if($isFirstSource)
							<div class="col-md-12">
								<div class="form-group">
									<label for="max_tenure">Maximum Loan Tenure (months) *</label>
									<div class="input-group">
										<input type="number" name="max_tenure" id="max_tenure" value="{{ $riby_details->max_tenure / 30 }}" placeholder="Maximum length of loans" class="form-control">
										<div class="input-group-append">
											<span class="input-group-text">months</span>
										</div>
									</div>
								</div>
							</div>
							@endif
							
							<div class="form-group mb-0 col-md-12">
								<label for="description">@lang('individual.description') <span class="important">*</span></label>
								<textarea name="description" id="" cols="30" rows="5" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ $package->description }}">{{ $package->description }}</textarea>
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
				</div>
			</form>
		</div>
	</div>
@endsection