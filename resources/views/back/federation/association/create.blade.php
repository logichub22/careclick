@extends('back/federation/layouts/master')

@section('title')
	Create a New Association
@endsection

@section('one-step')
    / Create New Association
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-4 col-md-3">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('associations.index') }}" class="btn btn-primary btn-block">Back to Associations</a>  
                </div>
                <div class="card-body">
                    <ul class="nav nav-4">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa fa-globe"></i> {{ $federation->name }} 
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa fa-home"></i> {{ $country = implode(\Illuminate\Support\Facades\DB::table('countries')->where('id', $federation->country)->pluck('name')->toArray()) }} 
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <i class="fa fa-users"></i>Members 0
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <i class="fas fa-warehouse"></i>Associations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <i class="fa fa-home"></i>Groups
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-8 col-md-9">
			<div class="card">
                <div class="card-header">
                    <h5 class="mb-1 text-uppercase">Add a new association</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('associations.store') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="org_id" value="{{ $federation->org_id }}">
                    <input type="hidden" name="is_financial" value="1">
                    <input type="hidden" name="country" value="{{ $federation->country }}">
                    <h5 class="mb-1">Association details</h5>
    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Organization Name <span class="important">*</span></label>
                                <input type="text" class="signup-input form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" placeholder="Organization Name">
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="country">Country <span class="important">*</span></label>
                            <select class="signup-input{{ $errors->has('country') ? ' is-invalid' : '' }} form-control" id="countryName" disabled>
                                <option value="" disabled="" selected="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ $country->id === $federation->country ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('country'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('country') }}</strong>
                                </span>
                            @endif
                        </div>
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
                    </div>
                    <div class="row">
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
                                <label for="domain">Organization Website Link</label>
                                <input type="text" class="signup-input form-control{{ $errors->has('domain') ? ' is-invalid' : '' }}" name="domain" placeholder="https://www" value="{{ old('domain') }}">
                                @if ($errors->has('domain'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('domain') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="org_email">Organization Email</label>
                                <input type="text" class="signup-input form-control{{ $errors->has('org_email') ? ' is-invalid' : '' }}" name="org_email" value="{{ old('org_email') }}" placeholder="Organization Email">
                                @if ($errors->has('org_email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('org_email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Permit File</label>
                            <div class="form-group{{ $errors->has('permit') ? ' has-error' : '' }}">
                                <input type="file" name="permit" placeholder="Choose Avatar" class="form-control" />
                                @if ($errors->has('permit'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('permit') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Tax Certififcate</label>
                            <div class="form-group{{ $errors->has('tax') ? ' has-error' : '' }}">
                                <input type="file" name="tax" placeholder="Choose Avatar" class="form-control" />
                                @if ($errors->has('tax'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tax') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <hr />
                    <h5 class="mb-1">Contact person / Association Administrator</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name">First Name <span class="important">*</span></label>
                                <input type="text" class="signup-input form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name') }}" placeholder="First Name">
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
                                <input type="text" class="signup-input form-control{{ $errors->has('other_names') ? ' is-invalid' : '' }}" name="other_names" value="{{ old('other_names') }}" placeholder="Last Name">
                                @if ($errors->has('other_names'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('other_names') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email Address <span class="important">*</span></label>
                                <input type="email" class="signup-input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="johndoe@example.com" value="{{ old('email') }}">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-block">Create Association</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
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
              $('#nav-account-tab').trigger('click');
            });
            // Go to upload
            $('.upload').click(function(e){
              e.preventDefault();
              $('#nav-docs-tab').trigger('click');
            });
            // Go to upload
            $('.uploaddocs').click(function(e){
              e.preventDefault();
              $('#nav-docs-tab').trigger('click');
            });
            // Go to verify
            $('.verify').click(function(e){
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
        });
    </script>
@endpush