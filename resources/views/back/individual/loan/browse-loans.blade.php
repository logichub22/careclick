@extends('back/individual/layouts/master')

@section('title')
	Browse Loans
@endsection

@section('one-step')
    / Loan
@endsection

@section('spec-styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endsection


@section('content')
	<!-- <div class="row">
        <div class="w-100">
            <div class="card">
                <div class="card-header">
                    <h4>Filter Loans</h4>
                    <form id="filterForm" method="POST" action="{{ route('user.searchloans') }}">
					{{ csrf_field() }}
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Repayment Plan</label>
							<select name="repayment_plan" id="repayment_plan" class="form-control" required>
								<option value="" disabled="" selected="">Select Plan</option>
		                        <option value="weekly">Weekly</option>
		                        <option value="bi-weekly">Bi-Weekly</option>
		                        <option value="monthly">Monthly</option>
		                    </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Minimum Interest Rate</label>
							<div class="form-group">
								<input type="text" class="form-control" name="min_interest" required placeholder="minimum interest">
							</div>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Maximum Interest Rate</label>
							<div class="form-group">
								<input type="text" class="form-control" name="max_interest" required placeholder="maximum interest">
							</div>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Has Insurance?</label>
							<select name="insured" id="insured" class="form-control" required>
								<option value="" disabled="" selected="">Has Insurance?</option>
		                        <option value="1">Yes</option>
		                        <option value="0">No</option>
		                    </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Minimum Amount</label>
							<input type="text" class="form-control" name="min_amount" required placeholder="minimum amount">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Maximum Amount</label>
							<input type="text" class="form-control" name="max_amount" required placeholder="maximum amount">
                        </div>

                        <div class="form-group col-md-4">
                            <button type="submit" class="btn btn-primary btn-block">Apply Filter</button>
                        </div>
			            </form>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <div class="row">
        <div class="w-100">
            <div class="card">
                <div class="card-header">
                    <h4>Available Loan Packages</h4>
                    <div class="card-header-action">
                        <a href="#loan-cards" data-tab="loans-tab" class="btn">Cards</a>
                        <a href="#loan-table" data-tab="loans-tab" class="btn active">Table</a>
                    </div>
                </div>
                <div class="card-body">
                    <div data-tab-group="loans-tab" id="loan-cards">

                    </div>
                    <div data-tab-group="loans-tab" id="loan-table" class="table-responsive active">
                        <table class="table table-striped table-hover text-left" id="tableExport" style="width:100%;">
													<thead>
														<tr>
															<th>Loan Name</th>
															<th>Minimum Amount</th>
															<th>Maximum Amount</th>
															<th>Interest Per Annum</th>
															<th>Repayment Plan</th>
															<th>Insured</th>
															<th>Action</th>
														</tr>
													</thead>

													<tbody>
														@foreach($packages as $package)
															<tr>
																<td>{{ $package->name }}</td>
																<td>{{ $package->currency }} {{ $package->min_amount }}</td>
																<td>{{ $package->currency }} {{ $package->max_amount }}</td>
																<td>{{ $package->interest_rate }} %</td>
																<td>{{ $package->repayment_plan }}</td>
																<td>
																	@if($package->insured)
																		Yes
																	@else
																		No
																	@endif
																</td>
																<td>
																	<a href="{{ route('user.applyloan', $package->id) }}" class="btn btn-sm btn-primary">Apply</a>
																</td>
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
	{{-- <script>
		$(document).ready(function (e) {
			var form = $('#filterForm');
			form.submit(function(e) {
				event.preventDefault();
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.ajax({
					type: 'POST',
					url: '{{ URL::to('organization/filter-loans') }}',
	                dataType: "json",
	                success: function(data) {
	                	$('tbody').empty();
	                	$('tbody').html(data);
	                }
				});
			});
		})
	</script> --}}

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
