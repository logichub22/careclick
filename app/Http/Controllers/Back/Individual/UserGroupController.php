<?php

namespace App\Http\Controllers\Back\Individual;

use App\User;
use App\Models\General\Role;
use Illuminate\Http\Request;
use App\Models\General\Group;
use App\Models\General\Invite;
use App\Models\General\Wallet;
use App\Models\General\Currency;
use Illuminate\Validation\Rule;
use App\Mail\GroupMemberCreated;
use App\Models\General\Document;
use App\Models\General\GroupContribution;
use App\Models\General\UserDetail;
use Illuminate\Support\Facades\DB;
use App\Models\General\GroupMember;
use App\Models\General\GroupWallet;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use App\Mail\SendMemberEmail;
use App\Mail\CancelMemberEmail;
use App\Mail\RenewMemberEmail;
use App\Mail\DeleteMemberEmail;
use App\Mail\NotifyGroupAdmin;
use App\Mail\GroupBulkEmail;
use Illuminate\Support\Facades\Response;
use App\Services\Group\MemberImport;
use App\Models\General\GroupContributionSetting;
use Validator;
use App\Notifications\NewGroupToAssociationRequest;
use App\Models\Organization\Organization;
use App\Models\General\Transaction;

use App\Helpers\CommonFunctions;

