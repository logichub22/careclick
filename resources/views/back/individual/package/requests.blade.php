@extends('back/individual/layouts/master')

@section('title')
	Loan Requests
@endsection

@section('one-step')
    / Loan Requests
@endsection

@section('page-nav')
	<h4>Loan Requests</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item"><a href="#">Lending</a></li>
		<li class="breadcrumb-item active">Requests</li>
	</ol>
@endsection

@section('content')
	<div class="row">
        <div class="w-100">
            <div class="card">
                <div class="card-header">
                    <h4 class="font-weight-light">Loan Requests</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md text-left">
                            <thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>Status</th>
									<th>Date of Request</th>
									<th>Action</th>
								</tr>
							</thead>

                            @if(count($loan_requests) > 0 )
							<tbody>
                                @foreach($loan_requests as $request)
        							<tr>
        								<td>{{ $request->name }}</td>
        								<td>{{ $request->email }}</td>
                                        <td>
                                            @if($request->status == 0)
                                                Pending
                                            @elseif($request->status == 1)
                                                Approved
                                            @elseif($request->status == 2)
                                                Declined
                                            @elseif($request->status == 3)
                                                Paid
                                            @else
                                                Defaulted
                                            @endif
                                        </td>
        								<td>{{ date('M j, Y', strtotime($request->created_at)) . ' at ' . date('H:i', strtotime($request->created_at)) }}</td>
        								<td>
        									<a href="#viewRequest" data-toggle="modal" class="btn btn-primary">View</a> &nbsp;                            
        								</td>
        							</tr>
                                @endforeach
							</tbody>
                            @else
                                <div class="alert alert-warning align-center" colspan="6">
                                    You do not have any loan requests.
                                </div>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('spec-scripts')
    @if(count($loan_requests) > 0 )
        <!-- Edit Group Modal -->
            <div class="modal fade" id="viewRequest" tabindex="-1" role="dialog" aria-labelledby="viewRequest" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>
                                            User Name: {{ $request->name }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p>
                                            Amount: {{ $request->amount }}
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>
                                            Credit Score: {{ $request->borrower_credit_score }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p>
                                            Repayment Plan: {{ $request->repayment_plan}}
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>
                                            Package Name: {{ $request->packageName }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p>
                                            Minimum Credit Score: {{ $request->min_credit_score }}
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>
                                            Interest Rate: {{ $request->interest_rate }}%
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p>
                                            Insured: @if($request->insured == 0)
                                                        No
                                                    @elseif($request->insured == 1)
                                                        Yes
                                                    @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <h1>Identification Document should be displayed here</h1>
                                    <img src="{{$path}}" alt="{{$path}}">
                                </div>
                         <!-- <a href="#" class="btn btn-info btn-danger"> View Identification Document</a> &nbsp; -->
                         <a href="{{ route('user.approve', $request->id, $request->loan_package_id ) }}" class="btn btn-info btn-primary"> Approve Request</a>
                         <a href="{{ route('user.decline', $request->id ) }}" class="btn btn-danger"> Decline Request</a>
                        </div>
                        </div>
                    </div>
                </div>
        <!--// End Edit Group Modal //-->
    @endif
@endsection