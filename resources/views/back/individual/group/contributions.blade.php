@extends('back/individual/layouts/master')

@section('title')
	My Contributions
@endsection

@section('one-step')
    / Groups / Contributions
@endsection

@section('page-nav')
	<h4>My Groups</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="#">Home</a></li>
		<li class="breadcrumb-item"><a href="#">Groups</a></li>
		<li class="breadcrumb-item active">My Groups</li>
	</ol>
@endsection

@section('content')
	<div class="row">
        <div class="w-100">
            <div class="card">
                <div class="card-header">
                    <h4 class="font-weight-light">My Group Contributions</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md text-left">
                            <thead>
								<tr>
									<th>Group Name</th>
									<th>Organization</th>
									<th>Status</th>
                                    <th>Amount</th>
									<th>Date of Contribution</th>
								</tr>
							</thead>
							<!-- Fake Data -->
							<tbody>
								<!-- <tr>
									<td>Group Name</td>
									<td>Organization's Name</td>
									<td>Defaulted</td>
                                    <td>NGN 50,000</td>
									<td>2nd June, 2020</td>
								</tr> -->
								@if(count($groups) > 0)
									@foreach($groups as $group)
										<tr>
											<td>{{$group->name}}</td>
											<td>{{ $organization->name }}</td>
											<td>
												@if($group->status == 1)
													Paid
												@else
													Defaulted
												@endif
											</td>
											<td>{{ $group->amount }}</td>
											<td>{{ $group->date_of_contribution }}</td>
										</tr>
									@endforeach
								@endif
							</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection