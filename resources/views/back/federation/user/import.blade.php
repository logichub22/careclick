@extends('back/organization/layouts/master')

@section('title')
	Import Users
@endsection

@section('page-nav')
	<h4>Organization Users</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="{{ route('users.index') }}"></a>User Management</li>
		<li class="breadcrumb-item active">Import Users</li>
	</ol>
@endsection

@section('content')

@endsection