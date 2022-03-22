@extends('front/layouts/master')

@section('title')
    About Us
@endsection

@section('body-class')
    about
@endsection

@section('content')
    @include('front/partials/about/_header')
    @include('front/partials/about/_advantage')
    @include('front/partials/about/_proposition')
    @include('front/partials/about/_social_impact')
    @include('front/partials/about/_ecosystem')
    @include('front/partials/about/_team')
    @include('front/partials/about/_faq')
    @include('front/partials/about/_articles')
    @include('front/partials/_modals')
@endsection
