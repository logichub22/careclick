@extends('back/organization/layouts/master')

@section('title')
	Loan Requests
@endsection

@section('one-step')
    / Loan Requests
@endsection

@section('spec-styles')
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
	<div class="row">
        <div class="w-100">
            <div class="card">
                <div class="card-header">
                    <h4 class="font-weight-light">Loan Requests in {{ $organization->detail->name }}</h4>
                    @if ($user->isFirstSource())
                    <div class="card-header-action">
                        <a href="{{ route('organization.loans') }}" class="btn btn-info btn-icon icon-right">Approved Loans<i class="fas fa-chevron-right"></i></a>
                    </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md text-left datatable">
                            <thead>
								<tr>
                                    <th>S/N</th>
									<th>Name</th>
									<th>Email
                                        @if ($is_firstsource)
                                        / Phone No
                                        @endif
                                    </th>
                                    @if ($is_firstsource) <th>Package Name</th> @endif
                                    <th>Amount</th>
									<th>Date of Request</th>
									<th>Action</th>
								</tr>
							</thead>
							@if(count($loan_requests) > 0)
							<tbody>
                                @if ($is_firstsource)
                                    @foreach ($loan_requests as $sn=>$request)
                                        <tr>
                                            <td>{{$sn+1}}</td>
                                            <td>{{ $request->applicant_name }}</td>
                                            <td>{{ $request->applicant_email ?? $request->applicant_phone_number }}</td>
                                            @if($is_firstsource)<td>{{ $request->package_name }}</td>@endif
                                            <td>{{ 'NGN ' . $request->amount }}</td>
                                            <td>{{ date('M j, Y', strtotime($request->application_time)) . ' at ' . date('H:i', strtotime($request->application_time)) }}</td>
                                            <td>
                                                <a href="#viewRequest" data-id="{{ $request->id }}" class="btn btn-primary view-request">View</a> &nbsp;
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach($loan_requests as $sn=>$request)
                                        <tr>
                                            <td>{{ $sn + 1 }}</td>
                                            <td>{{ "{$request->name} {$request->other_names}" }}</td>
                                            <td>{{ $request->email }}</td>
                                            <td>{{ "{$request->currency} {$request->amount}" }}</td>
                                            <td>{{ date('M j, Y', strtotime($request->created_at)) . ' at ' . date('H:i', strtotime($request->created_at)) }}</td>
                                            <td>
                                                <a href="#viewRequest" data-id="{{ $request->id }}" class="btn btn-primary view-request">View</a> &nbsp;
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
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
    <script src="{{ asset('assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/datatables.js') }}"></script>

    <script>
        $(document).ready(function(){
            // let loan_info, loan_details, riby_details;
            $('.view-request').on('click', function(e){
                e.preventDefault();
                let loan_id = $(this).data('id');

                $.get('/organization/loan-requests/'+loan_id, function(response){
                    let loan_info = JSON.parse(response);
                    let loan_details = loan_info.request;
                    let riby_details = loan_info.riby_details;
                    let loan_details_content = "";
                    let insured;
                    let pendingApproval;

                    if(!loan_info.isFirstSource){
                        pendingApproval = loan_details.status == 0;
                        insured = loan_details.insured == 1 ? "Yes" : "No";

                        loan_details_content = `
                            <div class="row">
                                <div class="col-md-6">
                                    <p>
                                        User Name: ${loan_details.name} ${loan_details.other_names}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        Amount: ${loan_details.currency} ${loan_details.amount}
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>
                                        Credit Score: ${loan_details.borrower_credit_score}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        Repayment Plan: ${loan_details.repayment_plan}
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>
                                        Package Name: ${loan_details.packageName}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        Minimum Credit Score: ${loan_details.min_credit_score}
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>
                                        Interest Rate: ${loan_details.interest_rate}%
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        Insured: ${insured}
                                    </p>
                                </div>
                            </div>
                        `;
                            // <div class="row">
                            //     <h4>Identification Document should be displayed here</h4>
                            //     <img src="" alt="" id="id_doc">
                            // </div>

                        /*
                        let id_url;
                        if(loan_details.identification_document == null){
                            id_url=  '';
                        }
                        else{
                            let doc = loan_info.path + loan_details.identification_document;
                            // $("#id_doc").attr('src', id_url);
                        }
                        */
                    }
                    else{
                        pendingApproval = loan_details.approval_stage == 0 || loan_details.approval_stage == 1;
                        let applicant_data = riby_details.applicant_data;

                        let currency = riby_details.loan_type.currency;

                        loan_details_content = `
                            <h5>Applicant Lending Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>
                                        Applicant Name: ${loan_details.applicant_name}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        Amount: ${currency} ${Intl.NumberFormat('en-US').format(riby_details.amount)}
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>
                                        Total Loans Applied for: ${applicant_data.applications.count}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        Total Approved Loans: ${applicant_data.loans.count}
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>
                                        Total Amount Repaid: ${currency} ${Intl.NumberFormat('en-US').format(applicant_data.loans.total_amount_paid)}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        Total Amount Outstanding: ${currency} ${Intl.NumberFormat('en-US').format(applicant_data.loans.total_expected_amount)}
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <h5>Loan Package Details</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>
                                        Package Name: ${loan_details.package_name}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        Interest Rate: ${riby_details.metric.interest_rate}%
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>
                                        Loan Tenure: ${(riby_details.tenure/30)}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p>
                                        Repayment Plan: ${riby_details.loan_type.repayment_frequency}
                                    </p>
                                </div>
                            </div>
                        `;

                        if(loan_info.requires_final_approval == true){
                            let first_approver = `${loan_info.first_approval.by.name} ${loan_info.first_approval.by.other_names}`;

                            loan_details_content += `
                                <br><hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>
                                            First Approved by: <span style='font-weight:bold'>${(first_approver)}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p>
                                            Approval Time: ${loan_info.first_approval.time}
                                        </p>
                                    </div>
                                </div>
                            `;
                        }
                            /*
                            <div class="row">
                                <h4>Identification Document should be displayed here</h4>
                                <img src="" alt="" id="id_doc">
                            </div>
                            */
                    }

                    if(pendingApproval){
                        loan_details_content += `
                            <a href="${loan_info.approveUrl}" class="btn btn-info btn-primary" id="approve-btn"> Approve Request</a>
                            <a href="${loan_info.declineUrl}" class="btn btn-danger" id="decline-btn"> Decline Request</a>
                        `;
                    }
                    else{
                        loan_details_content += `
                        <div class="text-center">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>`;
                    }

                    $("#loan-details-content").html(loan_details_content);
                    $("#viewRequest").modal('show');
                })
            });
        });
    </script>
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
                        <div class="card-body" id="loan-details-content"></div>
                    </div>
                </div>
            </div>
    <!--// End Edit Group Modal //-->
    @endif
@endsection
