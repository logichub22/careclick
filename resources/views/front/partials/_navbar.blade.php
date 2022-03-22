<nav class="mt-nav-bar" uk-sticky="top: 120; animation: uk-animation-slide-top;">
    <div id='stars'></div>
    <div id='stars2'></div>
    <div id='stars3'></div>    <div class="mt-nav-container">
        <div class="mt-nav">
            <div class="mt-nav-left">
                <img src="{{ asset('img/logo.png') }}" alt="">
            </div>
            <div class="mt-nav-right">
                <ul class="mt-nav-list">
                    <li><a href="/">Home</a></li>
                    <li><a href="/about">About</a></li>
                    <li  uk-tooltip="title: Our Products"><a href="#">Products <span class="caret"></span></a>
                    <div uk-dropdown="animation: uk-animation-slide-top-small; duration: 1000">
                    <ul class="uk-nav uk-dropdown-nav">
                    <li class=""><a href="#" >Jfintech</a>
                        <div uk-dropdown="pos: right-top; offset: 1;">

                        <ul>
                        
                        <div class="uk-dropdown-blank uk-panel uk-panel-box uk-text-uppercase">Jamborow Fintech (Financial inclusion, Jcore). </div>                      
                    </ul>
                    </div>
                       
                        </li>

                    <li class="uk-nav-divider"></li>
                        <li class=""><a href="#" >Jmart</a>
                        <div uk-dropdown="pos: right-top; offset: 1;">

                        <ul>
                        
                        <div class="uk-dropdown-blank uk-panel uk-panel-box uk-text-uppercase">Jamborow Mart (E-commerce and trading platform for SMEs, Jmart). <br> <div class="uk-text-bold uk-text-center">coming soon!</div></div>                      
                    </ul>
                    </div>
                       
                        </li>
                        <li class="uk-nav-divider"></li>
                        <li><a href="#">CrytoPay</a>
                        <div uk-dropdown="pos: right-top; offset: 1;">

                        <ul>
                        
                        <div class="uk-dropdown-blank uk-panel uk-panel-box uk-text-uppercase">Jamborow Cryptopay (cryptocurrency payment solution for merchants<br> and customers, Jcryptopay). <br> <div class="uk-text-bold uk-text-center">coming soon!</div></div>
                        
                    </ul>
                    </div>
                        </li>                        
                        <li class="uk-nav-divider"></li>
                        <li><a href="#">Serengeti</a>
                        <div uk-dropdown="pos: right-top; offset: 1;">

                        <ul>
                        
                        <div class="uk-dropdown-blank uk-panel uk-panel-box uk-text-uppercase">Serengeti (Jamborow ecosystem blockchain as a service solution – BAAS). <br> <div class="uk-text-bold uk-text-center">coming soon!</div></div>
                        
                    </ul>
                    </div>
                        </li>
                        <li class="uk-nav-divider"></li>
                        
                    </ul>
                    </div>
                    </li>
                    <li uk-tooltip="title: Our Services"><a href="#">Services <span class="caret"></span></a>
                    <div uk-dropdown="animation: uk-animation-slide-top-small; duration: 1000">
                    <ul class="uk-nav uk-dropdown-nav">
                        
                        <li class=""><a href="#" >Retail Service</a>
                        <div uk-dropdown="pos: right-top; offset: 1;">

                        <ul>
                    
                        <div class="uk-dropdown-blank uk-panel uk-panel-box uk-text-uppercase">Jamborow offers financial services and <br> credit score to retail B2B partners.</div>                        
                    </ul>
                    </div>
                       
                        </li>
                        <li class="uk-nav-divider"></li>
                        <li><a href="#">Saving Group Service</a>
                        <div uk-dropdown="pos: right-top; offset: 1;">

                        <ul>
                       
                        <div class="uk-dropdown-blank uk-panel uk-panel-box uk-text-uppercase">Jamborow provides access to a business platform<br> that provides financial service products (Microloans, <br>Micropension, Microinsurance, Microhealthplan etc)<br> to Saving Groups across Africa.</div> 
                        
                    </ul>
                    </div>
                        </li>                        
                        <li class="uk-nav-divider"></li>
                        <li><a href="#">Financial Service</a>
                        <div uk-dropdown="pos: right-top; offset: 1;">

                        <ul>
                        <div class="uk-dropdown-blank uk-panel uk-panel-box uk-text-uppercase">Jamborow business platform enables Saving Groups<br> across Africa build credit scored for their members hence<br> a credit footprint with access to credit.</div> 
                        
                    </ul>
                    </div>
                        </li>                       
                        <li class="uk-nav-divider"></li>
                        <li><a href="#">Leasing Service</a>
                        <div uk-dropdown="pos: right-top; offset: 1;">

                        <ul>
                        <div class="uk-dropdown-blank uk-panel uk-panel-box uk-text-uppercase">Jamborow ecosystem enables access to<br> BPNL service to farmers across Africa.</div> 
                        
                    </ul>
                    </div>
                    </ul>

                    </div>
                    </li>

                    <li><a href="/about#team">Team</a></li>
                    <li><a href="/about#articles">Press</a></li>
                    <li><a href="/contact">Contact</a></li>
                    @if(Auth::check())
                       @if(Auth::user()->roles()->first()->name === "normal-user")
                            <li><a href="/user/dashboard">Dashboard</a></li>
                       @elseif(Auth::user()->roles()->first()->name === "super-organization-admin")
                            <li><a href="/user/dashboard">Dashboard</a></li>
                       @elseif(Auth::user()->roles()->first()->name === "organization-user")
                            <li><a href="/user/dashboard">Dashboard</a></li>
                        @elseif(Auth::user()->roles()->first()->name === "admin")
                            <li><a href="/organization/dashboard">Dashboard</a></li>
                       @endif
                    @else
                    <li><a href="/login">Login</a></li>
                    <!-- <li><a href="/register">Join</a></li> -->
                   @endif
                </ul>
            </div>
            <a class="uk-navbar-toggle" uk-navbar-toggle-icon href="#!" uk-toggle="target: #offcanvas-nav-primary"></a>
        </div>
    </div>
