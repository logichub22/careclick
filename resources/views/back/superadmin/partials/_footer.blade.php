<footer class="footer">
	<div class="container-fluid">
		<div class="row text-xs-center">
			<div class="col-sm-4 text-sm-left mb-0-5 mb-sm-0">
				{{ date('Y') }} &copy; {{ config('app.name') }}
			</div>
			<div class="col-sm-8 text-sm-right">
				<ul class="nav nav-inline l-h-2">
					<li class="nav-item"><a class="nav-link text-black" href="{{ route('privacy') }}">Privacy</a></li>
					<li class="nav-item"><a class="nav-link text-black" href="{{ route('terms') }}">Terms</a></li>
					<li class="nav-item"><a class="nav-link text-black" href="{{ route('faq') }}">Help</a></li>
				</ul>
			</div>
		</div>
	</div>
</footer>