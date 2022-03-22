<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RaveController extends Controller
{
    protected function encrypt3Des($data, $key){

      $encData = openssl_encrypt($data, 'DES-EDE3', $key, OPENSSL_RAW_DATA);

        return base64_encode($encData);

     }

    public function chargeCard(Request $request)
    {
       //The request object should be used for production or final implementation with form validaion as the $data array will have entries from a users request (dynamic data)
        $this->validate(request(), [
            'card_number' => 'required',
            'cvv' => 'required',
            'expiry_month' => 'required',
            'expiry_year' => 'required',
            'amount' => 'required',
            'email' => 'required',
            'fullname' => 'required',
            'pin' => 'required'
        ]);
        $data = [
                    "card_number" => request('card_number'),
                     "cvv" => request('cvv'),
                     "expiry_month" => request('expiry_month'),
                     "expiry_year" => request('expiry_year'),
                     "currency" => "NGN",
                     "amount" => request('amount'),
                     "email" => request('email'),
                     "fullname" => request('fullname'),
                     "tx_ref" => "MC-3243e",
                     "redirect_url" => "https://webhook.site/3ed41e38-2c79-4c79-b455-97398730866c",
                     "authorization" => [
                        "mode" => "pin",
                        "pin" => request('pin')
                     ]
                ];

               //Encrypt json encoded data with 3DES
                $encrypted_data = $this->encrypt3Des(json_encode($data), "FLWSECK_TEST-c619ad5d252b85b9c2efd2c5ad30ad54-X");
                //Create Post data array for Guzzle HTTP API
                $postdata = array(
                 'PBFPubKey' => 'FLWPUBK_TEST-5df79a30fede15887599b94696f47c47-X',
                 'client' => $encrypted_data,
                 'alg' => '3DES-24');

                 //Make a call to flutterwave with $postdata
                $response = Http::withHeaders([
                  'Authorization' => 'Bearer FLWSECK_TEST-c619ad5d252b85b9c2efd2c5ad30ad54-X',
                  'Content-Type' => 'application/json',
                ])->post('https://api.flutterwave.com/v3/charges?type=card', $postdata);

                 //decode response and assign flw_ref and tx_ref values for validationa and verification endpoints
                 $res = json_decode($response, true);
                 $flw_ref = $res['data']['flw_ref'];
                 $txref = $res['data']['tx_ref'];

                //Validate charge made to card 
                $val_data = [
                                "PBFPubKey" => "FLWPUBK_TEST-5df79a30fede15887599b94696f47c47-X",    
                                "otp" => "123456",
                                "transaction_reference" => $flw_ref
                            ];

                $val_res = Http::withHeaders([
                  'Authorization' => 'Bearer FLWSECK_TEST-c619ad5d252b85b9c2efd2c5ad30ad54-X',
                  'Content-Type' => 'application/json',
                ])->post('https://api.ravepay.co/flwv3-pug/getpaidx/api/validatecharge', $val_data);


                //Verify Transaction
                $verify_data = [
                                  'txref' => $txref,
                                  'SECKEY' => 'FLWSECK_TEST-c619ad5d252b85b9c2efd2c5ad30ad54-X'
                              ];

                $verify = Http::withHeaders([
                  'Authorization' => 'Bearer FLWSECK_TEST-c619ad5d252b85b9c2efd2c5ad30ad54-X',
                  'Content-Type' => 'application/json',
                ])->post('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify', $verify_data);

                $verify = json_decode($verify, true);
                $status = $verify['status'];

                if ($status == "success") {

                    //This should redirect to a success page
                    return "Transaction Successful";
                }
                else{

                    //This should redirect to a failure page
                    return "Transaction failed";
                }
          }


    public function chargeAccount(Request $request)
    {
       //The request object should be used for production or final implementation with form validaion as the $data array will have entries from a users request (dynamic data)
        $this->validate(request(), [
            'amount' => 'required',
            'account_bank' => 'required',
            'account_number' => 'required',
            'currency' => 'required',
            'email' => 'required',
            'fullname' => 'required',
            'phone_number' => 'required'
        ]);

        $data = [
                    'tx_ref' => 'MC-1585230ew9v5050e8',
                    'amount' => request('amount'),
                    'account_bank' => request('account_bank'),
                    'account_number' => request('account_number'),
                    'currency' => request('currency'),
                    'email' => request('email'),
                    'phone_number' => request('phone_number'),
                    'fullname' => request('fullname')
                ];

                 //Make a call to flutterwave with $data
                $response = Http::withHeaders([
                  'Authorization' => 'Bearer FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X',
                  'Content-Type' => 'application/json',
                ])->post('https://api.flutterwave.com/v3/charges?type=debit_ng_account', $data);

                // return $response->json();

                $txref = $data['tx_ref'];

                //Verify Transaction

                $verify_data = [
                                  'txref' => $txref,
                                  'SECKEY' => 'FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X'
                              ];

                //Make a call to the verify transaction endpoint with $verify_data
                $verify_response = Http::withHeaders([
                  'Authorization' => 'Bearer FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X',
                  'Content-Type' => 'application/json',
                ])->post('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify', $verify_data);

                $resp = json_decode($verify_response, true);

                //create a variable to hold the value of amount and currency from transaction data and use for verification logic
                $amount = $data['amount'];
                $currency = $data['currency'];

                $payment_status = $resp['data']['status'];
                $charge_code = $resp['data']['chargecode'];
                $charge_amount = $resp['data']['amount'];
                $charge_currency = $resp['data']['currency'];

                //logic to verify if transaction was successful
                if (($charge_code == "00" || $charge_code == '0') && $charge_amount == $amount && $charge_currency == $currency) {

                    //This should redirect to a payment successful page 
                    return $payment_status;
                }
                else {

                    //This should redirect to a failure page
                    return "Transaction failed.";
                } 
    }

    public function bankTransfer(Request $request)
    {
        //The request object should be used for production or final implementation with form validaion as the $data array will have entries from a users request (dynamic data) This enpoint is also not returning tx_ref so please help me look into how it can be verified
        $this->validate(request(), [
            'amount' => 'required',
            'email' => 'required',
            'fullname' => 'required',
            'phone_number' => 'required',
            'currency' => 'required'
        ]);

        $data = [
                    "tx_ref" => "MC-1585230950508",
                    "amount" => request('amount'),
                    "email" => request('email'),
                    "phone_number" => request('phone_number'),
                    "currency" => request('currency'),
                    "fullname" => request('fullname')
                ];

                 //Make a call to flutterwave with $data
                $response = Http::withHeaders([
                  'Authorization' => 'Bearer FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X',
                  'Content-Type' => 'application/json',
                ])->post('https://api.flutterwave.com/v3/charges?type=bank_transfer', $data);

                //decode $response and assign tx_ref to a variable to use for verification
                $res = json_decode($response, true);
                // $txref = $res['tx_ref'];
                return $response->json();


                $verify_data = [
                                  'txref' => $txref,
                                  'SECKEY' => 'FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X'
                              ];

                //Make a call to the verify transaction endpoint with $verify_data
                $verify_response = Http::withHeaders([
                  'Authorization' => 'Bearer FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X',
                  'Content-Type' => 'application/json',
                ])->post('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify', $verify_data);

                $resp = json_decode($verify_response, true);

                //create a variable to hold the value of amount and currency from transaction data and use for verification logic
                $amount = $data['amount'];
                $currency = $data['currency'];

                $payment_status = $resp['status'];
                $charge_code = $resp['data']['chargecode'];
                $charge_amount = $resp['data']['amount'];
                $charge_currency = $resp['data']['currency'];

                //logic to verify if transaction was successful
                if (($charge_code == "00" || $charge_code == '0') && $charge_amount == $amount && $charge_currency == $currency) {

                    //This should redirect to a payment successful page 
                    return $payment_status;
                }
                else {

                    //This should redirect to a failure page
                    return $payment_status;
                } 
    }

    public function ussdPayment(Request $request)
    {
       //The request object should be used for production or final implementation with form validaion as the $data array will have entries from a users request (dynamic data)
        $this->validate(request(), [
            'amount' => 'required',
            'email' => 'required',
            'fullname' => 'required',
            'phone_number' => 'required',
            'currency' => 'required'
        ]);

        $data = [
                    "tx_ref" => "MC-15852309v5050e8",
                    "account_bank" => request('account_number'),
                    "amount" => request('amount'),
                    "currency" => request('currency'),
                    "email" => request('email'),
                    "phone_number" => request('phone_number'),
                    "fullname" => request('fullname')
                ];

                 //Make a call to flutterwave with $data
                $response = Http::withHeaders([
                  'Authorization' => 'Bearer FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X',
                  'Content-Type' => 'application/json',
                ])->post('https://api.flutterwave.com/v3/charges?type=ussd', $data);

                 //decode $response and assign tx_ref to a variable to use for verification
                 $res = json_decode($response, true);
                 $txref = $res['data']['tx_ref'];

                //Verify Transaction
                $verify_data = [
                                  'txref' => $txref,
                                  'SECKEY' => 'FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X'
                              ];

                $verify = Http::withHeaders([
                  'Authorization' => 'Bearer FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X',
                  'Content-Type' => 'application/json',
                ])->post('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify', $verify_data);

                $verify = json_decode($verify, true);
                $status = $verify['status'];

                if ($status == "success") {

                    //This should redirect to a success page
                    return "Transaction Successful";
                }
                else{

                    //This should redirect to a failure page
                    return "Transaction failed";
                }
    }

    public function ghanaMobileMoney(Request $request)
    {
       //The request object should be used for production or final implementation with form validaion as the $data array will have entries from a users request (dynamic data)
        $this->validate(request(), [
            'amount' => 'required',
            'email' => 'required',
            'fullname' => 'required',
            'phone_number' => 'required',
            'network' => 'required'
        ]);

        $data = [
                   "tx_ref" => "MC-158523s09v5050e8",
                   "amount" => request('amount'),
                   "currency" => "GHS",
                   "network" => request('network'),
                   "email" => request('email'),
                   "phone_number" => request('phone_number'),
                   "fullname" => request('fullname')
                ];

                 //Make a call to flutterwave with $data
                $response = Http::withHeaders([
                  'Authorization' => 'Bearer FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X',
                  'Content-Type' => 'application/json',
                ])->post('https://api.flutterwave.com/v3/charges?type=mobile_money_ghana', $data);

                //redirect to the url provided by the response

                 $res = json_decode($response, true);
                 $redirect_url = $res['meta']['authorization']['redirect'];

                 // return $redirect_url;

                return redirect($redirect_url);

                //After this redirection, get the transaction notification and then uncomment the code for verification

                //Verify Transaction
                // $verify_data = [
                //                   'txref' => $txref,
                //                   'SECKEY' => 'FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X'
                //               ];

                // $verify = Http::withHeaders([
                //   'Authorization' => 'Bearer FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X',
                //   'Content-Type' => 'application/json',
                // ])->post('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify', $verify_data);

                // $verify = json_decode($verify, true);
                // $status = $verify['status'];

                // if ($status == "success") {

                //     //This should redirect to a success page
                //     return "Transaction Successful";
                // }
                // else{

                //     //This should redirect to a failure page
                //     return "Transaction failed";
                // }
    }

    public function rwandaMobileMoney(Request $request)
    {
       //The request object should be used for production or final implementation with form validaion as the $data array will have entries from a users request (dynamic data)
        $data = [
                    "tx_ref" => "MC-158523s09v5050e8",
                    "order_id" => "USS_URG_893982923s2323",
                    "amount" => "1500",
                    "currency" => "RWF",
                    "email" => "user@flw.com",
                    "phone_number" => "054709929220",
                    "fullname" => "John Madakin"
                ];

                 //Make a call to flutterwave with $data
                $response = Http::withHeaders([
                  'Authorization' => 'Bearer FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X',
                  'Content-Type' => 'application/json',
                ])->post('https://api.flutterwave.com/v3/charges?type=mobile_money_rwanda', $data);

                 return $response->json();
                 $redirect_url = $res['meta']['authorization']['redirect'];

                 // return $redirect_url;

                return redirect($redirect_url);

                //After this redirection, get the transaction notification and then uncomment the code for verification

                //Verify Transaction
                // $verify_data = [
                //                   'txref' => $txref,
                //                   'SECKEY' => 'FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X'
                //               ];

                // $verify = Http::withHeaders([
                //   'Authorization' => 'Bearer FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X',
                //   'Content-Type' => 'application/json',
                // ])->post('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify', $verify_data);

                // $verify = json_decode($verify, true);
                // $status = $verify['status'];

                // if ($status == "success") {

                //     //This should redirect to a success page
                //     return "Transaction Successful";
                // }
                // else{

                //     //This should redirect to a failure page
                //     return "Transaction failed";
                // }
    }


    public function ugandaMobileMoney(Request $request)
    {
       //The request object should be used for production or final implementation with form validaion as the $data array will have entries from a users request (dynamic data)
        $data = [
                    "tx_ref" => "MC-1585230950508",
                    "amount" => "1500",
                    "email" => "user@flw.com",
                    "phone_number" => "054709929220",
                    "currency" => "UGX",
                    "fullname" => "John Madakin",
                    "redirect_url" => "https://rave-webhook.herokuapp.com/receivepayment",
                    "voucher" => 128373,
                    "network" => "MTN"
                ];

                 //Make a call to flutterwave with $data
                $response = Http::withHeaders([
                  'Authorization' => 'Bearer FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X',
                  'Content-Type' => 'application/json',
                ])->post('https://api.flutterwave.com/v3/charges?type=mobile_money_uganda', $data);

                $res = json_decode($response, true);
                $redirect_url = $res['meta']['authorization']['redirect'];

                 // return $redirect_url;

                return redirect($redirect_url);

                //After this redirection, get the transaction notification and then uncomment the code for verification

                //Verify Transaction
                // $verify_data = [
                //                   'txref' => $txref,
                //                   'SECKEY' => 'FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X'
                //               ];

                // $verify = Http::withHeaders([
                //   'Authorization' => 'Bearer FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X',
                //   'Content-Type' => 'application/json',
                // ])->post('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify', $verify_data);

                // $verify = json_decode($verify, true);
                // $status = $verify['status'];

                // if ($status == "success") {

                //     //This should redirect to a success page
                //     return "Transaction Successful";
                // }
                // else{

                //     //This should redirect to a failure page
                //     return "Transaction failed";
                // }
    }


    public function zambiaMobileMoney(Request $request)
    {
       //The request object should be used for production or final implementation with form validaion as the $data array will have entries from a users request (dynamic data)
        $data = [
                    "tx_ref" => "MC-15852113s09v5050e8",
                    "amount" => "1500",
                    "currency" => "ZMW",
                    "email" => "user@flw.com",
                    "phone_number" => "054709929220",
                    "fullname" => "John Madakin",
                    "order_id" => "URF_MMGH_1585323540079_5981535"
                ];

                 //Make a call to flutterwave with $data
                $response = Http::withHeaders([
                  'Authorization' => 'Bearer FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X',
                  'Content-Type' => 'application/json',
                ])->post('https://api.flutterwave.com/v3/charges?type=mobile_money_zambia', $data);

                 $res = json_decode($response, true);
                 $redirect_url = $res['meta']['authorization']['redirect'];

                 // return $redirect_url;

                return redirect($redirect_url);

                //After this redirection, get the transaction notification and then uncomment the code for verification

                //Verify Transaction
                // $verify_data = [
                //                   'txref' => $txref,
                //                   'SECKEY' => 'FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X'
                //               ];

                // $verify = Http::withHeaders([
                //   'Authorization' => 'Bearer FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X',
                //   'Content-Type' => 'application/json',
                // ])->post('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify', $verify_data);

                // $verify = json_decode($verify, true);
                // $status = $verify['status'];

                // if ($status == "success") {

                //     //This should redirect to a success page
                //     return "Transaction Successful";
                // }
                // else{

                //     //This should redirect to a failure page
                //     return "Transaction failed";
                // }
    }

    public function mpesa(Request $request)
    {
       //The request object should be used for production or final implementation with form validaion as the $data array will have entries from a users request (dynamic data)
        $this->validate(request(), [
            'amount' => 'required',
            'email' => 'required',
            'fullname' => 'required',
            'phone_number' => 'required'
        ]);

        $data = [
                    "tx_ref" => "MC-15852113s09v5050e8",
                    "amount" => request('amount'),
                    "currency" => "KES",
                    "email" => request('email'),
                    "phone_number" => request('phone_number'),
                    "fullname" => request('fullname')
                ];

                 //Make a call to flutterwave with $data
                $response = Http::withHeaders([
                  'Authorization' => 'Bearer FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X',
                  'Content-Type' => 'application/json',
                ])->post('https://api.flutterwave.com/v3/charges?type=mpesa', $data);

                 $res = json_decode($response, true);
                 $txref = $res['data']['tx_ref'];

                //Verify Transaction
                $verify_data = [
                                  'txref' => $txref,
                                  'SECKEY' => 'FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X'
                              ];

                $verify = Http::withHeaders([
                  'Authorization' => 'Bearer FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X',
                  'Content-Type' => 'application/json',
                ])->post('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify', $verify_data);

                $verify = json_decode($verify, true);
                $status = $verify['status'];

                if ($status == "success") {

                    //This should redirect to a success page
                    return "Transaction Successful";
                }
                else{

                    //This should redirect to a failure page
                    return "Transaction failed";
                }
    }


    public function franco(Request $request)
    {
       //The request object should be used for production or final implementation with form validaion as the $data array will have entries from a users request (dynamic data)
        $this->validate(request(), [
            'amount' => 'required',
            'email' => 'required',
            'fullname' => 'required',
            'phone_number' => 'required'
        ]);

        $data = [
                    "tx_ref" => "MC-158523s09v5050e8",
                    "amount" => request('amount'),
                    "currency" => "XAF",
                    "email" => request('email'),
                    "phone_number" => request('phone_number'),
                    "fullname" => request('fullname')
                ];

                 //Make a call to flutterwave with $data
                $response = Http::withHeaders([
                  'Authorization' => 'Bearer FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X',
                  'Content-Type' => 'application/json',
                ])->post('https://api.flutterwave.com/v3/charges?type=mobile_money_franco', $data);

                 $res = json_decode($response, true);
                 $txref = $res['data']['tx_ref'];

                //Verify Transaction
                $verify_data = [
                                  'txref' => $txref,
                                  'SECKEY' => 'FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X'
                              ];

                $verify = Http::withHeaders([
                  'Authorization' => 'Bearer FLWSECK_TEST-37138884fab76a864bccdc330b8ef843-X',
                  'Content-Type' => 'application/json',
                ])->post('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify', $verify_data);

                $verify = json_decode($verify, true);
                $status = $verify['status'];

                if ($status == "success") {

                    //This should redirect to a success page
                    return "Transaction Successful";
                }
                else{

                    //This should redirect to a failure page
                    return "Transaction failed";
                }
    }
}