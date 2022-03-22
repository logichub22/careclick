<?php

namespace App\Http\Controllers\Back\Trainer;

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
use App\Models\General\Group;
use App\Models\General\Wallet;

class PageController extends Controller
{
    public function getDashboard()
    {
        //dd(\App::getLocale());
        $user = Auth::user();

        $groups = DB::table('groups')
                ->join('users', 'users.id', '=', 'groups.user_id')
                ->join('group_trainers', 'group_trainers.group_id', '=', 'groups.id')
                ->where(['groups.trainer_id' => $user->id])
                ->select('groups.*', 'group_trainers.created_at as assigneddate', 'group_trainers.completed', 'users.name as firstname', 'users.other_names as othernames')
                ->orderBy('created_at', 'desc')
                ->get();

        $wallet = Wallet::where('user_id', $user->id)->first();
    	return view('back/trainer/pages/dashboard', compact('wallet', 'groups'));
    }

    public function manageProfile()
    {
    	$user = Auth::user();

    	$detail = DB::table('users')
                      ->join('user_details', 'user_details.user_id', '=', 'users.id')
                      ->where('user_details.user_id', '=' , $user->id)
                      ->first();

        $organization = DB::table('user_details')
                      ->join('organization_details', 'organization_details.id', '=', 'user_details.org_id')
                      ->where('organization_details.id', '=', $detail->org_id)
                      ->select('organization_details.name')
                      ->first();

        $groups = DB::table('groups')
                    ->join('users', 'users.id', '=', 'groups.user_id')
                    ->where('groups.user_id', '=', $user->id)
                    ->get();
      
        $membergroups = DB::table('groups')
                    ->join('group_members', 'group_members.group_id', '=', 'groups.id')
                    ->where('group_members.user_id', '=', $user->id)
                    ->get();

        $wallet = Wallet::where('user_id', $user->id)->first();

        $documents = DB::table('documents')->get();
        $incomes = DB::table('income_classes')->get();
        $income = implode(DB::table('income_classes')->where('id', $detail->income)->pluck('name')->toArray());
        $gender = implode(DB::table('genders')->where('id', $detail->gender)->pluck('name')->toArray());
        $doc = implode(DB::table('documents')->where('id', $detail->doc_type)->pluck('name')->toArray());
        $marital = implode(DB::table('maritals')->where('id', $detail->marital_status)->pluck('name')->toArray());
        $residence = implode(DB::table('resident_types')->where('id', $detail->residence)->pluck('name')->toArray());
        $country = implode(DB::table('countries')->where('id', $detail->country)->pluck('name')->toArray());

        return view('back/trainer/pages/profile', compact('detail', 'user', 'documents', 'incomes', 'income', 'gender', 'doc', 'marital', 'residence', 'country', 'organization', 'groups', 'membergroups', 'wallet'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->all();

        $myuser = DB::table('users')
                ->join('user_details', 'user_details.id', '=', 'users.id')
                ->where('user_details.user_id', '=', $user->id)
                ->select('users.*')
                ->first();

        $request->validate([
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
            'password' => 'sometimes|confirmed',
            'city' => 'required',
            'state' => 'sometimes',
            'postal_code' => 'required',
            'address' => 'required',
            'occupation' => 'required',
            'doc_no' => [
                'required',
                Rule::unique('user_details')->ignore($myuser->id),
            ],
            'doc_type' =>'required',
        ]);

        //dd($request);

        DB::table('users')->where('id', $user->id)->update([
            'name' => $request->name,
            'other_names' => $request->other_names,
            'email' => $request->email,
            'msisdn' => $request->msisdn,
        ]);

        if ($data['password']) {
            $user->password = bcrypt($request->password);
            $user->save();
            // Mail::to($user->email)->send(new AdminPasswordChanged($maildata));
            // Auth::logoutOtherDevices($user->password);
        }

        $oldId = $user->identification_document;
        if ($request->hasFile('identification_document')) {
            $file = request('identification_document');
            if (file_exists(public_path('documents/ids/' . $oldId))) {
                if (! is_null($oldId)) {
                    unlink(public_path('documents/ids/' . $oldId));
                }
            }
            $filename = strtoupper(Str::random(5)) . '.' . $file->getClientOriginalExtension();
            $user->identification_document = $filename;
            $user->save();
            $request->identification_document->move(
                base_path() . '/public/documents/ids', $filename
            );
        }

        DB::table('user_details')->where('user_id', $user->id)->update([
            'occupation' => $request->occupation,
            'income' => $request->income,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'doc_type' => $request->doc_type,
            'doc_no' => $request->doc_no,
        ]);

        Session::flash('success', 'Your details have been updated successfully');
        return redirect()->back();
    }

    public function transactions()
    {
        $user = Auth::user();

        $organization = DB::table('organizations')->where('admin_id',$user->id)->first();
        $transactions = DB::table('transactions')->where('org_id', $organization->id)->orderBy('id', 'desc')->get();

        return view('back/federation/transaction/index', compact('transactions'));
    }

    public function viewGroup(Request $request, $id)
    {
        $group = Group::find($id);

        $members = DB::table('users')
                ->join('group_members', 'group_members.user_id', '=', 'users.id')
                ->orderBy('created_at','desc')
                ->select('users.*', 'group_members.id as memberid', 'group_members.is_admin as admin', 'group_members.created_at as membercreated')
                ->where(array("group_members.group_id" => $group->id))
                ->take(5)
                ->get();

        $coordinator = User::with('detail')->where('id', $group->user_id)->first();

        $detail = $coordinator->detail;

        $region_detail = DB::table('administrative_regions')->where('country_id', $detail->country)->first();
        $result = [];

        array_push($result, $region_detail->level_one , $region_detail->level_two, $region_detail->level_three, $region_detail->level_four);

        $regions_arr = array_filter($result);

        if(!is_null($group->trainer_id)) {
            $trainingofficer = User::where('id', $group->trainer_id)->first();
        } else {
            $trainingofficer = null;
        }

        return view('back/trainer/pages/viewgroup', compact('group', 'members', 'coordinator', 'regions_arr', 'trainingofficer'));
        // return view('back/trainer/pages/viewgroup');
    }

}
