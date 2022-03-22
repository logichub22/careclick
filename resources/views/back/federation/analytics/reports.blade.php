@extends('back/federation/layouts/master')

@section('title')
	Reports
@endsection

@push('date-styles')
	<link rel="stylesheet" href="{{ asset('plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
	<link rel="stylesheet" href="{{ asset('plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endpush

@section('page-nav')
	<h4>Reports</h4>
	<ol class="breadcrumb no-bg mb-1">
		<li class="breadcrumb-item"><a href="{{ route('federation.dashboard') }}">Home</a></li>
		<li class="breadcrumb-item active">Reports</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-block bg-white">
				<h5 class="mb-1">Generate Report</h5>
				<form action="{{ route('fedreport.generate') }}" method="POST">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-md-4">
							<label>Type of Resource</label>
							<select name="resource_type" id="resource" class="form-control" required>
								<option value="" disabled="" selected="">Pick a resource type</option>
								<option value="organization">Federation</option>
                                <option value="user">User</option>
                                <option value="loan">Loan</option>
                                <option value="transaction">Transaction</option>
                            </select>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Start Date</label>
								<div class="form-group">
									<input type="date" class="form-control" name="from_date" value="{{ date('d/m/Y') }}" required>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<label>End Date</label>
							<div class="form-group">
								<input type="date" class="form-control" name="to_date" value="{{ date('d/m/Y') }}" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Title</label>
								<input type="text" class="form-control" name="title" placeholder="eg {{ date('F') . ' ' . date('Y') }}" required id="doc-title">
							</div>
						</div>
						<div class="col-md-4">
							<label>Sort Criteria</label>
							<select name="sort_by" id="" class="form-control" required>
                                <option value="" disabled="" selected="">Pick sort criteria</option>
                                <option value="created_at">Created At</option>
                            </select>
						</div>
						<div class="col-md-4">
							<label>Name of Saved Document</label>
							<div class="date">
								<input type="text" class="form-control" name="file-name" placeholder="eg {{ date('F') . '-Document' }}" required id="doc-name">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label><span id="type-text">Resource Criteria</span></label>
							<select name="type_criteria" id="criteria" class="form-control" required>
                                <option value="" disabled="" selected="">Pick criteria</option>
                            </select>
						</div>
						<div class="col-md-6">
							<label>Type of Report</label>
							<select name="type" id="" class="form-control" required>
	                            <option value="" disabled selected>Type</option>
	                            <option value="pdf">PDF</option>
	                            <option value="excel">Excel</option>
	                        </select>
						</div>
					</div>
					<br>
					<div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-block">Generate Report</button>
                        </div>
                    </div>
				</form>
			</div>
		</div>
	</div>
@endsection

@push('date-scripts')
	<script type="text/javascript" src="{{ asset('plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}/"></script>
	<script type="text/javascript" src="{{ asset('plugins/moment/moment.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
@endpush

@push('scripts')
	<script src="{{ asset('js/back/reports.js') }}"></script>
	<script src="{{ asset('js/back/forms-pickers.js') }}"></script>
@endpush
