@extends('auth/layouts/auth')

@section('title')
    Registration Successful
@endsection

@section('content')
	<img src="{{ asset('img/front/auth/dot-brown.svg') }}" alt="Right Image" class="right-img">
	<div class="container">
		@include('auth/partials/_navbar')
		
		<img src="{{ asset('img/front/auth/rectangle-16.svg') }}" alt="" class="rect">	
		<div class="row success-content">
			<div class="col-md-4">
				<img src="{{ asset('img/front/auth/screen.png') }}" alt="Screen" class="screen">
			</div>
			<div class="col-md-8">
				<h3 class="congrats">Congratulations!</h3>
				<p class="sub-text">Your account has been created.</p>
				<p class="success-main-text text-muted">
					We've sent you an email with the verification link. <br>
					Kindly confirm your account and get started.
				</p>
				<p class="button-holder">
					<a href="{{ route('login') }}" class="dash">Proceed to log in</a>
				</p>
			</div>
		</div>
	</div>
	<div class="has-footer">
		@include('auth/partials/_footer')
	</div>
@endsection