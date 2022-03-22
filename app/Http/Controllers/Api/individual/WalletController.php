<?php

namespace App\Http\Controllers\Back\Individual;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\General\Currency;
use App\User;
use App\Models\General\Saving;
use App\Models\General\Wallet;
use App\SavingsWallet;
use App\Models\General\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleClient;
use App\Helpers\CommonFunctions;
use App\Models\General\FlutterwaveTransfer;
class WalletController extends Controller
{
    public function personal()
    {
    	$user = Auth::user();
    	$wallet = Wallet::where('user_id', $user->id)->first();
        $deposits = Transaction::where('user_id', $user->id)->where('txn_type', 1)->orderBy('created_at', 'desc')->take(5)->get();
        $withdrawals = Transaction::where('user_id', $user->id)->where('txn_type', 2)->orderBy('created_at', 'desc')->take(5)->get();

        $currency =  DB::table('user_details')
                    ->join('currencies', 'country_id', '=', 'user_details.country')
                    ->where('user_details.user_id', '=', $user->id)
                    ->select('currencies.prefix')
                    ->first();
        // return $transactions;
    	//return view('back/individual/wallet/personal', compact('wallet', 'user', 'currency', 'deposits', 'withdrawals'));
        return response()->json([ 'status' => 200, 
                                  'user' => $user,
                                  'currency' => $currency,
                                  'deposits' => $deposits,
                                  'withdrawals' => $withdrawals, 
                                ]);
    }

    public function paymentMethod()
    {
    	$user = Auth::user();
    	return view('back/individual/wallet/payment', compact('user'));
    }

    public function payWithFlutterwave()
    {
        $user = Auth::user();

        $currency =  DB::table('user_details')
                    ->join('currencies', 'country_id', '=', 'user_details.country')
                    ->where('user_details.user_id', '=', $user->id)
                    ->select('currencies.prefix')
                    ->first();

        $paymentMethod = 'flutterwave';
        $paymentMethodText = 'Flutterwave';
       // return view('back/individual/wallet/payment-form', compact('user', 'currency', 'paymentMethod', 'paymentMethodText'));
        return response()->json([ 'status' => 200, 
                                  'user' => $user,
                                  'currency' => $currency,
                                  'paymentMethod' => $paymentMethod,
                                  'paymentMethodText' => $paymentMethodText, 
                                ]);
    } 

    public function processPayment(Request $request)
    {
        $this->validate(request(),[
            'currency' => 'required',
            'phonenumber' => 'required',
            'amount' => 'required',
            'email' => 'required',
            'name' => 'required'
        ]);
        // "https://webhook.site/1e5f60e3-a5ea-44bb-afbd-2d0a2cd13fea"
        $data = [
                    "tx_ref" => request('tx_ref'),
                    "amount" => request('amount'),
                    "currency" => request('currency'),
                    "redirect_url" => route('user.verify-payment'),
                    "payment_options" => request('payment_options'),
                    "customer" => [
                      "email" => request('email'),
                      "phonenumber" => request('phonenumber'),
                      "name" => request('name')
                    ],
                    "customizations" => [
                      "title" => "Jamborow Top Up",
                      "description" => "Top Up Your Jamborow Wallet",
                      "logo" => "https://jamborow.co.uk/img/front/Logo.png"
                    ]
                ];
        $flw_key = env('FLW_SECRET_KEY');
        $response = Http::withHeaders([
                  'Authorization' => 'Bearer ' . $flw_key,
                  'Content-Type' => 'application/json',
                ])->post('https://api.flutterwave.com/v3/payments', $data);

        $res = json_decode($response, true);
        $link = $res['data']['link'];

        return redirect($link);
    }



