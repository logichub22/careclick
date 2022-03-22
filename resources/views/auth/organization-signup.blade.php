@extends('auth/layouts/auth')

@section('title')
    Organization Sign Up
@endsection

@section('content')
	<img src="{{ asset('img/front/auth/rectangle.svg') }}" alt="Left Image" class="left-img" style="opacity: 0.1">
    <img src="{{ asset('img/front/auth/dot-brown.svg') }}" alt="Right Image" class="right-img">

    <div class="org-background">
    	<div class="auth-container">
    		@include('auth/partials/_navbar')

    		<div class="row push-down">
    			<div class="col-md-7">
    				<h3 class="{{ Request::is('organization-signup') ? 'is-white' : '' }} sign-category">Organization Sign Up</h3>
    			</div>
    			<div class="col-md-5">
    				<h3 class="is-pale">Tell us more about your organization</h3>
    			</div>
    		</div>

            {{-- @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif --}}

    		<div class="row">
                <div class="col-md-12 signup-box">
                    <div class="container">
                        <nav>
                            <ul class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="nav-org-tab" data-toggle="tab" href="#nav-org" role="tab" aria-controls="nav-org" aria-selected="true"><span class="number">01</span> Organization Detail</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="nav-account-tab" data-toggle="tab" href="#nav-account" role="tab" aria-controls="nav-account" aria-selected="false"><span class="number">02</span> Account Info</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="nav-docs-tab" data-toggle="tab" href="#nav-docs" role="tab" aria-controls="nav-docs" aria-selected="false"><span class="number">03</span> Upload Documents</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="nav-verification-tab" data-toggle="tab" href="#nav-verification" role="tab" aria-controls="nav-verifcation" aria-selected="false"><span class="number">04</span> Verify Information</a>
                                </li>
                            </ul>
                        </nav>

                        <!-- Input fields -->
                        <form action="{{ route('register.organization') }}" method="POST" class="indiv-form" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="tab-content" id="nav-tabContent">
                               <fieldset class="tab-pane fade show active" id="nav-org" role="tabpanel" aria-labelledby="nav-org-tab">
                                  <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Organization Name <span class="important">*</span></label>
                                            <input type="text" class="signup-input name form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" placeholder="Organization Name">
                                            @if ($errors->has('name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="country">Country <span class="important">*</span></label>
                                            <select name="country" id="countryName" class="signup-input{{ $errors->has('country') ? ' is-invalid' : '' }} form-control">
                                                <option value="" disabled="" selected="">Select Country</option>
                                                {{-- <option value="Kenya">Kenya</option>
                                                <option value="Tanzania">Tanzania</option>
                                                <option value="Uganda">Uganda</option> --}}
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('country'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('country') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                  </div> 
                                  
                                  <div class="row has-margin">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="msisdn">Business Phone Number</label>
                                            <input type="text" class="phone-input signup-input form-control{{ $errors->has('org_msisdn') ? ' is-invalid' : '' }}" name="org_msisdn" placeholder="234 7034724848" value="{{ old('org_msisdn') }}">
                                            @if ($errors->has('org_msisdn'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('org_msisdn') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="address">Physical Address <span class="important">*</span></label>
                                            <input type="text" class="signup-input form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" placeholder="No 8444 Fred Brighton street, Lagos Nigeria" value="{{ old('address') }}">
                                            @if ($errors->has('address'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('address') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="gender">Organization Type <span class="important">*</span></label>
                                            <select name="is_financial" class="form-control{{ $errors->has('is_financial') ? ' is-invalid' : '' }} signup-input">
                                                <option value="" disabled="" selected="">Select organization type</option>
                                                <option value="2">Federation</option>
                                                <option value="1">Association (normal organization)</option>
                                                <option value="0">Service Provider (eg Insurance)</option>
                                            </select>
                                            @if ($errors->has('is_financial'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('is_financial') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                  </div>

                                  <div class="row has-margin">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="domain">Organization Website Link</label>
                                            <input type="text" class="signup-input form-control{{ $errors->has('domain') ? ' is-invalid' : '' }}" name="domain" placeholder="www" value="{{ old('domain') }}">
                                            @if ($errors->has('domain'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('domain') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="org_email">Organization Email</label>
                                            <input type="text" class="signup-input form-control{{ $errors->has('org_email') ? ' is-invalid' : '' }}" name="org_email" value="{{ old('org_email') }}">
                                            @if ($errors->has('org_email'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('org_email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                  </div>

                                  <div class="row has-margin">
                                    <div class="col-md-6 return">
                                        {{-- <a href="{{ url('register') }}"><i class="fas fa fa-arrow-left"></i> Return to Account Type</a> --}}
                                    </div>
                                    <div class="col-md-6 continue">
                                        <a href="" class="account btn btn-primary my-button" style="color: white !important;">Continue to account info</a>
                                    </div>
                                  </div>
                               </fieldset>
                               <fieldset class="tab-pane fade" id="nav-account" role="tabpanel" aria-labelledby="nav-account-tab">
                                  <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="first_name">First Name <span class="important">*</span></label>
                                            <input type="text" class="signup-input form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name') }}">
                                            @if ($errors->has('first_name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('first_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="other_names">Other Names <span class="important">*</span></label>
                                            <input type="text" class="signup-input form-control{{ $errors->has('other_names') ? ' is-invalid' : '' }}" name="other_names" value="{{ old('other_names') }}">
                                            @if ($errors->has('other_names'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('other_names') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                  </div>
                                  <div class="row has-margin">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email Address <span class="important">*</span></label>
                                            <input type="email" class="signup-input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="moses@principle.tech" value="{{ old('email') }}">
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="msisdn">Personal Phone Number <span class="important">*</span></label>
                                            <input type="text" class="phone-input signup-input form-control{{ $errors->has('msisdn') ? ' is-invalid' : '' }}" name="msisdn" placeholder="2347891011204" value="{{ old('msisdn') }}">
                                            @if ($errors->has('msisdn'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('msisdn') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                  </div>
                                  <div class="row has-margin">
                                    <div class="col-md-6">
                                        <div class="form-group has-eye">
                                            <label for="password">Password <span class="important">*</span></label>
                                            <input type="password" id="password-field" class="password-field signup-input form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
                                            {{-- <span toggle=".password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>  --}}
                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group has-eye">
                                            <label for="password_confirmation">Confirm Password <span class="important">*</span></label>
                                            <input type="password" class="signup-input password-field form-control" name="password_confirmation"> 
                                            {{-- <span toggle=".password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span> --}}
                                        </div>
                                    </div>
                                  </div> 
                                  <div class="row has-margin">
                                    <div class="col-md-6 return">
                                        <a href="" class="detail"><i class="fas fa fa-arrow-left"></i> Return to Organization Detail</a>
                                    </div>
                                    <div class="col-md-6 continue">
                                        <a href="" class="btn btn-primary my-button upload">Upload Document</a>
                                    </div>
                                  </div>
                               </fieldset>
                               <fieldset class="tab-pane fade" id="nav-docs" role="tabpanel" aria-labelledby="nav-docs-tab">
                                   <div class="row">
                                       <div class="col-md-8 offset-md-2">
                                          <div class="row">
                                            <div class="col-md-6">
                                                <div class="col-auth-card text-center">
                                                    <img src="{{ asset('img/front/auth/upload.svg') }}" alt="">
                                                    <h3 class="upload-text">Upload a Permit File</h3>
                                                    <p class="drag" style="padding-left: 5px; padding-right: 5px;">
                                                      <input type="file" class="form-control{{ $errors->has('permit') ? ' is-invalid' : '' }} signup-input" name="permit" value="{{ old('permit') }}">
                                                      @if ($errors->has('permit'))
                                                          <span class="invalid-feedback" role="alert">
                                                              <strong>{{ $errors->first('permit') }}</strong>
                                                          </span>
                                                      @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col-auth-card text-center">
                                                    <img src="{{ asset('img/front/auth/upload.svg') }}" alt="">
                                                    <h3 class="upload-text">Upload a Tax Certificate</h3>
                                                    <p class="drag" style="padding-left: 5px; padding-right: 5px;">
                                                      {{-- Drag a file to attach or <a href="">browse</a> --}}
                                                      <input type="file" class="form-control{{ $errors->has('tax') ? ' is-invalid' : '' }} signup-input" name="tax" value="{{ old('tax') }}">
                                                      @if ($errors->has('tax'))
                                                          <span class="invalid-feedback" role="alert">
                                                              <strong>{{ $errors->first('tax') }}</strong>
                                                          </span>
                                                      @endif
                                                    </p>
                                                </div>
                                            </div>
                                          </div>
                                       </div>
                                   </div>
                                   <div class="row has-margin">
                                    <div class="col-md-6 return">
                                        <a href="" class="account"><i class="fas fa fa-arrow-left"></i> Return to Account Info</a>
                                    </div>
                                    <div class="col-md-6 continue">
                                        <a href="" class="btn btn-primary my-button verify">Verify Details</a>
                                    </div>
                                  </div>
                               </fieldset>
                               <fieldset class="tab-pane fade" id="nav-verification" role="tabpanel" aria-labelledby="nav-verification-tab">
                                <div class="row has-margin">
                                    <div class="col-md-6">
                                        <h3 class="verification-header">Organization Detail</h3>
                                         <div class="row">
                                            <div class="col-xs-6">
                                                <span class="detail-header">Org Name</span>
                                                <span class="detail-orgname">fddf</span>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="col-md-6">
                                        <h3 class="verification-header">Account Information</h3>
                                    </div>
                                </div> 
                                <p class="org-agree">
                                    By clicking <a>complete organization sign up</a>, you agree to the <a href="{{ url('terms') }}">terms and conditions</a>
                                </p>
                                <div class="row has-margin">
                                  <div class="col-md-6 return">
                                      <a href="" class="uploaddocs"><i class="fas fa fa-arrow-left"></i> Return to Upload Documents</a>
                                  </div>
                                  <div class="col-md-6 continue">
                                    <button type="submit" class="btn btn-primary my-button">Complete Organization Sign Up</button>
                                  </div>
                                </div>
                             </fieldset>
                            </div>
                        </form>
                        <!--// End Input Fields //-->
                    </div>
                </div>
            </div>
    	</div>

      <div class="has-footer">
            @include('auth/partials/_footer')
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script src="{{ asset('js/front/dropzone.js') }}"></script> --}}
    <script>
        $(document).ready(function () {
            // Go to organization detail
            $('.detail').click(function(e){
              e.preventDefault();
              $('#nav-org-tab').trigger('click');
            });
            // Go to account info
            $('.account').click(function(e){
              e.preventDefault();
              console.log(12334)

              $('#nav-account-tab').trigger('click');
            });
            // Go to upload
            $('.upload').click(function(e){
              e.preventDefault();
              console.log(12334)

              $('#nav-docs-tab').trigger('click');
            });
            // Go to upload
            $('.uploaddocs').click(function(e){
              e.preventDefault();
              $('#nav-docs-tab').trigger('click');
            });
            // Go to verify
            $('.verify').click(function(e){
            //   var orgName = $('.name').val();
              console.log(12334)
              e.preventDefault();
              //append content here
              $('#nav-verification-tab').trigger('click');
            });

            $(".toggle-password").click(function() {
                $(this).toggleClass("fa-eye fa-eye-slash");
                var input = $($(this).attr("toggle"));
                if (input.attr("type") == "password") {
                input.attr("type", "text");
                } else {
                input.attr("type", "password");
                }
            });

            // Append Country Code
             $('#countryName').change(function (e) {
                 e.preventDefault();
                 const name = $('#countryName').val();
                 if(name == 131) {
                     $('.phone-input').val(234);
                 } else if(name == 93) {
                     $('.phone-input').val(254);
                 } else if(name == 101) {
                     $('.phone-input').val(231);
                 } else if(name == 186) {
                     $('.phone-input').val(256);
                 } else if(name == 177) {
                     $('.phone-input').val(255);
                 } else if(name == 25) {
                     $('.phone-input').val(267);
                 } else if(name == 159) {
                     $('.phone-input').val(232);
                 } else {
                     $('.phone-input').val('');
                 }
             });
        });
    </script>
@endpush