<?php
namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client as GuzzleClient;

class RibyFunctions
{
    public static function RibyUrl($url, $auth=false){
        
        $base_auth_url = env('APP_ENV') == "local" ? "https://staging-accounts.riby.ng" : "https://accounts.riby.ng";
        $base_url = env('APP_ENV') == "local" ? "https://staging-apis.riby.ng" : "https://apis.riby.ng";

        $base = $auth == true ? $base_auth_url : $base_url;
        return $base . $url;
    }

    public static function RibyGenerateToken()
    {
        
        $client = new GuzzleClient(['http_errors' => false]);
        // $client = new \GuzzleHttp\Client(['verify' => false]);
        $client->setDefaultOption('verify', false);
        $signin_url = self::RibyUrl('/signin/v1/token', true);
        $riby_auth_username = env('RIBY_CLIENT_ID'); // "60d09c71b449c50001e693ab";
        $riby_auth_password = env('RIBY_CLIENT_KEY'); //"secret";

        $basic_token = \base64_encode("{$riby_auth_username}:{$riby_auth_password}");
        
        $header = [
            "Content-Type" => "application/x-www-form-urlencoded",
            "Authorization" => "Basic {$basic_token}",
        ];

        $content = [
            "scope" => "write_member read_member modify_member read_cooperative modify_cooperative write_role delete_role read_role modify_role write_contribution_type read_contribution_type modify_contribution_type write_contribution read_contribution write_loan_type read_loan_type modify_loan_type delete_loan_type write_loan_application read_loan_application modify_loan_application approve_loan_application reject_loan_application disburse_loan read_loan read_contribution_category read_branch write_branch read_withdrawal reject_withdrawal approve_withdrawal disburse_withdrawal write_loan",
        ];

        if(env('APP_ENV') == "local"){
            $content['username'] = env('RIBY_USERNAME');
            $content["password"] = env('RIBY_PASSWORD'); //$riby_acct_password,
            $content["grant_type"] = "password";
        }
        else{
            $content["grant_type"] = "client_credentials";
        }
        
        $response = $client->post($signin_url, [
            "headers" => $header,
            "form_params" => $content,
            "http_errors" => false,
        ]);

        $request_response = json_decode($response->getBody());
        $riby_key = $request_response->access_token;

        session([
            'riby' => [
                'key' => $riby_key,
                'expires_in' => time() + $request_response->expires_in
            ]
        ]);
    }

    public static function RibyGetToken(){
        $ribySession = isset(session('riby')['key'], session('riby')['expires_in'])
            ? session('riby')
            : null;

        if(!($ribySession != null && $ribySession['expires_in'] > time())){
            // Regenerate key
            self::RibyGenerateToken();
        }

        $token = session('riby');
        return $token['key'];
    }

    /*
    public static function isFirstSource($user_id){
        $org_info = DB::table('organizations')
            // ->join('organizations', 'organizations.admin_id', $user_id)
            ->join('organization_details', 'organization_details.org_id', 'organizations.id')
            ->select('organization_details.name')
            ->where('organizations.admin_id', $user_id)
            ->first();

        return $org_info != null ? $org_info->name == "Firstsource" : false;
    }
    */

    public static function RibyGetLoanRequests(){
        $token = self::RibyGetToken();
        
        $client = new GuzzleClient(['http_errors' => false]);
        $req_url = self::RibyUrl('/rcb/lm/v1/application?include_approvals=true');
        
        $header = [
            "Authorization" => "Bearer {$token}",
            "Content-Type" => "application/json",
        ];

        $response = $client->request('GET', $req_url, [
            "headers" => $header,
        ]);

        $request_response = json_decode($response->getBody());
        return $request_response;
    }

    public static function RibyGetLoanRequestDetails($loan_id){
        $token = self::RibyGetToken();
        
        $client = new GuzzleClient(['http_errors' => false]);
        $req_url = self::RibyUrl('/rcb/lm/v1/application/'.$loan_id);
        
        $header = [
            "Authorization" => "Bearer {$token}",
            "Content-Type" => "application/json",
        ];

        $response = $client->request('GET', $req_url, [
            "headers" => $header,
        ]);

        // $response = $client->get('/rcb/lm/v1/application', ['headers' => $header]);
        // return $response;

        $request_response = json_decode($response->getBody());

        // Get applicant details
        $applicant_id = $request_response->payload->customer_id;
        $applicant_data = self::RibyGetCustomerInfo($applicant_id);
        // $applicant_data = $applicant_info->payload;

        $request_response->payload->applicant_data = $applicant_data;
        return $request_response;
    }

