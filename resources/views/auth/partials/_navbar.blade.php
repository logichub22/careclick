<nav class="navbar navbar-expand-lg navbar-light bg-transparent" id="my-header">
   <a class="navbar-brand px-86 pt-2" href="{{ url('/') }}" title="Home">
      @if(!Request::is('organization-signup'))
       <img src="{{ asset('img/main/logo.png') }}" alt="{{ config('app.name') }} Logo">
      @else
        <img src="{{ asset('img/main/footer.png') }}" alt="{{ config('app.name') }} Logo">
      @endif
   </a>
   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
   <span class="navbar-toggler-icon {{ Request::is('organization-signup') ? ' make-white' : '' }}"></span>
   </button>
   <div class="collapse navbar-collapse" id="navbarSupportedContent">
      
      <!-- Right side of navbar -->
      <ul class="navbar-nav ml-auto">
           <li class="nav-item">
              @if(!Request::is('login'))
                <a class="nav-link login" href="{{ route('login') }}"><span class="{{ !Request::is('organization-signup') ? 'account' : 'org-account' }}">Got an account?</span> {{ __('Sign in here') }}</a>
              @else
                <a class="nav-link login" href="{{ route('register') }}"><span class="{{ !Request::is('organization-signup') ? 'account' : 'org-account' }}">Do not have an account?</span> {{ __('Register here') }}</a>
              @endif
           </li>
      </ul>
   </div>
</nav>