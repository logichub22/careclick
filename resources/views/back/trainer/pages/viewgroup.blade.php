@extends('back/trainer/layouts/master')

@section('title')
	View Group
@endsection

@section('page-nav')
	<h4>{{ $group->name }}</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('federation.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item active">View Group</li>
	</ol>
@endsection

@section('content')
<div class="row">
		<div class="col-sm-4 col-md-3">
			<div class="box bg-white">
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
			<a href="#messageAdmin" data-toggle="modal" class="btn btn-primary">Send Message</a>
        </div>
		<div class="col-sm-8 col-md-9">
			<div class="box box-block bg-white">
				<h5 class="mb-1">Group Details</h5>
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
			</div>
		</div>
    </div>

    <br />
    <div class="row">
            <div class="col-md-12">
                <div class="box box-block bg-white">
                    <h5 class="mb-1">Group Members</h5>
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

		<div class="modal fade" id="messageAdmin" tabindex="-1" role="dialog" aria-labelledby="messageAdmin" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="modal-title">Send a message</h4>
						</div>
						<form action="{{ route('general.group-email') }}" method="POST">
							{{ csrf_field() }}
							<div class="modal-body">
								<input type="hidden" value="{{ $group->name }}" name="groupname" id="name">
								<input type="hidden" value="{{ $coordinator->email }}" name="coordinatoremail">
								<input type="hidden" value="{{ $coordinator->name }}" name="coordinatorname">
								<input type="hidden" value="{{ $group->id }}" name="group">
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
@endsection