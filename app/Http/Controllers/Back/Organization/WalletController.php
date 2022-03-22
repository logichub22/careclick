<?php

namespace App\Http\Controllers\Back\Organization;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\General\Currency;
use App\User;
use App\Models\General\Wallet;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationWallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\General\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Helpers\OrganizationFunctions;
use GuzzleHttp\Client as GuzzleClient;
use App\Helpers\CommonFunctions;


class WalletController extends Controller
{
    public function personal()
    {
    	$user = Auth::user();
    	$balance = Wallet::where('user_id', $user->id)->pluck('balance')->toArray();
        $bal = number_format(implode($balance));

    	return view('back/organization/wallet/personal', compact('bal', 'user'));
    }

    public function organization()
    {
    	$user = Auth::user();
        $organization = OrganizationFunctions::userOrganization($user)['organization'];
    	// $organization = DB::table('organizations')->where('admin_id', $user->id)->pluck('id')->toArray();
    	$id = $organization->id;
    	$balance = OrganizationWallet::where('org_id', $id)->pluck('balance')->toArray();
        $bal = number_format(implode($balance));

    	return view('back/organization/wallet/organization', compact('bal', 'user'));
    }

    public function paymentMethod()
    {
        $user = Auth::user();
        return view('back/organization/wallet/payment', compact('user'));
    }

    public function payWithFlutterwave()
    {
        $user = Auth::user();
        $org = OrganizationFunctions::userOrganization($user)['organization'];
        $currency = OrganizationFunctions::organizationCurrency($org);

        $paymentMethod = 'flutterwave';
        $paymentMethodText = 'Flutterwave';

        return view('back/organization/wallet/payment-form', compact('org', 'user', 'currency', 'paymentMethod', 'paymentMethodText'));
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
                    "redirect_url" => route('org.verify-payment'),
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
            Session::flash('warning', 'Transaction cancelled!');
            return redirect()->route('org.pay-with-flutterwave');
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
            // $organization = DB::table('organizations')->where('admin_id', $user->id)->first();
            $organization = OrganizationFunctions::userOrganization($user)['organization'];


            $bl = DB::table('org_wallets')->where('org_id', $organization->id)->pluck('balance')->toArray();
            $bal = implode($bl);
            $balance = floatval($bal);

            $transaction = new Transaction();
            $transaction->org_id = $user->id;
            $transaction->txn_code = strtoupper(Str::random(10));
            $transaction->txn_type = 1;
            $transaction->transaction_type_id = DB::table('transaction_types')->where('name', 'deposit')->value('id');
            $transaction->amount = $amount;
            $transaction->save();

            $nb = $balance + $amount;

            DB::table('org_wallets')->where('org_id', $organization->id)->update([
                'balance' => $nb,
            ]);

            //This should redirect to a payment successful page
            Session::flash('success', 'Your wallet has been deposited with ' . $currency. ' '. number_format($amount));

            // return redirect()->route('org.pay-with-flutterwave');
            return redirect()->route('organization.wallet');
        }
        else {

            //This should redirect to a failure page
            Session::flash('error', 'Your transaction failed. Please, try again!');

            return redirect()->route('org.pay-with-flutterwave');
        }

