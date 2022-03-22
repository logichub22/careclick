@extends('back/organization/layouts/master')

@section('title')
	Invite Members
@endsection

@section('page-nav')
	<h4>Invite Members</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="{{ route('groups.index') }}"></a>Groups</li>
		<li class="breadcrumb-item active">Invite Members</li>
	</ol>
@endsection

@section('content')
	<div class="col-md-12">
		<div class="box box-block bg-white">
			<h5 class="mb-1">Invite Message</h5>
		</div>
	</div>
@endsection