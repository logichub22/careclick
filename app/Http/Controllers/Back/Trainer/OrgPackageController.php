<?php

namespace App\Http\Controllers\Back\Trainer;

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

        $organization = Organization::where('admin_id', $user->id)->first();
        $packages = LoanPackage::where('org_id', $organization->id)->get();
        return view('back/federation/loan-package/index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currencies = Currency::all();

        $user = Auth::user();

        $organization = Organization::where('admin_id', $user->id)->first();

        $walletBalance = OrganizationWallet::where('org_id', $organization->id)->first();

        return view('back/federation/loan-package/create', compact('currencies', 'walletBalance'));
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

        $organization = Organization::where('admin_id', $user->id)->first();
        $detail = OrganizationDetail::where('org_id', $organization->id)->first();

        $walletBalance = OrganizationWallet::where('org_id', $organization->id)->first();

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

        if ($request->min_amount > $walletBalance->balance || $request->max_amount > $walletBalance->balance) {
            Session::flash('error', 'Please ensure the minimum and maximum amount fall between your wallet balance (' . $walletBalance->balance . ')');
            return redirect()->back();
        } else {
            $package->save();
            $hasInsurance = $package->insured;

            $data = [
                'package_name' => $package->name,
                'interest_rate' => $package->interest_rate,
                'min_amount' => $package->min_amount,
                'max_amount' => $package->max_amount,
                'repayment_frequency' => $package->repayment_plan,
                'description' => $package->description,
                'currency' => $package->currency,
                'administrative_fee_rate' => 1000,
                'owner_id' => $organization->id,
                'owner_type' => 'Organization',
                'owner_name' => $detail->name,
                'description' => $package->description,
                'min_approval' => $package->min_credit_score,
            ];

           // Push Loan Offer to Riby
           //PushPackageToRiby::dispatch($data);

            //Proceed with insurance if the loan is insured 
            switch ($hasInsurance) {
                case true:
                    Session::flash('success', $package->name . ' has been created successfully. Kindly proceed with insuring it below');
                    return redirect()->route('packages.getInsurance', $package->id);
                    break;
                case false:
                    Session::flash('success', $package->name . ' has been created successfully for your organization');
                    return redirect()->route('org-packages.show', $package->id);
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
        $package = LoanPackage::findOrFail($id);

        $datas = DB::table('users')
                ->join('loans', 'loans.user_id', '=', 'users.id')
                ->select('users.name', 'users.other_names', 'loans.amount', 'loans.status', 'loans.id')
                ->where(['loans.loan_package_id' => $package->id])
                ->get();

        return view('back/federation/loan-package/show', compact('package', 'datas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $package = LoanPackage::findOrFail($id);
        
        // check if loan package has active loans
        $loans = DB::table('loans')->where('loan_package_id', $package->id)->whereNotIn('status', [3])->get();

        return view('back/federation/loan-package/edit', compact('package', 'loans'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getInsureLoan(Request $request, $id)
    {
        $package = LoanPackage::findOrFail($id);

        // Get all insurance providers and their packages

        return view('back/federation/insurance/insure-package', compact('package'));
    }

    public function getBorrowerDetail($id)
    {
        $loan = Loan::findOrFail($id);

        $detail = DB::table('loan_details')->where('loan_id', $loan->id)->first();

        $user = DB::table('users')
              ->join('user_details', 'user_details.user_id', '=', 'users.id')
              ->where('users.id', $loan->user_id)
              ->first();

        return view('back/federation/loan-package/borrower', compact('loan', 'detail', 'user'));
    }
}
