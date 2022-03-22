@extends('front/layouts/master')

@section('title')
	Privacy
@endsection

@section('content')
	<div class="page-banner terms-banner">
		<div class="container">
			<div class="banner-header text-center">
					Privacy Policy
			</div>
		</div>
	</div>
	<div class="mt-77 mb-62">
		<div class="container">
			<div class="row">
				<div class="col-md-8 offset-md-2">
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-6">
								<div class="term">
									<p class="term-desc">
										 Your privacy is important to us. It is Jamborow’s policy to respect your privacy regarding any information we may collect from you across our website, http://jamborow.co.uk, and other sites we own and operate.
									</p>
								</div>
								<div class="term">
									<p class="term-desc">
										We only ask for personal information when we truly need it to provide a service to you. We collect it by fair and lawful means, with your knowledge and consent. We also let you know why we’re collecting it and how it will be used.
									</p>
								</div>
								<div class="term">
									<p class="term-desc">
										We only retain collected information for as long as necessary to provide you with your requested service. What data we store, we’ll protect within commercially acceptable means to prevent loss and theft, as well as unauthorised access, disclosure, copying, use or modification.
									</p>
								</div>
								<div class="term">
									<p class="term-desc">
										We don’t share any personally identifying information publicly or with third-parties, except when required to by law.
									</p>
								</div>
								<div class="term">
									<p class="term-desc">
										Our website may link to external sites that are not operated by us. Please be aware that we have no control over the content and practices of these sites, and cannot accept responsibility or liability for their respective privacy policies.
									</p>
								</div>
								<div class="term">
									<p class="term-desc">
										You are free to refuse our request for your personal information, with the understanding that we may be unable to provide you with some of your desired services.
									</p>
								</div>
								<div class="term">
									<p class="term-desc">
										Your continued use of our website will be regarded as acceptance of our practices around privacy and personal information. If you have any questions about how we handle user data and personal information, feel free to contact us.
									</p>
								</div>
								<div class="term">
									<p class="term-desc">
										This policy is effective as of 8 August 2018.	
									</p>
								</div>	
							</div>
							<div class="col-md-2">
								
							</div>
							<div class="co-md-4">
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="bg-gray">
		<div class="container">
			<div class="row pbt-64">
				<div class="col-md-6 offset-md-3">
					<h4 class="action-header">
						<img src="{{ asset('img/front/privacy/envelope.png') }}" alt="Envelope Icon" class="pr-3">
						Sign up to our newsletter to stay up to date.
					</h4>
					<form class="form-inline pbt-27 action-form">
					   <input type="email" placeholder="Your Email Address" class="pl-5 is-wide"><span class="pt-27"></span>
					   <div class="sign-holder">
					   		<input type="submit" class="action-button" value="Sign me up">
					   </div>
					   
					   <span class="agreement">
					   	   <img src="{{ asset('img/front/privacy/accept.png') }}" alt="Accept Terms" class="pr-3 accept">
						   I agree my details be held and used by Incollab Inc. for the purpose of responding to my interest, personalising communications and keeping me up to date about relevant news and updates.
						</span>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection