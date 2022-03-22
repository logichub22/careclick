<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use GuzzleHttp\Client as GuzzleClient;

use App\User;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationWallet;
use App\Models\General\LoanPackage;
use App\Models\General\RibyLoan;
use App\Models\General\RibyTransfer;
use App\Models\General\FlutterwaveWithdrawal;
use App\Models\General\Transaction;

use App\Helpers\RibyFunctions;
use App\Helpers\CommonFunctions;

use Mail;
use App\Mail\RibyFinalApprovalEmail;

class OrganizationFunctions
{
    public static function userOrganization($user){
        // return the organization of which current user is an admin
        $isFirstSource = $user->isFirstSource();

        if($isFirstSource){
            $organization = DB::table('organizations')
                // ->join('organization_details', 'organization_details.org_id', 'organizations.id')
                ->join('users', 'users.id', 'organizations.admin_id')
                ->where('users.email', env('FIRSTSOURCE_EMAIL'))
                ->select('organizations.id') //, 'organization_details.*')
                ->first();
            $organization = Organization::where('id', $organization->id)->with('detail')->first();

            $is_superadmin = $user->email == env('FIRSTSOURCE_EMAIL') ? true : false;
        }
        else{
            $organization = DB::table('organizations')
                // ->join('organization_details', 'organization_details.org_id', 'organizations.id')
                // ->join('users', 'users.id', 'organizations.admin_id')
                ->where('organizations.admin_id', $user->id)
                ->select('organizations.id') //', 'organization_details.*')
                ->first();

            $organization = Organization::where('admin_id', $user->id)->with('detail')->first();
            $is_superadmin = true;
        }

        return [
            'organization' => $organization,
            'is_superadmin' => $is_superadmin
        ];
    }

    public static function organizationUsers($org_id, $latest=false){
        if($latest){
            $users = DB::table('users')
                    ->join('user_details', 'user_details.user_id', '=', 'users.id')
                    ->join('organizations', 'organizations.id', '=', 'user_details.org_id')
                    ->select('users.*')
                    ->where("organizations.admin_id", $org_id)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
        }
        else{
            $users = DB::table('users')
                    ->join('user_details', 'user_details.user_id', '=', 'users.id')
                    ->join('organizations', 'organizations.id', '=', 'user_details.org_id')
                    ->select('users.*')
                    ->where("organizations.id", $org_id)
                    ->get();
        }
        return $users;
    }

    public static function organizationGroups($org_id){
        $groups = DB::table('groups')->where('org_id', $org_id)->get();
        return $groups;
    }

    public static function organizationCurrency($org){
        $currency = DB::table('organization_details')
            ->join('currencies', 'country_id', '=', 'organization_details.country')
            ->where('organization_details.org_id', '=', $org->id)
            ->select('currencies.prefix')
            ->first();

        return $currency->prefix;
    }

    public static function organizationWallet($org_id){
        $wallet = OrganizationWallet::where('org_id', $org_id)->first();
        return $wallet;
    }

    public static function savingsWallet($user_id){
        $savings_wallet = DB::table('savings_wallet')->where('org_id', $user_id)->first();
        return $savings_wallet;
    }

    public static function loanPackages($user){
        // $isFirstSource = $user->isFirstSource();

        // if($isFirstSource){
        //     $riby_response = RibyFunctions::RibyGetLoanTypes();
        //     $packages = $riby_response->payload->loan_types;
        // }
        // else{

            $organization = OrganizationFunctions::userOrganization($user)['organization'];
            $packages = LoanPackage::where('org_id', $organization->id)->get();

            return $packages;
        // }
    }

    public static function loanPackageDetails($package_id, $user){
        $isFirstSource = $user->isFirstSource();

        // $package = $isFirstSource ? RibyFunctions::RibyShowLoanType($package_id)->payload : $package = LoanPackage::where('id', $package_id)->first();
        $package = LoanPackage::where('id', $package_id)->first();

        $riby_details = [];
        if($isFirstSource){
            $riby_details = RibyFunctions::RibyShowLoanType($package->riby_loantype_id)->payload;
            // $package->riby_details = $riby_details;
        }

        return [
            'package' => $package,
            'riby_details' => $riby_details,
        ];
    }

