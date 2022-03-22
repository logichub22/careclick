@extends('auth/layouts/auth')

@section('title')
    Register
@endsection

@section('content')
    <img src="{{ asset('img/front/auth/rectangle.svg') }}" alt="Left Image" class="left-img">
    <img src="{{ asset('img/front/auth/dot-brown.svg') }}" alt="Right Image" class="right-img">
    <div class="background">
        <div class="auth-container">
            <div class="auth-main-content">
                @include('auth/partials/_navbar')

                <h3 class="auth-header">Create account</h3>

                <div class="row no-gutters">
                    <div class="col-md-5 reg-cols col-individual">
                        <div class="container-fluid">
                           <img src="{{ asset('img/front/auth/individual-icon.png') }}" alt="Individual Icon" class="user-icon">
                            <h3 class="sign-up">Sign up as an Individual</h3>
                            <h3 class="time">Takes 1-3mins</h3>
                            <h3 class="text-right">
                                <a href="{{ route('individual-signup') }}"><i class="fas fa-arrow-right"></i></a>
                            </h3> 
                        </div>
                        
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-5 reg-cols col-organization">
                        <div class="container-fluid">
                            <img src="{{ asset('img/front/auth/individual-icon.png') }}" alt="Organization Icon" class="user-icon">
                            <h3 class="sign-up">Sign up as an Organization</h3>
                            <h3 class="time">Takes 2-5mins</h3>
                            <h3 class="text-right">
                                <a href="{{ route('organization-signup') }}"><i class="fas fa-arrow-right"></i></a>
                            </h3>    
                        </div> 
                    </div>
                </div>
            </div>
        </div>

        <div class="has-footer">
            @include('auth/partials/_footer')
        </div>
    </div>
@endsection