    public static function RibyGetLoans(){
        $token = self::RibyGetToken();
        
        $client = new GuzzleClient(['http_errors' => false]);
        $req_url = self::RibyUrl('/rcb/lm/v1/loan?include_repayments=true&include_repayment_schedules=true');
        
        $header = [
            "Authorization" => "Bearer {$token}",
            "Content-Type" => "application/json",
        ];

        $response = $client->request('GET', $req_url, [
            "headers" => $header,
        ]);

        $request_response = json_decode($response->getBody());
        return $request_response;
    }

    public static function RibyGetLoanDetails($loan_id){
        $token = self::RibyGetToken();
        
        $client = new GuzzleClient(['http_errors' => false]);
        $req_url = self::RibyUrl('/rcb/lm/v1/loan/'. $loan_id .'?include_repayments=true&include_repayment_schedules=true');
        
        $header = [
            "Authorization" => "Bearer {$token}",
            "Content-Type" => "application/json",
        ];

        $response = $client->request('GET', $req_url, [
            "headers" => $header,
        ]);

        $request_response = json_decode($response->getBody());
        return $request_response;
    }

    public static function declineLoan($loan_id)
    {
        $token = self::RibyGetToken();
        $req_url = self::RibyUrl('/rcb/lm/v1/application/reject');

        $header = [
            "Authorization" => "Bearer {$token}",
            "Content-Type" => "application/json",
        ];

        $body = json_encode([
            "approval" => [
                "application_id" => $loan_id,
                "approval_status" => "REJECTED",
                "comment" => "Unable to approve this loan at the moment"
            ]
        ]);

        $client = new GuzzleClient(['http_errors' => false]);
        $response = $client->request('POST', $req_url, [
            "headers" => $header,
            "body" => $body
        ]);

        $request_response = json_decode($response->getBody());
        return $request_response;
    }

    public static function approveLoan($loan_id)
    {
        $token = self::RibyGetToken();
        $req_url = self::RibyUrl('/rcb/lm/v1/application/approve');

        $header = [
            "Authorization" => "Bearer {$token}",
            "Content-Type" => "application/json",
        ];

        $body = json_encode([
            "approval" => [
                "application_id" => $loan_id,
                "approval_status" => "APPROVED",
                "comment" => "Loan approved by Firstsource credit provider"
            ]
        ]);

        $client = new GuzzleClient(['http_errors' => false]);
        $response = $client->request('POST', $req_url, [
            "headers" => $header,
            "body" => $body
        ]);

        $request_response = json_decode($response->getBody());
        return $request_response;
    }

    public static function newLoanType($data)
    {
        $token = self::RibyGetToken();
        $req_url = self::RibyUrl('/rcb/lm/v1/loan-type');

        $header = [
            "Authorization" => "Bearer {$token}",
            "Content-Type" => "application/json",
        ];

        $body = json_encode([
            'loan_type' => [
                'name' => $data['package_name'],
                'min_amount' => $data['min_amount'],
                'max_amount' => $data['max_amount'],
                'min_tenure' => 1*30, // 30 days (1 month)
                'max_tenure' => $data['max_tenure'] * 30, // in months
                'transaction_fee_frequency' => 'ONCE',
                'transaction_fee_rate' => 5,
                'interest_rate' => $data['interest_rate'],
                'interest_frequency' => 'RECURRING',
                'model' => 'STRAIGHT-LINE',
                'currency' => $data['currency'],
                'repayment_frequency' => 'MONTHLY',
                'late_repayment_frequency' => 'MONTHLY',
                'owner_name' => $data['owner_name'],
                'administrative_fee_rate' => 1,
                'administrative_fee_frequency' => 'ONCE',
                'payment_method_ids' => [1, 2, 4, 6, 7, 8],
                'description' => $data['description'],
                'min_approval' => 1,
                'start_date' => date('d-m-Y'),
                // 'owner_id' => $data['owner_id'],
                // 'owner_type' => $data['owner_type'],
                // 'end_date' => $data['end_date'],
                'requirements' =>  [
                    (object)[
                        'id' => 1, 'compulsory' => true, // First name
                        'id' => 2, 'compulsory' => true, // Last name
                        'id' => 6, 'compulsory' => true, // Phone number
                    ]],
                'visibility' => 1
            ]
        ]);

        $client = new GuzzleClient(['http_errors' => false]);
        $response = $client->request('POST', $req_url, [
            "headers" => $header,
            "body" => $body
        ]);

        $request_response = json_decode($response->getBody());
        return $request_response;
    }

    public static function RibyGetLoanTypes()
    {
        $token = self::RibyGetToken();
        $req_url = self::RibyUrl('/rcb/lm/v1/loan-type');

        $header = [
            "Authorization" => "Bearer {$token}",
            "Content-Type" => "application/json",
        ];

        $client = new GuzzleClient(['http_errors' => false]);
        $response = $client->request('GET', $req_url, [
            "headers" => $header,
        ]);

        $request_response = json_decode($response->getBody());
        return $request_response;
    }

