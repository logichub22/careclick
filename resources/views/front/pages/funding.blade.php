@extends('front/layouts/master')

@section('title')
	Get Funded
@endsection

@section('content')
	<div class="page-banner team-banner">
		<div class="container">
			<div class="banner-header">
				Fund Your<br>
				Next Great Idea
			</div>
		</div>
	</div>

	<div class="mt-77">
		<div class="container">
			<div class="row" style="padding-top: 15px; padding-bottom: 15px;">
				<div class="col-md-4 col-xs-12 col-sm-12">
					<div>
						<img src="{{ asset('img/front/funding/loan.jpg') }}" alt="Access Loans" class="team-image" style="width: 100%;">
					</div>
				</div>
				<div class="col-md-8">
					<span class="team-desc text-justify">
						<p class="funding-header">Access Loans</p>
						<p>{{ config('app.name') }} provides a range of financial loan products that can be tailor made to fit each customer's needs. With an easy loan application process, our platform offers the perfect opportunity to fund your next great idea.</p> 
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
						<img src="{{ asset('img/front/mission/section-woman.jpg') }}" alt="Access Loans" class="team-image" style="width: 100%;">
					</div>
				</div>
				<div class="col-md-8">
					<span class="team-desc text-justify">
						<p class="funding-header">Favourable Interest Rates</p>
						<p>With interest rates as low as 5% p.a, servicing your loan has never been much easier. No collateral is required, neither are any hidden fees.</p>
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
						<img src="{{ asset('img/front/funding/map.png') }}" alt="Access Loans" class="team-image" style="width: 100%;">
					</div>
				</div>
				<div class="col-md-8">
					<span class="team-desc text-justify">
						<p class="funding-header">Find Us</p>
						<p>We cover 70% of Africa, with our offices located all around the continent. Contact us for support or any enquiries you might have.</p> 
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