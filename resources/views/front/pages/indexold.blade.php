@extends('front/layouts/master')

@section('title')
Welcome
@endsection

@section('content')
	<!-- Intro Message -->
	<div class="container">
		<div class="row">
			<!-- Message -->
			<div class="col-md-6">
				<h5 class="topic">Inclusive Fintech</h5>
				<div class="welcome-message">
					africa's first inclusive, intelligent fintech platform
				</div>
				<br>
				<div class="blue-line"></div>
			</div>
			<div class="col-md-6">
				
			</div>
		</div>
	</div>
	<img src="{{ asset('img/front/landing/bg.svg') }}" alt="" class="fancy-bg d-none d-md-block">

	<div class="banner-text">
		<div class="row">
			<div class="col-md-12 text-center">
				<h3>catalyst for financial inclusion <br>
			<small>grassroot empowerment</small></h3>
			</div>
		</div>
	</div>

	<!-- Banner -->
	<div class="container-fluid">
		<div class="banner-box">
			<img src="{{ asset('img/front/mission/section-woman.jpg') }}" alt="Landing Page Banner" class="landing-banner img-responsive w-100">
		</div>
	</div>
	<!-- Main content with gray section -->
	<section class="landing-content">
		<div class="container landing-main">
			{{-- <div class="row" style="padding-top: 120px;">
				<div class="col-md-6">
					<h5 class="fund-header is-blue">Looking for funding?</h5>
					<p class="inclusive-text fund-text">
						We provide our partners with quick access to loans, equity and credit lines through our platform.
					</p>
					<a href="{{ route('funding') }}" class="type-user emphasized">Find out more</a>
				</div>
				<div class="col-md-6">
					<h5 class="fund-header is-blue">Looking to invest?</h5>
					<p class="inclusive-text fund-text">
						Jamborow attracts investments from a network of over 7 countries worldwide through the use of our platform.
					</p>
					<a href="{{ route('investing') }}" class="type-user emphasized">Find out more</a>
				</div>
			</div> --}}
			<div class="row intro-content">
				<div class="col-md-7">
					<p class="about"><span class="is-blue">Jamborow</span> is Africa’s first <span class="is-blue"> B2B AI </span>and <span class="is-blue">Blockchain</span> driven fintech platform focused on financial inclusion and grassroot empowerment. Having started life as a start-up Fintech that has quickly developed into a multifaceted economy for the unbanked and underbanked Ecosystem whereby we are able to directly impact on changing lives of the lower income and rural communities within Africa.</p>
				</div>
				<div class="col-md-5 inclusive">
					<div class="row">
						<div class="col-md-6">
							<p class="inclusive-title">Truly Financial Inclusive Platform</p>
						</div>
						<div class="col-md-6 has-hand">
							<img src="{{ asset('img/front/landing/hand.svg') }}" alt="Hand Icon" class="hand-image">
						</div>
					</div><br>
					<p class="inclusive-text">We aim to contribute to positive change in the lives of low income women and men, households and communities. This requires careful partner selection, monitoring and support as well as a balanced assessment of social performance.</p>
				</div>
			</div>
			{{-- <div class="row other-cols">
				<div class="col-md-4 mb-2">
					<div class="col-card">
						<div class="row">
							<div class="col-md-8">
								<p class="col-title">Convenient Balance of Financial Assets</p>
							</div>
							<div class="col-md-4 has-g-icon">
								<img src="{{ asset('img/front/landing/group.svg') }}" alt="Group Icon" class="col-img">
							</div>
						</div>
						<p class="col-text">Create a level the playing field for businesses and workers in the semi-formal sector through access to affordable financial services to stimulate their own economic growth.</p>
					</div>
				</div>
				<div class="col-md-4 mb-2">
					<div class="col-card">
						<div class="row">
							<div class="col-md-8">
								<p class="col-title">Fostering the Inclusive Ecosystem</p>
							</div>
							<div class="col-md-4 has-g-icon">
								<img src="{{ asset('img/front/landing/group.svg') }}" alt="Group Icon" class="col-img">
							</div>
						</div>
						<p class="col-text">Create a level the playing field for businesses and workers in the semi-formal sector through access to affordable financial services to stimulate their own economic growth.</p>
					</div>
				</div>
				<div class="col-md-4 mb-2">
					<div class="col-card">
						<div class="row">
							<div class="col-md-8">
								<p class="col-title">Super Fast Transfer of Loans</p>
							</div>
							<div class="col-md-4 has-g-icon">
								<img src="{{ asset('img/front/landing/group.svg') }}" alt="Group Icon" class="col-img">
							</div>
						</div>
						<p class="col-text">Facilitate access to funds, streamline loan approval/collection processes, and enable financial inclusion to increase the purchasing power of the poorest people in Nigeria.</p>
					</div>
				</div>
			</div> --}}
			<div class="row sahara">
				<div class="col-md-7">
					<img src="{{ asset('img/front/landing/man.jpg') }}" alt="Man on Phone" class="sec-image img-responsive w-100">
				</div>
				<div class="col-md-5 has-more">
					<p class="has-sahara">
						<span class="is-blue">Building</span> Sub Sahara Africa Mobile Finance Hubs
					</p>
					<p class="sahara-text pb-37">
						Nigeria for example, only 40% of Nigerian adults have an account with a financial institution or a mobile money provider, <strong>leaving 60% of the adult population unbanked</strong>, with no access to formal financial services.
					</p>
					<p class="sahara-text has-62">
						The unbanked are mostly <span class="is-red">unidentifiable</span> at the state and federal levels as they are not bank verification number (BVN) holders – the backbone of the national identification program.
					</p>
					<p class="has-arrow">
						<a href="{{ url('about') }}" class="read-more is-blue">Read more about us &nbsp;<i class="fas fa-arrow-right"></i></a>
					</p>
				</div>
			</div>
			<div class="row sahara">
				<div class="col-md-5 has-more">
					<p class="has-sahara">
						Jamborow’s ecosystem cuts across various PanAfrican Countries
					</p>
					<p class="sahara-text pb-37">
						We have a large network of partners and investors in the following countries and counting: Nigeria, Sierra Leone, Liberia, Botswana, Tanzania, Kenya, Uganda etc.
					</p>
				</div>
				<div class="col-md-7 text-right">
					<img src="{{ asset('img/front/landing/man_two.jpg') }}" alt="Man on Phone" class="img-responsive w-100 sec-image">
				</div>
			</div>
		</div>
	</section>
	<!-- Features section -->
	<section class="features" id="features">
		<h3 class="feature-main">Jamborow's Solutions</h3>
		<p class="feature-subtitle">Jamborow is the leading B2B Microlending Platform for all businesses in the Fintech space</p>
		<div class="container">
			{{-- <div class="row feature-row">
				<div class="col-lg-3 col-sm-6 pb-4 feature-col">
					<span class="feature-icon"><img src="{{ asset('img/front/landing/chat.png') }}" alt="SMS Messaging"></span>
					<span class="feature-title">Financial Inclusion</span>
					<div class="feature-separator"></div>
					<div class="feature-content">
						We provide a truly Inclusive, intelligent lending platform for all unbanked and underbanked.
					</div>
				</div>
				<div class="col-lg-3 col-sm-6 pb-4 feature-col">
					<span class="feature-icon"><img src="{{ asset('img/front/landing/chat.png') }}" alt="SMS Messaging"></span>
					<span class="feature-title">Instant Microloans</span>
					<div class="feature-separator"></div>
					<div class="feature-content">
						Instant access to insured microloans
					</div>
				</div>
				<div class="col-lg-3 col-sm-6 pb-4 feature-col">
					<span class="feature-icon"><img src="{{ asset('img/front/landing/check.png') }}" alt="Loan Check"></span>
					<span class="feature-title">Offline Channel</span>
					<div class="feature-separator"></div>
					<div class="feature-content">
						Credit check and proprietary credit scoring algorithm buttressed by individual credit data from the credit bureaus, where applicable.
					</div>
				</div>
				<div class="col-lg-3 col-sm-6 pb-4 feature-col">
					<span class="feature-icon"><img src="{{ asset('img/front/landing/admin.png') }}" alt="Admin Rights"></span>
					<span class="feature-title">Mobile Solution</span>
					<div class="feature-separator"></div>
					<div class="feature-content">
						Device agnostic mobile solution for both feature and smartphones ensure the inclusion of everyone
					</div>
				</div>
				
			</div> --}}
			{{-- <div class="row feature-row-two">
				<div class="col-lg-3 col-sm-6 pb-4 feature-col">
					<span class="feature-icon"><img src="{{ asset('img/front/landing/admin.png') }}" alt="Wallet System"></span>
					<span class="feature-title">Our Ecosystem</span>
					<div class="feature-separator"></div>
					<div class="feature-content">
						Ecosystem of thousands of users with symbiotic relationships
					</div>
				</div>
				<div class="col-lg-3 col-sm-6 pb-4 feature-col">
					<span class="feature-icon"><img src="{{ asset('img/front/landing/codeshare.png') }}" alt="Codeshare API"></span>
					<span class="feature-title">Rewards System</span>
					<div class="feature-separator"></div>
					<div class="feature-content">
						Rewards for customers with impeccable repayment histories
					</div>
				</div>
				
				<div class="col-lg-3 col-sm-6 pb-4 feature-col">
					<span class="feature-icon"><img src="{{ asset('img/front/landing/chat.png') }}" alt="SMS Messaging"></span>
					<span class="feature-title">Platforms</span>
					<div class="feature-separator"></div>
					<div class="feature-content">
						Easy integration with telco and other platforms
					</div>
				</div>
				<div class="col-lg-3 col-sm-6 pb-4 feature-col">
					<span class="feature-icon"><img src="{{ asset('img/front/landing/admin.png') }}" alt="User Access"></span>
					<span class="feature-title">Team</span>
					<div class="feature-separator"></div>
					<div class="feature-content">
						Passionate and experienced management team
					</div>
				</div>
			</div> --}}
			<div class="row">
				<div class="col-md-6 offset-md-3 text-center">
					<br>
					<a href="{{ route('about') }}" class="btn btn-primary">Find out more</a>
				</div>
			</div>
		</div>
	</section>

	<section class="innovation">
		<div class="row no-gutters">
			<div class="col-md-6 network">
				<p><img src="{{ asset('img/front/landing/play.svg') }}" alt="Network of Lendors" class="img-responsive"></p>
				<p class="innovation-header">
					Growing network of lenders
				</p>
				<p class="innovation-text">
					A competitive professional lender network with established lenders providing value-added services as our distribution channel and cross-member interaction, with other lenders transacting on our platform
				</p>
			</div>
			<div class="col-md-6 solution-pack">
				<p><img src="{{ asset('img/front/landing/innovation.svg') }}" alt="Innovation Solution Pack" class="img-responsive"></p>
				<p class="innovation-header">
					Innovation solution pack
				</p>
				<p class="innovation-text">
					Jamborow offers our users easy, convenient and immediate access to microloans, and our channel partners an easy, practical and organized way to manage and service their customers
				</p>
			</div>
		</div>
	</section>
	
	{{-- <img src="{{ asset('img/front/landing/oval-blue.svg') }}" alt="Oval Blue" class="oval-blue"> --}}

	{{-- <section class="blog">
		<div class="container">
			<h3 class="blog-title text-center">News</h3>
			<p class="text-center blog-subtitle">Get the latest news and info on our news page</p>

			<div class="row latest-posts">
				<div class="col-md-4 pb-2">
					<a href="" class="post-read">
						<div class="blog-card">
							<img src="{{ asset('img/front/landing/img-one.jpg') }}" alt="Blog Image One" class="blog-img w-100 img-responsive">
							<div class="blog-card-body">
								<h3 class="post-title">Africa's mobile economic revolution</h3>
								<p class="post-body">
									Half of Africa's one billion population has a mobile phone – and not just for talking. The power of telephony is forging a new enterprise culture..
								</p>
							</div>
						</div>
					</a>
				</div>
				<div class="col-md-4 pb-2">
					<a href="" class="post-read">
						<div class="blog-card">
							<img src="{{ asset('img/front/landing/img-two.jpg') }}" alt="Blog Image Two" class="blog-img w-100 img-responsive">
							<div class="blog-card-body">
								<h3 class="post-title">Increased cell phone coverage</h3>
								<p class="post-body">
									The increasing availability of cell phone coverage in Africa is contributing to an increase of payment on that continent, a recent study contends.
								</p>
							</div>
						</div>
					</a>
				</div>
				<div class="col-md-4 pb-2">
					<a href="" class="post-read">
						<div class="blog-card">
							<img src="{{ asset('img/front/landing/img-one.jpg') }}" alt="Blog Image Three" class="blog-img w-100 img-responsive">
							<div class="blog-card-body">
								<h3 class="post-title">Africa's phone market declines</h3>
								<p class="post-body">
									The overall mobile market in Africa was up 8.4%, primarily due to feature phone shipments. Overall mobile phone shipments for the first quarter ...
								</p>
							</div>
						</div>
					</a>
				</div>
			</div>

			<div class="row read-box">
				<div class="col-md-12 text-center">
					<a href="" class="visit-blog">Visit page</a>
				</div>
			</div>
		</div>
	</section>  --}}
	{{-- <br><br> --}}

	{{-- <section class="testimonials">
		<div class="container">
			<h3 class="text-center testimonial-header">Our users love the product!</h3>
			<div class="row quotes">
				<div class="col-md-12">
					<div class="carousel slide" id="quote-carousel" data-ride="carousel">
						<div class="carousel-inner text-center">
							<div class="carousel-item active">
								<div class="row">
                                    <div class="col-sm-8 offset-sm-2">
                                        <p class="quote">A value-added microlending platform with a diversified user base</p>
                                        <p class="author">
                                        	<img src="{{ asset('img/front/landing/author.jpg') }}" alt="Author Avatar">
                                        	<span class="names">
                                        		<span class="author-name">Adejemi Franklin</span>
                                        	</span>
                                        </p>
                                    </div>
                                </div>
							</div>
							<div class="carousel-item">
								<div class="row">
                                    <div class="col-sm-8 offset-sm-2">
                                        <p class="quote">A value-added platform to aid financial inclusion</p>
                                        <p class="author">
                                        	<img src="{{ asset('img/front/landing/author.jpg') }}" alt="Author Avatar">
                                        	<span class="names">
                                        		<span class="author-name">Adejemi Franklin</span>
                                        	</span>
                                        </p>
                                    </div>
                                </div>
							</div>
						</div>
						<a class="carousel-control-prev" href="#quote-carousel" role="button" data-slide="prev">
						    <i class="fa fa-angle-left fa-3x" aria-hidden="true" style="color: #323643;"></i>
						    <span class="sr-only">Previous</span>
						</a>
						<a class="carousel-control-next" href="#quote-carousel" role="button" data-slide="next">
						    <i class="fa fa-angle-right fa-3x" aria-hidden="true" style="color: #323643;"></i>
						    <span class="sr-only">Next</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</section> --}}

	{{-- <img src="{{ asset('img/front/landing/oval-dark.svg') }}" alt="Oval Dark" class="oval-dark"> --}}

	{{-- <section class="landing-subscription">
		<div class="container">
			<div class="col-md-8 offset-md-2 sub-box text-center">
				<h3 class="box-subheader">With Jamborow, you can now</h3>
				<h4 class="box-header">Focus more on onboarding the unbanked.</h4>
				<form action="" class="floating-form form-inline text-center">
					{{ csrf_field() }}
					<input type="email" placeholder="Your e-mail address" class="land-input">
					<input type="submit" class="land-submit" value="Subscribe">
				</form>
				<h3 class="spam">We will not spam you</h3>
			</div>
			<img src="{{ asset('img/front/landing/dot-brown-clean.png') }}" alt="Dot Brown" class="dot-brown">
		</div>
	</section> --}}
@endsection