class UserGroupController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('group-status')->except('index', 'create', 'store');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        // check role
        if($user->hasRole('group-admin')) {
            // $groups = DB::table('groups')
            //     ->join('users', 'users.id', '=', 'groups.user_id')
            //     ->select('groups.*')
            //     ->where('users.id', '=', $user->id)
            //     ->get();
            $groups = DB::table('groups')
                    ->join('group_members', 'group_members.group_id', '=', 'groups.id')
                    ->where('group_members.user_id', '=', $user->id)
                    ->select('groups.*', 'group_members.status as memberstatus', 'group_members.is_admin as is_admin')
                    ->get();
        } else {
            $groups = DB::table('groups')
                    ->join('group_members', 'group_members.group_id', '=', 'groups.id')
                    ->where('group_members.user_id', '=', $user->id)
                    ->select('groups.*', 'group_members.status as memberstatus', 'group_members.is_admin as is_admin')
                    ->get();
        }

        // $date = DB::table('groups')
        //            ->join('group_members', 'group_members.group_id', '=', 'groups.id')
        //            ->where('group_members.user_id', '=', $user->id)
        //            ->pluck('group_members.created_at')
        //            ->toArray();

        // $created = implode($date);

        return view('back/individual/group/index', compact('groups', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $detail = $user->detail()->first();
        $country = $detail->country;

        $region_detail = DB::table('administrative_regions')->where('country_id', $country)->first();

        $result = [];

        array_push($result, $region_detail->level_one , $region_detail->level_two, $region_detail->level_three, $region_detail->level_four);

        $regions_arr = array_filter($result);

        $level_ones = DB::table('level_one')->where('country_id', $country)->get();

        // fetch associations that belong to this country;
        $associations = DB::table('organizations')
                            ->join('organization_details', 'organization_details.org_id', '=', 'organizations.id')
                            ->where('organization_details.country', '=', $country)
                            ->whereNotNull('organizations.federation')
                            ->select('organization_details.name', 'organizations.admin_id', 'organizations.id')
                            ->get();

        return view('back/individual/group/create', compact('user', 'associations', 'region_detail', 'regions_arr', 'level_ones'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:groups|max:100',
            'comment' => 'required|max:100',
            'association' => 'nullable',
            'bank' => 'required',
            'bank_name' => 'required_if:bank,1',
            'bank_branch' => 'required_if:bank,1',
            'account_no' => 'required_if:bank,1',
            'level_one' => 'required',
            'level_two' => 'required',
            'level_three' => 'nullable',
            'level_four' => 'nullable',
            'group_certificate' => 'nullable'
        ]);

        $data = $request->all();

        $group = new Group();
        $group->name = $request->name;
        $group->comment = $request->comment;
        $group->user_id = Auth::user()->id;
        $group->bank = $request->bank;
        $group->bank_name = $request->bank_name;
        $group->account_no = $request->account_no;
        $group->level_one = "Arusha";
        $group->level_two = "Mchacha";
        $group->level_three = $request->level_three;
        $group->level_three = $request->level_four;
        $group->association_id = $request->association;
        if($request->association != null) {
            $group->status = false;
        }
        $group->save();

        if ($request->hasFile('group_certificate')) {
            $file = request('group_certificate');
            $filename = strtoupper(Str::random(5)) . '.' . $file->getClientOriginalExtension();
            $group->group_certificate = $filename;
            $group->save();
            $data['group_certificate']->move(
                base_path() . '/public/documents/groups/certificates', $filename
            );
        }

        $user = Auth::user();
        $currency =  DB::table('user_details')
                    ->join('currencies', 'country_id', '=', 'user_details.country')
                    ->where('user_details.user_id', '=', $user->id)
                    ->select('currencies.prefix')
                    ->first();

        $this->saveGroupSettings($group->id);

        $wallet = new GroupWallet;
        $wallet->group_id = $group->id;
        $wallet->balance = 0;
        $wallet->save();

        // Store user in group members table
        $member = new GroupMember();
        $member->group_id = $group->id;
        $member->user_id = $user->id;
        $member->is_admin = true;
        $member->status = true;
        $member->save();

        $settings = new GroupContributionSetting();
        $settings->group_id = $group->id;
        $settings->amount = 0;
        $settings->currency = $currency->prefix;
        $settings->frequency = 'Monthly';
        $settings->save();


        if($request->association != null) {
            $association = Organization::where('id', $request->association)->first();

            // update detail in user detail to reflect being associated with association
            DB::table('user_details')->where('user_id', $user->id)->update([
                'org_id' => $association->id
            ]);
        }

        // Add group admin role and delete old ones
        DB::table('role_user')->where('user_id',$request->user_id)->delete();
        $user->attachRole(Role::where('name', 'group-admin')->first());

        // Send Mail
        $maildata = [
            'name' => $user->name,
            'group' => $group->name,
        ];

        Mail::to($user->email)->send(new NotifyGroupAdmin($maildata));

        if($request->association != null) {
            // Notify association administrator
            $association_admin = User::where('id', $association->admin_id)->first();
            $association_admin->notify(new NewGroupToAssociationRequest($group, $user));
        }

        Session::flash('success', $group->name . ' group has been created successfully');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = Group::findOrFail($id);

        if($group->status == false) {
            Session::flash('error', 'You cannot perform any further operations until your group has been activated');
            return redirect()->back();
        }

        $user = Auth::user();
        $detail = $user->detail()->first();
        $country = $detail->country;
        $currency = $currency =  DB::table('user_details')
                    ->join('currencies', 'country_id', '=', 'user_details.country')
                    ->where('user_details.user_id', '=', $user->id)
                    ->select('currencies.prefix')
                    ->first();
        // dd($currency);

        $region_detail = DB::table('administrative_regions')->where('country_id', $country)->first();

        $result = [];

        $coordinator = User::with('detail')->where('id', $group->user_id)->first();

        $cdetail = $coordinator->detail;

        if(!is_null($group->trainer_id)) {
            $trainingofficer = User::where('id', $group->trainer_id)->first();
        } else {
            $trainingofficer = null;
        }

        array_push($result, $region_detail->level_one , $region_detail->level_two, $region_detail->level_three, $region_detail->level_four);

        $regions_arr = array_filter($result);

        $level_ones = DB::table('level_one')->where('country_id', $country)->get();

        // fetch associations that belong to this country;
        $associations = DB::table('organizations')
                            ->join('organization_details', 'organization_details.org_id', '=', 'organizations.id')
                            ->where('organization_details.country', '=', $country)
                            ->whereNotNull('organizations.federation')
                            ->select('organization_details.name', 'organizations.admin_id', 'organizations.id')
                            ->get();


        $members = DB::table('users')
                ->join('group_members', 'group_members.user_id', '=', 'users.id')
                ->orderBy('created_at','desc')
                ->select('users.*', 'group_members.id as memberid', 'group_members.created_at as membercreated', 'group_members.is_admin as admin')
                ->where(array("group_members.group_id" => $group->id))
                ->get();

        $wallet = GroupWallet::where('group_id', $group->id)->first();

        $transactions = DB::table('transactions')
                      ->join('users', 'users.id', '=', 'transactions.user_id')
                      ->join('transaction_types', 'transaction_types.id', '=', 'transactions.transaction_type_id')
                      ->where(array('transactions.group_id' => $group->id))
                      ->orderBy('id', 'desc')
                      ->select('users.name', 'users.other_names', 'transactions.*', 'transaction_types.name as type')
                      ->get();

        return view('back/individual/group/show', compact('user', 'group', 'members', 'wallet', 'associations', 'region_detail', 'regions_arr', 'level_ones', 'coordinator', 'cdetail', 'trainingofficer', 'transactions', 'currency'));
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

        if($group->status == false) {
            Session::flash('error', 'You cannot perform any further operations until your group has been activated');
            return redirect()->back();
        }

        $this->validate($request, [
            'name' => [
                'required', 'max:100',
                Rule::unique('groups')->ignore($group->id),
            ],
            'association' => 'nullable',
            'comment' => 'required|max:100',
            'bank' => 'required',
            'bank_name' => 'required_if:bank,1',
            'bank_branch' => 'required_if:bank,1',
            'account_no' => 'required_if:bank,1',
            'level_one' => 'nullable',
            'level_two' => 'nullable',
            'level_three' => 'nullable',
            'group_certificate' => 'nullable'
        ]);

        $data = $request->all();

        $group = new Group();
        $group->name = $request->name;
        $group->comment = $request->comment;
        $group->user_id = Auth::user()->id;
        $group->bank = $request->bank;
        $group->bank_name = $request->bank_name;
        $group->account_no = $request->account_no;
        $group->level_one = $request->level_one;
        $group->level_two = "Mchacha";
        $group->level_three = $request->level_three;
        $group->association_id = $request->association;
        if($request->association != null) {
            $group->status = false;
        }
        $group->save();

        if ($request->hasFile('group_certificate')) {
            $file = request('group_certificate');
            $filename = strtoupper(Str::random(5)) . '.' . $file->getClientOriginalExtension();
            $group->group_certificate = $filename;
            $group->save();
            $data['group_certificate']->move(
                base_path() . '/public/documents/groups/certificates', $filename
            );
        }

        Session::flash('success', $group->name . ' has been updated successfully');
        return redirect()->route('user-groups.show', $group->id);
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

    public function getAddMember($id)
    {
        $group = Group::findOrFail($id);

        if($group->status == false) {
            Session::flash('error', 'You cannot perform any further operations until your group has been activated');
            return redirect()->back();
        }

        $user = User::with('detail')->where('id', Auth::user()->id)->first();

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

        $fields = $group->group_data;

        return view('back/individual/group/invite-members', compact('group', 'user', 'members', 'documents', 'countries', 'fields', 'incomes', 'maritals', 'genders', 'residents'));
    }

    public function postAddMember(Request $request)
    {
        $data = DB::table('users')
                ->join('user_details', 'user_details.id', '=', 'users.id')
                ->where('user_details.user_id', '=', Auth::user()->id)
                ->select('user_details.org_id')
                ->first();

        $group = Group::where('id', $request->group_id)->first();

        if($group->status == false) {
            Session::flash('error', 'You cannot perform any further operations until your group has been activated');
            return redirect()->back();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'other_names' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'msisdn' => 'required|unique:users,msisdn',
            'gender' => 'required',
            'doc_type' => 'required',
            'doc_no' => 'required|unique:user_details,doc_no',
            'dob' => 'required|date',
            'country' => 'required',
            'residence' => 'required',
            'city' => 'required',
            //'state' => 'required',
            'postal_code' => 'required',
            'address' => 'required',
            'income' => 'required',
            'occupation' => 'required',
            //'identification_document' => 'required',
        ]);

        $password = $this->generatePassword(6);

        // Store Member Into Users Table;
        $user = new User;
        $user->name = $request->name;
        $user->other_names = $request->other_names;
        $user->email = $request->email;
        $user->msisdn = $this->formatPhoneNumber($request->country, $request->msisdn);
        $user->account_no = $request->account_no;
        $user->password = Hash::make($password);
        $user->status = true;
        $user->verified = true;
        $user->save();

        // Attach Role
        $user->attachRole(Role::where('name','group-member')->first());

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

        // Store in Org Group Members
        $member = new GroupMember;
        $member->group_id = $request->group_id;
        $member->user_id = $user->id;
        $member->save();

        $detail = new UserDetail();
        $detail->user_id = $user->id;
        if(is_null($data)) {} else {$detail->org_id = $data->org_id;}
        $detail->gender = $request->gender;
        $detail->doc_type = $request->doc_type;
        $detail->dob = $request->dob;
        $detail->doc_no = $request->doc_no;
        $detail->country = $request->country;
        $detail->city = $request->city;
        $detail->address = $request->address;
        $detail->income = $request->income;
        $detail->occupation = $request->occupation;
        $detail->residence = $request->residence;
        $detail->marital_status = $request->marital_status;
        $detail->postal_code = $request->postal_code;
        $detail->save();


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

        $client = new \GuzzleHttp\Client;

        if ($detail->country == 131) {
            // Create Customer
            $response = $client->request('POST', 'http://104.131.174.54:7171/api/v1.0/customers', [
                'headers' => [
                    'api-key' => 'oksSJXwSB6dz9j3qYo6qiNv2iQbubyok',
                    'Content-Type' => 'application/json'
                ],
                'auth' => [
                    null,
                    'oksSJXwSB6dz9j3qYo6qiNv2iQbubyok'
                ],
                'body' => json_encode([
                    'email' => $user->email,
                    'phone' => $user->msisdn,
                    'first_name' => $user->name,
                    'other_name' => $user->other_names,
                    //'dob' => $newdob,
                    'gender' => strtoupper($detail->gender),
                    'address' => $detail->address,
                    'hometown' => $detail->city,
                    'occupation' => $detail->occupation,
                    'nationality_id' => $detail->country,
                ])
            ]);

            $customer = json_decode($response->getBody(), true);

            $fullname = $user->name . ' ' . $user->other_names;

            $myres = $customer['your_response'];

            $uid = $myres['username'];

            DB::table('users')->where('id', $user->id)->update(['customer_username' => $uid]);

            //Create new account for customer
            $res = $client->request('POST', 'http://104.131.174.54:7171/api/v1.0/accounts', [
                'headers' => [
                    'api-key' => 'oksSJXwSB6dz9j3qYo6qiNv2iQbubyok',
                    'Content-Type' => 'application/json'
                ],
                'auth' => [
                    null,
                    'oksSJXwSB6dz9j3qYo6qiNv2iQbubyok'
                ],
                'body' => json_encode([
                    'account_category_id' => 2,
                    'account_type_id' => 1,
                    'customer_uid' => $uid,
                    'name' => strtoupper($fullname),
                ])
            ]);

            $newcus = json_decode($res->getBody(), true);
            $newres = $newcus['your_response'];
            $account = $newres['account_number'];

            DB::table('users')->where('id', $user->id)->update(['account_no' => $account]);
        }


        // Data to send to mail
        $maildata = array(
            'name' => $user->name,
            'password' => $password,
            'email' => $user->email,
            'group' => $request->group_name,
            'trainer' => null
        );

        // Send Mail Here
        Mail::to($user->email)->send(new GroupMemberCreated($maildata));

        // Flash and redirect
        Session::flash('success', $user->name . ' has been added to' . ' ' . $request->group_name);
        return redirect()->route('usergroup.viewmember', $member->id);
    }

    public function viewMember($id)
    {
        $member = GroupMember::findOrFail($id);

        $group = DB::table('groups')
                ->join('group_members', 'group_members.group_id', '=', 'groups.id')
                ->where('groups.id', '=', $member->group_id)
                ->select('groups.name', 'groups.id', 'groups.status')
                ->first();

        if($group->status == false) {
            Session::flash('error', 'You cannot perform any further operations until your group has been activated');
            return redirect()->back();
        }

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

        return view('back/individual/group/member', compact('user', 'group', 'detail', 'gender', 'document', 'country', 'residence', 'income', 'member', 'data', 'documents', 'countries', 'incomes', 'maritals', 'genders', 'residents'));
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

    public function sendInvite(Request $request)
    {
        $request->validate([
            'subject' => 'required|max:255',
            'message' => 'required',
        ]);

        $invite = new Invite();
        $invite->subject = $request->subject;
        $invite->message = $request->message;
        $invite->user_id = $request->user_id;
        $invite->group_id = $request->group_id;
        $invite->save();

        // Send Notification

        Session::flash('success', 'An invite has been created successfully for this group');
        return redirect()->back();

    }

    public function groupDetail($id)
    {
        $group = Group::findOrFail($id);

        if($group->status == false) {
            Session::flash('error', 'You cannot perform any further operations until your group has been activated');
            return redirect()->back();
        }

        return view('back/individual/group/group-detail', compact('group'));
    }

    public function groupSettings($id)
    {
        $user = Auth::user();
        $group = Group::findOrFail($id);
        $currencies = Currency::all();

        if($group->status == false) {
            Session::flash('error', 'You cannot perform any further operations until your group has been activated');
            return redirect()->back();
        }

        // // Load settings file
        // $group_configs = file_get_contents(app_path(). '/Settings' . '/Groups' . '/' . $group->id . '.json');

        // $groups_arr = json_decode($group_configs, true);

        // dd($groups_arr);

        return view('back/individual/group/group-settings', compact('user', 'group', 'currencies'));
    }

    public function storeGroupData(Request $request)
    {
        $group = Group::where('id', $request->group)->first();

        if($group->status == false) {
            Session::flash('error', 'You cannot perform any further operations until your group has been activated');
            return redirect()->back();
        }

        $key = $request->field;
        $value = $request->datatype;

        $data = $group->group_data;

        $data[$key] = $value;

        $group->group_data = $data;

        $group->save();

        Session::flash('success', $key . ' is now a requirement for ' . $group->name);
        return redirect()->back();
    }

    public function generatePassword( $length = 6 )
    {
        $nums = "123456789";
        $password = substr( str_shuffle( $nums ), 0, $length );
        return $password;
    }

    public function updateMember(Request $request)
    {
        $user = $request->user_id;

        $myuser = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->where('user_details.user_id', '=', $user)
                ->select('users.*', 'user_details.country')
                ->first();

        // dd($myuser);

        // $model = User::where('id', $user)->first();

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

        //dd($request);

        DB::table('users')->where('id', $myuser->id)->update([
            'name' => $request->name,
            'other_names' => $request->other_names,
            'email' => $request->email,
            'msisdn' => $this->formatPhoneNumber($myuser->country, $request->msisdn),
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

    public function addMeeting()
    {
        $user = Auth::user();

        $groups = Group::where('user_id', $user->id)->get();

        return view('back/individual/group/meeting', compact('groups'));
    }

    public function generateTemplate(Request $request)
    {
        $user = Auth::user();

        $thisgroup = Group::where('user_id', $user->id)->first();
        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename='. $thisgroup->name . '.csv',
            'Expires' => '0',
            'Pragma' => 'public'
        ];

        $mycolumns = [];

        $user_columns = User::all(['name', 'other_names', 'email', 'msisdn', 'account_no'])->toArray();
        $detail_columns = UserDetail::all(['gender', 'marital_status', 'doc_type', 'doc_no', 'dob', 'country', 'residence', 'occupation', 'income', 'city', 'state', 'postal_code', 'address'])->toArray();
        //$group_columns = Group::all(['group_data'])->toArray();
        $group_columns = Group::where('user_id', $user->id)->pluck('group_data');

        $newd = $group_columns[0];

        //dd($newd);

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

    public function importMembers(Request $request, MemberImport $memberImport)
    {
        $validator = Validator::make($request->all(), [
            'file' =>'required',
        ]);

        if($validator->fails()) {
            return response()->json(['validationerrors' => $validator->errors()]);
        }

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

        return response()->json([
            'success' => 'Members successfully imported into your group',
        ]);
    }

    public function getGroupMessage(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        if($group->status == false) {
            Session::flash('error', 'You cannot perform any further operations until your group has been activated');
            return redirect()->back();
        }

        $user = $request->user();

        $members = DB::table('users')
                  ->join('group_members', 'group_members.user_id', '=', 'users.id')
                  ->join('groups', 'groups.id', '=', 'group_members.group_id')
                  ->where('groups.id', '=', $group->id)
                  ->select('users.*')
                  ->get();

        return view('back/individual/group/messaging', compact('group', 'members'));
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

    public function saveGroupSettings($id)
    {
        $path = app_path();

        // get country of user
        $user = Auth::user();

        $group = Group::find($id);

        $country = UserDetail::where('user_id', $user->id)->value('country');

        if($country == 177) {
            $group_configs = file_get_contents(app_path(). '/Settings' . '/tz.json');
        }else{
            $group_configs = file_get_contents(app_path(). '/Settings' . '/tz.json');
        }

        $formatted = json_encode(json_decode(preg_replace('!\\r?\\n!', "", $group_configs)));
        $group->group_settings = $formatted;
        $group->save();

        // create settings json file
        $fp = fopen(app_path().'/Settings/Groups/' . $id . '.json', 'w+');
        fwrite($fp, $formatted);
        fclose($fp);
    }

    public function formatPhoneNumber($country, $phone)
    {
        if($country == 131) {
           $formatted = preg_replace('/^0/','234',$phone);
        } else if($country == 93) {
            $formatted = preg_replace('/^0/','254',$phone);
        } else if($country == 101) {
            $formatted = preg_replace('/^0/','231',$phone);
        } else if($country == 186) {
            $formatted = preg_replace('/^0/','256',$phone);
        } else if($country == 177) {
            $formatted = preg_replace('/^0/','255',$phone);
        } else if($country == 25) {
            $formatted = preg_replace('/^0/','267',$phone);
        } else if($country == 159) {
            $formatted = preg_replace('/^0/','232',$phone);
        } else {

        }

        return $formatted;
    }

    public function myContributions()
    {
        $user = Auth::user();

        // check role
        // if($user->hasRole('group-admin')) {
        //     $groups = DB::table('groups')
        //         ->join('users', 'users.id', '=', 'groups.user_id')
        //         ->select('groups.*')
        //         ->where('groups.user_id', '=', $user->id)
        //         ->get();
        // } else {
        //     $groups = DB::table('groups')
        //             ->join('group_members', 'group_members.group_id', '=', 'groups.id')
        //             ->where('group_members.user_id', '=', $user->id)
        //             ->select('groups.*', 'group_members.status as memberstatus', 'group_members.is_admin as is_admin')
        //             ->get();
        // }

        $groups = DB::table('groups')
                ->join('group_members', 'group_members.group_id', '=', 'groups.id')
                ->join('group_contributions', 'group_contributions.group_id', '=', 'groups.id')
                ->join('users', 'users.id', '=', 'group_members.user_id')
                ->where('group_members.user_id', '=', $user->id)
                ->select('groups.*', 'group_members.is_admin as is_admin', 'group_contributions.status as status', 'group_contributions.amount as amount', 'group_contributions.created_at as date_of_contribution', 'users.name as firstname', 'users.other_names as lastname')
                ->get();

        $organization = DB::table('organization_details')
                    ->join('user_details', 'user_details.org_id', '=', 'organization_details.org_id')
                    ->where('user_details.user_id', '=', $user->id)
                    ->select('organization_details.*', 'organization_details.name as org_name')
                    ->first();
        // dd($groups);


        return view('back/individual/group/contributions', compact('groups', 'user', 'organization'));
    }
    public function makeContributions()
    {
        $user = Auth::user();

        $groups = DB::table('groups')
                ->join('group_members', 'group_members.group_id', '=', 'groups.id')
                ->where('group_members.user_id', '=', $user->id)
                ->select('groups.*', 'group_members.status as memberstatus', 'group_members.is_admin as is_admin')
                ->get();

        // $group_admin = DB::table('groups')
        //             ->join('users', 'users.id', '=', 'groups.user_id')
        //             ->select('groups.*')
        //             ->where('groups.user_id', '=', $user->id)
        //             ->get();

        $organization = DB::table('organization_details')
                    ->join('user_details', 'user_details.org_id', '=', 'organization_details.org_id')
                    ->where('user_details.user_id', '=', $user->id)
                    ->select('organization_details.*', 'organization_details.name as org_name')
                    ->first();

        // dd($organization);

        return view('back/individual/group/contribute', compact('user', 'groups', 'organization'));
    }

    public function getContributionSettings($id)
    {
        $group_contribution_settings = DB::table('group_contributions_settings')
                    ->select('*')
                    ->where('group_id', '=', $id)
                    ->first();
        
        return $group_contribution_settings ? json_encode($group_contribution_settings): json_encode([]);

        /*
        $frequency = DB::table('group_contributions_settings')
                     ->select('frequency')
                     ->where('group_id', '=', $id)
                     ->first();

        $amount = DB::table('group_contributions_settings')
                     ->select('amount')
                     ->where('group_id', '=', $id)
                     ->first();

        $currency = DB::table('group_contributions_settings')
                     ->select('currency')
                     ->where('group_id', '=', $id)
                     ->first();
        */


        // $settingsData = compact('frequency', 'amount', 'currency');

        // return json_encode($settingsData);
    }

    public function contribute(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
        ]);

        $user = Auth::user();
        $org_id = $request->org_id;
        $group_id = $request->group;

        if(Hash::check($request->password, $user->password)) {
            $user = Auth::user();
            $contribution = CommonFunctions::makeContribution($user, $org_id, $group_id);
            
            $response_type = $contribution[0] == true ? 'success' : 'error';
            Session::flash($response_type, $contribution[1]);
        }
        else {
            Session::flash('error', 'The password you entered is incorrect!');
        }
        return redirect()->route('usergroup.make-contributions');

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
}
