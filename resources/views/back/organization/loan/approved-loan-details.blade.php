@extends('back/organization/layouts/master')

@section('title')
	Loan Details
@endsection

@section('one-step')
    / Loans
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-5 col-md-4">
			<div class="card">
        <a href="{{ route('organization.loans') }}" class="btn btn-default btn-block text-left"><i class="fa fa-arrow-left"></i> Back to loans</a>
				<div class="card-header">
          <h5 class="mb-1">Loan Schedule</h5>
				</div>
				<div class="card-body">
          <a href="#repaymentSchedule" class="btn btn-secondary btn-block">View Repayment Schedule</a>

          <a href="#repaymentRecords" class="btn btn-info btn-block">Repayment Records</a>
				</div>
			</div>
		</div>
		<div class="col-sm-7 col-md-8">
			<div class="card">
				<div class="card-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th colspan="2"><h3>Loan Details</h3></th>
              </tr>
            </thead>
            <tbody class="loan-details">
              <style>
                .loan-details th {
                  width: 30%;
                }
              </style>
              <tr>
                <th>Applicant's full name</th>
                <td>{{ $loan->application->inputs->first_name . ' ' . $loan->application->inputs->last_name }}</td>
              </tr>
              <tr>
                <th>Phone number</th>
                <td>{{ $loan->application->inputs->phone_number }}</td>
              </tr>
              <tr>
                <th>Loan Type</th>
                <td>{{ $loan->application->loan_type->name }}</td>
              </tr>
              <tr>
                <th>Interest rate</th>
                <td>{{ $loan->application->loan_type->interest_rate }}%</td>
              </tr>
              <tr>
                <th>Repayment frequency</th>
                <td>{{ $loan->application->loan_type->repayment_frequency }}</td>
              </tr>
              <tr>
                <th>Duration</th>
                <td>{{ count($loan->repayment_schedules) . ' (' . strtolower($loan->metric->repayment_frequency) . ')' }}</td>
              </tr>
              <tr>
                <th>Loan Amount</th>
                <td>{{ $loan->application->loan_type->currency }} {{ number_format($loan->application->amount, 2) }}</td>
              </tr>
              <tr>
                <th>Interest</th>
                @php
									$interest = 0.00;
									foreach ($loan->repayment_schedules as $schedule) {
										$interest += floatval($schedule->interest);
									}
                @endphp
                <td>{{ $loan->application->loan_type->currency }} {{ number_format($interest, 2) }}</td>
              </tr>
              <tr>
                <th>Total amount to be paid</th>
                <td>{{ $loan->application->loan_type->currency }} {{ number_format($loan->application->amount + $interest, 2) }}</td>
              </tr>
            </tbody>
          </table>
				</div>
			</div>
		</div>
	</div>
  <div class="row">
		<div class="col-md-12">
			<div class="card" id="repaymentSchedule">
				<div class="card-header">
          <h5 class="my-1">Repayment Schedule</h5>
				</div>
				<div class="card-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>S/N</th>
                <th>Principal</th>
                <th>Interest</th>
                <th>Installment Amount</th>
                <th>Due Date</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($loan->repayment_schedules as $schedule)
                <tr>
                  <td>{{ $schedule->tenure_stage }}</td>
                  <td>{{ $loan->application->loan_type->currency }} {{ $schedule->principal }}</td>
                  <td>{{ $loan->application->loan_type->currency }} {{ $schedule->interest }}</td>
                  <td>{{ $loan->application->loan_type->currency }} {{ $schedule->expected_amount }}</td>
                  <td>{{ date('d-F-Y', strtotime($schedule->due_date)) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
				</div>
			</div>
		</div>
  </div>
  <div class="row">
		<div class="col-md-12">
			<div class="card" id="repaymentRecords">
				<div class="card-header">
                    <h4 class="my-1">Repayment Records</h4>
                    <div class="card-header-action">
                        <form action="{{ $csv_post }}" method="post" target="_blank">
                        @csrf
                        <button type="submit" class="btn btn-info btn-icon icon-right">Export to CSV<i class="fas fa-download"></i></button>
                        </form>
                    </div>
				</div>
				<div class="card-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>S/N</th>
                {{-- <th>Principal</th> --}}
                {{-- <th>Interest</th> --}}
                <th>Installment Amount</th>
                <th>Received Amount</th>
                <th>Balance</th>
                <th>Payment Date</th>
              </tr>
            </thead>
            <tbody>
              @php
                  $sn = 1;
                  $balance = 0.00;
                  $total = $loan->application->amount + $interest;
              @endphp
              @foreach ($loan->repayments as $repayment)
                @php
                  $total -= $repayment->actual_amount;
                @endphp
                <tr>
                  <td>{{ $sn++ }}</td>
                  {{-- <td>{{ $loan->application->loan_type->currency }} {{ $repayment->principal }}</td> --}}
                  {{-- <td>{{ $loan->application->loan_type->currency }} {{ $repayment->interest }}</td> --}}
                  <td>{{ $loan->application->loan_type->currency }} {{ $repayment->expected_amount }}</td>
                  <td>{{ $loan->application->loan_type->currency }} {{ $repayment->actual_amount }}</td>
                  <td>{{ $loan->application->loan_type->currency }} {{ $total }}</td>
                  <td>{{ date('d-F-Y', strtotime($repayment->payment_date)) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
				</div>
			</div>
		</div>
  </div>
@endsection
