<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Helpers\RibyFunctions;
use App\Models\General\RibyLoan;
use App\Models\General\LoanPackage;
use App\Models\Organization\Organization;
use App\Models\General\Wallet;
use App\Models\General\Role;

use App\Helpers\OrganizationFunctions;

use Mail;
use App\Mail\NewRibyLoanRequest;

class CronJobController extends Controller
{
    public function syncRibyApplications(){
        set_time_limit(0);
        $loan_requests = RibyFunctions::RibyGetLoanRequests();
        $request_ids = [];

        if($loan_requests->payload){
            $requests = array_reverse($loan_requests->payload->applications);

            foreach($requests as $request){
                if($request->approval_status != "PENDING") continue;

                $req_id = $request->id;

                $application_exists = RibyLoan::where('application_id', $req_id)->first();
                if(!$application_exists){
                    // Create Application Record
                    $package_name = ucwords($request->loan_type->name);
                    $amount = $request->amount;

                    $applicant = $request->inputs;
                    $applicant_name = ucwords($applicant->first_name . ' ' . $applicant->last_name);
                    $applicant_phone_number = isset($applicant->phone_number) ? $applicant->phone_number : null;
                    $applicant_email = isset($applicant->email) ? $applicant->email : null;

                    $application = new RibyLoan();
                    $application->application_id = $req_id;
                    $application->applicant_name = $applicant_name;
                    $application->applicant_phone_number = $applicant_phone_number;
                    $application->applicant_email = $applicant_email;
                    $application->amount = $amount;
                    $application->package_name = $package_name;
                    $application->application_time = date("Y-m-d H:i:s", strtotime($request->created_at));
                    $application->save();
                    
                    // $admin = User::where('email', env('FIRSTSOURCE_EMAIL'))->first();
                    $maildata = [
                        'amount' => $amount,
                        'package_name' => $package_name,
                        'admin_name' => "FirstSource",
                        // 'admin_name' => $admin->name,
                        // 'request_id' => $req_id
                    ];

                    // Create a notification
                    // Mail::to(env('FIRSTSOURCE_EMAIL'))->send(new NewRibyLoanRequest($maildata));
                    Mail::to(env('FIRSTSOURCE_NOTIFICATION_EMAIL'))->send(new NewRibyLoanRequest($maildata));
                    array_push($request_ids, $req_id);
                }
            }
        }

        if(count($request_ids) > 0)
            logger('IDs sent in this notification: ' . implode(',', $request_ids));
    }

    public function addNewAdmin(Request $request){
        // Create organization admin and insert into users table
        $org_id = $request->org_id;
        $organization = Organization::where('id', $org_id)->first();

        if($organization){

            $user = new User;
            $user->name = $request->name;
            $user->other_names = $request->other_names;
            $user->email = $request->email;
            $user->msisdn = $request->msisdn;
            $user->status = true;
            $user->verified = true;
            $user->password = bcrypt($request->password);
            $user->save();
            
            // Add user id to admin array
            $organization_admins = $organization->associate_admin_ids;
            $admin_array = \is_null($organization_admins) ? [] : \json_decode($organization_admins);
            array_push($admin_array, $user->id);
            $organization->associate_admin_ids = json_encode($admin_array);
            $organization->save();

            // Update User Wallet Table
            $wallet = new Wallet;
            $wallet->user_id = $user->id;
            $wallet->balance = 0;
            $wallet->save();

            // Attach role
            $user->attachRole(Role::where('name','admin')->first());
            return "User added successfully";
        }
    }

    public function syncRibyLoanPackages(){
        set_time_limit(0);
        $loan_packages = RibyFunctions::RibyGetLoanTypes();
        $package_ids = [];

        $user = User::where('email', env('FIRSTSOURCE_EMAIL'))->first();
        $org_id = OrganizationFunctions::userOrganization($user)['organization']->id;


        if($loan_packages->payload){
            $packages = array_reverse($loan_packages->payload->loan_types);

            foreach($packages as $type){
                $type_id = $type->id;
                $package_exists = LoanPackage::where('riby_loantype_id', $type_id)->first();
                // return [$type];
                if(!$package_exists){
                    // Create package Record
                    $package = new LoanPackage();
                    $package->riby_loantype_id = $type_id;
                    $package->name = ucwords($type->name);
                    $package->org_id = $org_id;
                    $package->repayment_plan = strtolower($type->repayment_frequency);
                    $package->min_credit_score = 1;
                    $package->min_amount = $type->min_amount;
                    $package->max_amount = $type->max_amount;
                    $package->interest_rate = $type->interest_rate;
                    $package->currency = $type->currency;
                    $package->description = $type->description;
                    $package->insured = 0;
                    $package->status = 1;
                    $package->save();

                    array_push($package_ids, $type_id);
                }
            }
        }

        if(count($package_ids) > 0) logger('Packages added: ' . implode(',', $package_ids));
    }
}
