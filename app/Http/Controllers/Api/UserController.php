<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use App\Models\General\Role;
use Illuminate\Support\Str;
use App\Models\General\Wallet;
use App\SavingsWallet;
use App\Events\UserCreatedEvent;
use App\Models\General\UserDetail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\General\Loan;
use App\Models\General\LoanDetail;
use App\Models\General\Transaction;

use App\Models\General\FlutterwaveTransfer;


use App\Helpers\CommonFunctions;

class UserController extends Controller
{
    public function getProfile(Request $request)
    {
        //return $request->all();
        $user = User::where('id', $request->user()->id)->with(['roles', 'detail', 'wallet'])->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'This profile does not exist'
            ]);
        }

        $detail = DB::table('users')
                      ->join('user_details', 'user_details.user_id', '=', 'users.id')
                      ->where('user_details.user_id', '=' , $user->id)
                      ->first();

        if($detail != null) {
            $organization = DB::table('user_details')
                        ->join('organization_details', 'organization_details.id', '=', 'user_details.org_id')
                        ->where('organization_details.id', '=', $detail->org_id)
                        ->select('organization_details.name')
                        ->first();

            $user['organization'] = $organization;
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile retrieved successfully',
            'user' => $user
        ]);
    }

    public function getProfileUssd($phone)
    {
        if(!$phone) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a phone number',
            ]);
        }

        $user = User::where('msisdn', $phone)->with(['wallet'])->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'This profile does not exist'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile retrieved successfully',
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'other_names' => 'required|string|max:255',
            'email' => [
                'required','string', 'email','max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'msisdn' => [
                'required',
                Rule::unique('users')->ignore($user->id),
            ],
            'account_no' => 'nullable|numeric',
            'city' => 'required',
            'state' => 'sometimes',
            'postal_code' => 'required',
            'address' => 'required',
            'occupation' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        DB::table('users')->where('id', $user->id)->update([
            'name' => $request->name,
            'other_names' => $request->other_names,
            'email' => $request->email,
            'msisdn' => preg_replace('/^0/','255',$request->msisdn),
            'account_no' => $request->account_no
        ]);

        DB::table('user_details')->where('user_id', $user->id)->update([
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'occupation' => $request->occupation
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    }

    public function changePassword(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $oldpassword = $user->password;

        $validator = \Validator::make($request->all(), [
            'currentpassword' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        
        if (Hash::check($request->currentpassword, $oldpassword)) {
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'The current password you have entered is incorrect'
            ]);
        }
    }

    public function getLatestTransactions(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $transactions = DB::table('transactions')->where('user_id', $user->id)->orderBy('id', 'desc')->take(5)->get();

        return response()->json([
            'success' => true,
            'message' => 'Transactions retrieved successfully',
            'transactions' => $transactions
        ]);
    }

    public function getTransactions(Request $request)
    {
        // $user = Auth::user();
        // $orgs = DB::table('organizations')->where('admin_id', $user->id);
        // $user = User::where('id', $request->user()->id)->first();

        $user = Auth::user();
        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        // $latest_transactions = DB::table('transactions')->where('user_id', $user->id)->orderBy('id', 'desc')->take(5)->pluck('id');

        // $transactions = DB::table('transactions')->where('user_id', $user->id)->whereNotIn('id', $latest_transactions)->orderBy('id', 'desc')->get();
        $transactions = DB::table('transactions')->where('user_id', $user->id)->orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Transactions retrieved successfully',
            'transactions' => $transactions
        ]);
    }

    public function getCreditTransactions(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $credits = DB::table('transactions')->where(array('user_id' => $user->id, 'txn_type' => 1))->orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Transactions retrieved successfully',
            'credits' => $credits
        ]);
    }

    public function getDebitTransactions(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $debits = DB::table('transactions')->where(array('user_id' => $user->id, 'txn_type' => 2))->orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Transactions retrieved successfully',
            'debits' => $debits
        ]);
    }

    public function getUserLoanPackages(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $packages = DB::table('loan_packages')->where('user_id', $user->id)->orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Loan packages retrieved successfully',
            'packages' => $packages
        ]);
    }

    public function getUserPackageDetailsByID(Request $request,$id){
       $user = User::where('id', $request->user()->id)->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $packagedata = DB::table('users')
                ->join('loans', 'loans.user_id', '=', 'users.id')
                ->select('users.name', 'users.other_names', 'loans.amount', 'loans.status', 'loans.id')
                ->where(['loans.loan_package_id' => $id])
                ->get();

        return response()->json([
            'success' => true,
            'message' => 'Loan packages retrieved successfully',
            'packages' => $packagedata
        ]);   


    }

    public function getLoanBorrowerDetails(Request $request,$id){

        $user = User::where('id', $request->user()->id)->first();

        // dd($user);

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }
               
        $loan = Loan::find($id);
        // dd($loan);
        if($loan){
            $detail = DB::table('loan_details')->where('loan_id', $loan->id)->first();

            $user = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->where('users.id', $loan->user_id)
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Loan packages retrieved successfully',
                'packages' => $detail,
                'user' => $user
            ]); 
        }

        else{
            return response()->json([
                'success' => false,
                'message' => 'Loan Package does not exist'
            ], 404);
        }
 

    }

    // public function get

    public function getUserLoans(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $loans = DB::table('loans')->where('user_id', $user->id)->orderBy('id', 'desc')->get();
        $loans_arr = [];

        foreach ($loans as $loan) {
            $detail = DB::table('loan_details')->where('loan_id', $loan->id)->first();
            $loan->details = $detail;
            // array_push($loans_arr, $loan);
        }

        return response()->json([
            'success' => true,
            'message' => 'Loans retrieved successfully',
            'loans' => $loans
        ]);
    }

    public function getUserLoanById(Request $request, $id)
    {
        $user = User::where('id', $request->user()->id)->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $loan = Loan::find($id);

        if($loan) {
            $loan_details = LoanDetail::where('loan_id', $loan->id)->first();

            return response()->json([
                'success' => true,
                'message' => $loan_details,
                'repayment_plan' => CommonFunctions::loanRepaymentScheduler($id)
            ]);
        } 

        return response()->json([
            'success' => false,
            'message' => 'Loan does not exist'
        ]);

    }

    public function repayLoan(Request $request, $id)
    {
        $user = Auth::user();

        $request->validate([
            'installments' => 'required',
        ]);

        $installments = $request->installments;

        $repayment_response = CommonFunctions::repayLoan($user, $id, $installments);
        
        $response_code = $repayment_response[0] == true ? 200 : 400;

        return response()->json([                    
            'success' => $repayment_response[0],
            'message' => $repayment_response[1]
        ], $response_code);
    }

    public function getUserNotifications(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'other_names' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'msisdn' => 'required|unique:users,msisdn',
            'gender' => 'required',
            'doc_type' => 'required',
            'doc_no' => 'required|unique:user_details,doc_no',
            'dob' => 'required|date',
            'country' => 'required',
            'residence' => 'required',
            'city' => 'required',
            //'state' => 'required',
            'postal_code' => 'required',
            'address' => 'required',
            'income' => 'required',
            'occupation' => 'required',
            //'identification_document' => 'required',
        ]);

        
    }

    public function getWalletBalance(Request $request){
        $msisdn = $request->phone;
        $user = User::where('msisdn', $msisdn)->first();
        if($user){
            $user_id = $user->id;
            $wallet = Wallet::where('user_id', $user_id)->first();
            return response()->json([
                "success" => true,
                "wallet_balance" => $wallet->balance
            ], 200);
        }
        else{
            return response()->json([
                "success" => false,
                "message" => "User not found",
            ], 404);
        }
    }

    public function creditJustriteWallet(Request $request){
        $loyalty_card_number = $request->loyalty_card_number;
        $amount = $request->amount;
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'https://jamborrowjustrite.posshop-ng.com/ICGService.svc/GetCustomerByCardNo/'.$loyalty_card_number);
        
        $request_response = json_decode($response->getBody());
        if(empty($request_response)){
            return response()->json([
                "success" => false,
                "message" => "User not found",
            ], 404);
        }
        else{
            $client_code = $request_response[0]->ClientCode;
            $client = new \GuzzleHttp\Client();
    
            $response = $client->request('GET', "https://jamborrowjustrite.posshop-ng.com/ICGService.svc/CreditCustomerAccount/{$client_code}/{$amount}");

            $request_response = json_decode($response->getBody());

            return response()->json([
                "success" => true,
                "message" => $request_response
            ], 200);
        }
    }

    public function checkCardNo(Request $request){
        $loyalty_card_number = $request->loyalty_card_number;
        $amount = $request->amount;
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'https://jamborrowjustrite.posshop-ng.com/ICGService.svc/GetCustomerByCardNo/'.$loyalty_card_number);
        
        $request_response = json_decode($response->getBody());
        if(empty($request_response)){
            return response()->json([
                "success" => false,
                "message" => "User not found",
            ], 404);
        }
        else{
            return response()->json([
                "success" => true,
                "message" => "Successful",
                "user" => $request_response[0]
            ], 200);
        }

    }

    public function updateKyc(Request $request) {
        $validator = \Validator::make($request->all(), [
            'loyalty_card_number' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'other_names' => 'required|string|max:255',
            'gender' => 'required',
            'marital_status' => 'required',
            'doc_type' => 'required',
            // 'doc_no' => 'required|unique:user_details,doc_no',
            'dob' => 'required',
            'country' => 'required',
            'residence' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'address' => 'required',
            'income' => 'required',
            'occupation' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $loyalty_card_number = $request->loyalty_card_number;
        $user = User::where('loyalty_card_number', $loyalty_card_number)->first();

        if(isset($request->email)){
            $email = $request->email;
        }
        else{
            $match = true;
            
            do{
                $email = $this->generateEmail();
                $user_check = User::where('email', $email)->count();

                if($user_check == 0) $match = false;
            }
            while($match);
        }

        if($user){
            // Update record
            $validator2 = \Validator::make($request->all(), [
                'phone_number' => 'required',
                'doc_no' => 'required',
            ]);

            if($validator2->fails()) {
                return response()->json(['errors' => $validator2->errors()->all()]);
            }

            $user_id = $user->id;
            $user->name = $request->name;
            $user->other_names = $request->other_names;
            $user->email = $email;
            $user->msisdn = $request->phone_number;
            $user->save();
            
            $detail = UserDetail::where('user_id', $user_id)->first();
            if(!$detail){
                $detail = new UserDetail;
                $detail->user_id = $user->id;
            }

            $detail->gender = $request->gender;
            $detail->marital_status = $request->marital_status;
            $detail->doc_type = $request->doc_type;
            $detail->doc_no = $request->doc_no;
            $detail->dob = $request->dob;
            $detail->residence = $request->residence;
            $detail->country = 131;
            $detail->city = $request->city;
            $detail->state = $request->state;
            $detail->postal_code = $request->postal_code;
            $detail->address = $request->address;
            $detail->income = $request->income;
            $detail->occupation = $request->occupation;
            $detail->save();

            $wallet = Wallet::where('user_id', $user->id)->first();
            if(!$wallet){
                // Create Wallet Entry
                $wallet = new Wallet;
                $wallet->user_id = $user->id;
                $wallet->balance = 0;
                $wallet->save();
            }
            
            $savings_wallet = SavingsWallet::where('user_id', $user->id)->first();
            if(!$savings_wallet){
                $savings_wallet = new SavingsWallet;
                $savings_wallet->user_id = $user->id;
                $savings_wallet->balance = 0;
                $savings_wallet->save();
            }

            return response()->json([
                "success" => true,
                "message" => "KYC Record updated successful",
                "user" => $user
            ], 200);
        }
        else{
            $validator2 = \Validator::make($request->all(), [
                'phone_number' => 'required|unique:users,msisdn',
                'doc_no' => 'required|unique:user_details,doc_no',
            ]);

            if($validator2->fails()) {
                return response()->json(['errors' => $validator2->errors()->all()]);
            }

            // Create new user
            $password = $this->generatePassword(6);

            // Store Member Into Users Table;
            $user = new User;
            $user->name = $request->name;
            $user->other_names = $request->other_names;
            $user->loyalty_card_number = $request->loyalty_card_number;
            $user->email = $email;
            $user->msisdn = $request->phone_number;
            $user->password = Hash::make($password);
            $user->status = true;
            $user->verified = true;
            $user->save();

            $detail = new UserDetail();
            $detail->user_id = $user->id;
            $detail->org_id = $request->org_id;
            $detail->gender = $request->gender;
            $detail->doc_type = $request->doc_type;
            $detail->dob = $request->dob;
            $detail->doc_no = $request->doc_no;
            $detail->country = 131; // NG
            $detail->city = $request->city;
            $detail->address = $request->address;
            $detail->income = $request->income;
            $detail->occupation = $request->occupation;
            $detail->marital_status = $request->marital_status;
            $detail->postal_code = $request->postal_code;
            $detail->residence = $request->residence;
            $detail->save();
    

            // Create Wallet Entry
            $wallet = new Wallet;
            $wallet->user_id = $user->id;
            $wallet->balance = 0;
            $wallet->save();

            $savings_wallet = new SavingsWallet;
            $savings_wallet->user_id = $user->id;
            $savings_wallet->balance = 0;
            $savings_wallet->save();


            return response()->json([
                "success" => true,
                "message" => "New customer account created successfuly",
                "user" => $user
            ], 200);
        }
    }

    public function generatePassword( $length = 6 )
    {
        $nums = "1234567890";
        $password = substr( str_shuffle( $nums ), 0, $length );
        return $password;
    }

    public function generateEmail( $length = 16 )
    {
        $nums = "abcdefghijklmnopqrstuvwxyz0123456789";
        $email = substr( str_shuffle( $nums ), 0, $length );
        return $email . "@justrite.jamborow.co.uk";
    }

    public function checkBalance(Request $request)
    {
        $user = Auth::user();

        $wallet = Wallet::where('user_id', $user->id)->first();
        $savings_wallet = SavingsWallet::where('user_id', $user->id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Balances retrieved successfully',
            'wallet_balance' => $wallet->balance,
            'savings_wallet_balance' => $savings_wallet->balance
        ], 200);
    }

    public function creditWallet(Request $request)
    {
        $user = Auth::user();

        $validator = \Validator::make($request->all(), [
            'amount' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 406);
        }

        $amount = $request->amount;

        $wallet = Wallet::where('user_id', $user->id)->first();
        $wallet_balance = $wallet->balance;

        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->txn_code = strtoupper(Str::random(10));
        $transaction->txn_type = 1;
        $transaction->transaction_type_id = 1;
        $transaction->amount = $amount;
        $transaction->save();

        $wallet->balance = $wallet_balance + $amount;
        $wallet->save();

        return response()->json([
            'success' => true,
            'message' => 'Wallet credited successfully',
            'wallet_balance' => $wallet->balance,
        ], 200);
    }

    public function debitWallet(Request $request)
    {
        $user = Auth::user();

        $validator = \Validator::make($request->all(), [
            'amount' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 406);
        }

        $amount = $request->amount;

        $wallet = Wallet::where('user_id', $user->id)->first();
        $wallet_balance = $wallet->balance;

        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->txn_code = strtoupper(Str::random(10));
        $transaction->txn_type = 2;
        $transaction->transaction_type_id = 2;
        $transaction->amount = $amount;
        $transaction->save();

        $wallet->balance = $wallet_balance - $amount;
        $wallet->save();

        return response()->json([
            'success' => true,
            'message' => 'Wallet debited successfully',
            'wallet_balance' => $wallet->balance,
        ], 200);
    }
    
    
    
    public function processWithdrawal(Request $request){
        
        $user = Auth::user();

       $payload = $request->all();
       $currency =  DB::table('users')
        ->join('user_details', 'user_id', '=', 'users.id')
        ->join('currencies', 'country_id', '=', 'user_details.country')
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
                
                $bl = DB::table('wallets')->where('user_id', $user->id)->pluck('balance')->toArray();
                
                $bal = implode($bl);
                $balance = floatval($bal);
    
                $newBalance = $balance - $transaction->amount;
                
                DB::table('wallets')->where('user_id', $user->id)->update([
                    'balance' => $newBalance, 
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Wallet debited successfully',
                    'wallet_balance' => $newBalance,
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
    
    
    
    public function proccessBillsPayment(Request $request){
                
        // $user = Auth::user();

       $payload = $request->all();
       $transfer = CommonFunctions::billsPaymentViaFlutterwave($payload);
       $data = json_decode(json_encode($transfer));
       return $data->status;
    //    if($data->status == 'success'){
           
                
               
    //         }else {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Transaction was unsuccesful',
    //                 'wallet_balance' => $org_wallet,
    //             ], 200);
    //         }
    //    }
    //    if($data->status == 'error'){
    //     return response()->json([
    //         'success' => true,
    //         'message' => $data->message,
    //     ], 200);
    //    }
       
    }   
    
    
   
}