</nav>
<div id="offcanvas-nav-primary" uk-offcanvas="overlay: true">
    <div id='stars'></div>
    <div id='stars2'></div>
    <div id='stars3'></div>    <div class="uk-offcanvas-bar uk-flex uk-flex-column">

        <ul class="uk-nav uk-nav-primary uk-nav-center uk-margin-auto-vertical">
            <li>
                <a href="/">Home</a>
            </li>
            <li>
                <a href="/about"> About </a>
            </li>
            <li class=""><a href="#">Products <span class="caret" style="color:#FFFFFF80"></span></a>
            <div uk-dropdown="animation: uk-animation-slide-top-small; duration: 1000">
                    <ul class="uk-nav uk-dropdown-nav">
                    <li class=""><a href="#" >Jfintech</a>
                        <div uk-dropdown="pos: right-top; offset: 1;">

                        <ul>
                        
                        <div class="uk-dropdown-blank uk-panel uk-panel-box uk-text-uppercase">Jamborow Fintech (Financial inclusion, Jcore). </div>                      
                    </ul>
                    </div>
                       
                        </li>

                    <li class="uk-nav-divider"></li>
                        <li class=""><a href="#" >Jmart</a>
                        <div uk-dropdown="pos: right-top; offset: 1;">

                        <ul>
                        
                        <div class="uk-dropdown-blank uk-panel uk-panel-box uk-text-uppercase">Jamborow Mart (E-commerce and trading platform for SMEs, Jmart). <br> <div class="uk-text-bold uk-text-center">coming soon!</div></div>                      
                    </ul>
                    </div>
                       
                        </li>
                        <li class="uk-nav-divider"></li>
                        <li><a href="#">CrytoPay</a>
                        <div uk-dropdown="pos: right-top; offset: 1;">

                        <ul>
                        
                        <div class="uk-dropdown-blank uk-panel uk-panel-box uk-text-uppercase">Jamborow Cryptopay (cryptocurrency payment solution for merchants<br> and customers, Jcryptopay). <br> <div class="uk-text-bold uk-text-center">coming soon!</div></div>
                        
                    </ul>
                    </div>
                        </li>                        
                        <li class="uk-nav-divider"></li>
                        <li><a href="#">Serengeti</a>
                        <div uk-dropdown="pos: right-top; offset: 1;">

                        <ul>
                        
                        <div class="uk-dropdown-blank uk-panel uk-panel-box uk-text-uppercase">Serengeti (Jamborow ecosystem blockchain as a service solution – BAAS). <br> <div class="uk-text-bold uk-text-center">coming soon!</div></div>
                        
                    </ul>
                    </div>
                        </li>
                        <li class="uk-nav-divider"></li>
                        
                    </ul>
                    </div>
                    </li>
                    <li class=""><a href="#">Services <span class="caret" style="color:#FFFFFF80"></span></a>
                    <div uk-dropdown="animation: uk-animation-slide-top-small; duration: 1000">
                    <ul class="uk-nav uk-dropdown-nav">
                        
                        <li class=""><a href="#" >Retail Service</a>
                        <div uk-dropdown="pos: right-top; offset: 1;">

                        <ul>
                    
                        <div class="uk-dropdown-blank uk-panel uk-panel-box uk-text-uppercase">Jamborow offers financial services and <br> credit score to retail B2B partners.</div>                        
                    </ul>
                    </div>
                       
                        </li>
                        <li class="uk-nav-divider"></li>
                        <li><a href="#">Saving Group Service</a>
                        <div uk-dropdown="pos: right-top; offset: 1;">

                        <ul>
                       
                        <div class="uk-dropdown-blank uk-panel uk-panel-box uk-text-uppercase">Jamborow provides access to a business platform<br> that provides financial service products (Microloans, <br>Micropension, Microinsurance, Microhealthplan etc)<br> to Saving Groups across Africa.</div> 
                        
                    </ul>
                    </div>
                        </li>                        
                        <li class="uk-nav-divider"></li>
                        <li><a href="#">Financial Service</a>
                        <div uk-dropdown="pos: right-top; offset: 1;">

                        <ul>
                        <div class="uk-dropdown-blank uk-panel uk-panel-box uk-text-uppercase">Jamborow business platform enables Saving Groups<br> across Africa build credit scored for their members hence<br> a credit footprint with access to credit.</div> 
                        
                    </ul>
                    </div>
                        </li>                       
                        <li class="uk-nav-divider"></li>
                        <li><a href="#">Leasing Service</a>
                        <div uk-dropdown="pos: right-top; offset: 1;">

                        <ul>
                        <div class="uk-dropdown-blank uk-panel uk-panel-box uk-text-uppercase">Jamborow ecosystem enables access to<br> BPNL service to farmers across Africa.</div> 
                        
                    </ul>
                    </div>
                    </ul>

                    </div>
                    </li>


            <li>
                <a href="/about#team"> Team</a>
            </li>
            <li>
                <a href="/about#articles"> Press</a>
            </li>
            <li>
                <a href="/contact"> Contact </a>
            </li>
          
             @if(Auth::check())
               @if(Auth::user()->roles()->first()->name === "normal-user")
                    <li><a href="/user/dashboard">Dashboard</a></li>
               @elseif(Auth::user()->roles()->first()->name === "super-organization-admin")
                    <li><a href="/user/dashboard">Dashboard</a></li>
               @elseif(Auth::user()->roles()->first()->name === "organization-user")
                    <li><a href="/user/dashboard">Dashboard</a></li>
                @elseif(Auth::user()->roles()->first()->name === "admin")
                    <li><a href="/organization/dashboard">Dashboard</a></li>
               @endif
            @else
            <li><a href="/login">Login</a></li>
            <!-- <li><a href="/register">Join</a></li> -->
           @endif
           
        </ul>

    </div>
</div>
