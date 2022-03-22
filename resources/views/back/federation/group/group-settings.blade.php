@extends('back/organization/layouts/master')

@section('title')
	Group Settings
@endsection

@push('styles')
	<style>
		label{
			font-weight: bold;
		}
	</style>
@endpush

@section('page-nav')
	<h4>Group Settings</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="#">Groups</a></li>
		<li class="breadcrumb-item"><a href="{{ route('groups.index') }}">My Groups</a></li>
		<li class="breadcrumb-item active">Settings</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-4">
			<div class="box bg-white">
				<ul class="nav nav-4">
					<li class="nav-item">
						<a class="nav-link">
							<i class="fa fa-home"></i> {{ $group->name }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link">
							<i class="far fa-calendar-alt"></i> Created {{ date('M j, Y', strtotime($group->created_at)) . ' at ' . date('H:i', strtotime($group->created_at)) }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('groups.show', $group->id) }}">
							<i class="fas fa-tachometer-alt"></i> Back to group portal
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('orggroup.addmember', $group->id) }}">
							<i class="fas fa-user-plus"></i> Back to add members
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-md-8">
			<div class="box box-block bg-white">
				<h5 class="mb-1">Customize your group database</h5>
				<form action="{{ route('group-data') }}" method="POST">
					{{ csrf_field() }}
					<input type="hidden" name="group" value="{{ $group->id }}">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="field">Display Text</label>
								<input type="text" class="form-control" placeholder="Field Name" name="field">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="value">Data Type</label>
								<select id="" class="form-control" name="datatype">
									<option value="" disabled="" selected="">Select data type</option>
									<option value="string">STRING</option>
									<option value="text">TEXT</option>
									<option value="integer">INTEGER</option>
									<option value="date">DATE</option>
									<option value="float">FLOAT</option>
									<option value="double">DOUBLE</option>
									<option value="boolean">BOOLEAN</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8 offset-md-2">
							<button type="submit" class="btn btn-primary btn-block">Add Data</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection