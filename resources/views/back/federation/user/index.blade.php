@extends('back/organization/layouts/master')

@section('title')
	Organization Users
@endsection

@section('page-nav')
	<h4>Organization Users</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item active">User Management</li>
	</ol>
@endsection

@section('content')
	<div class="box box-block bg-white">
		<h5 class="mb-1">
			All Your Users
			<a href="{{ route('users.create') }}" class="mr-auto btn btn-primary">New user</a>
		</h5>
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered table-2">
				<thead>
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th>Phone Number</th>
						<th>Status</th>
						<th>Date Added</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($users as $user)
						<tr>
							<td>{{ $user->name }}</td>
							<td>{{ $user->other_names }}</td>
							<td>{{ $user->msisdn }}</td>
							<td>
								@if($user->status)
									Active
								@else
									Inactive
								@endif
							</td>
							<td>{{ date('M j, Y', strtotime($user->created_at)) . ' at ' . date('H:i', strtotime($user->created_at)) }}</td>
							<td>
								<a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-primary" title="View User"><i class="far fa-eye"></i></a> &nbsp;
								{!! Form::open(['route' => ['users.destroy', $user->id], 'method' => 'DELETE', 'style' => 'display: inline-block']) !!}                                
                                   {{Form::button('<i class="fas fa-trash"></i>', array('type' => 'submit', 'class' => 'btn btn-sm btn-danger', 'title' => 'Delete'))}}
                                {!! Form::close() !!} &nbsp;&nbsp;
                                {{-- @if($user->status)
                                    {!! Form::open(['route' => ['user.deactivate', $user->id], 'method' => 'POST', 'style' => 'display: inline-block']) !!}                                
                                       {{Form::button('Deactivate', array('type' => 'submit', 'class' => 'btn btn-sm btn-danger', 'title' => 'Deactivate'))}}
                                    {!! Form::close() !!} &nbsp;&nbsp;
                                @else
									{!! Form::open(['route' => ['user.activate', $user->id], 'method' => 'POST', 'style' => 'display: inline-block']) !!}                                
                                       {{Form::button('Activate', array('type' => 'submit', 'class' => 'btn btn-sm btn-primary', 'title' => 'Activate'))}}
                                    {!! Form::close() !!} &nbsp;&nbsp;
                                @endif --}}
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection