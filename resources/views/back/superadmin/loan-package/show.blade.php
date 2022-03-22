@extends('back/superadmin/layouts/master')

@section('title')
	View Package
@endsection

@section('one-step')
	/ Loan Package Detail
@endsection

@section('spec-scripts')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
	<div class="row">
        <div class="col-sm-4 col-md-3">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-4">
                        <li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fa fa-home"></i>Package Name: {{ $package->name }}
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fa fa-users"></i> Borrowers
							<div class="float-xs-right">{{ count($datas) }}</div>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fas fa-calendar"></i> Repayment Plan
							<span class="float-xs-right">
								{{ $package->repayment_plan }}
							</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fas fa-laptop"></i> Status
							<div class="float-xs-right">
								@if($package->status)
									Active
								@else
									Inactive
								@endif
							</div>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fas fa-percent"></i>Minimum Credit Score
							<div class="float-xs-right">{{ $package->min_credit_score }}</div>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fa fa-balance-scale"></i> Interest Rate?
							<div class="float-xs-right">{{ $package->interest_rate }}% per annum</div>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#">
							<i class="fas fa-shield-alt"></i> Insured?
							<div class="float-xs-right">
								@if($package->insured)
									Yes
								@else
									No
								@endif
							</div>
						</a>
					</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-8 col-md-9">
             <div class="card">
                <div class="card-header">
                    <h4 class="mb-1">Loans borrowed from this package</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-left" id="tableExport" style="width:100%;">
                            <thead>
								<tr>
									<th>Borrower</th>
									<th>Status</th>
	                                <th>Date Borrowed</th>
								</tr>
							</thead>
							<tbody>
								@foreach($datas as $data)
									<tr>
										<td>{{ $data->name . ' ' . $data->other_names }}</td>
										<td>
											@if($data->status == 0)
												Pending
											@elseif($data->status == 1)
												Approved
											@elseif($data->status == 2)
												Declined
											@elseif($data->status == 3)
												Paid
											@else
												Defaulted
											@endif
										</td>
										<td>{{ $data->created_at }}</td>
									</tr>
								@endforeach
							</tbody>
                        </table>
                    </div>
                </div>
             </div>
         </div>
     </div>
@endsection

@section('spec-scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script src="{{ asset('assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/datatables.js') }}"></script>
@endsection