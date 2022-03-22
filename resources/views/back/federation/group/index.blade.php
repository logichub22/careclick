@extends('back/organization/layouts/master')

@section('title')
	My Groups
@endsection

@section('page-nav')
	<h4>My Groups</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="#">Groups</a></li>
		<li class="breadcrumb-item active">My Groups</li>
	</ol>
@endsection

@section('content')
	<div class="box box-block bg-white">
		<h5 class="mb-1">Groups I Own</h5>
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered table-2">
				<thead>
					<tr>
						<th>Group Name</th>
						<th>Comment</th>
						<th>Status</th>
						<th>Date of Creation</th>
						<th>Action</th>
					</tr>
				</thead>

				<!-- Fake Data -->
				<tbody>
					@if(count($groups) > 0)
						@foreach($groups as $group)
							<tr>
								<td>{{ $group->name }}</td>
								<td>{{ $group->comment }}</td>
								<td>
									@if($group->status)
										Active
									@else
										Inactive
									@endif
								</td>
								<td>{{ date('M j, Y', strtotime($group->created_at)) . ' at ' . date('H:i', strtotime($group->created_at)) }}</td>
								<td>
									<a href="{{ route('groups.show', $group->id) }}" class="btn btn-sm btn-primary" title="View Group"><i class="fa fa-eye"></i></a> &nbsp;
									<a href="{{ route('orggroupsets', $group->id) }}" class="btn btn-sm btn-success" title="Group Settings"><i class="fas fa-cogs"></i></a> &nbsp;
								</td>
							</tr>
						@endforeach
					@else
						<tr>
							<td class="alert alert-warning align-center" colspan="6">No groups available! <a href="{{ route('groups.create') }}">Create one here</a></td>
						</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
	<div class="box box-block bg-white">
		<h5 class="mb-1">Groups I Belong To</h5>
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered table-2">
				<thead>
					<tr>
						<th>Group Name</th>
						<th>Comment</th>
						<th>Your Account Status</th>
						<th>Action</th>
					</tr>
				</thead>

				<!-- Fake Data -->
				<tbody>
					@foreach($membergroups as $group)
						<tr>
							<td>{{ $group->name }}</td>
							<td>{{ $group->comment }}</td>
							<td>
								@if($group->memberstatus)
									Active
								@else
									Inactive
								@endif
							</td>
							<td>{{ date('M j, Y', strtotime($group->created_at)) . ' at ' . date('H:i', strtotime($group->created_at)) }}</td>
							<td>
								<a href="{{ route('groups.show', $group->id) }}" class="btn btn-sm btn-primary" title="View Group"><i class="fa fa-eye"></i></a> &nbsp;
								{{-- <a href="{{ route('org-groups.') }}" class="btn btn-sm btn-success" title="Edit Group"><i class="fa fa-pencil"></i></a> &nbsp; --}}
								<a href="" class="btn btn-sm btn-danger" title="Delete Group"><i class="fa fa-trash"></i></a> &nbsp;
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection