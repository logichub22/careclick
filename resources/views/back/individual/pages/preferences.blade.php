@extends('back/individual/layouts/master')

@section('title')
	@lang('individual.preferences')
@endsection

@section('page-nav')
	<h4>@lang('individual.preferences')</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang('individual.home')</a></li>
		<li class="breadcrumb-item active">@lang('individual.preferences')</li>
	</ol>
@endsection

@section('content')

@endsection