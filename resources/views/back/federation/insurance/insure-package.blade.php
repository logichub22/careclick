@extends('back/organization/layouts/master')

@section('title')
	Insure Package
@endsection

@section('page-nav')
	<h4>Insure {{ $package->name }} Package</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="#">Lending</a></li>
		<li class="breadcrumb-item active">Insure Package</li>
	</ol>
@endsection

@section('content')

@endsection