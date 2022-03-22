@extends('back/individual/layouts/master')

@section('title')
	Group Detail
@endsection

@section('one-step')
    / Group / Detail
@endsection

@section('page-nav')
	<h4>Group Detail</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="{{ route('user-groups.index') }}">Groups</a></li>
		<li class="breadcrumb-item active">Groups i belong to</li>
	</ol>
@endsection

@section('content')

@endsection