    public function verifyPayment(Request $request)
    {
        $user = Auth::user();
        //Code to verify payment and return response to user
        if($request->status == "cancelled"){

            //This should redirect to a failure page
            // Session::flash('warning', 'Transaction cancelled!');
            // return redirect()->route('user.pay-with-flutterwave');
            return response()->json([ 'status' => 403, 'timestamp' => Carbon::now() ]);
        }

        $trans_id = $request->transaction_id;
        $flw_key = env('FLW_SECRET_KEY');
        $response = Http::withHeaders([
                  'Authorization' => 'Bearer ' . $flw_key,
                ])->get('https://api.flutterwave.com/v3/transactions/'. $trans_id .'/verify');
        // dd($request);

        $payment_status = $response['status'];
        $currency = $response['data']['currency'];
        $amount = $response['data']['amount'];

        //logic to verify if transaction was successful
        if ($payment_status == "success") {

            //Credit wallet
            $bl = DB::table('wallets')->where('user_id', $user->id)->pluck('balance')->toArray();
            $bal = implode($bl);
            $balance = floatval($bal);

            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->txn_code = strtoupper(Str::random(10));
            $transaction->txn_type = 1;
            $transaction->transaction_type_id = DB::table('transaction_types')->where('name', 'deposit')->value('id');
            $transaction->amount = $amount;
            $transaction->save();

            $nb = $balance + $amount;

            // dd($nb);

            DB::table('wallets')->where('user_id', $user->id)->update([
                'balance' => $nb,
            ]);

            //This should redirect to a payment successful page
            //Session::flash('success', 'Your wallet has been deposited with ' . $currency. ' '. number_format($amount));

                //return redirect()->route('user.pay-with-flutterwave');
                return response()->json([ 'status' => 200, 'timestamp' => Carbon::now() ]);
        }
        else {

            //This should redirect to a failure page
           // Session::flash('error', 'Your transaction failed. Please, try again!');

                //return redirect()->route('user.pay-with-flutterwave');
                return response()->json([ 'status' => 500, 'timestamp' => Carbon::now() ]);
        }

        // dd($response->json());
    }

    public function savings()
    {
        $user = Auth::user();
        $wallet = DB::table('savings_wallet')->where('user_id', $user->id)->first();
        $currency =  DB::table('user_details')
                    ->join('currencies', 'country_id', '=', 'user_details.country')
                    ->where('user_details.user_id', '=', $user->id)
                    ->select('currencies.prefix')
                    ->first();

        //return view('back/individual/savings/index', compact('wallet', 'user', 'currency'));
        return response()->json([ 'status' => 200, 
                                  'wallet' => $wallet,
                                  'user' => $user,
                                  'currency' => $currency,
                             ]);
        
    }

    public function creditSavings()
    {
        $user = Auth::user();
        $wallet = Wallet::where('user_id', $user->id)->first();
       // return view('back/individual/savings/fund', compact('wallet', 'user'));
       return response()->json([ 'status' => 200, 
                                 'wallet' => $wallet,
                                 'user' => $user,
                               ]);
    }

    public function debitSavings()
    {
        $user = Auth::user();
        $available_balance = CommonFunctions::withdrawableSavingsBalance($user);
        //return view('back/individual/savings/withdraw', compact('available_balance', 'user'));
        return response()->json([ 'status' => 200, 
                                  'available_balance' => $available_balance,
                                   'user' => $user,
                                ]);
    }

    public function save(Request $request)
    {
        /*$this->validate($request, [
            'password' => 'required|confirmed',
        ]);*/
        if($request->password != $request->password_confirmation){
           // Session::flash('warning', 'The passwords you entered do not match');
           return response()->json([ 'status' => 203, 'timestamp' => Carbon::now() ]);
        }
        elseif($request->amount == ""){
            //Session::flash('warning', 'Please enter a valid amount');
            //return redirect()->back();
            return response()->json([ 'status' => 406, 'timestamp' => Carbon::now() ]);
        }

        $user = Auth::user();
        $amount = floatval($request->amount);
        $duration = intval($request->duration);

        if(Hash::check($request->password, $user->password)) {

            $savings_transaction = CommonFunctions::creditSavings($user, $amount, $duration);

            $alert_class = $savings_transaction[0] == true ? 'success' : 'error';

            //Session::flash($alert_class, $savings_transaction[1]);
            //return redirect()->route('savings-add-money');
            return response()->json([ 'status' => 200, 'timestamp' => Carbon::now() ]);
        }
        else {
            //Session::flash('error', 'The password you entered is incorrect!');
           // return redirect()->back();
           return response()->json([ 'status' => 203, 'timestamp' => Carbon::now() ]);
        }
    }

