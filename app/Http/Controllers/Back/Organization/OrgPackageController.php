<?php

namespace App\Http\Controllers\Back\Organization;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\General\LoanPackage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\General\Currency;
use App\Http\Requests\LoanPackageRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Organization\Organization;
use App\Models\General\Loan;
use App\Models\Organization\OrganizationWallet;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Jobs\PushPackageToRiby;
use App\Models\Organization\OrganizationDetail;
use App\Helpers\RibyFunctions;
use App\Helpers\OrganizationFunctions;

class OrgPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $isFirstSource = $user->isFirstSource();
        $packages = OrganizationFunctions::loanPackages($user);
        // return (array) $packages;

        return view('back/organization/loan-package/index', compact('packages', 'user', 'isFirstSource'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $isFirstSource = $user->isFirstSource();

        $organization = OrganizationFunctions::userOrganization($user)['organization'];
        $org_currency = OrganizationFunctions::organizationCurrency($organization);

        $walletBalance = OrganizationWallet::where('org_id', $organization->id)->first();

        return view('back/organization/loan-package/create', compact('walletBalance', 'user', 'org_currency', 'isFirstSource'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoanPackageRequest $request)
    {
        $user = Auth::user();
        $isFirstSource = $user->isFirstSource();

        $organization = OrganizationFunctions::userOrganization($user)['organization'];
        // $organization = Organization::where('admin_id', $user->id)->first();
        $detail = OrganizationDetail::where('org_id', $organization->id)->first();

        $walletBalance = OrganizationWallet::where('org_id', $organization->id)->first();

        if (!$isFirstSource && ($request->min_amount > $walletBalance->balance || $request->max_amount > $walletBalance->balance)) {
            Session::flash('error', 'Please ensure the minimum and maximum amount fall between your wallet balance (' . $walletBalance->balance . ')');
            return redirect()->back();
        }
        else{
            $package = new LoanPackage();
            $package->org_id = $organization->id;
            $package->name = $request->name;
            $package->repayment_plan = $request->repayment_plan;
            $package->min_credit_score = $request->min_score;
            // $package->max_credit_score = $request->max_score;
            $package->insured = $request->insured;
            $package->min_amount = $request->min_amount;
            $package->max_amount = $request->max_amount;
            $package->currency = $request->currency;
            $package->interest_rate = $request->interest;
            $package->description = $request->description;
            // $package->save();

            if($isFirstSource){
                $data = [
                    'package_name' => $package->name,
                    'min_amount' => $package->min_amount,
                    'max_amount' => $package->max_amount,
                    'max_tenure' => $request->max_tenure,
                    'interest_rate' => $package->interest_rate,
                    'currency' => $package->currency,
                    // 'administrative_fee_rate' => 1,
                    // 'administrative_fee_frequency' => "ONCE",
                    'owner_name' => $detail->name,
                    'description' => $package->description,
                    'repayment_frequency' => $package->repayment_plan,
                    'late_repayment_frequency' => $package->repayment_plan,
                    // 'min_approval' => $package->min_credit_score,
                    // 'owner_id' => $organization->id,
                    // 'owner_type' => 'Organization',
                ];

                // Push Loan Offer to Riby
                //PushPackageToRiby::dispatch($data);
                $ribyPush = RibyFunctions::newLoanType($data);
                if($ribyPush->responseCode == 1 && $ribyPush->responseText == "ok"){
                    $package->riby_loantype_id = $ribyPush->payload->id;
                }

            }

            $package->save();

            $package_id = $isFirstSource ? $ribyPush->payload->id : $package->id;

            //Proceed with insurance if the loan is insured
            $hasInsurance = $package->insured;
            switch ($hasInsurance) {
                case true:
                    Session::flash('success', $package->name . ' has been created successfully. Kindly proceed with insuring it below');
                    return redirect()->route('packages.getInsurance', $package_id);
                    break;
                case false:
                    Session::flash('success', $package->name . ' has been created successfully for your organization');
                    return redirect()->route('org-packages.show', $package_id);
                    break;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $isFirstSource = $user->isFirstSource();

        $monthly_interest = $weekly_interest = $daily_interest = 0;

        if($isFirstSource){
            $riby_response = RibyFunctions::RibyShowLoanType($id);
            $package = $riby_response->payload;
            $datas = [];
        }
        else{
            $package = LoanPackage::findOrFail($id);

            $datas = DB::table('users')
                    ->join('loans', 'loans.user_id', '=', 'users.id')
                    ->select('users.name', 'users.other_names', 'loans.amount', 'loans.status', 'loans.id')
                    ->where(['loans.loan_package_id' => $package->id])
                    ->get();

            $percentage_decimal = $package->interest_rate/100;
            $monthly_interest = ($percentage_decimal/12)*100;
            $weekly_interest  = ($percentage_decimal/52)*100;
            $daily_interest = ($percentage_decimal/365)*100;
        }

        return view('back/organization/loan-package/show', compact('package', 'datas', 'user', 'monthly_interest', 'weekly_interest', 'daily_interest', 'isFirstSource'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $isFirstSource = $user->isFirstSource();

        $package_data = OrganizationFunctions::loanPackageDetails($id, $user);

        $package = $package_data['package'];
        $riby_details = $package_data['riby_details'];

        $organization = OrganizationFunctions::userOrganization($user)['organization'];

        $walletBalance = OrganizationWallet::where('org_id', $organization->id)->first();

        // check if loan package has active loans
        $loans = $datas = [];
        if(!$isFirstSource){
            $loans = DB::table('loans')->where('loan_package_id', $package->id)->whereNotIn('status', [3])->get();

            $datas = DB::table('users')
                ->join('loans', 'loans.user_id', '=', 'users.id')
                ->select('users.name', 'users.other_names', 'loans.amount', 'loans.status', 'loans.id')
                ->where(['loans.loan_package_id' => $package->id])
                ->get();
        }

        return view('back/organization/loan-package/edit', compact('package', 'loans', 'user', 'datas', 'walletBalance', 'isFirstSource', 'riby_details'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $isFirstSource = $user->isFirstSource();


        $package_data = OrganizationFunctions::loanPackageDetails($id, $user);

        $package = $package_data['package'];
        $riby_details = $package_data['riby_details'];

        $package->name = $request->name;
        $package->min_amount = $request->min_amount;
        $package->max_amount = $request->max_amount;
        $package->interest_rate = $request->interest_rate;
        $package->description = $request->description;
        $package->save();

        if($isFirstSource){
            $data = [
                'name' => $request->name,
                'min_amount' => $request->min_amount,
                'max_amount' => $request->max_amount,
                'description' => $request->description,
                'max_tenure' => $request->max_tenure * 30,
                'interest_rate' => $request->interest_rate,
                'visibility' => 1
            ];

            $loantype_id = $package->riby_loantype_id;

            $update_package = RibyFunctions::RibyEditLoanType($loantype_id, $data);

            if($update_package->responseCode != 1){
                Session::flash('error', 'Update error: ' .$update_package->responseText);
                return redirect()->route('org-packages.edit', $id);
            }
        }

        Session::flash('success', 'Loan package modified successfully');
        return redirect()->route('org-packages.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $isFirstSource = $user->isFirstSource();

        if($isFirstSource){
            $package_data = OrganizationFunctions::loanPackageDetails($id, $user);
            $loantype_id = $package_data['package']->riby_loantype_id;

            $delete_package = RibyFunctions::RibyDeleteLoanType($loantype_id);

            if($delete_package->responseCode != 1){
                Session::flash('warning', 'Failed to delete loan package: ' .$delete_package->responseText);
            }
            else{
                //Delete from DB
                $packages = LoanPackage::destroy($id);
                Session::flash('success', 'Package has been deleted successfully');
            }

        }
        else{
            if(Loan::where('loan_package_id', $id)->count() > 0) {
                Session::flash('warning', 'Package cannot be deleted because it has active subscribers.');
            }
            else {
                return "deleting...";
                $packages = LoanPackage::destroy($id);
                Session::flash('success', 'Package has been deleted successfully');
            }
        }
        return redirect()->route('org-packages.index');
    }

    public function getInsureLoan(Request $request, $id)
    {
        $user = Auth::user();
        $package = LoanPackage::findOrFail($id);

        // Get all insurance providers and their packages

        return view('back/organization/insurance/insure-package', compact('package', 'user'));
    }

    public function getBorrowerDetail($id)
    {
        $loan = Loan::findOrFail($id);

        $detail = DB::table('loan_details')->where('loan_id', $loan->id)->first();

        $user = DB::table('users')
              ->join('user_details', 'user_details.user_id', '=', 'users.id')
              ->where('users.id', $loan->user_id)
              ->first();

        return view('back/organization/loan-package/borrower', compact('loan', 'detail', 'user'));
    }

    public function loanRequests(Request $request)
    {
        $user = Auth::user();
        $org_array = OrganizationFunctions::userOrganization($user);
        $organization = $org_array['organization'];
        $is_firstsource = $user->isFirstSource();

        $loan_requests = OrganizationFunctions::loanRequests($user, $org_array);

        return view('back/organization/loan-package/requests', compact('organization', 'user', 'loan_requests', 'is_firstsource'));
    }

    public function loanRequestDetails($id){
        $user = Auth::user();
        $isFirstSource = $user->isFirstSource();

        $request_details = OrganizationFunctions::loanRequestDetails($id, $user);
        return json_encode($request_details);

        /*
        if($isFirstSource){
            $loan_request = RibyFunctions::RibyGetLoanRequestDetails($id);

            return json_encode([
                'isFirstSource' => true,
                'request' => $loan_request->payload,
                'path' => $path,
                'approveUrl' => route('organization.approve', $id),
                'declineUrl' => route('organization.decline', $id),
            ]);
        }
        else{
            $loan_request = DB::table('loans')
                    ->join('users', 'users.id', '=', 'loans.user_id')
                    ->join('loan_packages', 'loan_packages.id', '=', 'loans.loan_package_id')
                    ->select('users.name', 'users.other_names', 'users.email', 'loans.amount', 'loans.borrower_credit_score', 'loans.status', 'loans.created_at', 'loan_packages.name as packageName', 'loan_packages.min_credit_score', 'loan_packages.interest_rate', 'loan_packages.insured', 'loan_packages.repayment_plan', 'loan_packages.currency', 'loans.id', 'loans.loan_package_id', 'users.identification_document')
                    ->where('loans.status', '=', '0')
                    ->where('loans.id', $id)
                    ->first();

            return json_encode([
                'isFirstSource' => false,
                'request' => $loan_request,
                'path' => $path,
                'approveUrl' => route('organization.approve', $id),
                'declineUrl' => route('organization.decline', $id),
            ]);
        }
        */
    }

    public static function approveLoan($id, $user){
        //
    }
}
