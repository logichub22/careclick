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

                <h3 class="push-down sign-category" style="margin-top: 30px;">Reset Your Password</h3>

                <div class="signup-box py-2 mt-4">
                    <div class="container">
                        <form method="POST" action="{{ route('password.request') }}" aria-label="{{ __('Reset Password') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="row">
                                <div class="col-md-8 offset-md-2">
                                    <div class="form-group">
                                        <label for="email">{{ __('E-Mail Address') }}</label>
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
                                        <label for="password">{{ __('Password') }}</label>
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
                                <div class="col-md-8 offset-md-2">
                                    <div class="form-group">
                                        <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                        <input type="password" class="signup-input form-control" name="password_confirmation">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 offset-md-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block my-button">{{ __('Reset Password') }}</button>
                                    </div>
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