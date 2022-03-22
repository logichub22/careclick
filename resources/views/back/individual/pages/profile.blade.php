@extends('back/individual/layouts/master')

@section('title')
	@lang('individual.profile')
@endsection

@section('one-step')
    / Profile
@endsection



@section('content')
	@if ($errors->any())
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif
	<div class="section-body">
    <div class="row mt-sm-4">
      <div class="col-12 col-md-12 col-lg-4">
        <div class="card author-box">
          <div class="card-body">
            <div class="author-box-center">
              <img alt="image" src="{{ asset('img/avatars/' . Auth::user()->avatar) }}" class="rounded-circle author-box-picture">
              <div class="clearfix"></div>
              <div class="author-box-name">
                <a href="#">{{ $user->name . ' ' . $user->other_names }}</a>
              </div>
              <div class="author-box-job">{{ $user->email }}</div>
            </div>
            <div class="text-center">
              <div class="author-box-description">
                <a class="btn btn-primary" href="#changeAvatar" data-toggle="modal">
                      <i class="fas fa-pencil-alt"></i>
                      @lang('individual.changeavatar')
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-12 col-lg-8">
        <div class="card">
          <div class="padding-20">
            <ul class="nav nav-tabs" id="myTab2" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#about" role="tab"
                  aria-selected="true">About</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="profile-tab2" data-toggle="tab" href="#settings" role="tab"
                  aria-selected="false">@lang('individual.editprofile')</a>
              </li>
            </ul>
            <div class="tab-content tab-bordered" id="myTab3Content">
              <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="home-tab2">
                <div class="row">
                  <div class="col-md-3 col-6 b-r">
                    <strong>@lang('individual.fullnames')</strong>
                    <br>
                    <p class="text-muted">{{ $user->name . ' ' . $user->other_names }}</p>
                  </div>
                  <div class="col-md-3 col-6 b-r">
                    <strong>@lang('individual.maritalstatus')</strong>
                    <br>
                    <p class="text-muted">{{ $marital }}</p>
                  </div>
                  <div class="col-md-3 col-6 b-r">
                    <strong>@lang('individual.emailaddress')</strong>
                    <br>
                    <p class="text-muted">{{ $user->email }}</p>
                  </div>
                  <div class="col-md-3 col-6">
                    <strong>@lang('individual.country')</strong>
                    <br>
                    <p class="text-muted">{{ $country }}</p>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-3 col-6 b-r">
                    <strong>Date Joined</strong>
                    <br>
                    <p class="text-muted">{{ $user->created_at }}</p>
                  </div>
                  <div class="col-md-3 col-6 b-r">
                    <strong>@lang('individual.accountnumber')</strong>
                    <br>
                    <p class="text-muted">
                    	@if(is_null($user->account_no))
								@lang('individual.notset')
							@else
								{{ $user->account_no }}
						@endif
                    </p>
                  </div>
                  <div class="col-md-3 col-6 b-r">
                    <strong>@lang('individual.occupation')</strong>
                    <br>
                    <p class="text-muted">{{ $detail->occupation }}</p>
                  </div>
                  <div class="col-md-3 col-6">
                    <strong>@lang('individual.address')</strong>
                    <br>
                    <p class="text-muted">{{ $detail->address }}</p>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-3 col-6 b-r">
                    <strong>@lang('individual.gender')</strong>
                    <br>
                    <p class="text-muted">{{ $gender }}</p>
                  </div>
                  <div class="col-md-3 col-6 b-r">
                    <strong>@lang('individual.city')</strong>
                    <br>
                    <p class="text-muted">{{ $detail->city }}</p>
                  </div>
                  <div class="col-md-3 col-6 b-r">
                    <strong>@lang('individual.incomeclass')</strong>
                    <br>
                    <p class="text-muted">{{ $income }}</p>
                  </div>
                  <div class="col-md-3 col-6">
                    <strong>@lang('individual.residence')</strong>
                    <br>
                    <p class="text-muted">{{ $residence }}</p>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-3 col-6 b-r">
                    <strong>@lang('individual.yourgroups')</strong>
                    <br>
                    <p class="text-muted">{{ count($groups) }}</p>
                  </div>
                  <div class="col-md-3 col-6 b-r">
                    <strong>@lang('individual.groupsjoined')</strong>
                    <br>
                    <p class="text-muted">{{ count($membergroups) }}</p>
                  </div>
                  <div class="col-md-3 col-6 b-r">
                    <strong>@lang('individual.organization')</strong>
                    <br>
                    <p class="text-muted">
                    	@if(is_null($organization))
								@lang('individual.none')
							@else
								{{ $organization->name }}
						@endif
                    </p>
                  </div>
                  <div class="col-md-3 col-6">
                    <strong>@lang('individual.accountbalance')</strong>
                    <br>
                    <p class="text-muted">{{ number_format($wallet->balance) }}</p>
                  </div>
                </div>
                @if( $credit_score != null )
                <div class="row">
                  <div class="col-md-3 col-6">
                    <strong>Credit Score</strong>
                    <br>
                    <p class="text-muted">{{ $credit_score->borrower_credit_score }}</p>
                  </div>
                </div>
                @elseif($credit_score = null)
                  <div class="row">
                    <div class="col-md-3 col-6">
                      <strong>Credit Score</strong>
                      <br>
                      <p class="text-muted">Not Available</p>
                    </div>
                  </div>
                @endif

              <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="profile-tab2">
                  <div class="card-header">
                    <h4>@lang('individual.updateyourdetails')</h4>
                    <form action="{{ route('updateprofile') }}" method="POST" enctype="multipart/form-data">
          					{{ csrf_field() }}
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="form-group col-md-6 col-12">
                        <label for="name">@lang('individual.firstname') <span class="important">*</span></label>
                            <input type="text" class="signup-input form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $user->name }}">
                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                      </div>
                      <div class="form-group{{ $errors->has('other_names') ? ' has-error' : '' }} col-md-6 col-12">
							<label>@lang('individual.othernames') <span class="important">*</span></label>
	                        <input type="text" name="other_names" placeholder="Other Names" class="form-control" value="{{ $user->other_names }}" />
	                         @if ($errors->has('user_name'))
	                            <span class="help-block">
	                                <strong>{{ $errors->first('user_name') }}</strong>
	                            </span>
	                        @endif
                    </div>
                    </div>
                    <div class="row">
                       <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} col-md-6 col-12">
							<label>@lang('individual.emailaddress')<span class="important">*</span></label>
			                <input type="email" name="email" placeholder="Email Address" class="form-control" value="{{ $user->email }}" />
			                @if ($errors->has('email'))
			                    <span class="help-block">
			                        <strong>{{ $errors->first('email') }}</strong>
			                    </span>
			                @endif
			            </div>
                      <div class="form-group{{ $errors->has('msisdn') ? ' has-error' : '' }} col-md-6 col-12">
							<label>@lang('individual.phonenumber') <span class="important">*</span></label>
			                <input type="text" name="msisdn" placeholder="Personal Phone Number" class="form-control" value="{{ $user->msisdn }}" />
			                @if ($errors->has('msisdn'))
			                    <span class="help-block">
			                        <strong>{{ $errors->first('msisdn') }}</strong>
			                    </span>
			                @endif
			            </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-6 col-12">
                        <label for="state">@lang('individual.accountnumber') <span class="important">*</span></label>
                            <input type="text" class="signup-input form-control{{ $errors->has('account_no') ? ' is-invalid' : '' }}" name="account_no" value="{{ $user->account_no }}" placeholder="Bank Account Number">
                            @if ($errors->has('account_no'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('account_no') }}</strong>
                                </span>
                            @endif
                      </div>
                      <div class="form-group col-md-6 col-12">
                        <label for="occupation">@lang('individual.occupation') <span class="important">*</span></label>
                            <input type="text" class="signup-input form-control{{ $errors->has('occupation') ? ' is-invalid' : '' }}" name="occupation" value="{{ $detail->occupation }}" placeholder="Occupation">
                            @if ($errors->has('occupation'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('occupation') }}</strong>
                                </span>
                            @endif
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-12 col-12">
                        <label for="income">@lang('individual.yearlyincome') <span class="important">*</span></label>
                                <select name="income" class="signup-input{{ $errors->has('income') ? ' is-invalid' : '' }} form-control">
									<option value="" disabled="" selected="">@lang('individual.selectincomeclass')</option>
									@foreach($incomes as $income)
										<option value="{{ $income->id }}">{{ $income->name }}</option>
									@endforeach
								</select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-6 col-12">
                        <label for="doc_type">@lang('individual.documentofidentification') <span class="important">*</span></label>
							<select name="doc_type" class="form-control{{ $errors->has('doc_type') ? ' is-invalid' : '' }} signup-input" value="{{ old('doc_type') }}">
								<option value="" disabled selected>@lang('individual.selecttypeofdocument')</option>
                                @foreach($documents as $document)
                                    <option value="{{ $document->id }}">{{ $document->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('doc_type'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('doc_type') }}</strong>
                                </span>
                            @endif
                      </div>
                      <div class="form-group col-md-6 col-12">
                        <label for="doc_no">@lang('individual.documentnumber') <span class="important">*</span></label>
                            <input type="text" class="signup-input{{ $errors->has('doc_no') ? ' is-invalid' : '' }} form-control" name="doc_no" value="{{ $detail->doc_no }}">
                            @if ($errors->has('doc_no'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('doc_no') }}</strong>
                                </span>
                            @endif
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-6 col-12">
                        <label for="identification_document">
							@if(is_null($user->identification_document))
								@lang('individual.uploaddocument')
							@else 
								@lang('individual.changedocumentfile')
							@endif <span class="important">*</span></label>
						<input type="file" name="identification_document" class="form-control signup-input">
						@if ($errors->has('identification_document'))
							<span class="invalid-feedback" role="alert">
								<strong>{{ $errors->first('identification_document') }}</strong>
							</span>
						@endif
                      </div>
                      <div class="form-group col-md-6 col-12">
                        <label for="city">@lang('individual.city') <span class="important">*</span></label>
                            <input type="text" class="signup-input form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ $detail->city }}" placeholder="City">
                            @if ($errors->has('city'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('city') }}</strong>
                                </span>
                            @endif
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-6 col-12">
                        <label for="state">@lang('individual.stateofresidence') <span class="important">*</span></label>
	                            <input type="text" class="signup-input form-control{{ $errors->has('state') ? ' is-invalid' : '' }}" name="state" value="{{ $detail->state }}" placeholder="State of Residence">
	                            @if ($errors->has('state'))
	                                <span class="invalid-feedback" role="alert">
	                                    <strong>{{ $errors->first('state') }}</strong>
	                                </span>
	                            @endif
                      </div>
                      <div class="form-group col-md-6 col-12">
                        <label for="postal_code">@lang('individual.postalcode') <span class="important">*</span></label>
							<input type="text" class="signup-input form-control{{ $errors->has('postal_code') ? ' is-invalid' : '' }}" name="postal_code" placeholder="e.g. 28301" value="{{ $detail->postal_code }}" id="postal_code">
							@if ($errors->has('postal_code'))
								<span class="invalid-feedback" role="alert">
									<strong>{{ $errors->first('postal_code') }}</strong>
								</span>
							@endif
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-12 col-12">
                        <label for="address">@lang('individual.permanentaddress') <span class="important">*</span></label>
								<input type="text" class="signup-input form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ $detail->address }}" id="address">
								@if ($errors->has('address'))
									<span class="invalid-feedback" role="alert">
										<strong>{{ $errors->first('address') }}</strong>
									</span>
								@endif
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-6 col-12">
                        <label>@lang('individual.newpassword')</label>
				                <input type="password" name="password" placeholder="Password" class="form-control" />
				                @if ($errors->has('password'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('password') }}</strong>
				                    </span>
				                @endif
                      </div>
                      <div class="form-group col-md-6 col-12">
                        <label>@lang('individual.confirmnewpassword')</label>
				                <input type="password" name="password_confirmation" placeholder="Confirm Password" class="form-control" />
                      </div>
                    </div>
                  <div class="card-footer text-right">
                  	<button type="submit" class="btn btn-primary btn-block">@lang('individual.updateprofile')</button>
                  </div>
                  </div>
                  </form>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('spec-styles')
    <!-- Change Avatar Modal -->
  <div class="modal fade" id="changeAvatar" tabindex="-1" role="dialog" aria-labelledby="changeAvatar" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('pic.change') }}" method="POST" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12 text-center">
                <h5>Current Avatar</h5>
                <img src="{{ asset('img/avatars/' . Auth::user()->avatar) }}" alt="Current Avatar">
              </div>
            </div>
            <br>
            <div class="form-group">
              <label for="recipient-name" class="form-control-label">Choose Pic:</label>
              <input type="file" class="form-control{{ $errors->has('avatar') ? ' is-invalid' : ''}}" name="avatar">
              @if($errors->has('avatar'))
                <span class="invalid-feedback" role="alert">
                   <strong>{{ $errors->first('avatar') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update Avatar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--// End Change AVatar Modal //-->
@endsection