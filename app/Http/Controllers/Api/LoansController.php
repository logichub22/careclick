<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Back\Individual\UserLoanController;
use App\Models\General\Loan;
use App\Models\General\LoanDetail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\General\LoanPackage;
use Illuminate\Support\Facades\Validator;
use App\Services\Loan\OrgLoanService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\General\Wallet;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationWallet;
use App\Mail\LoanBorrowedEmail;
use App\Mail\NotifyLenderEmail;
use Mail;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use App\Models\General\Transaction;
use App\User;
use App\Notifications\LoanAppliedNotification;
use App\Models\General\Currency;
use App\Http\Requests\LoanPackageRequest;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Jobs\PushPackageToRiby;

use App\Helpers\CommonFunctions;

class LoansController extends Controller
{
    //Get the available loans

    public function getLatestPackages(Request $request){
    		$user = $request->user();

	    	if(!$user) {
	            return response()->json([
	                'success' => false,
	                'message' => 'User not found'
	            ]);
	        }

    	    $packages = DB::table('loan_packages')
			            ->where('status', true)
			            ->whereNotIn('user_id',[$user->id])
			            ->orderby('id','desc')
			            ->take(5)
			            ->get();

            // dd($packages);

               return response()->json([                 	
			        'success' => true,
			        'message' => 'Latest Loan packages retrieved successfully',
			        'packages' => $packages
        	]);
    }


    public function getAllPackages(Request $request){

    	$user = $request->user();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        return response()->json([                 	
            'success' => true,
            'message' => 'All Loan packages retrieved successfully',
            'packages' => CommonFunctions::showLoanPackages($user)
        ]);			            

        /*
    	//get the already displayed packages
        $latest = DB::table('loan_packages')
                    ->where('status', true)
                    ->whereNotIn('user_id',[$user->id])
                    ->orderby('id','desc')
                    ->take(5)
                    ->pluck('id');
		//fetch only the remaining packages
        $packages = DB::table('loan_packages')
                    ->where('status', true)
                    ->whereNotIn('user_id',[$user->id])
                    ->whereNotIn('id',$latest)
                    ->orderby('id','desc')
                    ->take(5)
                    ->get();

                // dd($packages);
        */

    }

    //get an individual package details
    public function getPackageDetails(Request $request,$id){
    		$user = $request->user();

    	   if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

    		$packagedetails = DB::table('loan_packages')
			            ->where('status', true)
			        	->where('id',$id)
			            ->first();


			            
           return response()->json([                 	
			        'success' => true,
			        'message' => 'Loan package retrieved successfully',
			        'packagedetails' => $packagedetails
        	]);			

    }


    //make a loan application request for the loan package. Packege identified by it id parameter

    public function initiateLoanApplication(Request $request, $id){
        $user = $request->user();        
    	if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $input = $request->all();
        $validator = \Validator::make($input, [
            'amount' => 'required|numeric',
            'length_of_loan' => 'required|numeric',        
            'loan_title' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 406);
        }

        $amount = $request->amount;
        $duration = $request->length_of_loan;
        $loan_title = $request->loan_title;
        $package_id = $id;

        $loan_data = CommonFunctions::generateLoanData($user, $package_id, $amount, $duration, $loan_title);

        return response()->json([                    
            'success' => true,
            'message' => 'Loan Request Details',
            'loanrequest' => $loan_data
        ]);
    }

