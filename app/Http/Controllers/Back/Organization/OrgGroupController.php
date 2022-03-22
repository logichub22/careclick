<?php

namespace App\Http\Controllers\Back\Organization;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use App\Models\General\Group;
use App\Models\General\Currency;
use DB;
use Auth;
use App\Models\Organization\Organization;
use App\Models\General\GroupContributionSetting;
use App\Models\General\GroupTrainer;
use App\Models\General\GroupWallet;
use App\Http\Requests\UserRequest;
use App\User;
use App\Models\General\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\General\GroupMember;
use App\Mail\GroupMemberCreated;
use App\Mail\SendMemberEmail;
use App\Mail\CancelMemberEmail;
use App\Mail\RenewMemberEmail;
use App\Mail\DeleteMemberEmail;
use App\Mail\GroupBulkEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\General\Wallet;
use App\Services\Group\MemberImport;
use App\Models\General\Document;
use App\Models\General\UserDetail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use App\Notifications\AssignTrainerToGroup;
use App\Notifications\ChangeTrainerToGroup;
use App\Notifications\TrainerAssignedToYourGroup;
use App\SavingsWallet;

use App\Helpers\OrganizationFunctions;

class OrgGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();        
        $organization = OrganizationFunctions::userOrganization($user)['organization'];
        $groups = OrganizationFunctions::organizationGroups($organization->id);

        /*
        $organization = Organization::with('detail')->where('admin_id', Auth::user()->id)->first();
        $groups = DB::table('groups')
                ->join('organizations', 'organizations.id', '=', 'groups.org_id')
                ->select('groups.*')
                ->where('organizations.admin_id', '=', $user->id)
                ->get();
        */

        // $groups = DB::table('groups')
        //         ->join('users', 'users.id', '=', 'groups.user_id')
        //         ->where('groups.association_id', '=', $organization->id)
        //         ->select('groups.*', 'users.name as firstname', 'users.other_names as othernames')
        //         ->orderBy('created_at', 'desc')
        //         ->get();
        $membergroups = DB::table('groups')
                      ->join('group_members', 'group_members.group_id', '=', 'groups.id')
                      ->where('group_members.user_id', '=', $user->id)
                      ->select('groups.*', 'group_members.status as memberstatus')
                      ->get();

        return view('back/organization/group/index', compact('groups', 'membergroups', 'organization', 'user'));
    }

    public function contributions()
    {
        $user = Auth::user();
        $organization = Organization::with('detail')->where('admin_id', Auth::user()->id)->first();
        
        // $groups = DB::table('groups')
        //         ->join('organizations', 'organizations.id', '=', 'groups.org_id')
        //         ->join('group_members', 'group_members.group_id', '=', 'groups.id')
        //         ->join('user_details', 'user_details.org_id', '=', 'groups.org_id')
        //         ->join('users', 'users.id', '=', 'user_details.user_id')
        //         ->where('organizations.admin_id', '=', $user->id)
        //         ->select('groups.*', 'group_members.is_admin as admin', 'users.name as firstname', 'users.other_names as lastname')
        //         ->get();

        $groups = DB::table('group_contributions')
                  ->join('users', 'users.id', '=', 'group_contributions.user_id')
                  ->

                dd($groups);

        return view('back/organization/group/contributions', compact('groups', 'organization', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $organization = OrganizationFunctions::userOrganization($user)['organization'];
        // $organization = Organization::where('admin_id', Auth::user()->id)->first();
        return view('back/organization/group/create', compact('organization', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user(); //Org
        $organization = OrganizationFunctions::userOrganization($user)['organization'];
        // $organization = DB::table('organizations')->where('admin_id', $user->id)->first();
        $currency =  DB::table('organization_details')
                    ->join('currencies', 'country_id', '=', 'organization_details.country')
                    ->where('organization_details.org_id', '=', $organization->id)
                    ->select('currencies.prefix')
                    ->first();

        $this->validate($request, [
            'name' => 'required|unique:groups|max:100',
            'comment' => 'required|max:100',
            'account_no' => 'nullable|numeric|unique:groups,account_no',
        ]);

        $group = new Group();
        $group->name = $request->name;
        $group->comment = $request->comment;
        $group->org_id = $request->org_id;
        $group->user_id = $user->id;
        $group->account_no = $request->account_no;
        $group->save();

        $wallet = new GroupWallet;
        $wallet->group_id = $group->id;
        $wallet->balance = 0;
        $wallet->save();

        $settings = new GroupContributionSetting();
        $settings->group_id = $group->id;
        $settings->amount = 0;
        $settings->currency = $currency->prefix;
        $settings->frequency = 'monthly';
        $settings->save();

        $trainer = DB::table('group_trainers')
                   ->where('group_id', '=', $group->id)
                   ->get();

        if(count($trainer) == 0){
            $group_trainer = new GroupTrainer();
            $group_trainer->group_id = $group->id;
            $group_trainer->user_id = 1;
            $group_trainer->save();
        }

        // Add group admin role
        // $user = Auth::user();
        $user_group_admin = DB::table('role_user')
                            ->where('user_id', '=', $user->id)
                            ->where('role_id', '=', 7)
                            ->first();

        if ($user_group_admin == null) {
            $user->attachRole(Role::where('name', 'group-admin')->first());

            Session::flash('success', $group->name . ' group has been created successfully');
            return redirect()->route('groups.show', $group->id);
        }
        elseif($user_group_admin->role_id == 7) {
            Session::flash('success', $group->name . ' group has been created successfully');
            return redirect()->route('groups.show', $group->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();

        $group = Group::findOrFail($id);

        $members = DB::table('users')
                ->join('group_members', 'group_members.user_id', '=', 'users.id')
                ->orderBy('created_at','desc')
                ->select('users.*', 'group_members.id as memberid', 'group_members.is_admin as admin', 'group_members.created_at as membercreated')
                ->where(array("group_members.group_id" => $group->id))
                ->take(5)
                ->get();

        $group_contributions = DB::table('group_contributions')
                               ->join('users', 'users.id', '=', 'group_contributions.user_id')
                               ->join('group_contributions_settings', 'group_contributions_settings.group_id', '=', 'group_contributions.group_id')
                               ->orderBy('created_at', 'desc')
                               ->select('group_contributions.*', 'users.name as firstname', 'users.other_names as lastname', 'users.email as email', 'users.msisdn as phone', 'group_contributions_settings.currency as currency')
                               ->where('group_contributions.group_id', $group->id)
                               ->get();
                               // dd($group_contributions);


        $coordinator = User::with('detail')->where('id', $group->user_id)->first();

        if(isset($coordinator->detail)){
            $detail = $coordinator->detail;
        }
        else{
            $org_detail = Organization::with('detail')->where('id', $group->org_id)->first();
            $detail = $org_detail->detail;
        }

        $region_detail = DB::table('administrative_regions')->where('country_id', $detail->country)->first();
        $result = [];

        array_push($result, $region_detail->level_one , $region_detail->level_two, $region_detail->level_three, $region_detail->level_four);

        $regions_arr = array_filter($result);

        $org = OrganizationFunctions::userOrganization($user)['organization'];

        $fed = Organization::where('id', $org->federation)->first();

        $trainers = User::with('detail')->whereHas('roles', function($q){
                $q->where([
                    ['name', '=' ,'trainer'],
                    ['status', '=', true]
                ]);
        })->get();

        $trainers_arr = [];

        foreach($trainers as $trainer) {
            if($trainer->detail->org_id == $fed->id) {
                array_push($trainers_arr, $trainer);
            }
        }

        if(!is_null($group->trainer_id)) {
            $trainingofficer = User::where('id', $group->trainer_id)->first();
        } else {
            $trainingofficer = null;
        }

        return view('back/organization/group/show', compact('group', 'members', 'coordinator', 'regions_arr', 'trainers_arr', 'trainingofficer', 'user', 'group_contributions'));

        //return view('back/organization/group/show', compact('group', 'members', 'wallet'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $this->validate($request,[
            'name' => [
                'required', 'max:100',
                Rule::unique('groups')->ignore($group->id),
            ],
            'comment' => 'required|max:100',
            'account_no' => [
                'nullable', 'numeric',
                Rule::unique('groups')->ignore($group->id),
            ],
        ]);

        $group->name = $request->name;
        $group->comment = $request->comment;
        $group->org_id = $group->org_id;
        $group->account_no = $request->account_no;
        $group->status = true;
        $group->save();

        Session::flash('success', $group->name . ' has been updated successfully');
        return redirect()->route('groups.show', $group->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function inviteMembers($id)
    {
        $group = Group::findOrFail($id);

        return view('back/organization/group/invite', compact('group'));
    }

    public function getAddMember($id)
    {
        $group = Group::findOrFail($id);

        $user = Auth::user();
        $organization = OrganizationFunctions::userOrganization($user)['organization'];

        /*
        $organization = DB::table('organizations')
                      ->join('organization_details', 'organization_details.org_id', '=', 'organizations.id')
                      ->where('organizations.admin_id', '=' , $user->id)
                      ->select('organization_details.*')
                      ->first();
        */

        $members = DB::table('users')
                 ->join('group_members', 'group_members.user_id', '=', 'users.id')
                 ->where('group_members.group_id', '=', $group->id)
                 ->select('users.*', 'group_members.status as memberstatus',
                    'group_members.id as memberid')
                 ->get();

        $documents = Document::all();
        $countries = DB::table('countries')->whereIn('id', [25, 93, 101, 131, 159, 177, 186,])->get();
        $incomes = DB::table('income_classes')->get();
        $maritals = DB::table('maritals')->get();
        $genders = DB::table('genders')->get();
        $residents = DB::table('resident_types')->get();

        $roles = DB::table('roles')->whereIn('name', ['group-member', 'group-admin'])->get();

        $fields = $group->group_data;

        return view('back/organization/group/add-member', compact('group', 'organization', 'members', 'documents', 'countries', 'fields', 'incomes', 'maritals', 'genders', 'residents', 'user', 'roles'));
    }

    public function postAddMember(Request $request)
    
    {
        
        dd(Hash::make('password'));

        // $request->validate([php
        //     'name' => 'required|string|max:255',
        //     'other_names' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:users',
        //     'msisdn' => 'required|unique:users,msisdn',
        //     'gender' => 'required',
        //     'doc_type' => 'required',
        //     'doc_no' => 'required|unique:user_details,doc_no',
        //     'dob' => 'required|date',
        //     'country' => 'required',
        //     'residence' => 'required',
        //     'city' => 'required',
        //     //'state' => 'required',
        //     'postal_code' => 'required',
        //     'address' => 'required',
        //     'income' => 'required',
        //     'occupation' => 'required',
        //     'role' => 'required',
        //     //'identification_document' => 'required',
        // ]);

        // dd($request->role);

        $password = $this->generatePassword(6);
        
        dd($password);

        // Store Member Into Users Table;
        $user = new User;
        $user->name = $request->name;
        $user->other_names = $request->other_names;
        $user->email = $request->email;
        $user->msisdn = $request->msisdn;
        $user->account_no = $request->account_no;
        $user->password = Hash::make($password);
        $user->status = true;
        $user->verified = true;
        $user->save();

        // Attach Role
        if ($request->role == 6) {
            $user->attachRole(Role::where('name','group-member')->first());
        }
        elseif($request->role == 7) {
            $user->attachRole(Role::where('name','group-admin')->first());
        }

        if ($request->hasFile('identification_document')) {
            $file = request('identification_document');
            $filename = strtoupper(Str::random(5)) . '.' . $file->getClientOriginalExtension();
            $user->identification_document = $filename;
            $user->save();
            $request->identification_document->move(
                base_path() . '/public/documents/ids', $filename
            );
        }

        // Create Wallet Entry
        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->balance = 0;
        $wallet->save();

        $savings_wallet = new SavingsWallet;
        $savings_wallet->user_id = $user->id;
        $savings_wallet->balance = 0;
        $savings_wallet->save();

        // Store in Org Group Members
        $member = new GroupMember;
        $member->group_id = $request->group_id;
        $member->user_id = $user->id;
        if($request->role == 7) {
            $member->is_admin = 1;
        }
        else{
            $member->is_admin = 0;
        }
        $member->save();

        $detail = new UserDetail();
        $detail->user_id = $user->id;
        $detail->org_id = $request->org_id;
        $detail->gender = $request->gender;
        $detail->doc_type = $request->doc_type;
        $detail->dob = $request->dob;
        $detail->doc_no = $request->doc_no;
        $detail->country = $request->country;
        $detail->city = $request->city;
        $detail->address = $request->address;
        $detail->income = $request->income;
        $detail->occupation = $request->occupation;
        $detail->marital_status = $request->marital_status;
        $detail->postal_code = $request->postal_code;
        $detail->residence = $request->residence;
        $detail->save();

        $group = Group::where('id', $request->group_id)->first();
        if (! is_null($group->group_data)) {
            $membernew = GroupMember::where('id', $member->id)->first();
            $fields = $group->group_data;
            $array = filter_input_array(INPUT_POST);
            $newArray = $array['fields'];

            foreach ($fields as $key => $value) {
                $data = $membernew->member_data;
                $field = $key;
                $data[$field] = null;
                $membernew->member_data = $data;
                $membernew->save();
            }

            $values = array_values($newArray);
            $keys = array_keys($membernew->member_data);
            $newdata = array_combine($keys, $values);

            $membernew->member_data = $newdata;
            $membernew->save();
        }

        // Country IDS
        // Nigeria 131
        // Kenya 93
        // Liberia 101
        // Uganda 186
        // Tanzania 177
        // Botswana 25
        // Sierra Leone 159

        // $client = new \GuzzleHttp\Client;

        // if ($detail->country == 131) {
        //     // Create Customer
        //     $response = $client->request('POST', 'http://104.131.174.54:7171/api/v1.0/customers', [
        //         'headers' => [
        //             'api-key' => 'oksSJXwSB6dz9j3qYo6qiNv2iQbubyok',
        //             'Content-Type' => 'application/json'
        //         ],
        //         'auth' => [
        //             null,
        //             'oksSJXwSB6dz9j3qYo6qiNv2iQbubyok'
        //         ],
        //         'body' => json_encode([
        //             'email' => $user->email,
        //             'phone' => $user->msisdn,
        //             'first_name' => $user->name,
        //             'other_name' => $user->other_names,
        //             //'dob' => $newdob,
        //             'gender' => strtoupper($detail->gender),
        //             'address' => $detail->address,
        //             'hometown' => $detail->city,
        //             'occupation' => $detail->occupation,
        //             'nationality_id' => $detail->country,
        //         ])
        //     ]);

        //     $customer = json_decode($response->getBody(), true);

        //     $fullname = $user->name . ' ' . $user->other_names;

        //     $myres = $customer['your_response'];

        //     $uid = $myres['username'];

        //     DB::table('users')->where('id', $user->id)->update(['customer_username' => $uid]);

        //     //Create new account for customer
        //     $res = $client->request('POST', 'http://104.131.174.54:7171/api/v1.0/accounts', [
        //         'headers' => [
        //             'api-key' => 'oksSJXwSB6dz9j3qYo6qiNv2iQbubyok',
        //             'Content-Type' => 'application/json'
        //         ],
        //         'auth' => [
        //             null,
        //             'oksSJXwSB6dz9j3qYo6qiNv2iQbubyok'
        //         ],
        //         'body' => json_encode([
        //             'account_category_id' => 2,
        //             'account_type_id' => 1,
        //             'customer_uid' => $uid,
        //             'name' => strtoupper($fullname),
        //         ])
        //     ]);

        //     $newcus = json_decode($res->getBody(), true);
        //     $newres = $newcus['your_response'];
        //     $account = $newres['account_number'];

        //     DB::table('users')->where('id', $user->id)->update(['account_no' => $account]);
        // }
        $trainer = DB::table('group_trainers')
                   ->join('users', 'users.id', '=', 'group_trainers.user_id')
                   ->where('group_id', '=', $group->id)
                   ->select('users.name', 'users.other_names')
                   ->first();
        // $trainer_name = $trainer->name . ' '. $trainer->other_names;

        // Data to send to mail
        $maildata = array(
            'name' => $user->name,
            'password' => $password,
            'email' => $user->email,
            'group' => $request->group_name,
            'trainer' => $trainer
            //'customer_username' => $uid,
            //'account_number' => $account,
        );

        // Send Mail Here
        Mail::to($user->email)->send(new GroupMemberCreated($maildata));

        // Flash and redirect
        Session::flash('success', $user->name . ' has been added to' . ' ' . $request->group_name);
        return redirect()->route('group.viewmember', $member->id);
    }

    public function importMembers(Request $request, MemberImport $memberImport)
    {
        $request->validate([
            'file' =>'required',
        ]);

        $file = $request->file;

        // Read File Contents
        $path = $file->getRealPath();
        $rows = array_map('str_getcsv', file($path));
        $total = count($rows);

        $header = array_shift($rows);

        if (!$memberImport->checkImportData($rows, $header)) {
            $request->session()->flash('error-rows', $memberImport->getErrorRows());
            return response()->json([
                'uploaderror' => 'Error in upload. Correct and re-upload',
                'errors' => Session::get('error-rows'),
            ]);
        }

        $memberImport->createMembers($header, $rows);

        // return response()->json([
        //     'success' => 'Members successfully imported into your group',
        // ]);
        Session::flash('success', 'Members successfully imported into your group');
        return redirect()->route('groups.index');
    }

    public function generateTemplate(Request $request)
    {
        $user = Auth::user();
        $organization = OrganizationFunctions::userOrganization($user)['organization'];
        /*
        $organization = DB::table('organizations')
                    ->join('organization_details', 'organization_details.org_id', '=', 'organizations.id')
                    ->where('organizations.admin_id', '=', $user->id)
                    ->select('organization_details.*')
                    ->first();
        */
        $thisgroup = Group::where('org_id', $organization->id)->first();

        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=group_template.csv',
            'Expires' => '0',
            'Pragma' => 'public'
        ];

        $mycolumns = [];

        $user_columns = User::all(['name', 'other_names', 'email', 'msisdn', 'account_no'])->toArray();
        $detail_columns = UserDetail::all(['gender', 'marital_status', 'doc_type', 'doc_no', 'dob', 'country', 'residence', 'occupation', 'income', 'city', 'state', 'postal_code', 'address'])->toArray();
        $group_columns = Group::where('org_id', $organization->id)->pluck('group_data');

        $newd = $group_columns[0];

        if (!is_null($newd)) {
            $newd = array_keys($newd);
        }

        array_unshift($user_columns, array_keys($user_columns[0]));
        array_unshift($detail_columns, array_keys($detail_columns[0]));
        $columns = array_values($user_columns[0]);
        $columns2 = array_values($detail_columns[0]);

        if(!is_null($newd)){
            array_push($mycolumns, array_merge($columns, $columns2, array_values($newd)));
        } else {
            array_push($mycolumns, array_merge($columns, $columns2));
        }

        $callback = function() use ($mycolumns)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $mycolumns[0]);
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function viewMember($id)
    {
        $member = GroupMember::findOrFail($id);

        $group = DB::table('groups')
                ->join('group_members', 'group_members.group_id', '=', 'groups.id')
                ->where('groups.id', '=', $member->group_id)
                ->select('groups.name', 'groups.id')
                ->first();

        $user = DB::table('users')
                ->join('group_members', 'group_members.user_id', '=', 'users.id')
                ->where('group_members.id', '=', $member->id)
                ->select('users.*', 'group_members.status as memberstatus', 'group_members.id as memberid')
                ->first();

        $detail = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->where('user_details.user_id', '=', $user->id)
                ->select('user_details.*')
                ->first();

        $gender = implode(DB::table('genders')->where('id', $detail->gender)->pluck('name')->toArray());
        $document = implode(DB::table('documents')->where('id', $detail->doc_type)->pluck('name')->toArray());
        $country = implode(DB::table('countries')->where('id', $detail->country)->pluck('name')->toArray());
        $income = implode(DB::table('income_classes')->where('id', $detail->income)->pluck('name')->toArray());
        $residence = implode(DB::table('resident_types')->where('id', $detail->residence)->pluck('name')->toArray());

        $documents = Document::all();
        $countries = DB::table('countries')->whereIn('id', [25, 93, 101, 131, 159, 177, 186,])->get();
        $incomes = DB::table('income_classes')->get();
        $maritals = DB::table('maritals')->get();
        $genders = DB::table('genders')->get();
        $residents = DB::table('resident_types')->get();

        $data = $member->member_data;

        return view('back/organization/group/member', compact('user', 'group', 'detail', 'gender', 'document', 'country', 'residence', 'income', 'member', 'data', 'documents', 'countries', 'incomes', 'maritals', 'genders', 'residents'));
    }

    public function sendMemberEmail(Request $request)
    {
        $name = $request->member_name;
        $subject = $request->subject;
        $message = $request->message;
        $email = $request->email;

        // Data to send to mail
        $maildata = array(
            'name' => $name,
            'subject' => $subject,
            'message' => $message,
            'email' => $email,
            'sender' => 'Group Administrator',
        );

        // Send Mail Here
        Mail::to($email)->send(new SendMemberEmail($maildata));

        // Flash and redirect
        Session::flash('success', 'An email has been successfully sent to ' . $name);
        return redirect()->back();
    }

    public function cancelMember(Request $request, $id)
    {
        $member = GroupMember::findOrFail($id);

        $name = $request->member_name;
        $email = $request->email;
        $group = $request->group;

        DB::table('group_members')->where('id', $member->id)->update(['status' => false]);

        // Data to send to mail
        $maildata = array(
            'name' => $name,
            'email' => $email,
            'group' => $group,
        );

        // Send Mail Here
        Mail::to($email)->send(new CancelMemberEmail($maildata));

        Session::flash('success', 'Membership cancelled successfully');
        return redirect()->back();
    }

    public function renewMember(Request $request, $id)
    {
        $member = GroupMember::findOrFail($id);

        $name = $request->member_name;
        $email = $request->email;
        $group = $request->group;

        DB::table('group_members')->where('id', $member->id)->update(['status' => true]);

        // Data to send to mail
        $maildata = array(
            'name' => $name,
            'email' => $email,
            'group' => $group,
        );

        // Send Mail Here
        Mail::to($email)->send(new RenewMemberEmail($maildata));

        Session::flash('success', 'Membership renewed successfully');
        return redirect()->back();
    }

    public function deleteMember(Request $request, $id)
    {
        $member = GroupMember::findOrFail($id);

        $groupid = $request->group_id;
        $name = $request->member_name;
        $email = $request->email;
        $group = $request->group;

        DB::table('group_members')->where('id', $member->id)->delete();

        // Data to send to mail
        $maildata = array(
            'name' => $name,
            'email' => $email,
            'group' => $group,
        );

        // Send Mail Here
        Mail::to($email)->send(new DeleteMemberEmail($maildata));

        Session::flash('success', 'Member has been deleted successfully');
        return redirect()->route('orggroup.addmember', $groupid);
    }

    public function generatePassword( $length = 6 )
    {
        $nums = "123456789";
        $password = substr( str_shuffle( $nums ), 0, $length );;
        return $password;
      
    }

    public function downloadCsvTemplate()
    {
        $path = base_path() . '/public/templates/grouptemplate.csv';

        return response()->download($path);
    }

    public function groupSettings($id)
    {
        $user = Auth::user();
        $group = Group::findOrFail($id);
        $currencies = Currency::all();
        
        $currency =  DB::table('organizations')
        ->join('organization_details', 'org_id', '=', 'organizations.id')
        ->join('currencies', 'country_id', '=', 'organization_details.country')
        ->where('organizations.admin_id', '=', $user->id)
        ->select('currencies.prefix')
        ->first();
        $users = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->join('organizations', 'organizations.id', '=', 'user_details.org_id')
                ->select('users.*')
                ->where(array("organizations.admin_id" => $user->id))
                ->where('deleted_at', NULL)
                ->get();

        $group_contribution_settings = GroupContributionSetting::where('group_id', $id)->first();
        $frequency = $group_contribution_settings == null ? "" : $group_contribution_settings->frequency;

        return view('back/organization/group/group-settings', compact('group', 'user', 'currencies', 'users', 'currency', 'group_contribution_settings', 'frequency'));
    }

    public function storeGroupData(Request $request)
    {
        $group = Group::where('id', $request->group)->first();

        $key = $request->field;
        $value = $request->datatype;

        $data = $group->group_data;

        $data[$key] = $value;

        $group->group_data = $data;

        $group->save();

        Session::flash('success', $key . ' is now a requirement for ' . $group->name);
        return redirect()->back();
    }

    public function updateMember(Request $request)
    {
        $user = $request->user_id;

        $myuser = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->where('user_details.user_id', '=', $user)
                ->select('users.*')
                ->first();

        $request->validate([
            'name' => 'required|string|max:255',
            'other_names' => 'required|string|max:255',
            'email' => [
                'required','string', 'email','max:255',
                Rule::unique('users')->ignore($myuser->id),
            ],
            'msisdn' => [
                'required',
                Rule::unique('users')->ignore($myuser->id),
            ],
            'city' => 'required',
            'address' => 'required',
            'occupation' => 'required',
        ]);

        DB::table('users')->where('id', $myuser->id)->update([
            'name' => $request->name,
            'other_names' => $request->other_names,
            'email' => $request->email,
            'msisdn' => $request->msisdn,
        ]);

        $oldId = $myuser->identification_document;
        if ($request->hasFile('identification_document')) {
            $file = request('identification_document');
            if (file_exists(public_path('documents/ids/' . $oldId))) {
                if (! is_null($oldId)) {
                    unlink(public_path('documents/ids/' . $oldId));
                }
            }
            $filename = strtoupper(Str::random(5)) . '.' . $file->getClientOriginalExtension();
            $myuser->identification_document = $filename;
            DB::table('users')->where('id', $myuser->id)->update([
                'identification_document' => $filename,
            ]);
            $request->identification_document->move(
                base_path() . '/public/documents/ids', $filename
            );
        }

        DB::table('user_details')->where('user_id', $myuser->id)->update([
            'occupation' => $request->occupation,
            'income' => $request->income,
            'city' => $request->city,
            'address' => $request->address,
        ]);

        Session::flash('success', 'Member details have been updated successfully');
        return redirect()->back();
    }

    public function getGroupMessage(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $user = $request->user();

        $members = DB::table('users')
                  ->join('group_members', 'group_members.user_id', '=', 'users.id')
                  ->join('groups', 'groups.id', '=', 'group_members.group_id')
                  ->where('groups.id', '=', $group->id)
                  ->select('users.*')
                  ->get();

        return view('back/organization/group/messaging', compact('group', 'members', 'user'));
    }

    public function postGroupMessage(Request $request)
    {
        $data = $request->all();

        foreach($data['recipients'] as $recipient)
        {
            $user = DB::table('users')->where('id', $recipient)->first();
            $email = $user->email;

            $maildata = [
                'name' => $user->name . ' ' . $user->other_names,
                'email' => $email,
                'subject' => $data['subject'],
                'message' => $data['message'],
                'group' => $data['group']
            ];

            Mail::to($email)->send(new GroupBulkEmail($maildata));
        }

        Session::flash('success', 'Email sent successfully');
        return redirect()->back();
    }

    public function groupRequests(Requests $request)
    {
        return view('back/organization/group/requests');
    }

    public function activateGroup(Request $request, $id)
    {
        $group = Group::find($id);

        $group->status = true;
        $group->save();

        Session::flash('success', 'Group activated successfully');
        return redirect()->back();
    }

    public function deactivateGroup(Request $request, $id)
    {
        $group = Group::find($id);

        $group->status = false;
        $group->save();

        Session::flash('success', 'Group deactivated successfully');
        return redirect()->back();
    }

    public function sendEmailGroups(Request $request)
    {
        $coordinatorname = $request->coordinatorname;
        $subject = $request->subject;
        $message = $request->message;
        $coordinatoremail = $request->coordinatoremail;
        $trainername = $request->trainername;
        $traineremail = $request->traineremail;

        if($request->recipient == 1) {
            // Data to send to mail
            $maildata = array(
                'name' => $coordinatorname,
                'subject' => $subject,
                'message' => $message,
                'email' => $coordinatoremail,
                'sender' => 'Association Administrator',
            );

            Mail::to($coordinatoremail)->send(new SendMemberEmail($maildata));
        } else if($request->recipient == 0) {
            $members = DB::table('users')
                     ->join('group_members', 'group_members.user_id', '=', 'users.id')
                     ->where('group_members.group_id', '=', $request->group)
                     ->select('users.name', 'users.email')
                     ->get();

            if(count($members) > 0) {
                foreach($members as $member) {
                    $maildata = array(
                        'name' => $member->name,
                        'subject' => $subject,
                        'message' => $message,
                        'email' => $member->email,
                        'sender' => 'Association Administrator',
                    );

                    Mail::to($member->email)->send(new SendMemberEmail($maildata));
                }
            }
        } else if($request->recipient == 2) {
            $maildata = array(
                'name' => $trainername,
                'subject' => $subject,
                'message' => $message,
                'email' => $traineremail,
                'sender' => 'Association Administrator',
            );

            Mail::to($traineremail)->send(new SendMemberEmail($maildata));
        }

        // Flash and redirect
        Session::flash('success', 'Group email has been successfully sent');
        return redirect()->back();
    }

    public function assignTrainer(Request $request)
    {
        $data = $request->all();
        $group = Group::find($request->group);

        $request->validate([
            'trainer' => 'required',
            'training_doc' => 'nullable',
            'message' => 'nullable'
        ]);

        $group->trainer_id = $request->trainer;
        $group->save();

        if ($request->hasFile('training_doc')) {
            $file = request('training_doc');
            $filename = strtoupper(Str::random(5)) . '.' . $file->getClientOriginalExtension();
            DB::table('group_trainers')->insert(['group_id' => $group->id, 'user_id' => $request->trainer, 'message' => $request->message, 'training_doc' => $filename, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()]);
            $data['training_doc']->move(
                base_path() . '/public/documents/trainers', $filename
            );
        } else {
            DB::table('group_trainers')->insert(['group_id' => $group->id, 'user_id' => $request->trainer, 'message' => $request->message, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()]);
        }

        $user = User::where('id', $request->trainer)->first();

        //find group owner
        $groupowner =  User::where('id', $group->user_id)->first();

        // Notify Trainer and Group Owner
        $user->notify(new AssignTrainerToGroup($group));
        $groupowner->notify(new TrainerAssignedToYourGroup($user));


        Session::flash('success', 'Trainer added successfully');
        return redirect()->back();
    }

    public function changeTrainer(Request $request)
    {
        $group = Group::find($request->group);

        $request->validate([
            'trainer' => 'required',
            'training_doc' => 'nullable',
            'message' => 'nullable'
        ]);

        if($group->trainer_id == $request->trainer) {
            Session::flash('error', 'The trainer you have selected is already assigned to this group');
            return redirect()->back();
        }

        $group->trainer = $request->trainer;
        $group->save();

        if ($request->hasFile('training_doc')) {
            $file = request('training_doc');
            $filename = strtoupper(Str::random(5)) . '.' . $file->getClientOriginalExtension();
            DB::table('group_trainers')->where('group_id', $group->id)->update(['user_id' => $request->trainer, 'message' => $request->message, 'training_doc' => $filename, 'updated_at' => \Carbon\Carbon::now()]);
            $data['training_doc']->move(
                base_path() . '/public/documents/trainers', $filename
            );
        } else {
            DB::table('group_trainers')->where('group_id', $group->id)->update(['user_id' => $request->trainer, 'message' => $request->message, 'updated_at' => \Carbon\Carbon::now()]);
        }

        Session::flash('success', 'Trainer has been changed successfully');
        return redirect()->back();
    }

    public function contributionSettings(Request $request)
    {
        $group = Group::where('id', $request->group)->first();

        $group_id = $request->group;
        $amount = $request->amount;
        $currency = $request->currency;
        $frequency = $request->frequency;

        $group_settings = DB::table('group_contributions_settings')
                        ->where('group_id', $group_id)
                        ->first();
        // dd($group_settings);

        if ($group_settings != null) {

            DB::table('group_contributions_settings')
                ->where('group_id', $group_id)
                ->update(['amount' => $amount,
                          'currency' => $currency,
                          'frequency' => $frequency
                        ]);
        }
        else {
            $settings = new GroupContributionSetting();
            $settings->group_id = $group_id;
            $settings->amount = $amount;
            $settings->currency = $currency;
            $settings->frequency = $frequency;
            $settings->save();
        }

        Session::flash('success', 'Your contribution settings for ' . $group->name . ' has been saved.');
        return redirect()->back();
        // dd($currency);
    }

    public function setGroupAdmin(Request $request)
    {
        $group_id = $request->group_id;
        $is_member = DB::table('group_members')
                   ->where('group_id', $group_id)
                   ->where('user_id', '=', $request->group_admin)
                   ->first();

        if($is_member == null){
            $newMbr = new GroupMember();
            $newMbr->group_id = $group_id;
            $newMbr->user_id = $request->group_admin;
            $newMbr->is_admin = 1;
            $newMbr->status = true;
            $newMbr->save();

            Session::flash('success', 'Group Admin has been successfully added.');
            return redirect()->back();
        }
        elseif($is_member != null && $is_member->is_admin == 0 ){
            DB::table('group_members')
                ->where('group_id', $group_id)
                ->where('user_id', '=', $request->group_admin)
                ->update([
                    'is_admin' => 1
                        ]);
            Session::flash('success', 'Group Admin has been successfully added.');
            return redirect()->back();
        }
        else{

            Session::flash('warning', 'Selected Member is already an admin');
            return redirect()->back();
        }

        // dd($is_member);
    }

}
