<?php

namespace App\Http\Controllers\Back\Organization;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\Organization\OrganizationWallet;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use App\Mail\AdminPasswordChanged;
use App\Models\General\Transaction;

use App\Helpers\OrganizationFunctions;

class PageController extends Controller
{
    public function getDashboard()
    {
        $user = Auth::user();
        $org_array = OrganizationFunctions::userOrganization($user);
        $org = $org_array['organization'];

        $users = OrganizationFunctions::organizationUsers($org->id);
        $latest = OrganizationFunctions::organizationUsers($org->id, true);

        $groups = OrganizationFunctions::organizationGroups($org->id);
        $currency = OrganizationFunctions::organizationCurrency($org);
        $wallet = OrganizationFunctions::organizationWallet($org->id);
        $savings_wallet = OrganizationFunctions::savingsWallet($org->admin_id);

        /*
        $admin = Auth::user()->id;
        $users = DB::table('users')
                    ->join('user_details', 'user_details.user_id', '=', 'users.id')
                    ->join('organizations', 'organizations.id', '=', 'user_details.org_id')
                    ->select('users.*')
                    ->where(array("organizations.admin_id" => $admin))
                    ->get();

        */

        return view('back/organization/pages/dashboard', compact('users', 'latest', 'wallet', 'groups', 'user', 'org', 'currency', 'savings_wallet'));
    }

    public function manageProfile()
    {
        $user = Auth::user();
        $organization = OrganizationFunctions::userOrganization($user)['organization'];

        /*
    	$organization = DB::table('organizations')
                      ->join('organization_details', 'organization_details.org_id', '=', 'organizations.id')
                      ->where('organizations.admin_id', '=' , $user->id)
                      ->first();
        */

    	return view('back/organization/pages/profile', compact('organization', 'user'));
    }

    public function updateOrganization(Request $request)
    {
        $user = Auth::user();

        $data = $request->all();
        $organization = OrganizationFunctions::userOrganization($user)['organization'];

        /*
    	$organization = DB::table('organizations')
                      ->join('organization_details', 'organization_details.org_id', '=', 'organizations.id')
                      ->where('organizations.admin_id', '=' , $user->id)
                      ->select('organization_details.*', 'organizations.id as myId')
                      ->first();
        */

        $request->validate([
            'name' => [
                'required',
                Rule::unique('organization_details')->ignore($organization->myId),
            ],
            'org_msisdn' => [
                'nullable',
                Rule::unique('organization_details')->ignore($organization->myId),
            ],
            'msisdn' => [
                'required',
                Rule::unique('users')->ignore($user->id),
            ],
            'address' => 'required',
            'domain' => 'nullable|active_url',
            'first_name' => 'required',
            'other_names' => 'required',
            'org_msisdn' => [
                'nullable',
                Rule::unique('organization_details')->ignore($organization->myId),
            ],
            'email' => [
                'required',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'sometimes|confirmed',
            'permit' => 'sometimes|max:2000',
            'tax' => 'sometimes|max:2000',
        ]);

        //dd($request);

        // Update Users Table
        DB::table('users')->where('id', $user->id)->update([
            'name' => $request->first_name,
            'other_names' => $request->other_names,
            'email' => $request->email,
            'msisdn' => $request->msisdn,
        ]);

        if ($data['password']) {
            $user->password = bcrypt($request->password);
            $user->save();
            Mail::to($user->email)->send(new AdminPasswordChanged($maildata));
            Auth::logoutOtherDevices($user->password);
        }

        DB::table('organization_details')->where('org_id', $organization->myId)->update([
           'org_email' => $request->org_email,
           'address' => $request->address,
           'name' => $request->name,
           'domain' => $request->domain,
           'org_msisdn' => $request->org_msisdn,
           'updated_at' => \Carbon\Carbon::now(),
        ]);

        $oldTax = $organization->tax_certificate;
        $oldPermit = $organization->permit_file;
        if ($request->hasFile('tax')) {
            $file = request('tax');
            if (file_exists(public_path('documents/tax/' . $oldTax))) {
                if (! is_null($oldTax)) {
                    unlink(public_path('documents/tax/' . $oldTax));
                }
            }

            $filename = strtoupper(Str::random(5)) . '.' . $file->getClientOriginalExtension();
            DB::table('organization_details')->where('id', $organization->myId)->update([
                'tax_certificate' => $filename,
            ]);
            $request->tax->move(
                base_path() . '/public/documents/tax', $filename
            );
        }

        if ($request->hasFile('permit')) {
            $file = request('permit');
            if (file_exists(public_path('documents/permit/' . $oldPermit))) {
                if (! is_null($oldPermit)) {
                    unlink(public_path('documents/permit/' . $oldPermit));
                }
            }

            $filename = strtoupper(Str::random(5)) . '.' . $file->getClientOriginalExtension();
            DB::table('organization_details')->where('id', $organization->myId)->update([
                'permit_file' => $filename,
            ]);
            $request->permit->move(
                base_path() . '/public/documents/permit', $filename
            );
        }

        Session::flash('success', 'Your details have been successfully updated');
        return redirect()->back();
    }

    public function accessLogs(Request $request)
    {
        $user = Auth::user();

    	$organization = DB::table('organizations')
                      ->where('organizations.admin_id', '=' , $user->id)
                      ->first();

        $logs = DB::table('access_logs')
                ->join('users', 'users.id','=', 'access_logs.user_id')
                ->join('user_details','user_details.user_id', 'access_logs.user_id')
                ->where('user_details.org_id', $organization->id)
                ->orderBy('created_at', 'desc')
                ->select('access_logs.*', 'users.name', 'users.other_names', 'users.email', 'users.msisdn')
                ->get();

       // dd($logs);

        return view('back/organization/pages/logs', compact('logs'));
    }

    public function transactions()
    {
        $user = Auth::user();

        $organization = DB::table('organizations')->where('admin_id',$user->id)->first();
        $transactions = DB::table('transactions')->where('org_id', $organization->id)->orderBy('id', 'desc')->get();

        return view('back/organization/transaction/index', compact('transactions', 'user'));
    }

    public function getLedger(Request $request)
    {
        $user = $request->user();
        $organization = DB::table('organizations')->where('admin_id',$user->id)->first();

        $transactions = DB::table('transactions')->where('org_id', $organization->id)->orderBy('id', 'desc')->get();
        $bal = OrganizationWallet::where('org_id', $organization->id)->first();

        $balance = $bal->balance;

        $newTrans = Transaction::select('id', 'amount', 'txn_type', 'created_at',
                DB::raw('@balance := @balance + IF(txn_type = "1", amount, -amount) AS balance'))
         ->from(DB::raw('transactions, (SELECT @balance := 0) AS balanceStart'))
         ->where('org_id', $organization->id)
         ->get();

         dd($newTrans);

        //dd($debits);
        return view('back/organization/transaction/ledger', compact('transactions', 'credits', 'debits'));
    }

}
