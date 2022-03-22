<div class="site-header">
	<nav class="navbar navbar-light">
		<div class="navbar-left">
			<a class="navbar-brand" href="{{ url('/') }}" title="Back Home">
				<div class="logo">
					<img src="{{ asset('img/main/footer.png') }}" alt="" style="width: 58%;">
				</div>
			</a>
			<div class="toggle-button dark sidebar-toggle-first float-xs-left hidden-md-up">
				<span class="hamburger"></span>
			</div>
			<div class="toggle-button-second dark float-xs-right hidden-md-up">
				<i class="ti-arrow-left"></i>
			</div>
			<div class="toggle-button dark float-xs-right hidden-md-up" data-toggle="collapse" data-target="#collapse-1">
				<span class="more"></span>
			</div>
		</div>
		<div class="navbar-right navbar-toggleable-sm collapse" id="collapse-1">
			<div class="toggle-button dark sidebar-toggle-second float-xs-left hidden-sm-down">
				<span class="hamburger"></span>
			</div>
			<div class="toggle-button-second dark float-xs-right hidden-sm-down">
				<i class="ti-arrow-left"></i>
			</div>
			<ul class="nav navbar-nav float-md-right">


				<li class="nav-item dropdown hidden-sm-down">
					<a href="#" data-toggle="dropdown" aria-expanded="false">
					<i class="fas fa-flag"></i>  @lang('layout.language')
					</a>
					<div class="dropdown-menu dropdown-menu-right animated fadeInUp">
						<a class="dropdown-item" href="{{ url('/change-language') }}?lang=en">
							<i class="fas fa-flag"></i> @lang('layout.english')
						</a>
						<a class="dropdown-item" href="{{ url('/change-language') }}?lang=sw">
							<i class="fas fa-flag"></i> @lang('layout.kiswahili')
						</a>
					<!-- 	<div class="dropdown-divider"></div> -->
				
					
					</div>
				</li>

				<li class="nav-item dropdown">
					<a class="nav-link" href="#" data-toggle="dropdown" aria-expanded="false">
						<i class="fas fa-bell"></i>
						<span class="hidden-md-up ml-1">@lang('layout.notifications')</span>
						<span class="tag tag-danger top">{{ count(Auth::user()->unreadNotifications) }}</span>
					</a>
					<div class="dropdown-messages dropdown-tasks dropdown-menu dropdown-menu-right animated fadeInUp">
						@foreach(Auth::user()->unreadNotifications->take(5) as $notification)
							<div class="m-item">
								<div class="mi-icon bg-primary"><i class="ti-comment"></i></div>
								<div class="mi-text">
										{{ $notification->data['data'] }}
								</div>
								<div class="mi-time">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($notification->created_at))->diffForHumans() }}</div>
							</div>
						@endforeach
						<a class="dropdown-more" href="#">
							<strong>@lang('layout.viewallnotifications')</strong>
						</a>
					</div>
				</li>


				<li class="nav-item dropdown hidden-sm-down">
					<a href="#" data-toggle="dropdown" aria-expanded="false">
						<span class="avatar box-32">
							<img src="{{ asset('img/avatars/' . Auth::user()->avatar) }}" alt="User Avatar">
						</span>
					</a>
					<div class="dropdown-menu dropdown-menu-right animated fadeInUp">
						<a class="dropdown-item" href="{{ route('organization.profile') }}">
							<i class="ti-user mr-0-5"></i> @lang('layout.profile')
						</a>
						<a class="dropdown-item" href="{{ url('/') }}">
							<i class="fa fa-home mr-0-5"></i> @lang('layout.home')
						</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="ti-power-off mr-0-5"></i> @lang('layout.signout')</a>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
	                         @csrf
	                    </form>
					</div>
				</li>
			</ul>
			<ul class="nav navbar-nav">
				<li class="nav-item hidden-sm-down">
					<a class="nav-link toggle-fullscreen" href="#">
						<i class="ti-fullscreen"></i>
					</a>
				</li>
				<li class="nav-item dropdown hidden-sm-down">
					<a class="nav-link" href="#" data-toggle="dropdown" aria-expanded="false">
						<i class="ti-layout-grid3"></i>
					</a>
					<div class="dropdown-apps dropdown-menu animated fadeInUp">
						<div class="a-grid">
							<div class="row row-sm">
								<div class="col-xs-4">
									<div class="a-item">
										<a href="#">
											<div class="ai-icon"><img class="img-fluid" src="img/brands/dropbox.png" alt=""></div>
											<div class="ai-title">Dropbox</div>
										</a>
									</div>
								</div>
								<div class="col-xs-4">
									<div class="a-item">
										<a href="#">
											<div class="ai-icon"><img class="img-fluid" src="img/brands/github.png" alt=""></div>
											<div class="ai-title">Github</div>
										</a>
									</div>
								</div>
								<div class="col-xs-4">
									<div class="a-item">
										<a href="#">
											<div class="ai-icon"><img class="img-fluid" src="img/brands/wordpress.png" alt=""></div>
											<div class="ai-title">Wordpress</div>
										</a>
									</div>
								</div>
								<div class="col-xs-4">
									<div class="a-item">
										<a href="#">
											<div class="ai-icon"><img class="img-fluid" src="img/brands/gmail.png" alt=""></div>
											<div class="ai-title">Gmail</div>
										</a>
									</div>
								</div>
								<div class="col-xs-4">
									<div class="a-item">
										<a href="#">
											<div class="ai-icon"><img class="img-fluid" src="img/brands/drive.png" alt=""></div>
											<div class="ai-title">Drive</div>
										</a>
									</div>
								</div>
								<div class="col-xs-4">
									<div class="a-item">
										<a href="#">
											<div class="ai-icon"><img class="img-fluid" src="img/brands/dribbble.png" alt=""></div>
											<div class="ai-title">Dribbble</div>
										</a>
									</div>
								</div>
							</div>
						</div>
						<a class="dropdown-more" href="#">
							<strong>View all apps</strong>
						</a>
					</div>
				</li>
			</ul>

			<div class="header-form float-md-left ml-md-2">
				<form>
					<input type="text" class="form-control b-a" placeholder="@lang('layout.searchfor')...">
					<button type="submit" class="btn bg-white b-a-0">
						<i class="ti-search"></i>
					</button>
				</form>
			</div>
		</div>
	</nav>
</div>