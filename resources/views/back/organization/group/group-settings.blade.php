@extends('back/organization/layouts/master')

@section('title')
	Group Settings
@endsection

@section('one-step')
    / Group Settings
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
			<div class="card">
				<div class="card-body">
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
						<li class="nav-item">
							<a href="#addGroupAdmin" class="btn btn-success btn-block" data-toggle="modal">
								<i class="fas fa-user-shield"></i> Add Group Admin
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- <div class="col-md-8">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">Customize your group database</h5>
				</div>
				<div class="card-body">
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
		</div> -->
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">Customize Your Group contribution settings</h5>
				</div>
				<div class="card-body">
					<form action="{{ route('orggroup.contributions-settings') }}" method="POST">
						{{ csrf_field() }}
						<input type="hidden" name="group" value="{{ $group->id }}">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="value">Currency</label>
									<input type="hidden" name="currency" value="{{ $currency->prefix }}">
									<input type="text" class="form-control" value="{{ $currency->prefix }}" disabled>
									{{-- <select id="" class="form-control" name="currency">
										<option value="" disabled="" selected="">Select Currency</option>
										@foreach($currencies as $currency)
											<option value="{{ $currency->prefix }}">{{ $currency->prefix }}, {{ $currency->name }}</option>
										@endforeach
									</select> --}}
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="field">Amount</label>
									<input type="text" class="form-control" placeholder="Enter Contribution Amount" name="amount" {{ $group_contribution_settings != null ? "value={$group_contribution_settings->amount}" : '' }}>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="value">Frequency</label>
									<select id="" class="form-control" name="frequency">
										<option value="" disabled="" selected="">Select Frequency</option>
										<option value="weekly" {{ $frequency == "weekly" ? "selected" : "" }}>WEEKLY</option>
										<option value="monthly" {{ $frequency == "monthly" ? "selected" : "" }}>MONTHLY</option>
										<option value="yearly" {{ $frequency == "yearly" ? "selected" : "" }}>YEARLY</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-8 offset-md-2">
								<button type="submit" class="btn btn-primary btn-block">Save Settings</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('spec-scripts')
	<!-- Import Users Modal -->
	<div class="modal fade" id="addGroupAdmin" tabindex="-1" role="dialog" aria-labelledby="editGroup" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
							<form action="{{ route('set-group-admin') }}" method="POST">
									{{ csrf_field() }}
									<input type="hidden" name="group_id" value="{{ $group->id }}">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="value">Organization Users</label>
												<select id="" class="form-control" name="group_admin">
													<option value="" disabled="" selected="">Select Group Admin</option>
													@foreach( $users as $user)
														<option value="{{ $user->id }}">{{ $user->name }} {{ $user->other_names}}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-8 offset-md-2">
											<button type="submit" class="btn btn-primary btn-block">Set Group Admin</button>
										</div>
									</div>
								</form>
					</div>
				</div>
			</div>
		</div>
		<!--// End Edit Group Modal //-->
@endsection
