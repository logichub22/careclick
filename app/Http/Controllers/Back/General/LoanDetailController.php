<?php

namespace App\Http\Controllers\Back\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Organization\OrgLoanDetail;
use App\Models\Organization\OrgLoan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class LoanDetailController extends Controller
{
    public function getOrgLoanDetail(Request $request, $id)
    {
    	$user = Auth::user();

    	$loan = OrgLoan::findOrFail($id);

    	$loanDetails = DB::table('org_loans')
	                ->join('users', 'users.id', '=', 'org_loans.user_id')
	                ->join('org_loan_details', 'org_loan_details.loan_id', '=', 'org_loans.id')
	                ->select('users.name', 'users.other_names', 'org_loans.amount', 'org_loans.status', 'org_loans.id as l_id', 'org_loan_details.*')
	                ->where(['org_loan_details.loan_id' => $loan->id])
	                ->get();
    	return view('back/organization/loan/organization/loan-details', compact('loanDetails'));
    }

    public function getIndividualLoanDetail(Request $request, $id)
    {
    	
    }
}
