<div class="row">
	<div class="col-md-4">
		<div class="box box-block tile tile-2 bg-primary mb-2">
			<div class="t-icon right"><i class="fas fa-store-alt"></i></div>
			<div class="t-content">
				<h1 class="mb-1">{{ count($groups) }}</h1>
				<h6 class="text-uppercase">Groups</h6>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="box box-block tile tile-2 bg-success mb-2">
			<div class="t-icon right"><i class="far fa-money-bill-alt"></i></div>
			<div class="t-content">
				<h1 class="mb-1">{{ number_format($wallet->balance) }}</h1>
				<h6 class="text-uppercase">Balance</h6>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="box box-block tile tile-2 bg-danger mb-2">
			<div class="t-icon right"><i class="fa fa-users"></i></div>
			<div class="t-content">
				<h1 class="mb-1">0</h1>
				<h6 class="text-uppercase">Users</h6>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
			<div class="box box-block bg-white">
					<h5 class="mb-1">Groups Assigned To You</h5>
					<div class="table-responsive">
						<table class="table table-striped table-hover table-bordered table-2">
							<thead>
								<tr>
									<th>Group Name</th>
									<th>Coordinator</th>
									<th>Members</th>
									<th>Training Status</th>
									<th>Date Assigned</th>
									<th>Action</th>
								</tr>
							</thead>
			
							<!-- Fake Data -->
							<tbody>
									@foreach($groups as $group)
									<tr>
										<td>{{ $group->name }}</td>
										<td>{{ $group->firstname . ' ' . $group->othernames }}</td>
										<td>{{ \App\Models\General\GroupMember::where('group_id', $group->id)->count() }}</td>
										<td>
											@if($group->completed)
												Completed
											@else
												Ongoing
											@endif
										</td>
										<td>{{ date('M j, Y', strtotime($group->assigneddate)) . ' at ' . date('H:i', strtotime($group->assigneddate)) }}</td>
										<td>
											<a href="{{ route('trainer.viewgroup', $group->id) }}" class="btn btn-sm btn-primary" title="View Group">View</a> &nbsp;
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
	</div>
</div>