    public static function loanRequests($user, $org_array){
        $organization = $org_array['organization'];
        $isFirstSource = $user->isFirstSource();
        if($isFirstSource){
            if($org_array['is_superadmin']){
                // Final stage request
                $loan_requests = RibyLoan::where('approval_stage', 0)->orderBy('id', 'desc')->get();

            }
            else{
                // First stage requests
                $loan_requests = RibyLoan::where('approval_stage', 1)->orderBy('id', 'desc')->get();
            }
        }
        else{
            $loan_requests = DB::table('loans')
                    ->join('users', 'users.id', '=', 'loans.user_id')
                    ->join('loan_packages', 'loan_packages.id', '=', 'loans.loan_package_id')
                    ->select('users.name', 'users.other_names', 'users.email', 'loans.amount', 'loans.borrower_credit_score', 'loans.status', 'loans.created_at', 'loan_packages.name as packageName', 'loan_packages.min_credit_score', 'loan_packages.interest_rate', 'loan_packages.insured', 'loan_packages.repayment_plan', 'loan_packages.currency', 'loans.id', 'loans.loan_package_id', 'users.identification_document')
                    ->where('loan_packages.org_id', $organization->id)
                    ->where('loans.status', '=', '0')
                    ->get();

        }

        return $loan_requests;
    }

    public static function loanRequestDetails($id, $user){
        $path = '/documents/ids/';
        $isFirstSource = $user->isFirstSource();

        $request = [
            'isFirstSource' => $isFirstSource,
            'path' => $path,
            'approveUrl' => route('organization.approve', $id),
            'declineUrl' => route('organization.decline', $id),
            'requires_final_approval' => false
        ];

        if($isFirstSource){
            $loan_request = RibyLoan::where('id', $id)->first();
            $request_id = $loan_request->application_id;
            $riby_details = RibyFunctions::RibyGetLoanRequestDetails($request_id);
            $request['riby_details'] = $riby_details->payload;

            if($loan_request->approval_stage == 1){
                $request['requires_final_approval'] = true;
                $request['first_approval'] = [
                    'by' => User::where('id', $loan_request->first_approval_by)->first(),
                    'time' => date('M j, Y', strtotime($loan_request->first_approval_time)) . ' at ' . date('g:ia', strtotime($loan_request->first_approval_time))
                ];
            }
        }
        else{
            $loan_request = DB::table('loans')
                    ->join('users', 'users.id', '=', 'loans.user_id')
                    ->join('loan_packages', 'loan_packages.id', '=', 'loans.loan_package_id')
                    ->select('users.name', 'users.other_names', 'users.email', 'loans.amount', 'loans.borrower_credit_score', 'loans.status', 'loans.created_at', 'loan_packages.name as packageName', 'loan_packages.min_credit_score', 'loan_packages.interest_rate', 'loan_packages.insured', 'loan_packages.repayment_plan', 'loan_packages.currency', 'loans.id', 'loans.loan_package_id', 'users.identification_document')
                    ->where('loans.status', '=', '0')
                    ->where('loans.id', $id)
                    ->first();
        }

        $request['request'] = $loan_request;
        return $request;


        return [
            'isFirstSource' => $isFirstSource,
            'request' => $loan_request,
            'path' => $path,
            'approveUrl' => route('organization.approve', $id),
            'declineUrl' => route('organization.decline', $id),
        ];
    }

    public static function approveLoan($id, $user){
        $isFirstSource = $user->isFirstSource();

        if($isFirstSource){
            $loan = RibyLoan::where('id', $id)->whereIn('approval_stage', [0, 1])->first();

            if($loan){
                $approver = $user->id;
                $approval_time = date("Y-m-d H:i:s");

                $approval_stage = $loan->approval_stage;
                $next_stage = $approval_stage + 1;
                $loan->approval_stage = $next_stage;

                if($approval_stage == 0){
                    $loan->first_approval_by = $approver;
                    $loan->first_approval_time = $approval_time;
                    $loanApproval = ['info', "Loan request has passed first approval, and funds will be disbursed as soon as it receives final approval"];

                    $admin = User::where('email', env('FIRSTSOURCE_EMAIL2'))->first();
                    $maildata = [
                        'amount' => $loan->amount,
                        'applicant_name' => $loan->applicant_name,
                        'package_name' => $loan->package_name,
                        'admin_name' => $admin->name,
                    ];

                    // Create a notification
                    Mail::to(env('FIRSTSOURCE_EMAIL2'))->send(new RibyFinalApprovalEmail($maildata));
                }
                else{
                    $org_array = self::userOrganization($user);
                    $organization = $org_array['organization'];
                    $org_wallet = OrganizationWallet::where('org_id', $organization->id)->first();

                    $wallet_balance = $org_wallet->balance;
                    $amount = $loan->amount;

                    if($amount > $wallet_balance){
                        $loanApproval = ["warning", "You do not have sufficient balance to approve this loan."];
                    }
                    else{

                        $bank_transfer = RibyFunctions::TransferViaFlutterwave($amount);
                        $transfer_data = $bank_transfer->data;

                        if($bank_transfer->status == "success"){
                            // Create debit transaction
                            $transaction = new Transaction();
                            $transaction->org_id = $organization->id;
                            $transaction->txn_code = $transfer_data->reference;
                            $transaction->txn_type = 4; // Pending Debit
                            $transaction->transaction_type_id = 4; // Loan request
                            $transaction->amount = $transfer_data->amount;
                            $transaction->save();

                            // Update wallet balance
                            $org_wallet->balance = $wallet_balance - $amount;
                            $org_wallet->save();

                            // Create database record
                            $trf = new RibyTransfer();
                            $trf->fw_id = $transfer_data->id;
                            $trf->account_no = $transfer_data->account_number;
                            $trf->bank_code = $transfer_data->bank_code;
                            $trf->currency = $transfer_data->currency;
                            $trf->amount = $transfer_data->amount;
                            $trf->fee = $transfer_data->fee;
                            $trf->reference = $transfer_data->reference;
                            $trf->narration = $transfer_data->narration;
                            // $trf->meta = is_null;
                            $trf->loan_request_id = $loan->application_id;
                            $trf->status = $transfer_data->status;
                            $trf->message = $transfer_data->complete_message;
                            $trf->save();

                            $loanApproval = ["info", "Loan approval and transfer are now being queued for processing."];


                            $loan->second_approval_by = $approver;
                            $loan->second_approval_time = $approval_time;
                        }
                        else{
                            $loanApproval = ["error", $bank_transfer->message . ": " . $transfer_data->complete_message];
                        }
                    }
                }

                $loan->save();
            }
            else{
                $loanApproval = ["error", "This loan request has either been already approved or declined."];
            }
        }
        else{
            $loanApproval = CommonFunctions::approveLoan($id, $user);
        }

        return $loanApproval;
    }

