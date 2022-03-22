@extends('back/organization/layouts/master')

@section('title')
	My Groups
@endsection

@section('one-step')
    / Groups / Contributions
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
                    <h4 class="font-weight-light">Group contributions in {{ $organization->detail->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md text-left">
                            <thead>
								<tr>
									<th>Group Name</th>
									<th>Coordinator</th>
									<th>Member</th>
									<th>Status</th>
                                    <th>Amount</th>
									<th>Date of Creation</th>
								</tr>
							</thead>
							<!-- Fake Data -->
							<tbody>
								@foreach($groups as $group)
									<tr>
										<td>{{ $group->name }}</td>
										@if($group->admin)
	                                        <td>{{ $group->firstname }} {{ $group->lastname}}</td>
	                                    @else 
	                                        <td></td>
	                                    @endif
	                                    <!-- @if($group->admin !== 1)
	                                        <td>{{ $group->name }} {{ $group->lastname}}</td>
	                                    @else 
	                                        <td></td>
	                                    @endif -->
										<!-- td>Coordinator's Name</td>
	                                    <td>Member's Name</td>
										<td>Defaulted</td>
	                                    <td>NGN 50,000</td>
										<td>2nd June, 2020</td> -->
									</tr>
								@endforeach
							</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection