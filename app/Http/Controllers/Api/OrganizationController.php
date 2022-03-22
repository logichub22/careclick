<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use Auth;
use DB;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationDetail;
use App\Models\Organization\OrganizationWallet;

use App\Models\General\Loan;
use App\Models\General\LoanDetail;

use App\Helpers\CommonFunctions;

use App\Models\General\FlutterwaveTransfer;


class OrganizationController extends Controller
{
    public function balance(Request $request){
        $user = Auth::user();
        $organization = Organization::where('admin_id', $user->id)->first();
        
        $org_wallet = OrganizationWallet::where('org_id', $organization->id)->first();
        return response()->json([
            'success' => true,
            'payload' => $org_wallet->balance
        ]);
    }

    public function orgDetails(){
        $user = Auth::user();
        $organization = Organization::where('admin_id', $user->id)->with('detail')->first();
        
        return response()->json([
            'success' => true,
            'payload' => $organization
        ]);
    }

    public function getLoanPackages(){
        $user = Auth::user();
        $organization = Organization::where('admin_id', $user->id)->first();
        
        $org_packages = DB::table('loan_packages')->where('org_id', $organization->id)->get();

        return response()->json([
            'success' => true,
            'payload' => $org_packages
        ]);
    }

    public function showLoanPackage($id){
        $user = Auth::user();
        $organization = Organization::where('admin_id', $user->id)->first();
        
        $org_package = DB::table('loan_packages')->where('org_id', $organization->id)->where('id', $id)->first();

        return response()->json([
            'success' => true,
            'payload' => $org_package
        ]);
    }

    public function getLoanRequests(){
        $user = Auth::user();
        $organization = Organization::where('admin_id', $user->id)->first();

        $loan_requests = DB::table('loans')
                ->join('users', 'users.id', '=', 'loans.user_id')
                ->join('loan_packages', 'loan_packages.id', '=', 'loans.loan_package_id')
                ->select('users.name', 'users.other_names', 'users.email', 'loans.amount', 'loans.borrower_credit_score', 'loans.status', 'loans.created_at', 'loan_packages.name as packageName', 'loan_packages.min_credit_score', 'loan_packages.interest_rate', 'loan_packages.insured', 'loan_packages.repayment_plan', 'loan_packages.currency', 'loans.id', 'loans.loan_package_id', 'users.identification_document')
                ->where('loan_packages.org_id', $organization->id)
                ->where('loans.status', '=', '0')
                ->get();

        return response()->json([
            'success' => true,
            'payload' => $loan_requests
        ]);
    }

    public function showLoanRequest($id){
        $user = Auth::user();
        $organization = Organization::where('admin_id', $user->id)->first();
        
        $loan_request = DB::table('loans')
            ->join('users', 'users.id', '=', 'loans.user_id')
            ->join('loan_packages', 'loan_packages.id', '=', 'loans.loan_package_id')
            ->select('users.name', 'users.other_names', 'users.email', 'loans.amount', 'loans.borrower_credit_score', 'loans.status', 'loans.created_at', 'loan_packages.name as packageName', 'loan_packages.min_credit_score', 'loan_packages.interest_rate', 'loan_packages.insured', 'loan_packages.repayment_plan', 'loan_packages.currency', 'loans.id', 'loans.loan_package_id', 'users.identification_document')
            ->where('loans.status', '=', '0')
            ->where('loans.id', $id)
            ->first();

        return response()->json([
            'success' => true,
            'payload' => $loan_request
        ]);
    }

    public function approveLoan(Request $request, $id){
        $user = $request->user();
        $loan = Loan::where('id', $id)->where('status', 0)->first();

        if($loan){
            CommonFunctions::approveLoan($id, $user);

            $loan = Loan::where('id', $id)->first();
            $loan_details = LoanDetail::where('loan_id', $id)->first();

            $loan->loan_details = $loan_details;

            return response()->json([
                'success' => true,
                'payload' => $loan
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'error' => "Loan not found"
            ], 404);
        }
    }

    public function declineLoan(Request $request, $id){
        $user = $request->user();
        $loan = Loan::where('id', $id)->where('status', 0)->first();

        if(!$loan){
            CommonFunctions::declineLoan($id);

            $loan = Loan::where('id', $id)->first();
            $loan_details = LoanDetail::where('loan_id', $id)->first();

            $loan->loan_details = $loan_details;

            return response()->json([
                'success' => true,
                'payload' => $loan
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'error' => "Loan not found"
            ], 404);
        }
    }

    public function listLoans(){
        $loans = Loan::all();
        
        return $loans;
    }
    
    
    