    public static function withdraw($user, $amount, $account_number, $bank){
        $organization = OrganizationFunctions::userOrganization($user)['organization'];
        $org_wallet = OrganizationWallet::where('org_id', $organization->id)->first();
        $wallet_balance = $org_wallet->balance;

        $charges = CommonFunctions::calculateFlutterwaveCharges($amount);
        $total_amount = round($amount + $charges, 2);

        if($total_amount <= $wallet_balance){
            // Initiate withdrawal
            $req_url = 'https://api.flutterwave.com/v3/transfers';
            $flw_key = env('FLW_SECRET_KEY');
            $header = [
                "Authorization" => "Bearer {$flw_key}",
                "Content-Type" => "application/json",
            ];

            $is_staging = env('APP_ENV') == "local";

            $request_payload = [
                "amount" => $amount,
                "narration" => "Jamborow Withdrawal",
                "currency" => "NGN",
                "account_bank" => $bank,
                "account_number" => $account_number,
                "callback_url" => route('verify-fw-withdrawal')
            ];

            if($is_staging){
                $mock_success = "DU_1";
                $mock_fail = "_ST_FDU_1";
                $request_payload['reference'] = "ribyfs_" . Str::random(10) . '_PMCK' . $mock_success;
            }
            else{
                $request_payload['reference'] = "txn_" . Str::random(14);
            }

            $client = new GuzzleClient(['http_errors' => false]);
            $response = $client->request('POST', $req_url, [
                "headers" => $header,
                "body" => json_encode($request_payload)
            ]);

            $response_obj = json_decode($response->getBody());
            $response_data = $response_obj->data;
            // END Flutterwave transfer

            // Create debit transaction
            $transaction = new Transaction();
            $transaction->org_id = $organization->id;
            $transaction->txn_code = $response_data->reference;
            $transaction->txn_type = 4; // Pending Debit
            $transaction->transaction_type_id = 2; // Withdrawal
            $transaction->amount = $total_amount;// $response_data->amount;
            $transaction->save();


            // Debit wallet
            $org_wallet->balance = $wallet_balance - $total_amount;
            $org_wallet->save();


            // Create database record
            $trf = new FlutterwaveWithdrawal();
            $trf->fw_id = $response_data->id;
            $trf->org_id = $organization->id;
            $trf->account_name = $response_data->full_name;
            $trf->account_number = $response_data->account_number;
            $trf->bank = $response_data->bank_code;
            $trf->currency = $response_data->currency;
            $trf->amount = $response_data->amount;
            $trf->fee = $response_data->fee;
            $trf->reference = $response_data->reference;
            $trf->narration = $response_data->narration;
            $trf->status = $response_data->status;
            $trf->message = $response_data->complete_message;
            $trf->save();

            // wait for callback
            $withdrawal = [true, "Your withdrawal request has been received and will be processed shortly."];
        }
        else{
            $withdrawal = [false, "You do not have sufficient balance to complete this transaction."];
        }
        return $withdrawal;
    }
}
