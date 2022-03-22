@extends('back/organization/layouts/master')

@section('title')
	Add Payment Method
@endsection

@section('page-nav')
	<h4>Add Payment Method</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item active">Add Payment Method</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-block bg-white">
				<h5 class="mb-1">Bank Details
					<small class="text-right"><a href="#addBank" data-toggle="modal" class="btn btn-primary">Add Account</a></small></h5>
				<div class="table-responsive">
					<table class="table table-striped table-hover table-bordered">
						<thead>
							<tr>
								{{-- <th>Bank</th> --}}
								<th>Account Number</th>
								<th>Status</th>
								<th>Primary Account ?</th>
								<th>Added On</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if(count($accounts) > 0)
								@foreach($accounts as $account)
									<tr>
										<td>{{ $account->account_no }}</td>
										<td>
											@if($account->verified)
												Verified
											@else
												Not Verified
											@endif
										</td>
										<td>
											@if(!$account->is_primary)
												Yes
											@else
												No
											@endif
										</td>
										<td>{{ $account->created_at }}</td>
										<td>
											@if($account->verified)
												<button class="btn btn-sm btn-primary" disabled>Verify</button>
											@else
												<button class="btn btn-sm btn-primary" data-target="#verifyBank" data-toggle="modal">Verify</button>
											@endif &nbsp;
											@if(!$account->is_primary)
												<button class="btn btn-sm btn-success" id="makePrimary">Make Primary</button>
											@endif
										</td>
									</tr>
								@endforeach
							@else
								<tr>
									<td colspan="3">No bank accounts found</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
		
	</div>

	<div class="modal fade" id="addBank" tabindex="-1" role="dialog" aria-labelledby="addBank" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Add a Bank Account</h4>
				</div>
				<form action="{{ route('bankaccount.add') }}" method="POST">
					{{ csrf_field() }}
					<div class="modal-body">
						{{-- <div class="form-group">
							<label for="bank" class="form-control-label">Bank</label>
							<select name="bank" id="" class="form-control">
								<option value="" disabled="" selected="">Select Bank</option>
								<option value="">Access Bank</option>
								<option value="">Citibank</option>
								<option value="">Diamond Bank</option>
								<option value="">Union Bank</option>
								<option value="">Unity Bank</option>
								<option value="">Wema Bank</option>
							</select>
							@if($errors->has('bank'))
								<span class="invalid-feedback" role="alert">
									 <strong>{{ $errors->first('bank') }}</strong>
								</span>
							@endif
						</div> --}}
						<div class="form-group">
							{{-- <strong>Note:</strong>Ensure you use an account number linked to an existing phone number. We will send a one time password (OTP) for verification. --}}
						</div>
						<div class="form-group">
							<label for="account_no" class="form-control-label">Account Number</label>
							<input type="text" name="account_no" class="form-control" value="{{ old('account_no') }}" placeholder="Account Number">
							@if($errors->has('account_no'))
								<span class="invalid-feedback" role="alert">
									 <strong>{{ $errors->first('account_no') }}</strong>
								</span>
							@endif
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Add Account</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="verifyBank" tabindex="-1" role="dialog" aria-labelledby="verifyBank" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Verify this account</h4>
				</div>
				<form action="" method="POST">
					{{ csrf_field() }}
					<div class="modal-body">
						<div class="form-group">
							<label for="account_no" class="form-control-label">Enter OTP</label>
							<input type="text" name="otp" class="form-control" value="{{ old('otp') }}" placeholder="Enter OTP">
							@if($errors->has('otp'))
								<span class="invalid-feedback" role="alert">
									 <strong>{{ $errors->first('otp') }}</strong>
								</span>
							@endif
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Verify</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script>
		$(document).ready(function () {
			$('#makePrimary').click(function(event) {
				swal({
				  title: "Are you sure?",
				  text: "By clicking OK, this will be set as your primary account",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {
				    swal({
					  title: "Success",
					  text: "This is now your primary account",
					  icon: "success",
					})
				  } else {
				    
				  }
				});
			});
		})
	</script>
@endpush