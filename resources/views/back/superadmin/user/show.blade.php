@extends('back/superadmin/layouts/master')

@section('title')
	View User
@endsection

@section('one-step')
	/ User Detail
@endsection

@section('page-nav')
	<h4>View User</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('super.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="{{ route('all-users.index') }}">User Management</a></li>
		<li class="breadcrumb-item active">View User</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-3 col-md-4">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">User Details</h5>
				</div>
				<div class="card-body">
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
				</div>
				
				{{-- <div class="row">
					<div class="col-md-12">
						@if($user->status)
					 		<a href="#" class="btn btn-danger btn-block" id="cancel">Cancel Membership</a>
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
				</div> --}}
			</div>
		</div>
		<div class="col-sm-9 col-md-8">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">Additional User Details</h5>
				</div>
				<div class="card-body">
					<ul class="nav nav-tabs nav-tabs-2">
						<li class="nav-item" style="width: 20%;">
							<a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true"><i class="ti-user text-muted mr-0-25"></i> Profile</a>
						</li>
						<li class="nav-item" style="width: 20%;">
							<a class="nav-link" id="loan-tab" data-toggle="tab" href="#loan" role="tab" aria-controls="loan" aria-selected="true"><i class="ti-user text-muted mr-0-25"></i> Loans</a>
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
								<p>Account No: 
									@if(is_null($user->account_no))
										None
									@else 
										{{ $user->account_no }}
									@endif
								</p>
								<p>Document: 
									@empty($document)
										None
									@else
										{{ $document }} 
									@endempty
								</p>
								<p>Identification Number: 
									@if(empty($detail->doc_no))
										None
									@else 
										{{ $detail->doc_no }}
									@endif
								</p>
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
								<p>Address: 
									@empty($detail->address)
										None
									@else
										{{ $detail->address }} 
									@endempty
								</p>
								<p>Country: 
									@empty($country)
										Not Specified
									@else
										{{ $country }} 
									@endempty
								</p>
								<p>City: 
									@empty($detail->city)
										None
									@else
										{{ $detail->city }} 
									@endempty
								</p>
								<p>Gender: 
									@empty($gender)
										Not Specified
									@else
										{{ $gender }} 
									@endempty
								</p>
								<p>Occupation: 
									@empty($detail->occupation)
										Not Specified
									@else
										{{ $detail->occupation }} 
									@endempty
								</p>
								<p>Annual Income: 
									@empty($income)
										None
									@else
										{{ $income }} 
									@endempty
								</p>
								<p>Residence Type: 
									@empty($residence)
										Not Specified
									@else
										{{ $residence }} 
									@endempty
								</p>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="loan" role="tabpanel" aria-labelledby="loan-tab">
						<br>
						<div class="row">
                            <div class="col-md-12">
                                <h5 class="mb-1">Loans Borrowed</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-2">
                                        <thead>
                                            <tr>
                                                <th>Loan Name</th>
                                                <th>Date Borrowed</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($loans as $loan)
                                                <tr>
                                                    <td>{{ $loan->name }}</td>
                                                    <td>{{ $loan->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
									<table class="table table-bordered table-hover table-2">
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
						<form action="{{ route('super.emailmember') }}" method="POST">
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
	</div>
@endsection

