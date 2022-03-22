@extends('auth/layouts/auth')

@section('title')
    Individual Sign Up
@endsection

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/gijgo@1.9.10/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://cdn.jsdelivr.net/npm/gijgo@1.9.10/css/gijgo.min.css" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    <img src="{{ asset('img/front/auth/rectangle.svg') }}" alt="Left Image" class="left-img">
    <img src="{{ asset('img/front/auth/dot-brown.svg') }}" alt="Right Image" class="right-img">
    <div class="background">
        <div class="auth-container">
            <div class="auth-main-content">
                @include('auth/partials/_navbar')

                <h3 class="push-down sign-category">Individual Sign Up</h3>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="signup-box">
                    <nav>
                        <ul class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="nav-personal-tab" data-toggle="tab" href="#nav-personal" role="tab" aria-controls="nav-personal" aria-selected="true"><span class="number">01</span> Personal Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nav-address-tab" data-toggle="tab" href="#nav-address" role="tab" aria-controls="nav-address" aria-selected="false"><span class="number">02</span> Address Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nav-password-tab" data-toggle="tab" href="#nav-password" role="tab" aria-controls="nav-password" aria-selected="false"><span class="number">03</span> Create Password</a>
                            </li>
                        </ul>
                    </nav>

                    <div class="container">
                        <form action="{{ url('register') }}" method="POST" class="indiv-form" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="tab-content" id="nav-tabContent">
                                   <fieldset class="tab-pane fade show active" id="nav-personal" role="tabpanel" aria-labelledby="nav-personal-tab">
                                      <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">First Name <span class="important">*</span></label>
                                                <input type="text" class="signup-input form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" id="name">
                                                @if ($errors->has('name'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="other_names">Last Name <span class="important">*</span></label>
                                                <input type="text" class="signup-input form-control{{ $errors->has('other_names') ? ' is-invalid' : '' }}" name="other_names" value="{{ old('other_names') }}" id="other_names">
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
                                                <label for="gender">Gender <span class="important">*</span></label>
                                                <select name="gender" class="form-control{{ $errors->has('gender') ? ' is-invalid' : '' }} signup-input" id="gender">
                                                    <option value="" disabled selected>Select Gender</option>
                                                    @foreach($genders as $gender)
                                                        <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('gender'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('gender') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="marital_status">Marital Status <span class="important">*</span></label>
                                                <select name="marital_status" class="form-control{{ $errors->has('marital_status') ? ' is-invalid' : '' }} signup-input" id="marital_status">
                                                        <option value="" disabled selected>Select Marital Status</option>
                                                        @foreach($maritals as $marital)
                                                            <option value="{{ $marital->id }}">{{ $marital->name }}</option>
                                                        @endforeach
                                                </select>
                                                @if ($errors->has('marital_status'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('marital_status') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                      </div> 
                                      <div class="row has-margin">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="doc_type">Document of Identification <span class="important">*</span></label>
                                                <select name="doc_type" class="form-control{{ $errors->has('doc_type') ? ' is-invalid' : '' }} signup-input" value="{{ old('doc_type') }}" id="doc_type">
                                                    <option value="" disabled="" selected="">Document of Identification</option>
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
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="doc_no">Document Number <span class="important">*</span></label>
                                                <input type="text" class="signup-input{{ $errors->has('doc_no') ? ' is-invalid' : '' }} form-control" name="doc_no" value="{{ old('doc_no') }}" id="doc_no">
                                                @if ($errors->has('doc_no'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('doc_no') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                      </div> 
                                      <div class="row has-margin">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="identification_document">Upload Document <span class="important">*</span></label>
                                                <input type="file" name="identification_document" class="form-control signup-input">
                                                @if ($errors->has('identification_document'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('identification_document') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="residence">Resident Type <span class="important">*</span></label>
                                                <select name="residence" class="form-control{{ $errors->has('residence') ? ' is-invalid' : '' }} signup-input" id="residence">
                                                    <option value="" disabled selected>Select Resident Type</option>
                                                    @foreach($residents as $resident)
                                                        <option value="{{ $resident->id }}">{{ $resident->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('residence'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('residence') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                      </div> 
                                      <div class="row has-margin">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="dob">Date of Birth (dd/mm/yyyy) <span class="important">*</span></label>
                                                <input type="date" class="signup-input{{ $errors->has('dob') ? ' is-invalid' : '' }} form-control" name="dob" value="{{ old('dob') }}" id="dob">
                                                @if ($errors->has('dob'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('dob') }}</strong>
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
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="occupation">What's your occupation?</label>
                                                <input type="text" class="signup-input form-control{{ $errors->has('occupation') ? ' is-invalid' : '' }}" name="occupation" value="{{ old('occupation') }}" placeholder="Occupation">
                                                @if ($errors->has('occupation'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('occupation') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="income">How much is your yearly income?</label>
                                                <select name="income" class="signup-input{{ $errors->has('income') ? ' is-invalid' : '' }} form-control">
                                                    <option value="" disabled="" selected="">Select Income Class</option>
                                                    @foreach($incomes as $income)
                                                        <option value="{{ $income->id }}">{{ $income->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('income'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('income') }}</strong>
                                                    </span>
                                                @endif <br>
                                                <span style="color: #d4d9dd;">This information will help us give you a better loan</span>
                                            </div>
                                        </div>
                                      </div>
                                      <div class="row has-margin">
                                        <div class="col-md-6 return">
                                            <a href="{{ url('register') }}"><i class="fas fa fa-arrow-left"></i> Return to Account Type</a>
                                        </div>
                                        <div class="col-md-6 continue">
                                            <a href="" class="address btn btn-primary my-button">Continue to address details</a>
                                        </div>
                                      </div>
                                   </fieldset>
                                   <fieldset class="tab-pane fade" id="nav-address" role="tabpanel" aria-labelledby="nav-address-tab">
                                      <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="city">City <span class="important">*</span></label>
                                                <input type="text" class="signup-input form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ old('city') }}" id="city">
                                                @if ($errors->has('city'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('city') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="state">State of Residence <span class="important">*</span></label>
                                                <input type="text" class="signup-input form-control{{ $errors->has('state') ? ' is-invalid' : '' }}" name="state" value="{{ old('state') }}" id="state">
                                                @if ($errors->has('state'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('state') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                      </div> 
                                      <div class="row has-margin">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="postal_code">Postal Code <span class="important">*</span></label>
                                                <input type="text" class="signup-input form-control{{ $errors->has('postal_code') ? ' is-invalid' : '' }}" name="postal_code" placeholder="e.g. 28301" value="{{ old('postal_code') }}" id="postal_code">
                                                @if ($errors->has('postal_code'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('postal_code') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="msisdn">Phone Number <span class="important">*</span></label>
                                                <input type="text" class="signup-input form-control{{ $errors->has('msisdn') ? ' is-invalid' : '' }}" name="msisdn" value="{{ old('msisdn') }}" id="phoneInput">
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
                                            <div class="form-group">
                                                <label for="email">Email Address <span class="important">*</span></label>
                                                <input type="email" class="signup-input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" id="email">
                                                @if ($errors->has('email'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address">Permanent Address <span class="important">*</span></label>
                                                <input type="text" class="signup-input form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ old('address') }}" id="address">
                                                @if ($errors->has('address'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('address') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                      </div> 
                                      <div class="row has-margin">
                                        <div class="col-md-6 return">
                                            <a href="" class="personal"><i class="fas fa fa-arrow-left"></i> Return to Personal Info</a>
                                        </div>
                                        <div class="col-md-6 continue">
                                            <a href="" class="btn btn-primary my-button password">Create Password</a>
                                        </div>
                                      </div>
                                   </fieldset>
                                   <fieldset class="tab-pane fade" id="nav-password" role="tabpanel" aria-labelledby="nav-password-tab">
                                       <div class="row">
                                           <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="password">Password <span class="important">*</span></label>
                                                    <input type="password" class="signup-input form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"> 
                                                    @if ($errors->has('password'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('password') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                           </div>
                                       </div>
                                       <div class="row">
                                           <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="password_confirmation">Confirm Password <span class="important">*</span></label>
                                                    <input type="password" class="signup-input form-control" name="password_confirmation"> <br>
                                                    <span class="terms">By clicking <span class="blue">complete individual sign up</span> you agree to the <a href="{{ url('terms') }}" class="blue">terms and conditions</a></span>
                                                </div>
                                           </div>
                                       </div>
                                       <div class="row has-margin">
                                        <div class="col-md-6 return">
                                            <a href="" class="address"><i class="fas fa fa-arrow-left"></i> Return to Address Info</a>
                                        </div>
                                        <div class="col-md-6 continue">
                                            <button type="submit" class="btn btn-primary my-button" id="sendData">Complete Individual Sign Up</button>
                                        </div>
                                      </div>
                                   </fieldset>
                                </div>
                            </form>
                            <!--// End Input Fields //-->
                    </div>
                </div>
                
            </div>
            <br><br><br><br>
        </div>

        <div class="has-footer">
            @include('auth/partials/_footer')
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Go to personal 
            $('.personal').click(function(e){
              e.preventDefault();
              $('#nav-personal-tab').trigger('click');
            });
            // Go to address
            $('.address').click(function(e){
              e.preventDefault();
              $('#nav-address-tab').trigger('click');
            });
            // Go to address
            $('.password').click(function(e){
              e.preventDefault();
              $('#nav-password-tab').trigger('click');
            });
            $('.has-eye').append('<span class="far fa-eye"></span>'); 

            // Append Country Code
            // $('#countryName').change(function (e) {
            //     e.preventDefault();
            //     const name = $('#countryName').val();
            //     if(name == 131) {
            //         $('#phoneInput').val(234);
            //     } else if(name == 93) {
            //         $('#phoneInput').val(254);
            //     } else if(name == 101) {
            //         $('#phoneInput').val(231);
            //     } else if(name == 186) {
            //         $('#phoneInput').val(256);
            //     } else if(name == 177) {
            //         $('#phoneInput').val(255);
            //     } else if(name == 25) {
            //         $('#phoneInput').val(267);
            //     } else if(name == 159) {
            //         $('#phoneInput').val(232);
            //     } else {
            //         $('#phoneInput').val('');
            //     }
            // });
        });
    </script>
@endpush