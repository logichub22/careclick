<footer class="main-footer">
	<div class="container footer-content">
		<div class="row">
			<div class="col-md-7 has-contacts">
				<div class="row">
					<div class="col-md-6 contact-group">
						<span class="footer-title">Email:</span>
						<span class="footer-text">info@jamborow.co.uk</span>
					</div>
					{{-- <div class="col-md-6 contact-group-two">
						<span class="footer-title">Contact Us:</span>
						<span class="footer-text">+234 (123) 456789</span>
					</div> --}}
				</div>
			</div>
			<div class="col-md-5">
				<div>
					<ul class="list-unstyled list-inline footer-nav">
					  <li class="list-inline-item pr-3"><a href="{{ url('/') }}">Home</a></li>
					  <li class="list-inline-item pr-3"><a href="{{ url('faq') }}">FAQ</a></li>
					  <li class="list-inline-item pr-3"><a href="{{ url('team') }}">Team</a></li>
					  <li class="list-inline-item"><a href="{{ url('mission') }}">Mission</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="footer-separator my-3"></div>
		<div class="row">
			<div class="col-md-6 logo-holder">
				<a href="{{ url('/') }}" title="Home"><img src="{{ asset('img/main/footer-logo.png') }}" alt="White Footer Logo" class="footer-logo"></a>
			</div>
			<div class="col-md-6">
				<br>
				<ul class="list-unstyled list-inline footer-nav has-gray">
				  <li class="list-inline-item pr-3"><a href="{{ url('privacy') }}">Privacy</a></li>
				  <li class="list-inline-item pr-3"><a href="{{ url('terms') }}">Disclaimer</a></li>
				  <li class="list-inline-item"><a>Copyright {{ date("Y") }}</a></li>
				</ul>
			</div>
		</div>
		{{-- <div class="row address">
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-6">
						<span class="footer-text">227 Roberts Lop Suite 101</span>
					</div>
					<div class="col-md-6">
						<span class="footer-text">038 Zboncak Burg Suite 073</span>
					</div>
				</div>
			</div>
		</div> --}}
	</div>
</footer>