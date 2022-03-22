<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Config;

class OrgCoopBankController extends Controller
{
    protected $CK;
    protected $keys;
    public $action;

    public $client;

    public function index()
    {
        $user = Auth::user();
        return view('back/organization/coopbank/index', compact('user'));
    }

    public function generateToken() {
        $this->keys = config('services.coopbank');
        $CK = $this->keys['ck'];
        $SK = $this->keys['sk'];
        
        $this->client = new \GuzzleHttp\Client(['verify' => false]);
        
        $authorization = base64_encode("$CK:$SK");
        $header = ["Authorization" => "Basic {$authorization}s"];
        $content = ["grant_type" => "client_credentials"];

        $url = "https://developer.co-opbank.co.ke:8243/token";

        $response = $this->client->post($url, [
            "headers" => $header,
            "form_params" => $content,
            "http_errors" => false,
        ]);

        $responseObj = json_decode($response->getBody());
        $token = $responseObj->access_token;

        return $token;
    }

    public function handleRequest(Request $request){
        $action = $request->action;

        $data = array(
            "account_number" => $request->account_number,
            "message_reference" => $request->message_reference
        );

        $token = $this->generateToken();

        $this->action = $action;

        if($action == "balance"){
            return $this->checkBalance($request, $token);
        }
        elseif($action == "transactions"){
            return $this->transactions($request, $token);
        }
        elseif($action == "mini_statement"){
            return $this->miniStatement($request, $token);
        }
        elseif($action == "statement"){
            return $this->statement($request, $token);
        }
        elseif($action == "validation"){
            return $this->validation($request, $token);
        }
        elseif($action == "exchange"){
            return $this->exchange($request, $token);
        }
        elseif(in_array($action, ["iftransfer", "pesalink_transfer", "pesalink_phone", "mpesa"])){
            return $this->iftransfer($request, $token);
        }
        elseif($action == "status"){
            return $this->status($request, $token);
        }
    }

    public function checkBalance($requestData, $token) {

        $msgReference = $requestData['message_reference'];
        $accountNumber = $requestData['account_number'];

        $url = 'https://developer.co-opbank.co.ke:8243/Enquiry/AccountBalance/1.0.0';
        $header = [
            "Authorization" => "Bearer {$token}",
            "Content-Type" => "application/json"
        ];
        $content = [
            "MessageReference" => $msgReference,
            "AccountNumber" => $accountNumber
        ];

        $response = $this->client->post($url, [
            "headers" => $header,
            "json" => $content,
            "http_errors" => false,
        ]);

        $responseObj = json_decode($response->getBody());
        $page = [
            'title' => "Account Balance",
            'action' => $this->action
        ];

        $user = Auth::user();
        return view('back/organization/coopbank/basic', compact('user', 'page', 'responseObj'));
    }

    public function transactions($requestData, $token) {

        $msgReference = $requestData['message_reference'];
        $accountNumber = $requestData['account_number'];
        $noOfTransactions = $requestData['no_of_transactions'];

        $url = 'https://developer.co-opbank.co.ke:8243/Enquiry/AccountTransactions/1.0.0/';
        $header = [
            "Authorization" => "Bearer {$token}",
            "Content-Type" => "application/json"
        ];
        $content = [
            "MessageReference" => $msgReference,
            "AccountNumber" => $accountNumber,
            "NoOfTransactions" => $noOfTransactions
        ];

        $response = $this->client->post($url, [
            "headers" => $header,
            "json" => $content,
            "http_errors" => false,
        ]);

        $responseObj = json_decode($response->getBody());
        $page = [
            'title' => "Transactions",
            'action' => $this->action,
        ];

        $user = Auth::user();
        return view('back/organization/coopbank/transactions', compact('user', 'page', 'responseObj'));
    }

    public function statement($requestData, $token){

        $msgReference = $requestData['message_reference'];
        $accountNumber = $requestData['account_number'];
        $dates = explode(" - ", $requestData->period);

        $url = 'https://developer.co-opbank.co.ke:8243/Enquiry/FullStatement/Account/1.0.0/';
        $header = [
            "Authorization" => "Bearer {$token}",
            "Content-Type" => "application/json"
        ];
        $content = [
            "MessageReference" => $msgReference,
            "AccountNumber" => $accountNumber,
            "StartDate" => $dates[0],
            "EndDate" => $dates[1]
        ];

        $response = $this->client->post($url, [
            "headers" => $header,
            "json" => $content,
            "http_errors" => false,
        ]);

        $responseObj = json_decode($response->getBody());
        $page = [
            'title' => "Account Full Statement",
            'action' => $this->action
        ];

        $user = Auth::user();
        return view('back/organization/coopbank/transactions', compact('user', 'page', 'responseObj'));
    }

    public function miniStatement($requestData, $token) {

        $msgReference = $requestData['message_reference'];
        $accountNumber = $requestData['account_number'];

        $url = 'https://developer.co-opbank.co.ke:8243/Enquiry/MiniStatement/Account/1.0.0/';
        $header = [
            "Authorization" => "Bearer {$token}",
            "Content-Type" => "application/json"
        ];
        $content = [
            "MessageReference" => $msgReference,
            "AccountNumber" => $accountNumber
        ];

        $response = $this->client->post($url, [
            "headers" => $header,
            "json" => $content,
            "http_errors" => false,
        ]);

        $responseObj = json_decode($response->getBody());
        $page = [
            'title' => "Account Mini Statement",
            'action' => $this->action
        ];

        $user = Auth::user();
        return view('back/organization/coopbank/transactions', compact('user', 'page', 'responseObj'));
    }

