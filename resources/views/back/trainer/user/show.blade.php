@extends('back/organization/layouts/master')

@section('title')
	View User
@endsection

@section('page-nav')
	<h4>View User</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="{{ route('users.index') }}">User Management</a></li>
		<li class="breadcrumb-item active">View User</li>
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
					Account Number:
					@if(is_null($user->account_no))
						Not Set
					@else
						{{ $user->account_no }}
					@endif
				</p>
				<p>
					 Added On: {{ $user->created_at }}
				</p>
				<p>
					 Membership Status: 
					 @if($user->status)
					 	Active
					 @else
						Inactive
					 @endif
				</p>
				@if(! is_null($user->identification_document))
					<p>
						<a href="{{ route('streamid', $user->id) }}" class="btn btn-primary">View Identification Document</a>
				    </p>
				@endif
				<div class="row">
					<div class="col-md-12">
						@if($user->status)
					 		{{-- <a href="#" class="btn btn-danger btn-block" id="cancel">Cancel Membership</a> --}}
					 		{!! Form::open(['route' => ['deactivate', $user->id], 'method' => 'POST', 'style' => 'display: inline-block', 'id' => 'deactivateForm']) !!}  
					 		   <input type="hidden" name="user_id" value="{{ $user->id }}">  
					 		   <input type="hidden" value="{{ $user->name }}" name="name" id="name">
							   <input type="hidden" value="{{ $user->email }}" name="email">                           
                               {{Form::button('Deactivate User', array('id'=> 'deactivate', 'class' => 'btn btn-block btn-danger', 'title' => 'Deactivate User'))}}
                            {!! Form::close() !!}
						@else
							{!! Form::open(['route' => ['activate', $user->id], 'method' => 'POST', 'style' => 'display: inline-block', 'id' => 'activateForm']) !!} 
							   <input type="hidden" name="user_id" value="{{ $user->id }}">   
					 		   <input type="hidden" value="{{ $user->name }}" name="name" id="name">
							   <input type="hidden" value="{{ $user->email }}" name="email">                               
                               {{Form::button('Activate User', array('id'=> 'activate', 'class' => 'btn btn-block btn-success', 'title' => 'Activate User'))}}
                            {!! Form::close() !!}
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-9 col-md-8">
			<div class="box box-block bg-white">
				<h5 class="mb-1">Additional User Details</h5>
				<ul class="nav nav-tabs nav-tabs-2">
					<li class="nav-item" style="width: 20%;">
						<a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true"><i class="ti-user text-muted mr-0-25"></i> Profile</a>
					</li>
					<li class="nav-item" style="width: 20%;">
						<a class="nav-link" id="edit-tab" data-toggle="tab" href="#edit" role="tab" aria-controls="edit" aria-selected="true"><i class="ti-user text-muted mr-0-25"></i> Manage</a>
					</li>
					<li class="nav-item" style="width: 20%;">
						<a class="nav-link" id="group-tab" data-toggle="tab" href="#group" role="tab" aria-controls="group" aria-selected="true"><i class="ti-user text-muted mr-0-25"></i> Groups</a>
					</li>
					<li class="nav-item" style="width: 20%;">
						<a class="nav-link" id="transaction-tab" data-toggle="tab" href="#transaction" role="tab" aria-controls="transaction" aria-selected="true"><i class="ti-user text-muted mr-0-25"></i> Transactions</a>
					</li>
					<li class="nav-item" style="width: 20%;">
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
					<div class="tab-pane" id="edit" role="tabpanel" aria-labelledby="edit-tab">
						<br>
						<form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
							{{ method_field('PATCH') }}
							{{ csrf_field() }}
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
								@if(is_null($user->identification_document))
									<div class="col-md-4">
										<div class="form-group">
											<label for="identification_document">Upload Document <span class="important">*</span></label>
											<input type="file" name="identification_document" class="form-control signup-input">
											@if ($errors->has('identification_document'))
												<span class="invalid-feedback" role="alert">
													<strong>{{ $errors->first('identification_document') }}</strong>
												</span>
											@endif
										</div>	
									</div>
								@endif
							</div>
							<div class="row">
								<div class="col-md-8 offset-md-2">
									<button type="submit" class="btn btn-block btn-primary">Update User Details</button>
								</div>
							</div>
						</form>
					</div>
					<div class="tab-pane" id="group" role="tabpanel" aria-labelledby="group-tab">
						<br>
						<div class="row">
							<div class="col-md-12">
								<h5 class="mb-1">Groups Owned</h5>
								<div class="table-responsive">
									<table class="table table-bordered table-hover table-2">
										<thead>
											<tr>
												<th>Group Name</th>
												<th>Date Created</th>
											</tr>
										</thead>
										<tbody>
											@foreach($owned as $group)
												<tr>
													<td>{{ $group->name }}</td>
													<td>{{ $group->created_at }}</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<h5 class="mb-1">Groups Joined</h5>
								<div class="table-responsive">
									<table class="table table-bordered table-hover">
										<thead>
											<tr>
												<th>Group Name</th>
												<th>Joined On</th>
											</tr>
										</thead>
										<tbody>
											@foreach($membergroups as $group)
												<tr>
													<td>{{ $group->name }}</td>
													<td>{{ $group->created_at }}</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="transaction" role="tabpanel" aria-labelledby="transaction-tab">
						<br>
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-bordered table-hover table-2">
										<thead>
											<tr>
												<th>Transaction Code</th>
												<th>Type</th>
												<th>Amount</th>
												<th>Transaction Date</th>
											</tr>
										</thead>
										<tbody>
											@foreach($transactions as $transaction)
												<tr>
													<td>{{ $transaction->txn_code }}</td>
													<td>
														@if($transaction->txn_type == 1)
															Credit
														@else
															Debit
														@endif
													</td>
													<td>{{ number_format($transaction->amount) }}</td>
													<td>{{ $transaction->created_at }}</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="message" role="tabpanel" aria-labelledby="message-tab">
						<br>
						<h5 class="mb-1">Send a private message to {{ $user->name }}</h5>
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
										<textarea name="message" id="" cols="30" rows="6" class="form-control" placeholder="Hi {{ $user->name }}"></textarea>
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
@endsection

@push('scripts')
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script>
		$(document).ready(function() {
			// Cancel Membership
			$('#deactivate').click(function(event) {
				event.preventDefault();
				var name = $('#name').val();
				swal({
				  title: "Are you sure?",
				  text: "By clicking OK, " + name + "'s account will be deactivated",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {
				    $('#deactivateForm').submit();
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
			$('#activate').click(function(event) {
				event.preventDefault();
				var name = $('#name').val();
				swal({
				  title: "Are you sure?",
				  text: "By clicking OK, " + name + "'s account will be activated",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {
				    $('#activateForm').submit();
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