    public static function RibyShowLoanType($id)
    {
        $token = self::RibyGetToken();
        $req_url = self::RibyUrl('/rcb/lm/v1/loan-type/'.$id);

        $header = [
            "Authorization" => "Bearer {$token}",
            "Content-Type" => "application/json",
        ];

        $client = new GuzzleClient(['http_errors' => false]);
        $response = $client->request('GET', $req_url, [
            "headers" => $header,
        ]);

        $request_response = json_decode($response->getBody());
        return $request_response;
    }

    public static function RibyEditLoanType($id, $data)
    {
        $token = self::RibyGetToken();
        $req_url = self::RibyUrl('/rcb/lm/v1/loan-type/'.$id);

        $header = [
            "Authorization" => "Bearer {$token}",
            "Content-Type" => "application/json",
        ];

        $client = new GuzzleClient(['http_errors' => false]);
        $response = $client->request('PUT', $req_url, [
            "headers" => $header,
            "body" => json_encode([
                "loan_type" => $data
            ])
        ]);

        $request_response = json_decode($response->getBody());
        return $request_response;
    }

    public static function RibyDeleteLoanType($id)
    {
        $token = self::RibyGetToken();
        $req_url = self::RibyUrl('/rcb/lm/v1/loan-type/'.$id);

        $header = [
            "Authorization" => "Bearer {$token}",
            "Content-Type" => "application/json",
        ];

        $client = new GuzzleClient(['http_errors' => false]);
        $response = $client->request('DELETE', $req_url, [
            "headers" => $header,
            /*"body" => json_encode([
                "loan_type" => $data
            ])*/
        ]);

        $request_response = json_decode($response->getBody());
        return $request_response;
    }

    public static function TransferViaFlutterwave($amount)
    {
        // Transfer from Jamborow to Riby   
        $req_url = 'https://api.flutterwave.com/v3/transfers';
        $flw_key = env('FLW_SECRET_KEY');
        $header = [
            "Authorization" => "Bearer {$flw_key}",
            "Content-Type" => "application/json",
        ];

        $is_staging = env('APP_ENV') == "local";

        $request_payload = [
            "amount" => $amount,
            "narration" => "APR Firstsource",
            "currency" => "NGN",
            "account_bank" => env('RIBY_ACCOUNT_BANK'), 
            "account_number" => env('RIBY_ACCOUNT_NUMBER'),
            "callback_url" => route('org.verify-riby-transfer')
        ];

        if($is_staging){
            $mock_success = "DU_1";
            $mock_fail = "_ST_FDU_1";
            $request_payload['reference'] = "ribyfs_" . Str::random(10) . '_PMCK' . $mock_success;
        }
        else{
            $request_payload['reference'] = "rbfs_" . Str::random(14);
        }

        $client = new GuzzleClient(['http_errors' => false]);
        $response = $client->request('POST', $req_url, [
            "headers" => $header,
            "body" => json_encode($request_payload)
        ]);

        // $response_code = $response->getStatusCode();

        return json_decode($response->getBody());
    }

    public static function RibyGetCustomerInfo($customer_id)
    {
        $token = self::RibyGetToken();
        $client = new GuzzleClient(['http_errors' => false]);

        $header = [
            "Authorization" => "Bearer {$token}",
            "Content-Type" => "application/json",
        ];
        
        $extra = [
            'token' => $token,
            'client' => $client,
            'header' => $header
        ];

        return [
            'loans' => self::RibyCustomerLoans($customer_id, $extra),
            'applications' => self::RibyCustomerApplications($customer_id, $extra),
        ];
    }

    public static function RibyCustomerLoans($customer_id, $extra)
    {
        $token = $extra['token'];
        $client = $extra['client'];
        $header = $extra['header'];

        $req_url = self::RibyUrl('/rcb/lm/v1/loan/stat/member/'.$customer_id);

        $response = $client->request('GET', $req_url, [
            "headers" => $header,
        ]);

        $request_response = json_decode($response->getBody());
        return $request_response->payload;
    }

    public static function RibyCustomerApplications($customer_id, $extra)
    {
        $token = $extra['token'];
        $client = $extra['client'];
        $header = $extra['header'];

        $req_url = self::RibyUrl('/rcb/lm/v1/application/count/member/'.$customer_id);

        $response = $client->request('GET', $req_url, [
            "headers" => $header,
        ]);

        $request_response = json_decode($response->getBody());
        return $request_response->payload;
    }
}