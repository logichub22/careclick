@extends('front/layouts/master')

@section('title')
	Invest With Us
@endsection

@section('content')
	<div class="page-banner team-banner">
		<div class="container">
			<div class="banner-header">
				Invest<br>
				With Us
			</div>
		</div>
	</div>

	<div class="mt-77">
		<div class="container">
			<div class="row" style="padding-top: 15px; padding-bottom: 15px;">
				<div class="col-md-4 col-xs-12 col-sm-12">
					<div>
						<img src="{{ asset('img/front/investing/coins.jpg') }}" alt="Access Loans" class="team-image" style="width: 100%;">
					</div>
				</div>
				<div class="col-md-8">
					<span class="team-desc text-justify">
						<p class="funding-header">Diversify your portfolio</p>
						<p>Earn weekly, bi-weekly and monthly returns from the loans you invest in. By setting terms of your own, you control how much you earn from the loans you invest in.</p> 
						<p style="padding-top: 35px;">
							<a href="{{ route('register') }}" class="type-user emphasized">Get Started</a>
						</p>
					</span>
				</div>
			</div>
			<hr style="background-color: blue; opacity: 0.2;">
			<div class="row" style="padding-top: 15px; padding-bottom: 15px;">
				<div class="col-md-4 col-xs-12 col-sm-12">
					<div>
						<img src="{{ asset('img/front/investing/grow.jpg') }}" alt="Access Loans" class="team-image" style="width: 100%;">
					</div>
				</div>
				<div class="col-md-8">
					<span class="team-desc text-justify">
						<p class="funding-header">Financial Security</p>
						<p>By partnering with trusted insurance providers, {{ config('app.name') }} offers investors a flexible opportunity of insuring their loan packages at fair prices.</p>
						<p style="padding-top: 10px;">
							<a href="{{ route('register') }}" class="type-user emphasized">Get Started</a>
						</p> 
					</span>
				</div>
			</div>
			<hr style="background-color: blue; opacity: 0.2;">
			<div class="row" style="padding-top: 15px; padding-bottom: 15px;">
				<div class="col-md-4 col-xs-12 col-sm-12">
					<div>
						<img src="{{ asset('img/front/investing/support.jpg') }}" alt="Access Loans" class="team-image" style="width: 100%;">
					</div>
				</div>
				<div class="col-md-8">
					<span class="team-desc text-justify">
						<p class="funding-header">Customer Support</p>
						<p>We offer 24 hour customer support to all our esteemed clients. Find us via phone or email us, we respond within minutes.</p>
						<p style="padding-top: 30px;">
							<a href="{{ route('contact') }}" class="type-user emphasized">Contact Us</a>
						</p> 
					</span>
				</div>
			</div>
			<br>
		</div>
	</div>
@endsection