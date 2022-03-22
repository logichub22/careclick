@extends('auth/layouts/auth')

@section('title')
    Reset Your Password
@endsection

@section('content')
    <img src="{{ asset('img/front/auth/rectangle.svg') }}" alt="Left Image" class="left-img">
    <img src="{{ asset('img/front/auth/dot-brown.svg') }}" alt="Right Image" class="right-img">

    <div class="background">
        <div class="auth-container">
            <div class="auth-main-content">
                @include('auth/partials/_navbar')

                <h3 class="push-down sign-category" style="margin-top: 30px;">Enter Email</h3>

                @include('alert/messages')

                <div class="signup-box py-2 mt-4">
                    <div class="container">
                        <form method="POST" action="{{ route('password.email') }}" aria-label="{{ __('Reset Password') }}" class="indiv-form">
                            @csrf
                            <div class="row">
                                <div class="col-md-8 offset-md-2">
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" class="signup-input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}">
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
                                        <button type="submit" class="btn btn-primary btn-block my-button">{{ __('Send Password Reset Link') }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 offset-md-2">
                                    <p class="password-container">
                                        <a href="{{ route('login') }}" class="forgot is-blue">
                                            Back to Log in
                                        </a>
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