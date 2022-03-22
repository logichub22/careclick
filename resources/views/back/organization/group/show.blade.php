@extends('back/organization/layouts/master')

@section('title')
	View Group
@endsection

@section('one-step')
    / View Group
@endsection

@section('spec-styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
<div class="row">
		<div class="col-sm-4 col-md-3">
			<a href="#messageAdmin" data-toggle="modal" class="btn btn-primary">Send Message</a>
			&nbsp;
			@if(is_null($group->trainer_id))
				<a href="#assignTrainer" data-toggle="modal" class="btn btn-success">Assign Trainer</a>
			@else
				<a href="#changeTrainer" data-toggle="modal" class="btn btn-success">Change Trainer</a>
			@endif
			<br /> <br />
				<a href="{{ route('orggroup.addmember', $group->id) }}" class="btn btn-primary">Add Member</a>
            <br /> <br />
			<div class="card">
				<div class="card-body">
					<ul class="nav nav-4">
					<li class="nav-item">
						<a class="nav-link" href="#">
							Date Created: {{ $group->created_at }} 
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							Group Status: 
							@if($group->status)
								Active
							@else
								Inactive
							@endif
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							Members: {{ count($members) }} 
						</a>
                    </li>
                    <li class="nav-item">
                    	<a class="nav-link btn btn-success" href="{{ route('org.groupmessage', $group->id) }}">Message Members</a>
                    </li>
                    @if(count($regions_arr) === 4)
                        @foreach($regions_arr as $key => $value)
                            @if($key == 0)
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <strong>{{ $value }}</strong>: {{ $group->level_one }} 
                                    </a>
                                </li>
                            @elseif($key == 1)
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <strong>{{ $value }}</strong>: {{ $group->level_two }} 
                                    </a>
                                </li>
                            @elseif($key == 2)
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <strong>{{ $value }}</strong>: {{ $group->level_three == null ? 'None' : $group->level_three }} 
                                    </a>
								</li>
							@elseif($key == 3)
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <strong>{{ $value }}</strong>: {{ $group->level_four == null ? 'None' : $group->level_four }} 
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @endif
				</ul>
				</div>
			</div>
        </div>
		<div class="col-sm-8 col-md-9">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">Group Details</h5>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<strong>Group Name</strong>
								<p>{{ $group->name }}</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<strong>Account Number</strong>
								<p>{{ $group->account_no == null ? 'Not Available' : $group->account_no }}</p>
							</div>
	                    </div>
	                    <div class="col-md-4">
	                        <div class="form-group">
	                            <strong>Bank Account?</strong>
	                            <p>{{ $group->bank == true ? 'Yes' : 'No' }}</p>
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
	                        <div class="col-md-4">
	                            <div class="form-group">
	                                <strong>Account Number</strong>
	                                <p>{{ $group->account_no == null ? 'Not Available' : $group->account_no }}</p>
	                            </div>
	                        </div>
	                        <div class="col-md-4">
	                            <div class="form-group">
	                                <strong>Bank Name</strong>
	                                <p>{{ $group->bank_name == null ? 'Not Available' : $group->bank_name }}</p>
	                            </div>
	                        </div>
	                        <div class="col-md-4">
	                            <div class="form-group">
	                                <strong>Bank branch</strong>
	                                <p>{{ $group->bank_branch == null ? 'Not Available' : $group->bank_branch }}</p>
	                            </div>
	                        </div>
	                    </div>
					<hr>
					<h5 class="mb-1">Coordinator Details</h5>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<strong>Name</strong>
								<p>{{ $coordinator->name . ' ' . $coordinator->other_names }}</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<strong>Phone Number</strong>
								<p>+{{ $coordinator->msisdn }}</p>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<strong>Email</strong>
								<p>
									{{ $coordinator->email }}
								</p>
							</div>
						</div>
					</div>
					<hr>
					<h5 class="mb-1">Trainer Details</h5>
					@if(is_null($trainingofficer)) 
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<p>No trainer assigned</p>
								</div>
							</div>
						</div>
					@else
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<strong>Name</strong>
									<p>{{ $trainingofficer->name . ' ' . $trainingofficer->other_names }}</p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<strong>Phone Number</strong>
									<p>+{{ $trainingofficer->msisdn }}</p>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<strong>Email</strong>
									<p>
										{{ $trainingofficer->email }}
									</p>
								</div>
							</div>
						</div>
					@endif
				</div>
			</div>
		</div>
    </div>

    <br />
    <div class="row">
            <div class="col-md-12">
                <div class="card">
                	<div class="card-header">
                		<h5 class="mb-1">Group Members</h5>
                	</div>
                	<div class="card-body">
                		<div class="table-responsive">
                        <table class="table table-bordered table-hover table-2">
                            <thead>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Phone Number</th>
                                <th>Member Since</th>
                            </thead>
                            <tbody>
                                @foreach ($members as $member)
                                    <tr>
                                        <td>{{ $member->name . ' ' . $member->other_names }}</td>
                                        <td>{{ $member->email }}</td>
                                        <td>
                                            @if($member->admin)
                                                <span class="badge badge-primary">Coordinator</span>
                                            @else 
                                                <span class="label label-primary">Member</span>
                                            @endif
                                        </td>
                                        <td>{{ $member->msisdn }}</td>
                                        <td>{{ $member->membercreated }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                	</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                	<div class="card-header">
                		<h5 class="mb-1">Group Contributions</h5>
                	</div>
                	<div class="card-body">
                		<div class="table-responsive">
                        <table class="table table-bordered table-hover table-2">
                            <thead>
                                <th>Name</th>
								<th>Email</th>
								<th>Phone Number</th>
								<th>Status</th>
                                <th>Amount</th>
                            </thead>
                            <tbody>
                                @foreach($group_contributions as $contribution)
                                	<tr>
                                		<td>{{ $contribution->firstname }} {{ $contribution->lastname }}</td>
                                		<td>{{ $contribution->email }}</td>
                                		<td>{{ $contribution->phone }}</td>
                                		@if($contribution->status == 1)
	                                		<td>Paid</td>
	                                	@else
	                                		<td>Defaulted</td>
	                                	@endif
	                                	<td>{{ $contribution->currency }} {{ $contribution->amount }}</td>
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
@section('spec-scripts')
	<!-- Edit Group Modal -->
	<div class="modal fade" id="messageAdmin" tabindex="-1" role="dialog" aria-labelledby="messageAdmin" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form action="{{ route('general.group-email') }}" method="POST">
						{{ csrf_field() }}
						<div class="modal-body">
							<input type="hidden" value="{{ $group->name }}" name="groupname" id="name">
                            <input type="hidden" value="{{ $coordinator->email }}" name="coordinatoremail">
							<input type="hidden" value="{{ $coordinator->name }}" name="coordinatorname">
							<input type="hidden" value="{{ $group->id }}" name="group">
							@if(!is_null($trainingofficer))
								<input type="hidden" value="{{ $trainingofficer->email }}" name="traineremail">
								<input type="hidden" value="{{ $trainingofficer->name }}" name="trainername">
							@endif
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="subject">Subject</label>
										<input type="text" class="form-control" name="subject" value="{{ old('subject') }}" placeholder="Subject of your message" required>
									</div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="subject">Recipient</label>
                                        <select name="recipient" class="form-control" required id="">
                                            <option value="" disabled selected>Select Recipient</option>
                                            <option value="1">Coordinator ({{ $coordinator->name . ' ' . $coordinator->other_names }})</option>
											<option value="0" {{ count($members) < 2 ? ' disabled' : '' }}>All Members</option>
											<option value="2" {{ $group->trainer_id == null ? ' disabled' : '' }}>Trainer ({{ $group->trainer_id == null ? ' No trainer assigned' : $trainingofficer->name . ' ' . $trainingofficer->other_names }})</option>
                                        </select>
                                    </div>
                                </div>
								<div class="col-md-12">
									<div class="form-group">
										<label for="message">Message Body</label>
										<textarea name="message" id="" cols="30" rows="6" class="form-control" placeholder="Send Message" required></textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Send Message</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	<!--// End Edit Group Modal //-->

		@if(is_null($group->trainer_id))
		<div class="modal fade" id="assignTrainer" tabindex="-1" role="dialog" aria-labelledby="messageAdmin" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<form action="{{ route('association.assign-trainer') }}" method="POST" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="modal-body">
								<input type="hidden" value="{{ $group->id }}" name="group" id="name">
								@if(count($trainers_arr) < 1) 
									<div class="alert alert-warning">
										No trainers are registered under the federation you belong to
									</div>
								@endif
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="subject">Trainer <span class="important">*</span></label>
											<select name="trainer" id="" class="form-control{{ $errors->has('trainer') ?' is-invalid' : '' }}" required>
												<option value="" disabled selected>Select Trainer</option>
												@foreach($trainers_arr as $trainer)
													<option value="{{ $trainer->id }}">{{ $trainer->name . ' ' . $trainer->other_names }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label for="subject">Training Document</label>
											<input type="file" name="training_doc" class="form-control{{ $errors->has('training_doc') ? ' is-invalid' : '' }}">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label for="message">Any comments?</label>
											<textarea name="message" id="" cols="30" rows="6" class="form-control" placeholder="Additional comments you might have for trainer"></textarea>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="submit" {{ count($trainers_arr) < 1 ? ' disabled' : '' }} class="btn btn-primary">Assign</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!--// End Edit Group Modal //-->
		@else
		<div class="modal fade" id="changeTrainer" tabindex="-1" role="dialog" aria-labelledby="messageAdmin" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="modal-title">Change Trainer</h4>
						</div>
						<form action="{{ route('association.change-trainer') }}" method="POST" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="modal-body">
								<input type="hidden" value="{{ $group->id }}" name="group" id="name">
								@if(count($trainers_arr) < 1) 
									<div class="alert alert-warning">
										No trainers are registered under the federation you belong to
									</div>
								@endif
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="subject">Trainer <span class="important">*</span></label>
											<select name="trainer" id="" class="form-control{{ $errors->has('trainer') ?' is-invalid' : '' }}" required>
												<option value="" disabled selected>Select Trainer</option>
												@foreach($trainers_arr as $trainer)
													<option value="{{ $trainer->id }}">{{ $trainer->name . ' ' . $trainer->other_names }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label for="subject">Training Document</label>
											<input type="file" name="training_doc" class="form-control{{ $errors->has('training_doc') ? ' is-invalid' : '' }}">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label for="message">Any comments?</label>
											<textarea name="message" id="" cols="30" rows="6" class="form-control" placeholder="Additional comments you might have for trainer">{{ \Illuminate\Support\Facades\DB::table('group_trainers')->where('group_id', $group->id)->value('message') }}</textarea>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="submit" {{ count($trainers_arr) < 1 ? ' disabled' : '' }} class="btn btn-primary">Change</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!--// End Edit Group Modal //-->
		@endif
@endsection

@section('spec-scripts')
	<script>
		$(document).ready(function() {
			$('#trainer').on('change', function() {
				
			})
		})
	</script>

    <script src="{{ asset('assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/datatables.js') }}"></script>
@endsection
