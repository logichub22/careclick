@extends('back/organization/layouts/master')

@section('title')
	Add Money
@endsection

@section('one-step')
    / Savings / Add Money
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="card col-md-3">
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
							<a href="{{ route('orgsavings') }}" class="btn btn-primary btn-block">Back to savings</a>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">Fund Savings Wallet</h5>
				</div>
				<div class="card-body">
					<form id="saveFunds" method="POST" action="{{ route('org-savings') }}">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="amount">Amount</label>
								<input type="text" class="form-control" name="amount" placeholder="How much do you want to save?" id="amount">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="wallet_balance">Wallet Balance</label>
								<input type="text" class="form-control" name="balance" placeholder="NGN {{ number_format($wallet->balance) }}" id="balance" disabled="">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="duration">Duration</label>
								<select class="form-control" name="duration" id="duration">
									<option value="30">30 days(One Month)</option>
									<option value="60">60 days(Two Months)</option>
									<option value="90">90 days(Three Months)</option>
									<option value="120">120 days(Four Months)</option>
									<option value="150">150 days(Five Months)</option>
									<option value="180">180 days(Six Months)</option>
									<option value="210">210 days(Seven Months)</option>
									<option value="240">240 days(Eight Months)</option>
									<option value="270">270 days(Nine Months)</option>
									<option value="300">300 days(Ten Months)</option>
									<option value="330">330 days(Eleven Months)</option>
									<option value="30">360 days(Twelve Months)</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="password">Password</label>
								<input type="password" class="form-control" name="password" placeholder="Enter your password" id="password">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="confirm_balance">Confirm Password</label>
								<input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" id="password_confirmation">
							</div>
						</div>
						<div class="col-md-8 offset-md-2">
							<a href="#" class="btn btn-primary btn-block" id="save">Save</a>
						</div>
					</div>
				</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('spec-scripts')
	<script>
		$(document).ready(function() {
			// Cancel Membership
			$('#save').click(function(event) {
				event.preventDefault();
				var name = $('#name').val();
				var duration = $('#duration').val();
				swal({
				  title: "Are you sure?",
				  text: "By clicking OK, you won't be able to access your savings until after " + duration + " days",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {
				    $('#saveFunds').submit();
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
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endsection