        // dd($response->json());
    }

    public function savings()
    {
        $user = Auth::user();
        // dd($user->id);
        // $organization = DB::table('organizations')->where('admin_id', $user->id)->first();
        $organization = OrganizationFunctions::userOrganization($user)['organization'];

        $wallet = DB::table('savings_wallet')->where('org_id', $user->id)->first();
        $currency = OrganizationFunctions::organizationCurrency($org);

        return view('back/organization/savings/index', compact('wallet', 'user', 'currency'));
    }




    public function withdrawal()
    {
        $user = Auth::user();
        $org = OrganizationFunctions::userOrganization($user)['organization'];
        $currency = OrganizationFunctions::organizationCurrency($org);
        $banks = CommonFunctions::getAllBanks($currency);
        $wallet = DB::table('org_wallets')->where('org_id', $org->id)->first();
        $wallet_balance = $wallet->balance;

        $paymentMethod = 'flutterwave';

        return view('back/organization/wallet/withdrawal-form', compact('org', 'user', 'currency', 'paymentMethod', 'banks', 'wallet_balance'));
    }

    public function processWithdrawal(Request $request){
        $user = Auth::user();
        $org = OrganizationFunctions::userOrganization($user)['organization'];

        // check password validity
        if(Hash::check($request->password, $user->password)){

            $amount = $request->amount;
            $account_number = $request->account_number;
            $bank = $request->bank;

            $withdrawal = OrganizationFunctions::withdraw($user, $amount, $account_number, $bank);

            $alert_class = $withdrawal[0] == true ? 'success' : 'error';

            Session::flash($alert_class, $withdrawal[1]);
            return redirect()->route('organization.wallet');
        }
        else{
            Session::flash('error', 'The password you entered is incorrect!');

            return redirect()->back();
        }

    // //    if($currency == "NGN"){
    // //      $verifyAccountNumber = CommonFunctions::verifyAccountNumber($payload);
    // //    }
    //    $transfer = CommonFunctions::TransferViaFlutterwave($payload);
    //    $user_currency = OrganizationFunctions::organizationCurrency($user);
    //    $banks = CommonFunctions::getAllBanks($user_currency);

    //    $paymentMethod = 'flutterwave';
    //    $paymentMethodText = 'Flutterwave';
    //    $data = json_decode(json_encode($transfer));
    //    if($data->status == 'success'){
    //         $transaction = new FlutterwaveTransfer();
    //         $transaction->user_id = $user->id;
    //         $transaction->org_id = $user->org_id;
    //         $transaction->full_name = $data->data->full_name;
    //         $transaction->bank_name = $data->data->bank_name;
    //         $transaction->reference = $data->data->reference;
    //         $transaction->narration = $data->data->narration;
    //         $transaction->status = $data->data->status;
    //         $transaction->account_number = $data->data->account_number;
    //         $transaction->amount = $data->data->amount;
    //         $transaction->date = $data->data->created_at;
    //         $transaction->amount = $data->data->amount;
    //         $transaction->save();
    //         if($transaction->id){

    //             $org_wallet = OrganizationWallet::where('org_id', $org->org_id)->first();
    //             $wallet_balance = $org_wallet->balance;

    //             $org_wallet->balance = $wallet_balance - $transaction->amount;
    //             $org_wallet->save();

    //             Session::flash('success', 'Transaction was successful');
    //             return view('back/organization/wallet/withdrawal-form', compact('org', 'user', 'currency', 'paymentMethod', 'paymentMethodText', 'banks'));

    //         }
    //    }
    //    if($data->status == 'error'){
    //      Session::flash('error', 'Transaction was unsuccessful. Try again');
    //      return view('back/organization/wallet/withdrawal-form', compact('org', 'user', 'currency', 'paymentMethod', 'paymentMethodText', 'banks'));
    //    }

    }

    public function creditSavings()
    {
        $user = Auth::user();
        $organization = OrganizationFunctions::userOrganization($user)['organization'];
        // $organization = DB::table('organizations')->where('admin_id', $user->id)->first();
        $wallet = DB::table('org_wallets')->where('org_id', $organization->id)->first();
        return view('back/organization/savings/fund', compact('wallet', 'user'));
    }

    public function debitSavings()
    {
        $user = Auth::user();
        $wallet = DB::table('savings_wallet')->where('org_id', $user->id)->first();
        return view('back/organization/savings/withdraw', compact('wallet', 'user'));
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
        ]);

        $user = Auth::user();
        $amount = $request->amount;
        $duration = $request->duration;

        if(Hash::check($request->password, $user->password)) {
            // $organization = DB::table('organizations')->where('admin_id', $user->id)->first();
            $organization = OrganizationFunctions::userOrganization($user)['organization'];

            $bl = DB::table('org_wallets')->where('org_id', $organization->id)->pluck('balance')->toArray();
            $bal = implode($bl);
            $balance = floatval($bal);

            $sv = DB::table('savings_wallet')->where('org_id', $user->id)->pluck('balance')->toArray();
            $sav = implode($sv);
            $savings_balance = floatval($sav);

            $newBalance = $savings_balance + $amount;

            // dd($balance);

            //Debit Main Wallet
            if($amount <= $balance) {
                $transaction = new Transaction();
                $transaction->org_id = $user->id;
                $transaction->txn_code = strtoupper(Str::random(10));
                $transaction->txn_type = 2;
                $transaction->transaction_type_id = DB::table('transaction_types')->where('name', 'savings')->value('id');
                $transaction->amount = $amount;
                $transaction->save();

                $nb = $balance - $amount;

                // dd($nb);

                DB::table('org_wallets')->where('org_id', $organization->id)->update([
                    'balance' => $nb,
                ]);

                //Credit Savings Wallet
                $transaction = new Transaction();
                $transaction->org_id = $user->id;
                $transaction->txn_code = strtoupper(Str::random(10));
                $transaction->txn_type = 1;
                 $transaction->transaction_type_id = DB::table('transaction_types')->where('name', 'savings')->value('id');
                $transaction->amount = $amount;
                $transaction->save();

                DB::table('savings_wallet')->where('org_id', $user->id)->update([
                    'balance' => $newBalance,
                    'duration' => $duration,
                ]);

                Session::flash('success', 'Your savings wallet has been deposited with NGN' . ' ' . number_format($amount));

                return redirect()->route('orgsavings-add-money');
            }
            else {
                Session::flash('error', 'You cannot save more than what is in your main wallet.');

                return redirect()->route('orgsavings-add-money');
            }

        }
        else {
            Session::flash('error', 'The password you entered is incorrect!');

            return redirect()->route('orgsavings-add-money');
        }
    }

     public function debit(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
        ]);

        $user = Auth::user();
        $amount = $request->amount;

        $duration = DB::table('savings_wallet')
                    ->where('org_id', $user->id)
                    ->select('duration')
                    ->first();
        // dd($duration);

        $date_created = DB::table('savings_wallet')
                        ->where('org_id', $user->id)
                        ->select('created_at')
                        ->first();
        $date_created = Carbon::parse($date_created->created_at)->diffInDays();
        // dd($date_created);
        // $current = new Carbon();

        if(Hash::check($request->password, $user->password)) {
            $bl = DB::table('org_wallets')->where('org_id', $user->id)->pluck('balance')->toArray();
            $bal = implode($bl);
            $balance = floatval($bal);

            $sv = DB::table('savings_wallet')->where('org_id', $user->id)->pluck('balance')->toArray();
            $sav = implode($sv);
            $savings_balance = floatval($sav);

            $newBalance = $balance + $amount;

            // dd($newBalance);

            //Debit Savings Wallet
            if($amount <= $savings_balance && $date_created >= $duration->duration) {
                $transaction = new Transaction();
                $transaction->org_id = $user->id;
                $transaction->txn_code = strtoupper(Str::random(10));
                $transaction->txn_type = 2;
                $transaction->transaction_type_id = DB::table('transaction_types')->where('name', 'savings')->value('id');
                $transaction->amount = $amount;
                $transaction->save();

                $nb = $savings_balance - $amount;

                // dd($nb);

                DB::table('savings_wallet')->where('org_id', $user->id)->update([
                    'balance' => $nb,
                ]);

                //Credit Main Wallet
                $transaction = new Transaction();
                $transaction->org_id = $user->id;
                $transaction->txn_code = strtoupper(Str::random(10));
                $transaction->txn_type = 1;
                 $transaction->transaction_type_id = DB::table('transaction_types')->where('name', 'savings')->value('id');
                $transaction->amount = $amount;
                $transaction->save();

                DB::table('org_wallets')->where('org_id', $user->id)->update([
                    'balance' => $newBalance,
                ]);

                Session::flash('success', 'Your main wallet has been credited with NGN' . ' ' . number_format($amount));

                return redirect()->route('orgsavings-transfer');
            }
            else {
                Session::flash('error', 'Your savings have not reached its maturity period');

                return redirect()->route('orgsavings-transfer');
            }
        }
        else {
            Session::flash('error', 'The password you entered is incorrect!');

            return redirect()->route('orgsavings-transfer');
        }
    }



    public function reportCSV()
    {

        $users = DB::table('users')
        ->join('user_details', 'user_details.user_id', '=', 'users.id')
        ->join('loans', 'loans.user_id', '=', 'users.id')
        ->select('*','loans.id as loan_id')
        ->get();

        dd($users);
    $count = count($users);
    if ($count > 0) {
    $users = json_decode(json_encode($users), true);

    $columns = array("Loan Id", "Gender", "Marital Status", "Education", "Loan Amount", "Credit History", "Loan Status", "Borrowing reason", "Settled Loan", "Active Loan", "Decline Loans", "Loan accounts", "User Id", "Nationality");
    $callback = function() use ($users, $columns)
  {
      $file = fopen('php://output', 'w');
      fputcsv($file, $columns);
      foreach($users as $user) {
          fputcsv($file, array($user["name"], $user["country"], $user["address"], $user["email"], $user["msisdn"], $user["verified"]));
      }
      fclose($file);
  };

  $headers = array(
      "Content-type" => "text/csv",
      "Content-Disposition" => "attachment; filename=exportdata.csv",
      "Pragma" => "no-cache",
      "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
      "Expires" => "0"
  );

  return \Response::stream($callback, 200, $headers);

    }

}


}
