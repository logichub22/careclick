@extends('front/layouts/master')

@section('title')
    Contact Us
@endsection

@section('body-class')
    contact--us
@endsection

@section('content')
    <main>
        <div class="mt-container">
            <h4 class="mt-title uk-width-1-1@l mt-white uk-margin-remove">Contact Us</h4>
            <p class="mt-white uk-width-1-1@l uk-margin-remove mt-light">Please indicate the nature of your inquiry and
                fill the form below</p>
            <div class="uk-width-1-1@m uk-flex uk-flex-wrap uk-child-width-1-3@m">
                <div class="uk-width-2-3@m contact--form">
                    <div>
                        <form action="#">
                            <div class="uk-margin-top uk-margin">
                                <input class="uk-input uk-width-4-5@m" type="text" placeholder="Your Name">
                            </div>
                            <div class="uk-margin-top uk-margin">
                                <input class="uk-input uk-width-4-5@m" type="text" placeholder="Company Name">
                            </div>
                            <div class="uk-margin-top uk-margin">
                                <input class="uk-input uk-width-4-5@m" type="text" placeholder="Email">
                            </div>
                            <div class="uk-margin-top uk-margin">
                                <input class="uk-input uk-width-4-5@m" type="text" placeholder="Phone Number">
                            </div>
                            <div class="uk-margin-top uk-margin">
                                <textarea name="message" cols="20" rows="5" placeholder="Message"
                                          class="uk-textarea uk-width-4-5@m">{{ old('message') }}</textarea>
                                @if($errors->has('message'))
                                    <span class="invalid-feedback" role="alert">
                                                {{ $errors->first('message') }}
                                            </span>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
                <div class="contact--card">
                    <div class="mt-trans-card">
                        <div>
                            <div class="img--side">
                                <img src="{{ asset('img/at_email.png') }}" alt="">
                            </div>
                            <div class="write--side">
                                <ul class="uk-list">
                                    <li>info@jamborow.co.uk</li>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <div class="img--side">
                                <img src="{{ asset('img/call.png') }}" alt="">
                            </div>
                            <div class="write--side">
                                <ul class="uk-list">
                                    <li>+1(832)855-8808</li>
                                    <li>+234 909 328 9108</li>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <div class="img--side">
                                <img src="{{ asset('img/address.png') }}" alt="">
                            </div>
                            <div class="write--side">
                                <ul class="uk-list">
                                    <li>
                                        Jamborow UK Ltd 71-75 Shelton Street Covent Garden, London
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <div class="img--side">
                                <img src="{{ asset('img/locations.png') }}" alt="">
                            </div>
                            <div class="write--side">
                                <ul class="uk-list">
                                    <li>
                                        Kenya, Tanzania, Nigeria, Liberia, Sierra Leone, Uganda and Botswana.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--<div class="uk-flex uk-child-width-1-3@m uk-flex-wrap uk-flex-middle">
            <div class="uk-padding-small form-box">
                <h3 class="shadow-title c-black uk-margin-remove">Contact <span class="color-primary">Us</span>
                </h3>
                    <p class="support uk-margin-remove">For support or any questions:</p>
                    <p class="email uk-margin-remove">Email us at <a href="mailto:info@jamborow.co.uk">info@jamborow.co.uk</a></p>
                    <h5 class="info-header uk-margin-remove-bottom uk-text-bold">Reach us through:</h5>
                    <p class="uk-margin-remove">+1(832)855-8808</p>
                    <p class="uk-margin-remove">+234 909 328 9108</p>
                    <h5 class="info-header uk-margin-remove-bottom">We are located in:</h5>
                    <p class="uk-margin-bottom uk-margin-remove-top">Kenya, Tanzania, Nigeria, Liberia, Sierra Leone, Uganda and Botswana.</p>
                <h5 class="info-header uk-margin-remove uk-text-bold">Business Address:</h5>
                <p class="uk-margin-remove">Jamborow UK Ltd</p>
                <p class="uk-margin-remove">71-75 Shelton Street</p>
                <p class="uk-margin-remove">Covent Garden</p>
                <p class="uk-margin-remove">London</p>
                <p class="uk-margin-small-bottom">WC2H 9JQ</p>
                @include('alert/messages')
                <form action="{{ route('postmessage') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }} contact-input" name="company" placeholder="Company Name" value="{{ old('company') }}">
                                @if($errors->has('company'))
                                    <span class="invalid-feedback" role="alert">
                                                {{ $errors->first('company') }}
                                            </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }} contact-input" name="name" placeholder="Contact Name" value="{{ old('name') }}">
                                @if($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                                {{ $errors->first('name') }}
                                            </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} contact-input" name="email" placeholder="Email" value="{{ old('email') }}">
                                @if($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                                {{ $errors->first('email') }}
                                            </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="tel" class="form-control{{ $errors->has('telephone') ? ' is-invalid' : '' }} contact-input" name="telephone" placeholder="Telephone" value="{{ old('telephone') }}">
                                @if($errors->has('telephone'))
                                    <span class="invalid-feedback" role="alert">
                                                {{ $errors->first('telephone') }}
                                            </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea name="message" cols="20" rows="5" placeholder="Message" class="form-control{{ $errors->has('message') ? ' is-invalid' : '' }} contact-input">{{ old('message') }}</textarea>
                                @if($errors->has('message'))
                                    <span class="invalid-feedback" role="alert">
                                                {{ $errors->first('message') }}
                                            </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" disabled class="btn btn-primary btn-block contact-submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>--}}
    </main>
@endsection
