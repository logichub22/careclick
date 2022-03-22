@extends('front/layouts/master')

@section('title')
    Africa’s First Inclusive &amp; Intelligent Fintech Platform
@endsection

@section('body-class')
    index
@endsection

@section('content')
    @include('front/partials/_header')
    <section class="about--section">
        <div class="mt-container">
            <div class="mt-about-data">
                <div>
                    <h1 class="mt-style-title">About Jamborow</h1>
                    <p class="mt-p-data">
                        Jamborow is <span class="mt-regular">Africa’s first B2B AI and Blockchain driven fintech platform</span>
                        focused on <span class="mt-regular">financial inclusion</span> and <span class="mt-regular">grassroot empowerment</span>.
                    </p>
                    <p class="mt-p-data-2 uk-visible@m extra">
                        Jamborow is a Financial Technology platform that provides white label solutions (SaaS) to
                        provide access to financial services for informal, unbanked and underbanked sectors across
                        Africa to promote economic and financial inclusion.
                    </p>
                    <a class="uk-width-1-1@m uk-display-block uk-text-center uk-visible@m" href="/about">
                        <span class="mt-lucky">Read More</span>
                    </a>
                    <button class="uk-width-1-1@m uk-display-block uk-hidden@m uk-text-center" id="toggle-extra">
                        <span class="mt-lucky read-span">Read More</span>
                        <span class="mt-lucky uk-visible@m extra">Read Less</span>
                    </button>
                </div>
            </div>
        </div>
    </section>
    <section class="problem--solution">
        <div class="mt-container">
            <div class="mt-oppurtunities uk-width-1-1@m">
                <h1 class="mt-roch mt-title mt-white">The Problem</h1>
                <div class="mt-oppurtunity-list">
                    <div class="mt-oppurtunity">
                        <div class="uk-text-center uk-width-1-1@l">
                            <img src="{{ asset('img/unbanked.svg') }}" alt="">
                        </div>
                        <p class="mt-oppurtunity-details">
                            Massive unbanked population in Africa: According to the World Bank, 65% (approx. 400 million) of
                            the adult population are unbanked
                        </p>
                    </div>
                    <div class="mt-oppurtunity">
                        <div class="uk-text-center uk-width-1-1@l">
                            <img src="{{ asset('img/big_data.svg') }}" alt="">
                        </div>
                        <p class="mt-oppurtunity-details">
                            No insight and data-driven science around lending to attract big money for micro lenders.
                        </p>
                    </div>
                    <div class="mt-oppurtunity">
                        <div class="uk-text-center uk-width-1-1@l">
                            <img src="{{ asset('img/Savings.svg') }}" alt="">
                        </div>
                        <p class="mt-oppurtunity-details">
                            Licensed Savings Groups: Micro-saving and microlending groups across Africa are manually
                            operated and do not have access to the necessary tools for growth and serve their customers
                            efficiently.
                        </p>
                    </div>
                    <div class="mt-oppurtunity">
                        <div class="uk-text-center uk-width-1-1@l">
                            <img src="{{ asset('img/service.svg') }}" alt="">
                        </div>
                        <p class="mt-oppurtunity-details">
                            Banks cannot service this space.
                        </p>
                    </div>
                    <div class="mt-oppurtunity">
                        <div class="uk-text-center uk-width-1-1@l">
                            <img src="{{ asset('img/p2p.svg') }}" alt="">
                        </div>
                        <p class="mt-oppurtunity-details">
                            Peer-to-peer lenders (diaspora, individual lenders, etc.) do not have a platform to operate.
                        </p>
                    </div>
                    <div class="uk-invisible"></div>
                </div>
            </div>
            <div class="mt-problem uk-width-1-2@m">
                <h1 class="mt-roch mt-title mt-white">The Opportunities</h1>
                <ul class="uk-list uk-list-bullet uk-list-large">
                    <li>
                        Jamborow is digitizing KYC data of the micro-saving and lending schemes (Village)
                        creating credit footprint for financial inclusion.
                    </li>
                    <li>
                        Jamborow is Africa’s first B2B AI and Blockchain driven fintech platform focused on
                        financial inclusion and grassroot empowerment.
                    </li>
                    <li>
                        Users (the village) will be able to enjoy access to financial services afforded to
                        other population in developing countries. This greatly enables the concept of
                        financial inclusion services.
                    </li>
                </ul>
            </div>
            <div class="mt-solution uk-width-1-2@m">
                <div class="mt-sol-bg">
                    <h1 class="uk-margin-small-left mt-roch">The Solutions</h1>
                    <ul class="uk-list uk-list-bullet uk-list-small">
                        <li> Peer-to-peer lending facilitation</li>
                        <li> Digitization of Grassroot (Village) Data through: KYC, Credit Scoring
                        </li>
                        <li> AI engine leveraging Data science, and Big Data analytics.
                        </li>
                        <li> Massive opportunity to access financial services.
                        </li>
                    </ul>
                    {{--
                    <a class="uk-margin-large-top uk-display-block" href="#oppurtunities" uk-toggle>
                        <h3 class="mt-lucky">More</h3>
                    </a>
                    <div id="oppurtunities" class="uk-flex-top" uk-modal>
                        <div id='stars'></div>
                        <div id='stars2'></div>
                        <div id='stars3'></div>
                        <div class="uk-modal-dialog uk-padding uk-margin-auto-vertical">

                            <button class="uk-modal-close-default" type="button" uk-close></button>
                            <ul class="uk-list uk-list-bullet uk-list-small">
                                <li>
                                    Jamborow is digitizing KYC data of the micro-saving and lending schemes (Village)
                                    creating credit footprint for financial inclusion.
                                </li>
                                <li>
                                    Jamborow is Africa’s first B2B AI and Blockchain driven fintech platform focused on
                                    financial inclusion and grassroot empowerment.
                                </li>
                                <li>
                                    Users (the village) will be able to enjoy access to financial services afforded to
                                    other population in developing countries. This greatly enables the concept of
                                    financial inclusion services.
                                </li>
                            </ul>
                        </div>
                    </div>--}}
                </div>
            </div>
        </div>
    </section>
    <section class="features">
        <div class="mt-container">
            <h1 class="mt-lucky uk-width-1-1@m uk-margin-remove uk-text-center">Our Features</h1>
            <div class="mt-feature-list">
                <div class="mt-feature">
                    <img src="{{ asset('img/icon1.svg') }}" alt="">
                    <p class="mt-feature-details">
                        User ID and password protected third-party/channel partner portals with Access controls.
                    </p>
                </div>
                <div class="mt-feature">
                    <img src="{{ asset('asset/img/icon2.svg') }}" alt="">
                    <p class="mt-feature-details">
                        Credit check and proprietary credit scoring algorithm.
                    </p>
                </div>
                <div class="mt-feature">
                    <img src="{{ asset('asset/img/icon3.svg') }}" alt="">
                    <p class="mt-feature-details">
                        SMS messaging, USSD, Mobile App (Android and iOS).
                    </p>
                </div>
                <div class="mt-feature">
                    <img src="{{ asset('asset/img/icon4.svg') }}" alt="">
                    <p class="mt-feature-details">
                        Reporting capability with Business Intelligence.
                    </p>
                </div>
                <div class="mt-feature">
                    <img src="{{ asset('asset/img/icon5.svg') }}" alt="">
                    <p class="mt-feature-details">
                        Banner messaging capability to channel partner portals
                    </p>
                </div>
                <div class="mt-feature">
                    <img src="{{ asset('asset/img/icon6.svg') }}" alt="">
                    <p class="mt-feature-details">
                        Creation of wallets for all users
                    </p>
                </div>
                <div class="mt-feature">
                    <img src="{{ asset('asset/img/icon7.svg') }}" alt="">
                    <p class="mt-feature-details">
                        Codeshare API-enabled and pattern matching for KYC Data.
                    </p>
                </div>
                <div class="mt-feature">
                    <img src="{{ asset('asset/img/icon8.svg') }}" alt="">
                    <p class="mt-feature-details">
                        Integrated biometric capture capability.
                    </p>
                </div>
                <div class="mt-feature">
                    <img src="{{ asset('asset/img/icon9.svg') }}" alt="">
                    <p class="mt-feature-details">
                        AI (Robo Advisor) algorithm integration with blockchain KYC.
                    </p>
                </div>
            </div>

            <div class="mt-partners">
                <h3 class="mt-gradient-text">
                    Our Trusted Partners
                </h3>
                <div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1"
                     uk-slider="autoplay: true; autoplay-interval: 2000"
                     uk-scrollspy="cls: uk-animation-slide-bottom; repeat: true">
                    <ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-5@m">
                        <li>
                            <img src="{{ asset('asset/img/partner1.png') }}" alt=""/>
                        </li>
                        <li>
                            <img src="{{ asset('asset/img/partner2.png') }}" alt=""/>
                        </li>
                        <li>
                            <img src="{{ asset('asset/img/partner3.png') }}" alt=""/>
                        </li>
                        <li>
                            <img src="{{ asset('asset/img/partner4.png') }}" alt=""/>
                        </li>
                        <li>
                            <img src="{{ asset('asset/img/partner5.png') }}" alt=""/>
                        </li>
                        <li>
                            <img src="{{ asset('asset/img/partner6.png') }}" alt=""/>
                        </li>
                        <li>
                            <img src="{{ asset('asset/img/partner7.png') }}" alt=""/>
                        </li>
                        <li>
                            <img src="{{ asset('asset/img/partner10.png') }}" alt=""/>
                        </li>
                        <li>
                            <img src="{{ asset('asset/img/partner11.png') }}" alt=""/>
                        </li>
                        <li>
                            <img src="{{ asset('asset/img/partner12.png') }}" alt=""/>
                        </li>
                        <li>
                            <img src="{{ asset('asset/img/partner13.png') }}" alt=""/>
                        </li>
                        <li>
                            <img src="{{ asset('asset/img/partner14.png') }}" alt=""/>
                        </li>
                        <li>
                            <img src="{{ asset('asset/img/partner15.png') }}" alt=""/>
                        </li>
                        <li>
                            <img src="{{ asset('asset/img/partner16.png') }}" alt=""/>
                        </li>
                        <li>
                            <img src="{{ asset('asset/img/partner17.png') }}" alt=""/>
                        </li>
                        <li>
                            <img src="{{ asset('/img/partner18.png') }}" alt=""/>
                        </li>
                        <li>
                            <img src="{{ asset('asset/img/partner19.png') }}" alt=""/>
                        </li>
                    </ul>

                    <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous
                       uk-slider-item="previous">
                    </a>
                    <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next
                       uk-slider-item="next">
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
