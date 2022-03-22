@extends('auth/layouts/auth')

@section('title')
    Login
@endsection

@push('styles')
	<link rel="stylesheet" href="{{ asset('plugins/icheck/skins/square/blue.css') }}">
@endpush

@section('content')
	<img src="{{ asset('img/front/auth/rectangle.svg') }}" alt="Left Image" class="left-img">
    <img src="{{ asset('img/front/auth/dot-brown.svg') }}" alt="Right Image" class="right-img">

    <div class="background">
    	<div class="auth-container">
    		<div class="auth-main-content">
    			@include('auth/partials/_navbar')

    			@include('alert/messages')

    			<div class="signup-box mt-4 pt-2">
    				<div class="container">
    					<form action="{{ url('login') }}" method="POST" class="indiv-form">
    						{{ csrf_field() }}
    						<div class="row">
    							<div class="col-md-8 offset-md-2">
    								<div class="form-group">
		    							<label for="email">Email Address / Phone Number</label>
		                                <input type="text" class="signup-input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Your email or phone number" autofocus>
		                                @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
		    						</div>
    							</div>
    						</div>
    						<div class="row">
    							<div class="col-md-8 offset-md-2">
    								<div class="form-group">
		    							<label for="password">Password</label>
		                                <input type="password" class="signup-input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="password" placeholder="Password">
		                                @if ($errors->has('password'))
						                    <span class="invalid-feedback" role="alert">
						                        <strong>{{ $errors->first('password') }}</strong>
						                    </span>
						                @endif
		    						</div>
    							</div>
    						</div>
    						<div class="row">
    							<div class="col-md-8 offset-md-2">
    								<div class="form-group">
		    							<button type="submit" class="btn btn-primary btn-block my-button">Log In</button>
		    						</div>
    							</div>
    						</div>
    						<div class="row">
    							<div class="col-md-8 offset-md-2">
    								<p class="password-container">
						                <a href="{{ route('password.request') }}" class="forgot is-blue">
						                    Forgot password?
						                </a>
						            </p>
    							</div>
    						</div>
    						<div class="row">
    							<div class="col-md-8 offset-md-2">
    								<div class="password-container i-checks">
    									<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>&nbsp; Remember Me
    								</div>
    							</div>
    						</div> <br>
    						<div class="row">
    							<div class="col-md-8 offset-md-2">
    								<p class="password-container">
						                Do not have an account? <a href="{{ url('register') }}">Register Here</a>
						            </p>
    							</div>
    						</div>
    					</form>
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
    <!-- iCheck -->
    <script src="{{ asset('plugins/icheck/icheck.js') }}"></script>
    <script>
        $(document).ready(function () {
            // IChecks for fancy checkboxes and radio buttons
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
            });
        });
    </script>
@endpush