    public function validation($requestData, $token) {

        $msgReference = $requestData['message_reference'];
        $accountNumber = $requestData['account_number'];

        $url = 'https://developer.co-opbank.co.ke:8243/Enquiry/Validation/Account/1.0.0/';
        $header = [
            "Authorization" => "Bearer {$token}",
            "Content-Type" => "application/json"
        ];
        $content = [
            "MessageReference" => $msgReference,
            "AccountNumber" => $accountNumber
        ];

        $response = $this->client->post($url, [
            "headers" => $header,
            "json" => $content,
            "http_errors" => false
        ]);

        $responseObj = json_decode($response->getBody());
        $page = [
            'title' => "Account Validation",
            'action' => $this->action
        ];

        $user = Auth::user();
        return view('back/organization/coopbank/basic', compact('user', 'page', 'responseObj'));
    }

    public function exchange($requestData, $token) {

        $msgReference = $requestData['message_reference'];
        $currencyFrom = $requestData['currency_from'];
        $currencyTo = $requestData['currency_to'];

        $url = 'https://developer.co-opbank.co.ke:8243/Enquiry/ExchangeRate/1.0.0/'; 
        $header = [
            "Authorization" => "Bearer {$token}",
            "Content-Type" => "application/json"
        ];
        $content = [
            "MessageReference" => $msgReference,
            "FromCurrencyCode" => $currencyFrom,
            "ToCurrencyCode" => $currencyTo
        ];

        $response = $this->client->post($url, [
            "headers" => $header,
            "json" => $content,
            "http_errors" => false
        ]);

        $responseObj = json_decode($response->getBody());
        $page = [
            'title' => "Exchange Rate Calculator",
            'action' => $this->action
        ];

        $user = Auth::user();
        return view('back/organization/coopbank/basic', compact('user', 'page', 'responseObj'));
    }

    public function iftransfer($requestData, $token) {

        $msgReference = $requestData['message_reference'];
        $currency = $requestData['currency'];
        if($this->action == "pesalink_phone"){
            $accountTo = "254" . ltrim($requestData['phone_number'], "0");
            $destinationKey = "PhoneNumber";
        }
        elseif($this->action == "mpesa"){
            $accountTo = "254" . ltrim($requestData['phone_number'], "0");
            $destinationKey = "MobileNumber";
        }
        else{
            $accountTo = $requestData['destination_account'];
            $destinationKey = "AccountNumber";
        }
        $accountFrom = $requestData['account_number'];
        $amount = $requestData['amount'];
        $narration = $requestData['narration'];
        $referenceNumber = $requestData['reference_number'];

        switch($this->action){
            case "iftransfer":
                $title = "Internal Fund Transfer (Account to Account)";
                $url = 'https://developer.co-opbank.co.ke:8243/FundsTransfer/Internal/A2A/2.0.0'; 
                break;
            case "pesalink_transfer":
                $title = "PesaLink Funds Transfer";
                $bankCode = $requestData['bank_code'];
                $url = 'https://developer.co-opbank.co.ke:8243/FundsTransfer/External/A2A/PesaLink/1.0.0'; 
                break;
            case "pesalink_phone":
                $title = "PesaLink Send to Phone";
                $url = 'https://developer.co-opbank.co.ke:8243/FundsTransfer/External/A2M/PesaLink/1.0.0';
                break;
            case "mpesa":
                $title = "Send to M-Pesa";
                $url = 'https://developer.co-opbank.co.ke:8243/FundsTransfer/External/A2M/Mpesa/1.0.0/';
            default:
                $title = "";
        }

        $callbackUrl = 'http://localhost:8000/user/user-transactions';
        $header = [
            "Authorization" => "Bearer {$token}",
            "Content-Type" => "application/json"
        ];
        $content = [
            "MessageReference" => random_int(time(), time() + 400),
            "CallBackUrl" => $callbackUrl,
            "Source" => [
                "AccountNumber" => $accountFrom,
                "Amount" => $amount,
                "TransactionCurrency" => $currency,
                "Narration" => $narration
            ],
            "Destinations" => [array(
                "ReferenceNumber" => $referenceNumber,
                $destinationKey => $accountTo,
                "Amount" => $amount,
                "TransactionCurrency" => $currency,
                "Narration" => $narration,
                isset($bankCode) ? ['BankCode' => $bankCode] : [],
            )],
        ];

        $response = $this->client->post($url, [
            "headers" => $header,
            "json" => $content,
            "http_errors" => false
        ]);

        // $title = $this->action == "pesalink_transfer" ? "PesaLink Funds Transfer"  : "";

        $responseObj = json_decode($response->getBody());
        $page = [
            'title' => $title,
            'action' => $this->action
        ];

        $user = Auth::user();
        return view('back/organization/coopbank/basic', compact('user', 'page', 'responseObj'));
    }

    public function status($requestData, $token) {

        $msgReference = $requestData['reference_number'];

        $url = 'https://developer.co-opbank.co.ke:8243/Enquiry/TransactionStatus/2.0.0/';
        $header = [
            "Authorization" => "Bearer {$token}",
            "Content-Type" => "application/json"
        ];
        $content = [
            "MessageReference" => $msgReference,
        ];

        $response = $this->client->post($url, [
            "headers" => $header,
            "json" => $content,
            "http_errors" => false
        ]);

        $responseObj = json_decode($response->getBody());
        $page = [
            'title' => "Check Transaction Status",
            'action' => $this->action
        ];

        // return ($response->getBody());

        $user = Auth::user();
        return view('back/organization/coopbank/basic', compact('user', 'page', 'responseObj'));
    }
}
