@extends('back/organization/layouts/master')

@section('title')
	Reports
@endsection

@section('one-step')
    / Report
@endsection

@section('spec-styles')
	<link rel="stylesheet" href="{{ asset('plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
	<link rel="stylesheet" href="{{ asset('plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
	<div class="row">
        <div class="w-100">
            <div class="card">
                <div class="card-header">
                    <h4>Lending Report</h4>
                    <form action="#" method="POST">
					{{ csrf_field() }}
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Type of Resource</label>
							<select name="resource_type" id="resource" class="form-control" required>
								<option value="" disabled="" selected="">Pick a resource type</option>
                                {{-- <option value="user">User</option> --}}
                                <option value="loan">Loan</option>
                                <option value="transaction">Transaction</option>
                            </select>
                        </div>

                       <div class="form-group col-md-6">
                            <label>Title</label>
								<input type="text" class="form-control" name="title" placeholder="eg {{ date('F') . ' ' . date('Y') }}" required id="doc-title">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Start Date</label>
								<div class="form-group">
									<input type="date" class="form-control" name="from_date" value="{{ date('d/m/Y') }}" required>
								</div>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label>End Date</label>
							<div class="form-group">
								<input type="date" class="form-control" name="to_date" value="{{ date('d/m/Y') }}" required>
							</div>                        </div>
                        
                        <div class="form-group col-md-6">
                            <label>Sort Criteria</label>
							<select name="sort_by" id="" class="form-control" required>
                                <option value="" disabled="" selected="">Pick sort criteria</option>
                                <option value="created_at">Created At</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Name of Saved Document</label>
							<div class="date">
								<input type="text" class="form-control" name="file-name" placeholder="eg {{ date('F') . '-Document' }}" required id="doc-name">
							</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label><span id="type-text">Resource Criteria</span></label>
							<select name="type_criteria" id="criteria" class="form-control" required>
                                <option value="" disabled="" selected="">Pick criteria</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Type of Report</label>
							<select name="type" id="" class="form-control" required>
	                            <option value="" disabled selected>Type</option>
	                            <!-- <option value="pdf">PDF</option> -->
	                            <option value="excel">Excel</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block btn-disabled">Generate Report</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('spec-styles')
	<script type="text/javascript" src="{{ asset('plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}/"></script>
	<script type="text/javascript" src="{{ asset('plugins/moment/moment.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

	<script src="{{ asset('js/back/reports.js') }}"></script>
	<script src="{{ asset('js/back/forms-pickers.js') }}"></script>
@endsection
