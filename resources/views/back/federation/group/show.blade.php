@extends('back/organization/layouts/master')

@section('title')
	View Group
@endsection

@push('styles')
	<link rel="stylesheet" href="{{ asset('css/main/loader.css') }}">
@endpush

@section('page-nav')
	<h4>View Group</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="#">Groups</a></li>
		<li class="breadcrumb-item"><a href="{{ route('groups.index') }}">My Groups</a></li>
		<li class="breadcrumb-item active">View Group</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-6">
			<div class="errorbox box box-block bg-white" style="display: none;">
				<h5 class="">Email Import Errors</h5>
				<ul id="errorList">
					
				</ul>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="phone-errorbox box box-block bg-white" style="display: none;">
				<h5 class="">Phone Import Errors</h5>
				<ul id="phoneErrorList">
					
				</ul>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-4 col-md-3">
			<a href="#instructions" class="btn btn-primary btn-block" data-toggle="modal">Read Import Instructions</a> <br>
			<a href="#importMembers" class="btn btn-success btn-block" data-toggle="modal">Import Group Members</a> <br>

			<a href="{{ route('org.groupmessage', $group->id) }}" class="btn btn-success btn-block">Bulk Messaging</a> <br>
			<div class="box bg-white">
				<ul class="nav nav-4">
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fa fa-home"></i> {{ $group->name }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="far fa-money-bill-alt"></i> Balance
							<div class="float-xs-right">{{ number_format($wallet->balance) }}</div>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fa fa-users"></i> Members
							<div class="float-xs-right">{{ count($members) }}</div>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="far fa-calendar-alt"></i> Created {{ date('M j, Y', strtotime($group->created_at)) . ' at ' . date('H:i', strtotime($group->created_at)) }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fa fa-envelope"></i> Member Requests
							<div class="float-xs-right">0</div>
						</a>
					</li>
				</ul>
				<button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#editGroup" >Edit Group</button>
			</div>
		</div>

		<div class="col-sm-8 col-md-9">
			<div class="row">
				<div class="col-md-4">
					<div class="box box-block tile tile-2 bg-success mb-2">
						<div class="t-icon right"><i class="fa fa-credit-card"></i></div>
						<div class="t-content">
							<h1 class="mb-1">{{ number_format($wallet->balance) }}</h1>
							<h6 class="text-uppercase">Balance</h6>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="box box-block tile tile-2 bg-primary mb-2">
						<div class="t-icon right"><i class="fa fa-credit-card"></i></div>
						<div class="t-content">
							<h1 class="mb-1">0</h1>
							<h6 class="text-uppercase">Money Borrowed</h6>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="box box-block tile tile-2 bg-warning mb-2">
						<div class="t-icon right"><i class="fa fa-credit-card"></i></div>
						<div class="t-content">
							<h1 class="mb-1">0</h1>
							<h6 class="text-uppercase">Money Lend Out</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<div class="box box-block bg-white">
				<h5 class="mb-1">Summary of Transactions</h5>
				<div class="table-responsive">
					<table class="table-striped table table-hover table-bordered dataTable table-2">
						<thead>
							<tr>
								<th>Transaction Code</th>
								<th>Amount</th>
								<th>Status</th>
								<th>Transaction Date</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="box box-block bg-white">
				<h5 class="mb-1">Latest Group Members
					<small class="pull-right"><a href="{{ route('orggroup.addmember', $group->id) }}" class="btn btn-primary btn">View and add members</a></small>
				</h5>
				<div class="table-responsive">
					<table class="table-striped table table-hover table-bordered dataTable table-2">
						<thead>
							<tr>
								<th>Name</th>
								<th>Email Address</th>
							</tr>
						</thead>
						<tbody>
							@foreach($members as $member)
								<tr>
									<td>{{ $member->name . ' ' . $member->other_names }}</td>
									<td>{{ $member->email }}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Edit Group Modal -->
	<div class="modal fade" id="editGroup" tabindex="-1" role="dialog" aria-labelledby="editGroup" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Edit {{ $group->name }} Group</h4>
				</div>
				<form action="{{ route('groups.update', $group->id) }}" method="POST">
					{{ csrf_field() }}
					{{ method_field('PATCH') }}
					<div class="modal-body">
						<div class="form-group">
							<label for="recipient-name" class="form-control-label">Group Name:</label>
							<input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : ''}}" name="name" value="{{ $group->name }}">
							@if($errors->has('name'))
								<span class="invalid-feedback" role="alert">
									 <strong>{{ $errors->first('name') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group">
							<label for="account_no">Account</label>
							<input type="text" name="account_no" class="form-control{{ $errors->has('account_no') ? ' is-invalid' : '' }}" placeholder="Account Number" value="{{ $group->account_no }}" maxlength="100">
							@if($errors->has('account_no'))
								<span class="invalid-feedback" role="alert">
									<strong>{{ $errors->first('account_no') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group">
							<label for="message-text" class="form-control-label">Comments:</label>
							<textarea class="form-control{{ $errors->has('comment') ? ' is-invalid' : '' }}" name="comment">{{ $group->comment }}</textarea>
							@if($errors->has('comment'))
								<span class="invalid-feedback" role="alert">
									 <strong>{{ $errors->first('comment') }}</strong>
								</span>
							@endif
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Update Group</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!--// End Edit Group Modal //-->

	<!-- Import Users Modal -->
	<div class="modal fade" id="importMembers" tabindex="-1" role="dialog" aria-labelledby="editGroup" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Import Users</h4>
				</div>
				<form id="uploadForm" enctype="multipart/form-data">
					{{-- <input type="hidden" name="org_id" value="{{ $organization->id }}">
					<input type="hidden" name="organization" value="{{ $organization->name }}"> --}}
					<input type="hidden" name="grp_name" id="grp_name" value="{{ $group->name }}">
					<input type="hidden" name="group_id" id="group_id" value="{{ $group->id }}">
					<div class="modal-body">
						<div class="form-group">
							<label for="file" class="form-control-label">File Name:</label>
							<input type="file" class="form-control" name="file" required id="file">
							{{-- @if($errors->has('file'))
								<span class="invalid-feedback" role="alert">
									 <strong>{{ $errors->first('file') }}</strong>
								</span>
							@endif --}}
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeImport">Close</button>
						<button type="submit" class="btn btn-primary" id="importButton">Import</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!--// End Edit Group Modal //-->


	<div class="modal fade" id="instructions" tabindex="-1" role="dialog" aria-labelledby="instructions" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">User Upload Instructions</h4>
				</div>
				<div class="modal-body">
					<div>
						<h5 class="mb-1">1. File Format</h5>
						The file format of the file uploaded should be a csv file with a <strong>.csv</strong> file extension. For example, the file should have a name such as <strong>test.csv</strong> 
					</div>
					<br>
					<div>
						<h5 class="mb-1">2. Emails</h5>
						All emails for users should be unique. If the system identifies any email that is duplicated or already exists in the database, the upload operation will be unsuccessful and <strong>NO</strong> records will be saved. You will be notified of the emails that contain errors. 
					</div>
					<br>
					<div>
						<h5 class="mb-1">3. Gender</h5>
						The two genders, male and female, are represented as either <strong>1</strong> or <strong>2</strong>. Ideally, you should key in 1 or 2 to represent the relevant gender.
						<p>Male - <strong>1</strong> and Female - <strong>2</strong></p>
					</div>
					<br>
					<div>
						<h5 class="mb-1">4. Country</h5>
						The seven countries (for now) are all represented by numbers. This works the same way as the gender column.
						<p>Botswana - <strong>25</strong>, Kenya - <strong>93</strong>, Liberia - <strong>101</strong>, Nigeria - <strong>131</strong>, Sierra Leone - <strong>159</strong>, Tanzania - <strong>177</strong> and Uganda - <strong>186</strong></p>
					</div>
					<br>
					<div>
						<h5 class="mb-1">5. Document of Identification</h5>
						The document of identification can either be a National ID, Passport, Driving License or Voter ID. All these are represented by numbers 1, 2, 3 or 4 
						<p>National ID - <strong>1</strong>, Passport - <strong>2</strong>, Driving License - <strong>3</strong> and Voter ID - <strong>4</strong></p>
					</div>
					<br>
					<div>
						<h5 class="mb-1">6. Date of Birth</h5>
						The date of birth format should be month/day/year. Eg, For a user born on 20th October 1980 the date would be entered as 10/20/1980. The forward slashes are necessary.
					</div>
					<br>
					<div>
						<h5 class="mb-1">7. Approximate Annual Income</h5>
						The approximate annual income is represented by various classes. The income currency is Naira.
						<p>Below 50,000 - <strong>1</strong>, 50,001 to 250,000 - <strong>2</strong>, 250,001 to 500,000 <strong>3</strong>, 500,001 to Less than 1 million - <strong>4</strong>, 1 million to Less than 5 million - <strong>5</strong>, 5 million to Less than 10 million - <strong>6</strong>, Above 20 million - <strong>7</strong> and Not Specified - <strong>8</strong></p>
					</div>
					<br>
					<div>
						<h5 class="mb-1">8. Resident Type</h5>
						Residential - <strong>1</strong>, Non Residential - <strong>2</strong> and Foreign National - <strong>3</strong>
					</div>
					<br>
					<div>
						<h5 class="mb-1">9. Marital Status</h5>
						Married - <strong>1</strong> and  Single - <strong>2</strong>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Download Instructions</button>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script src="{{ asset('js/main/loader.js') }}"></script>

	<script>
		$(document).ready(function () {
			$('#uploadForm').submit(function(e) {
				e.preventDefault();
				var emails = [];
				var spinner = '<i class="fas fa-spinner fa-spin"></i> Importing...';
				var file_data = $('#file').prop('files')[0];
				var grp_name = $('#grp_name').val();
				var group_id = $('#group_id').val();
				var form_data = new FormData();
				form_data.append('file', file_data);
				form_data.append('grp_name', grp_name);
				form_data.append('group_id', group_id);
				$(document).ajaxStart(function(){
					appLoading.start();
					$('#importButton').html(spinner);
                });

                $(document).ajaxComplete(function(){
					appLoading.stop();
					$('#importButton').html('<span>Import</span>');
                });
                $.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				
				$.ajax({
					url: "{{ route('groupmembers.import') }}",
					data: form_data,
					method: 'POST',
					contentType: false,
					cache: false,
					processData: false,
					success: function (data) {
						$('#closeImport').trigger('click');
						if(data.errors) {
							// if (value.message === 'Phone number exists') {
							// 	$('.phone-errorbox').show();
							// } 
							// if (value.message === 'Email exists') {
							// 	$('.errorbox').show();
							// }
							$.each(data.errors, function(index, value){
								if (value.message === 'Phone number exists') {
									$('#phoneErrorList').append('<li>' + value.msisdn + ' ' + '-' + ' ' + value.message + '</li>');
								} 
								if (value.message === 'Email exists') {
									$('#errorList').append('<li>' + value.email + ' ' + '-' + ' ' + value.message + '</li>');
								}
							});
							swal({
                                title: "Upload Error",
                                text: data.uploaderror,
                                icon: "warning",
                                dangerMode: true,
                            })
						} else {
							$('#errorList').empty();
							$('.errorbox').hide();
							swal({
                                title: "Congrats",
                                text: data.success,
                                icon: "success"
                            })
						}
					}
				})
			})
		})
	</script>
@endpush
