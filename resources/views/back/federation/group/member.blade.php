@extends('back/organization/layouts/master')

@section('title')
	View Member
@endsection

@section('page-nav')
	<h4>View Member</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="{{ route('groups.index') }}">My Groups</a></li>
		<li class="breadcrumb-item active">View Member</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-3 col-md-4">
			<div class="box box-block bg-white">
				<h5 class="mb-1">Member Details</h5>
				<p>
					 Name: {{ $user->name . ' ' . $user->other_names }}
				</p>
				<p>
					 Email: {{ $user->email }}
				</p>
				<p>
					Phone: {{ $user->msisdn }}
				</p>
				<p>
					Account Number: {{ $user->account_no }}
				</p>
				<p>
					 Added On: {{ $user->created_at }}
				</p>
				<p>
					 Group Status: 
					 @if($user->memberstatus)
					 	Active Member
					 @else
						Inactive Member
					 @endif
				</p>
				{{-- <p>
					 Invite Status: 
					 @if($user->invitestatus)
					 	Accepted
					 @else
						Pending Acceptance
					 @endif
				</p> --}}
				<div class="row">
					<div class="col-sm-12 col-md-6">
						@if($user->memberstatus)
					 		{{-- <a href="#" class="btn btn-danger btn-block" id="cancel">Cancel Membership</a> --}}
					 		{!! Form::open(['route' => ['cancelmember', $user->memberid],'method' => 'POST', 'style' => 'display: inline-block', 'id' => 'cancelForm']) !!}  
					 		   <input type="hidden" name="group" value="{{ $group->name }}">   
					 		   <input type="hidden" value="{{ $user->name }}" name="member_name">
							   <input type="hidden" value="{{ $user->email }}" name="email">                           
                               {{Form::button('Cancel Membership', array('id'=> 'cancel', 'class' => 'btn btn-block btn-warning', 'title' => 'Cancel Membership'))}}
                            {!! Form::close() !!}
						@else
							{!! Form::open(['route' => ['renewmember', $user->memberid],'method' => 'POST', 'style' => 'display: inline-block', 'id' => 'renewForm']) !!} 
							   <input type="hidden" name="group" value="{{ $group->name }}">   
					 		   <input type="hidden" value="{{ $user->name }}" name="member_name">
							   <input type="hidden" value="{{ $user->email }}" name="email">                               
                               {{Form::button('Renew Membership', array('id'=> 'renew', 'class' => 'btn btn-block btn-primary', 'title' => 'Renew Membership'))}}
                            {!! Form::close() !!}
						@endif
					</div>
					<div class="col-sm-12 col-md-6">
						{!! Form::open(['route' => ['deletemember', $user->memberid],'method' => 'POST', 'style' => 'display: inline-block', 'id' => 'deleteForm']) !!}  
							<input type="hidden" name="group" value="{{ $group->name }}">   
				 		    <input type="hidden" value="{{ $user->name }}" name="member_name">
						    <input type="hidden" value="{{ $user->email }}" name="email">
						    <input type="hidden" value="{{ $group->id }}" name="group_id">
                           {{Form::button('Delete From Group', array('id' => 'delete', 'class' => 'btn btn-block btn-danger', 'title' => 'Cancel Membership'))}}
                        {!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-9 col-md-8">
			<div class="box box-block bg-white">
				<h5 class="mb-1">Additional Member Details</h5>
				<ul class="nav nav-tabs nav-tabs-2">
					<li class="nav-item" style="width: 25%;">
						<a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true"><i class="ti-user text-muted mr-0-25"></i> Profile</a>
					</li>
					<li class="nav-item" style="width: 25%;">
						<a class="nav-link" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true"><i class="ti-user text-muted mr-0-25"></i> Extra Info</a>
					</li>
					<li class="nav-item" style="width: 25%;">
						<a class="nav-link" id="edit-tab" data-toggle="tab" href="#edit" role="tab" aria-controls="edit" aria-selected="true"><i class="ti-user text-muted mr-0-25"></i> Manage</a>
					</li>
					<li class="nav-item" style="width: 25%;">
						<a class="nav-link" id="message-tab" data-toggle="tab" href="#message" role="tab" aria-controls="message" aria-selected="true"><i class="ti-user text-muted mr-0-25"></i> Messaging</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
						<br>
						<div class="row">
							<div class="col-md-6">
								<p>Full Name: {{ $user->name . ' ' . $user->other_names }}</p>
								<p>Email Address: {{ $user->email }}</p>
								<p>Phone Number: {{ $user->msisdn }}</p>
								<p>Account No: {{ $user->account_no }}</p>
								<p>Document: {{ $document }}</p>
								<p>Identification Number: {{ $detail->doc_no }}</p>
								<p>Membership: 
									@if($user->status) 
										Active
									@else
										Inactive
									@endif
								</p>
								<p>Added On: {{ $user->created_at }}</p>
							</div>
							<div class="col-md-6">
								<p>Address: {{ $detail->address }}</p>
								<p>Country: {{ $country }}</p>
								<p>City: {{ $detail->city }}</p>
								<p>Gender: {{ $gender }}</p>
								<p>Occupation: {{ $detail->occupation }}</p>
								<p>Annual Income: {{ $income }}</p>
								<p>Residence Type: {{ $residence }}</p>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="info" role="tabpanel" aria-labelledby="info-tab">
						<br>
						@if(is_null($data))
							{{ $user->name }} has no extra information
						@else
							@foreach ( $data as $key => $value )
								<p>{{ $key }} : 
								@if (is_array($value))
									@foreach($value as $description)
										{{ $description }},
									@endforeach
								@else	
									{{ $value }}
								@endif</p>
							@endforeach
						@endif
					</div>
					<div class="tab-pane" id="edit" role="tabpanel" aria-labelledby="edit-tab">
						<br>
						<form action="{{ route('org.updatemember') }}" method="POST" enctype="multipart/form-data">
							{{ csrf_field() }}
							<input type="hidden" name="user_id" value="{{ $user->id }}">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group{{ $errors->has('name') ? ' is-invalid' : '' }}">
										<label>First Name <span class="important"></span></label>
										<input type="text" name="name" placeholder="First Name" class="form-control" value="{{ $user->name }}" />
											@if ($errors->has('name'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('name') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group{{ $errors->has('other_names') ? ' is-invalid' : '' }}">
										<label>Other Names <span class="important"></span></label>
										<input type="text" name="other_names" placeholder="Other Names" class="form-control" value="{{ $user->other_names }}" />
											@if ($errors->has('user_name'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('user_name') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group{{ $errors->has('email') ? ' is-invalid' : '' }}">
										<label>Email Address <span class="important"></span></label>
										<input type="email" name="email" placeholder="Email Address" class="form-control" value="{{ $user->email }}" />
										@if ($errors->has('email'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('email') }}</strong>
											</span>
										@endif
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group{{ $errors->has('msisdn') ? ' is-invalid' : '' }}">
										<label>Phone Number <span class="important"></span></label>
										<input type="text" name="msisdn" placeholder="Phone Number" class="form-control" value="{{ $user->msisdn }}" />
										@if ($errors->has('msisdn'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('msisdn') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="city">City <span class="important">*</span></label>
										<input type="text" class="signup-input form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ $detail->city }}">
										@if ($errors->has('city'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('city') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="address">Physical Address <span class="important">*</span></label>
										<input type="text" class="signup-input form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ $detail->address }}">
										@if ($errors->has('address'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('address') }}</strong>
											</span>
										@endif
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="occupation">User Occupation <span class="important">*</span></label>
										<input type="text" class="signup-input form-control{{ $errors->has('occupation') ? ' is-invalid' : '' }}" name="occupation" value="{{ $detail->occupation }}" placeholder="Occupation">
										@if ($errors->has('occupation'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('occupation') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="income">Approximate annual income? <span class="important">*</span></label>
										<select name="income" class="signup-input{{ $errors->has('income') ? ' is-invalid' : '' }} form-control">
											<option value="" disabled="" selected="">Select Income Class</option>
											@foreach($incomes as $income)
												<option value="{{ $income->id }}">{{ $income->name }}</option>
											@endforeach
										</select>
										@if ($errors->has('income'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('income') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="identification_document">
											@if(is_null($user->identification_document)) 
												Upload Document
											@else
												Change Document
											@endif <span class="important">*</span></label>
										<input type="file" name="identification_document" class="form-control signup-input">
										@if ($errors->has('identification_document'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('identification_document') }}</strong>
											</span>
										@endif
									</div>	
								</div>
							</div>
							<div class="row">
								<div class="col-md-8 offset-md-2">
									<button type="submit" class="btn btn-block btn-primary">Update User Details</button>
								</div>
							</div>
						</form>
					</div>
					<div class="tab-pane" id="message" role="tabpanel" aria-labelledby="message-tab">
						<br>
						<form action="{{ route('emailmember') }}" method="POST">
							{{ csrf_field() }}
							<input type="hidden" value="{{ $user->name }}" name="member_name" id="name">
							<input type="hidden" value="{{ $user->email }}" name="email">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="subject">Subject</label>
										<input type="text" class="form-control" name="subject" value="{{ old('subject') }}" placeholder="Subject of your message">
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label for="message">Message Body</label>
										<textarea name="message" id="" cols="30" rows="6" class="form-control" placeholder="Hi {{ $user->name }}, are you getting your money's worth in the group so far?"></textarea>
									</div>
								</div>
								<div class="col-md-8 offset-md-2">
									<button type="submit" class="btn btn-primary btn-block">Send Message</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="box box-block bg-white">
				<h5 class="mb-1">Member Contributions</h5>
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-2">
						<thead>
							<th>Transaction Code</th>
							<th>Amount</th>
							<th>Date</th>
						</thead>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="box box-block bg-white">
				<h5 class="mb-1">Member Payouts</h5>
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-2">
						<thead>
							<th>Transaction Code</th>
							<th>Amount</th>
							<th>Date</th>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script>
		$(document).ready(function() {
			// Cancel Membership
			$('#cancel').click(function(event) {
				event.preventDefault();
				var name = $('#name').val();
				swal({
				  title: "Are you sure?",
				  text: "By clicking OK, " + name + "'s membership will be cancelled",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {
				    $('#cancelForm').submit();
				  } else {
				 //    swal({
					//   title: "Are you sure?",
					//   text: "By clicking OK, " + name + "'s membership will be cancelled",
					//   icon: "warning",
					//   buttons: true,
					//   dangerMode: true,
					// })
				  }
				});   
			});

			// Renew Membership
			$('#renew').click(function(event) {
				event.preventDefault();
				var name = $('#name').val();
				swal({
				  title: "Are you sure?",
				  text: "By clicking OK, " + name + "'s membership will be renewed",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {
				    $('#renewForm').submit();
				  } else {
				 //    swal({
					//   title: "Are you sure?",
					//   text: "By clicking OK, " + name + "'s membership will be cancelled",
					//   icon: "warning",
					//   buttons: true,
					//   dangerMode: true,
					// })
				  }
				});   
			});

			// Delete Membership
			$('#delete').click(function(event) {
				event.preventDefault();
				var name = $('#name').val();
				swal({
				  title: "Are you sure?",
				  text: "By clicking OK, " + name + " will be deleted from this group",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {
				    $('#deleteForm').submit();
				  } else {
				 //    swal({
					//   title: "Are you sure?",
					//   text: "By clicking OK, " + name + "'s membership will be cancelled",
					//   icon: "warning",
					//   buttons: true,
					//   dangerMode: true,
					// })
				  }
				});   
			});
		})
	</script>
@endpush