    public function debit(Request $request)
    {
        /*
        $this->validate($request, [
            'password' => 'required|confirmed',
        ]);*/
        $user = Auth::user();

        if($request->password != $request->password_confirmation){
            //Session::flash('warning', 'The passwords you entered do not match');
            //return redirect()->back();
            return response()->json([ 'status' => 203, 'timestamp' => Carbon::now() ]);
        }
        elseif($request->amount == ""){
            //Session::flash('warning', 'Please enter a valid amount');
            //return redirect()->back();
            return response()->json([ 'status' => 406, 'timestamp' => Carbon::now() ]);
        }

        if(Hash::check($request->password, $user->password)) {
            $amount = $request->amount;

            $savings_transaction = CommonFunctions::debitSavings($user, $amount);

            $alert_class = $savings_transaction[0] == true ? 'success' : 'error';

           // Session::flash($alert_class, $savings_transaction[1]);
            //return redirect()->route('savings-transfer');
            return response()->json([ 'status' => 200, 'timestamp' => Carbon::now() ]);
        }
        else {
            //Session::flash('error', 'The password you entered is incorrect!');
            //return redirect()->route('savings-transfer');
            return response()->json([ 'status' => 401, 'timestamp' => Carbon::now() ]);
        }
    }


    public function withdrawal()
    {
        $user = Auth::user();
        $currency = CommonFunctions::userCurrency($user);
        $banks = CommonFunctions::getAllBanks($currency);
        $paymentMethod = 'flutterwave';
       // return view('back/individual/wallet/withdrawal-form', compact( 'user', 'currency', 'paymentMethod', 'banks'));
       return response()->json([ 'status' => 200, 
                                 'currency' => $currency,
                                 'banks' => $banks,
                                 'paymentMethod' => $paymentMethod,
                                 'user' => $user,
                               ]);
    }


    public function processWithdrawal(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'account_no' => 'required',
            'narration' => 'required',
            'amount' => 'required',
            'bank_name' => 'required',

        ]);
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
       $banks = CommonFunctions::getAllBanks($currency);

       $paymentMethod = 'flutterwave';
       $paymentMethodText = 'Flutterwave';
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


                // Session::flash('success', 'Transaction was successful');
                // return view('back/individual/wallet/withdrawal-form', compact( 'user', 'currency', 'paymentMethod', 'paymentMethodText', 'banks'));
                return response()->json([ 'status' => 200, 
                                          'currency' => $currency,
                                          'banks' => $banks,
                                          'paymentMethod' => $paymentMethod,
                                          'paymentMethodText' => $paymentMethodText,
                                          'user' => $user,
                                        ]);
            }
       }
       if($data->status == 'error'){
        //  Session::flash('error', 'Transaction was unsuccessful. Try again');
        //  return view('back/individual/wallet/withdrawal-form', compact('org', 'user', 'currency', 'paymentMethod', 'paymentMethodText', 'banks'));
        return response()->json([ 'status' => 504, 
                                  'org' => $org,
                                  'currency' => $currency,
                                  'banks' => $banks,
                                  'paymentMethod' => $paymentMethod,
                                  'paymentMethodText' => $paymentMethodText,
                                  'user' => $user,
                                ]);
       }

    }


    public function billsPayment()
    {
        $user = Auth::user();
        $currency =  DB::table('users')
        ->join('user_details', 'user_id', '=', 'users.id')
        ->join('currencies', 'country_id', '=', 'user_details.country')
        ->select('currencies.prefix')
        ->first();

        $paymentMethod = 'flutterwave';
        $paymentMethodText = 'Flutterwave';

        //return view('back/individual/wallet/bills-payment', compact( 'user', 'currency', 'paymentMethod', 'paymentMethodText'));
        return response()->json([ 'status' => 504,
                                  'currency' => $currency,
                                  'paymentMethod' => $paymentMethod,
                                  'paymentMethodText' => $paymentMethodText,
                                  'user' => $user,
                                ]);
    }

    public function proccessBillsPayment(Request $request)
    {
    //    if($request->bill_type == 'airtime'){
    //     $this->validate($request, [
    //         'phone_no' => 'required',
    //         'network' => 'required',
    //         'amount' => 'required',
    //     ]);
    //    }

       $billsPayment = CommonFunctions::billsPaymentViaFlutterwave($request->all());
       dd($billsPayment);

    }

}