    public function loanApplicationRequest(Request $request,$id){

    	$user = $request->user();
    	$input = $request->all();
    	if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $validator = \Validator::make($input, [

            'amount' => 'required|numeric',
            'length_of_loan' => 'required|numeric',        
          	'payment_frequency' => 'nullable|numeric',
            'loan_title' => 'required',
            
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        //user wallet balance
        $balance = Wallet::where('user_id', $user->id)->pluck('balance')->toArray();
        $bal = implode($balance);

        // dd($bal);

        //the loan package details
        $packagedetails = DB::table('loan_packages')->where('status', true)->where('id',$id)
			              ->first();
        // Calculations
        $principal = $request->amount;
        $lengthOfLoan = $request->length_of_loan;
        $paymentFrequency = $request->payment_frequency;
        $annualInterestRate = $packagedetails->interest_rate / 100; // Convert to decimal for simplicity
        $titleOfLoan = $request->loan_title;
        $creditScore = 2;  //to be changed according to the credit score engine
        $walletBalance = $bal;
        // $processingFee = 0.02*principal;
        $rate = 0;
        $time = 0;
        $currency = $packagedetails->currency;
        $serviceFee = 1000;

        // USE PERIOD IN YEARS FOR SIMPLICITY
        // A = P(1 + rt) where A is total amount payable, r is interest rate as a decimal, t is time in years.

        switch ($paymentFrequency) {
            case 'WEEKLY':
                $time = round($lengthOfLoan / 52, 1);
                //$rate = $annualInterestRate / 52;
                break;
            case 'BI-WEEKLY':
                $time = round($lengthOfLoan / 26, 1);
                //$rate = $annualInterestRate / 26;
                break;
            case 'MONTHLY':
                $time = round($lengthOfLoan / 12, 1);
                //$rate = $annualInterestRate / 12;
                break;
        }        

        $valueOne = $annualInterestRate * $time;
        $valueTwo = 1 + $valueOne;
        $initialPayable = $principal * $valueTwo; // No service fee here
        $amountPayable = $initialPayable + $serviceFee;
        // $totalAmountPayable = $processingFee + $amountPayable;
        $interest = $initialPayable - $principal;

        // Calculate charge per installment
        $installmentAmount = $initialPayable / $lengthOfLoan;

        // CREDIT SCORE API 
        $creditScoreval = new UserLoanController();
        // $creditScoreval->creditScore();
        $score = $creditScoreval->creditScore();
        $request = [
            'principal' => number_format($principal),
            'subTotal' => number_format($initialPayable),
            'totalAmount' => number_format(round($amountPayable, 2)),
            'totalInterest' => number_format(round($interest, 2)),
            'installmentAmount' => number_format(round($installmentAmount, 2)),
            'titleOfLoan' => $titleOfLoan,
            'lengthOfLoan' => $lengthOfLoan,
            'expectedScore' => $creditScore,
            'score' => $score,
            'walletBalance' => $walletBalance,
            'serviceFee' => $serviceFee,
            'paymentFrequency' => $paymentFrequency,
            'annualInterest' =>$packagedetails->interest_rate,
            'currencyUnit' => $currency,
        ];

        // dd($res);
        return response()->json([                    
                    'success' => true,
                    'message' => 'Loan Request Details',
                    'loanrequest' => $request
            ]); 

    }

    public function confirmLoanApplication(Request $request, $id){
        $user = $request->user();        
    	if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $input = $request->all();
        $validator = \Validator::make($input, [
            'amount' => 'required|numeric',
            'length_of_loan' => 'required|numeric',        
            'loan_title' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 406);
        }

        $amount = $request->amount;
        $duration = $request->length_of_loan;
        $loan_title = $request->loan_title;
        $package = LoanPackage::where('id', $id)->first();

        $loan_application = CommonFunctions::requestLoan($user, $package, $amount, $duration, $loan_title);
        
        $response_code = $loan_application[0] == true ? 200 : 400;

        return response()->json([                    
            'success' => $loan_application[0],
            'message' => $loan_application[1]
        ], $response_code);
    }

    public function confirmLoanApplication_old(Request $request,$id){

    	$user = $request->user();

    	$input = $request->all();

    	if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $validator = \Validator::make($input, [

            'principal' => 'required',
            'subTotal' => 'required',
            'totalAmount' => 'required',
            'totalInterest' => 'required',
            'installmentAmount' => 'required',
            'titleOfLoan' => 'required',
            'lengthOfLoan' => 'required',
            'expectedScore' => 'required',
            'score' => 'required',
            'walletBalance' => 'required',
            'serviceFee' => 'required',
            'paymentFrequency' => 'required',
            'annualInterest' => 'required',
            'currencyUnit' => 'required',
            
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }


        $serviceFee = 1000;

        $creditScoreval = new UserLoanController();
       
        $score = $creditScoreval->creditScore();
   
        $mypackage = LoanPackage::where('id', $id)->first();

        if(is_null($mypackage->org_id)) {
            $wallet = Wallet::where('user_id', $mypackage->user_id)->first();
            $lender = User::where('id', $mypackage->user_id)->first();
        } else {
            $wallet = OrganizationWallet::where('org_id', $mypackage->org_id)->first();
            $lender = DB::table('users')
                    ->join('organizations', 'organizations.admin_id','=','users.id')
                    ->where('organizations.id','=',$mypackage->org_id)
                    ->select('users.*')
                    ->first();
        }

        if($request->amount > $wallet->balance) {
               return response()->json([                 	
			        'success' => false,
			        'message' => 'Loan Denied! This loan cannot be lend out at the moment'

        	]);	
            return redirect()->back();
        } else {
            // Store into loans table
            $loan = new Loan();
            $loan->loan_title = $request->loan_title;
            $loan->user_id = $request->user;
            $loan->loan_package_id = $id;
            $loan->amount = $request->amount;
            $loan->borrower_credit_score = $score;
            $loan->status = 1;
            $loan->save();

            // Interest Calculations
            $lengthOfLoan = $request->length_of_loan;
            $paymentFrequency = $request->payment_frequency;
            $annualInterestRate = $request->interest_rate / 100;

            $rate = 0;
            $time = 0;
            $currency = 'Naira';

            $principal = $request->amount;

            switch ($paymentFrequency) {
                case 'WEEKLY':
                    $time = round($lengthOfLoan / 52, 1);
                    //$rate = $annualInterestRate / 52;
                    break;
                case 'BI-WEEKLY':
                    $time = round($lengthOfLoan / 26, 1);
                    //$rate = $annualInterestRate / 26;
                    break;
                case 'MONTHLY':
                    $time = round($lengthOfLoan / 12, 1);
                    //$rate = $annualInterestRate / 12;
                    break;
            }

            $valueOne = $annualInterestRate * $time;
            $valueTwo = 1 + $valueOne;
            $initialPayable = $principal * $valueTwo;
            $amountPayable = $initialPayable + $serviceFee;
            // $totalAmountPayable = $processingFee + $amountPayable;
            $interest = $initialPayable - $principal;

            // Calculate charge per installment
            $installmentAmount = $initialPayable / $lengthOfLoan;
            $calcpay = new UserLoanController();

            $date = $calcpay->calcPaymentDate($request);

            $today = Carbon::now();

            if ($request->payment_frequency === "WEEKLY") {
                $nextPayment = $today->addWeek(1);
                $payDate = $nextPayment->toDateString();
            } elseif ($request->payment_frequency === "BI-WEEKLY") {
                $nextPayment = $today->addWeeks(2);
                $payDate = $nextPayment->toDateString();
            } else{
                $nextPayment = $today->addMonth(1);
                $payDate = $nextPayment->toDateString();
            }

            // Store into Loan Details Table
            $detail = new LoanDetail();
            $detail->loan_id = $loan->id;
            $detail->package_name = $mypackage->name;
            $detail->principal_due = $loan->amount;
            $detail->interest_due = $interest;
            $detail->amount_payable = $initialPayable;
            $detail->balance = $initialPayable;
            $detail->charge_per_installment = $installmentAmount;
            $detail->no_of_installments = $lengthOfLoan;
            $detail->interest_charge_frequency = $paymentFrequency;
            $detail->payback_date = $date;
            $detail->next_payment_date = $payDate;
            $detail->save();     

            $bl = DB::table('wallets')->where('user_id', $user->id)->pluck('balance')->toArray();
            $bal = implode($bl);
            $balance = floatval($bal);
            $newBalance = $balance + $principal;

            $package = LoanPackage::where('id', $id)->first();
            // dd($type);

            // Debit Lender (Owner of package) => Cash has reduced but he expects more money
            $transaction = new Transaction();
            if(is_null($package->org_id)) { // if a user owns the package
                $transaction->user_id = $package->user_id;
            } else {
                $transaction->org_id = $package->org_id;
            }
            $transaction->txn_code = strtoupper(Str::random(10));
            $transaction->txn_type = 2;
            $transaction->transaction_type_id = DB::table('transaction_types')->where('name', 'loan_request')->value('id');
            // $transaction->transaction_type_id = 2;
            $transaction->amount = $detail->principal_due;
            $transaction->save();

            if(is_null($package->org_id)) {
                $balance = DB::table('wallets')->where('user_id', $package->user_id)->pluck('balance')->toArray();
                $mybal = implode($balance);
                $new = floatval($mybal);
                $nb = $new - $principal;

                DB::table('wallets')->where('user_id', $package->user_id)->update([
                    'balance' => $nb,
                ]);
            } else {
                $balance = DB::table('org_wallets')->where('org_id', $package->org_id)->pluck('balance')->toArray();
                $mybal = implode($balance);
                $new = floatval($mybal);
                $nb = $new - $principal;

                DB::table('org_wallets')->where('org_id', $package->org_id)->update([
                    'balance' => $nb,
                ]);
            }

            // Credit Borrower (User) Account ==> The debt has increased
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->txn_code = strtoupper(Str::random(10));
            $transaction->txn_type = 1;
            $transaction->transaction_type_id = DB::table('transaction_types')->where('name', 'loan_request')->value('id');
            // $transaction->transaction_type_id = 1;
            $transaction->amount = $detail->principal_due;
            $transaction->save();

            // Deposit into wallet and make necessary deductions
            DB::table('wallets')->where('user_id', $user->id)->update(['balance' => $newBalance]);

            $maildata = [
                'principal' => $request->amount,
                'interest' => $request->interest_rate,
                'interest_due' => $interest,
                'package' => $package->name,
                'date' => $date,
                'loanName' => $request->loan_title,
                'name' => $request->name,
                'installments' => $request->length_of_loan,
                'interest_charge_frequency' => $request->payment_frequency,
                'total' => $initialPayable,
            ];

            // $loan = [
            //     'amount' => $request->amount,
            //     'borrower' => $user->name,
            //     'package_name' => $detail->package_name,
            // ];

            // Send mail to borrower
            Mail::to($user->email)->send(new LoanBorrowedEmail($maildata));

            // Send mail to lender
            $lender->notify(new LoanAppliedNotification($detail));
            Mail::to($lender->email)->send(new NotifyLenderEmail($maildata));

             return response()->json([                 	
			        'success' => true,
			        'message' => 'Loan application has been successfully processed'

        	]);	


        }


    }

    public function createPackage(Request $request){

	 	$user = $request->user();
    	$input = $request->all();
    	if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $validator = \Validator::make($input, [
            /*
            'name' => 'required|string|max:255',
            'repayment_plan' => 'required|string|max:255',
          	'min_score' => 'nullable|numeric',
            'insured' => 'required',
            'min_amount' => 'required',
            'max_amount' => 'required',
            'currency' => 'required',
            'interest' => 'required',
            'description' => 'required',
            */

            'name' => 'required|unique:loan_packages,name',
            'repayment_plan' => 'required',
            'min_score' => 'required|numeric|min:1|max:10',
            'min_amount' => 'required|numeric',
            'max_amount' => 'required|numeric',
            'interest' => 'required|numeric|min:1|max:100',
            'description' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 406);
        }

        // $walletBalance = Wallet::where('user_id', $user->id)->first();
        $organization = Organization::where('admin_id', $user->id)->first();
        $detail = OrganizationDetail::where('org_id', $organization->id)->first();
        $walletBalance = OrganizationWallet::where('org_id', $organization->id)->first();
        
        // Check if users wallet has minimum amount
        if($request->min_amount > $walletBalance->balance || $request->max_amount > $walletBalance->balance) {
          
            return response()->json([                 	
                'success' => false,
                'message' => 'Please ensure the minimum and maximum amount fall between your wallet balance (' . $walletBalance->balance . ')'
        	], 406);
        }
        else {
            $package = new LoanPackage();
            $package->org_id = $user->id;
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
            $package->save();

            $hasInsurance = $package->insured;

            /*
            $data = [
                'package_name' => $package->name,
                'interest_rate' => $package->interest_rate,
                'min_amount' => $package->min_amount,
                'max_amount' => $package->max_amount,
                'repayment_frequency' => $package->repayment_plan,
                'description' => $package->description,
                'currency' => $package->currency,
                'administrative_fee_rate' => 1000,
                'owner_id' => $user->id,
                'owner_type' => 'COOPERATIVE',
                "start_date" => '30-06-2018',
		        'end_date' => '30-12-2018',
                'owner_name' => $user->name . ' ' . $user->other_names,
                'description' => $package->description,
                'min_approval' => $package->min_credit_score,
            ];

            //Push Loan Offer to Riby
            PushPackageToRiby::dispatch($data);
            */
            
            //Proceed with insurance if the loan is insured 
            switch ($hasInsurance) {
                case true:
                    return response()->json([                 	
                        'success' => false,
                        'message' => $package->name . ' has been created successfully. Kindly proceed with insuring it.'
                    ]);
                    break;
                case false:
                    return response()->json([                 	
                        'success' => false,
                        'message' => $package->name . ' has been created successfully for your organization.'
                    ]);
                    break;
            }
        }

    }
}
