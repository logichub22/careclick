@extends('back/organization/layouts/master')

@section('title')
	My Groups
@endsection

@section('one-step')
    / Groups
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
	<div class="row">
        <div class="w-100">
            <div class="card">
                <div class="card-header">
                    <h4 class="font-weight-light">Groups in {{ $organization->detail->name }}</h4>
                    <div class="card-header-action">
                        <a href="{{ route('groups.create') }}" class="btn btn-danger btn-icon icon-right">Create
                            Group<i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md text-left">
                            <thead>
								<tr>
									<th>Group Name</th>
									<!-- <th>Coordinator</th> -->
									<th>Members</th>
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
											<td>{{ \App\Models\General\GroupMember::where('group_id', $group->id)->count() }}</td>
											<td>
												@if($group->status)
													Active
												@else
													Inactive
												@endif
											</td>
											<td>{{ date('M j, Y', strtotime($group->created_at)) . ' at ' . date('H:i', strtotime($group->created_at)) }}</td>
											<td>
												<a href="{{ route('groups.show', $group->id) }}" class="btn btn-sm btn-primary" title="View Group">View</a> &nbsp;
                                                <a href="{{ route('orggroupsets', $group->id) }}" class="btn btn-sm btn-primary" title="Group Settings">Settings</a> &nbsp;
												@if($group->status)
													 {!! Form::open(['route' => ['association.deactivate-group', $group->id],'method' => 'POST', 'style' => 'display: inline-block', 'id' => 'renewForm']) !!}                             
														<button type="submit" class="btn btn-danger btn-sm" title="Deactivate Group">Deactivate Group</button>
													{!! Form::close() !!}
												@else
													{!! Form::open(['route' => ['association.activate-group', $group->id],'method' => 'POST', 'style' => 'display: inline-block', 'id' => 'renewForm']) !!}                               
														<button type="submit" class="btn btn-success btn-sm" title="Activate Group">Activate Group</button>
													{!! Form::close() !!}
												@endif
											</td>
										</tr>
									@endforeach
                                @endif
							</tbody>
                            <!-- <tr>
                                <td>Irwansyah Saputra</td>
                                <td>Loan for day to day stuff</td>
                                <td>
                                    <div class="badge badge-success">Active</div>
                                </td>
                                <td>2017-01-09</td>
                                <td>
                                    <a href="#" class="btn btn-info mr-1">View</a>
                                    <a href="#" class="btn btn-dark mr-1">Edit</a>
                                    <a href="#" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Hasan Basri</td>
                                <td>Loan for day to day stuff</td>
                                <td>
                                    <div class="badge badge-success">Active</div>
                                </td>
                                <td>2017-01-09</td>
                                <td>
                                    <a href="#" class="btn btn-info mr-1">View</a>
                                    <a href="#" class="btn btn-dark mr-1">Edit</a>
                                    <a href="#" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Kusnadi</td>
                                <td>Loan for day to day stuff</td>
                                <td>
                                    <div class="badge badge-danger">Not Active</div>
                                </td>
                                <td>2017-01-11</td>
                                <td>
                                    <a href="#" class="btn btn-info mr-1">View</a>
                                    <a href="#" class="btn btn-dark mr-1">Edit</a>
                                    <a href="#" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Rizal Fakhri</td>
                                <td>Loan for day to day stuff</td>
                                <td>
                                    <div class="badge badge-success">Active</div>
                                </td>
                                <td>2017-01-11</td>
                                <td>
                                    <a href="#" class="btn btn-info mr-1">View</a>
                                    <a href="#" class="btn btn-dark mr-1">Edit</a>
                                    <a href="#" class="btn btn-danger">Delete</a>
                                </td>
                            </tr> -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection