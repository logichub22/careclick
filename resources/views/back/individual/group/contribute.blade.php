@extends('back/individual/layouts/master')

@section('title')
	Make Contributions
@endsection

@section('one-step')
    / Contribute
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-1">Group Contribution Form</h5>
				</div>
				<div class="card-body">
					@if(count($groups) > 0)
						<form method="POST" action="{{ route('user-contribute') }}">
						{{ csrf_field() }}
						<input class="form-control" type="hidden" value="{{ $organization->org_id}}" name="org_id">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="loan_title">Select Group</label>
										<select name="group" id="group" class="form-control{{ $errors->has('groups') ? ' is-invalid' : '' }}" required>
											<option value="" disabled="" selected="">Select Group</option>
											@foreach($groups as $group)
												<option value="{{ $group->id }}"> {{ $group->name }}</option>
												{{-- <input type="hidden" name="group_id" id="group_id" value="{{ $group->id }}"> --}}
											@endforeach
										</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="organization">Organization</label>
									<input class="form-control" type="text" placeholder="{{ $organization->org_name}}" name="org_name" disabled>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="frequency">Contribution Frequency</label>
									<input id="frequency" class="form-control" type="text" placeholder="MONTHLY" disabled="">
									<input id="frequency2" class="form-control" name="frequency" type="hidden"> 
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="amount">Amount</label>
									<input id="amount" class="form-control" type="text" placeholder="e.g 5000" disabled=""> 
									<input id="amount2" class="form-control" name="amount" type="hidden"> 
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="password">Password</label>
									<input class="form-control" type="password" name="password" placeholder="Enter Your Password" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="cpassword">Confirm Password</label>
									<input class="form-control" type="password" name="password_confirmation" placeholder="Confirm Your Password" required>
								</div>
							</div>

							<div class="col-md-8 offset-md-2">
								<button class="btn btn-primary btn-block" type="submit">Make Contribution</button>
							</div>
						</div>
					</form>
				@else
					<div class="alert alert-warning align-center" colspan="6">
						You do not belong to any group.
					</div>
				@endif
				</div>
			</div>
		</div>
	</div>
@endsection

@section('spec-scripts')
	{{-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> --}}
	<script type="text/javascript">
		$(document).ready(function() {
			$('#group').change(function() {
				var group_id = $(this).val();
					
			  if(group_id){
			    fetchRecords(group_id);
			  }

			});
		});

		function fetchRecords(id) {
			$.ajax({
				url: 'get-settings/'+id,
				type: 'get',
				dataType: 'json',
				success: function(response){
					console.log(response == '');
					if(response == ''){
						console.log('null value')
						alert('Sorry, group contribution settings have not been put in place. Please contact your group admin to set the group contribution settings.')
						$("input[type='password'], button[type='submit']").attr('disabled', true);
					}
					else{
						var frequency = response.frequency;
						var amount = response.amount;
						var currency = response.currency;

						console.log(frequency);
						console.log(amount);

						$("#frequency").attr('placeholder', frequency.toUpperCase());
						$("#amount").attr('placeholder', currency + ' ' +amount);

						$("#amount2").val(amount);
						$("#frequency2").val(frequency);
						
						$("input[type='password'], button[type='submit']").removeAttr('disabled');
					}
				}
			})
		}
	</script>

@endsection