    public function processWithdrawal(Request $request){
        
        
        // $result = CommonFunctions::uploadImageToAzureStorage($request);
        // dd($result); using this for test
        
        $user = Auth::user();
        $org = DB::table('organizations')
            ->join('organization_details', 'org_id', '=', 'organizations.id')
            ->where('organizations.admin_id', '=', $user->id)
            ->select('organization_details.*')
            ->first();
       $payload = $request->all();
       $currency =  DB::table('organizations')
       ->join('organization_details', 'org_id', '=', 'organizations.id')
       ->join('currencies', 'country_id', '=', 'organization_details.country')
       ->where('organizations.admin_id', '=', $user->id)
       ->select('currencies.prefix')
       ->first();
    //    if($currency == "NGN"){
    //      $verifyAccountNumber = CommonFunctions::verifyAccountNumber($payload);
    //    }
       $transfer = CommonFunctions::TransferViaFlutterwave($payload);
              
       $data = json_decode(json_encode($transfer));
       
       
       if($data->status == 'success'){
            $transaction = new FlutterwaveTransfer();
            $transaction->user_id = $user->id;
            $transaction->org_id = $user->org_id;
            $transaction->full_name = $data->data->full_name;
            $transaction->bank_name = $data->data->bank_name;
            $transaction->reference = $data->data->reference;
            $transaction->narration = $data->data->narration;
            $transaction->status = $data->data->status;
            $transaction->account_number = $data->data->account_number;
            $transaction->amount = $data->data->amount;
            $transaction->date = $data->data->created_at;
            $transaction->amount = $data->data->amount;
            $transaction->save();
            if($transaction->id){
                
                $org_wallet = OrganizationWallet::where('org_id', $org->org_id)->first();
                $wallet_balance = $org_wallet->balance;
                
                $org_wallet->balance = $wallet_balance - $transaction->amount;
                $org_wallet->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Wallet debited successfully',
                    'wallet_balance' => $org_wallet,
                    'transaction' => $transaction
                ], 200);
            }else {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaction was unsuccesful',
                    'wallet_balance' => $org_wallet,
                ], 200);
            }
       }
       if($data->status == 'error'){
        return response()->json([
            'success' => true,
            'message' => $data->message,
        ], 200);
       }
       
    }
    
    
    public function testAzureImageUpload(Request $request){
           
        $result = CommonFunctions::uploadImageToAzureStorage($request);
        return $result;
       
    }
    
    
    
    public function processBillsPayment(Request $request){
     
        
        $result = CommonFunctions::uploadImageToAzureStorage($request);
        dd($result); 
        
        $user = Auth::user();
        $org = DB::table('organizations')
            ->join('organization_details', 'org_id', '=', 'organizations.id')
            ->where('organizations.admin_id', '=', $user->id)
            ->select('organization_details.*')
            ->first();
       $payload = $request->all();
       $currency =  DB::table('organizations')
       ->join('organization_details', 'org_id', '=', 'organizations.id')
       ->join('currencies', 'country_id', '=', 'organization_details.country')
       ->where('organizations.admin_id', '=', $user->id)
       ->select('currencies.prefix')
       ->first();
    //    if($currency == "NGN"){
    //      $verifyAccountNumber = CommonFunctions::verifyAccountNumber($payload);
    //    }
       $transfer = CommonFunctions::TransferViaFlutterwave($payload);
              
       $data = json_decode(json_encode($transfer));
       
       
       if($data->status == 'success'){
            $transaction = new FlutterwaveTransfer();
            $transaction->user_id = $user->id;
            $transaction->org_id = $user->org_id;
            $transaction->full_name = $data->data->full_name;
            $transaction->bank_name = $data->data->bank_name;
            $transaction->reference = $data->data->reference;
            $transaction->narration = $data->data->narration;
            $transaction->status = $data->data->status;
            $transaction->account_number = $data->data->account_number;
            $transaction->amount = $data->data->amount;
            $transaction->date = $data->data->created_at;
            $transaction->amount = $data->data->amount;
            $transaction->save();
            if($transaction->id){
                
                $org_wallet = OrganizationWallet::where('org_id', $org->org_id)->first();
                $wallet_balance = $org_wallet->balance;
                
                $org_wallet->balance = $wallet_balance - $transaction->amount;
                $org_wallet->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Wallet debited successfully',
                    'wallet_balance' => $org_wallet,
                    'transaction' => $transaction
                ], 200);
            }else {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaction was unsuccesful',
                    'wallet_balance' => $org_wallet,
                ], 200);
            }
       }
       if($data->status == 'error'){
        return response()->json([
            'success' => true,
            'message' => $data->message,
        ], 200);
       }
       
    }
}
