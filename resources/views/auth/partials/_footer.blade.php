<footer class="main-footer">
	<div class="container footer-content">

		<div class="footer-separator my-3"></div>
		<div class="row">
			<div class="col-md-6 logo-holder">
				<a href="{{ url('/') }}" title="Home"><img src="{{ asset('img/main/footer-logo.png') }}" alt="White Footer Logo" class="footer-logo"></a>
			</div>
			<div class="col-md-6">
				<br>
				<ul class="list-unstyled list-inline footer-nav has-gray">
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