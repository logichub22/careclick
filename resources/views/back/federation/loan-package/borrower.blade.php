@extends('back/organization/layouts/master')

@section('title')
	View Loan Detail
@endsection

@section('page-nav')
	<h4>View Loan Detail</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('organization.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="#">Lending</a></li>
		<li class="breadcrumb-item"><a href="{{ route('org-packages.index') }}">My Packages</a></li>
		<li class="breadcrumb-item active">View Loan Detail</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-block bg-white">
				<div class="card">
							<div class="card-header clearfix">
								<h5 class="float-xs-left mb-0">Loan Name: {{ $loan->loan_title }}</h5>
								<div class="float-xs-right">Borrowed On {{ date('M j, Y', strtotime($loan->created_at)) . ' at ' . date('H:i', strtotime($loan->created_at)) }}</div>
							</div>
							<div class="card-block">
								<div class="row mb-2" id="printable">
									<div class="col-sm-8 col-xs-6">
										<p>
											Principal: <strong>&#8358; {{ number_format($loan->amount) }}</strong><br>
											Total Amount Owed: <strong>&#8358; {{ number_format($detail->amount_payable) }}</strong><br>
										    Expected Earnings: <strong>&#8358; {{ number_format($detail->interest_due) }}</strong><br>
										    Outstanding Balance: <strong>&#8358; {{ number_format($detail->balance) }}</strong><br>
										    Amount Payed: <strong>&#8358; {{ $detail->amount_payable - $detail->amount_payable }} </strong>
										</p> 
										    
										<p>
											No. of Installments: {{ $detail->no_of_installments }}<br>
											Interest Charge Frequency: {{ $detail->interest_charge_frequency }} <br>
											Charge Per Installment: <strong> &#8358; {{ number_format($detail->charge_per_installment) }}</strong>
										</p>
										<p>
											Loan Name: {{ $loan->loan_title }} <br>
											Borrowed On: {{ date('M j, Y', strtotime($loan->created_at)) . ' at ' . date('H:i', strtotime($loan->created_at)) }} <br>
											Payback Date: {{ $detail->payback_date }} <br>
											Next Payment Date: {{ $detail->next_payment_date }} <br>
										</p>
									</div>
									<div class="col-sm-4 col-xs-6">
										<h5>Borrower Details:</h5>
										<div class="clearfix mb-0-25">
											<span class="float-xs-left">Full Name:</span>
											<span class="float-xs-right">{{ $user->name . ' ' . $user->other_names }}</span>
										</div>
										<div class="clearfix mb-0-25">
											<span class="float-xs-left">Email Address:</span>
											<span class="float-xs-right">{{ $user->email }}</span>
										</div>
										<div class="clearfix mb-0-25">
											<span class="float-xs-left">Phone Number:</span>
											<span class="float-xs-right">{{ $user->msisdn }}</span>
										</div>
										<div class="clearfix mb-0-25">
											<span class="float-xs-left">Country:</span>
											<span class="float-xs-right">{{ $user->country }}</span>
										</div>
										<div class="clearfix mb-0-25">
											<span class="float-xs-left">State:</span>
											<span class="float-xs-right">{{ $user->state }}</span>
										</div>
										<div class="clearfix mb-0-25">
											<span class="float-xs-left">City:</span>
											<span class="float-xs-right">{{ $user->city }}</span>
										</div>
										<div class="clearfix">
											<span class="float-xs-left">Document of Identification:</span>
											<span class="float-xs-right">
												@if($user->doc_type === "1")
													National ID
												@elseif($user->doc_type === "2")
													Passport
												@elseif($user->doc_type === "3")
													Driving License
												@elseif($user->doc_type === "4")
													Voter ID
												@endif
											</span>
										</div>
										<div class="clearfix mb-0-25">
											<span class="float-xs-left">Identification Number:</span>
											<span class="float-xs-right">{{ $user->doc_no }}</span>
										</div>
										<div class="clearfix mb-0-25">
											<span class="float-xs-left">Residence:</span>
											<span class="float-xs-right">{{ $user->residence }}</span>
										</div>
									</div>

								</div>
								<h5 class="mb-1">Payments Made</h5>
								<table class="table table-bordered table-striped mb-2 table-2">
									<thead>
										<tr>
											<th>
												Amount
											</th>
											<th>
												Date Payed
											</th>
											<th>
												Outstanding Balance
											</th>
											<th>
												Transaction Status
											</th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
							</div>
							<div class="card-footer clearfix">
								<button type="button" class="btn btn-primary label-left float-xs-right mr-0-5" id="printInfo">
									<span class="btn-label"><i class="ti-printer"></i></span>
									Print
								</button>
							</div>
						</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script>
		function printData()
		{
		   var divToPrint=document.getElementById("printable");
		   newWin= window.open("");
		   newWin.document.write(divToPrint.outerHTML);
		   newWin.print();
		   newWin.close();
		}
		$('#printInfo').on('click',function(){
			printData();
		});
	</script>
@endpush