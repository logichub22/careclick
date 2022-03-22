@extends('back/organization/layouts/master')

@section('title')
	Transfer to main wallet
@endsection

@section('one-step')
    / Savings / Transfer
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
					<h5 class="mb-1">Withdraw from Savings Wallet</h5>
				</div>
				<div class="card-body">
					<form id="withdrawFunds" method="POST" action="{{ route('org-savings-transfer') }}">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="amount">Amount</label>
								<input type="text" class="form-control" name="amount" placeholder="How much do you want to transfer?" id="amount">
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
							<a href="#" class="btn btn-primary btn-block" id="withdraw">Transfer</a>
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
			$('#withdraw').click(function(event) {
				event.preventDefault();
				var amount = $('#amount').val();
				swal({
				  title: "Are you sure?",
				  text: "By clicking OK, you will be transfering NGN " + amount + " to your main wallet",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {
				    $('#withdrawFunds').submit();
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
