<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use App\Models\General\Role;
use App\Models\General\Group;
use App\Models\General\GroupWallet;
use App\Models\General\Wallet;
use App\Models\General\GroupMember;
use App\Models\General\Transaction;
use App\User;
use Illuminate\Support\Str;
use App\Notifications\NewGroupToAssociationRequest;
use App\Mail\NotifyGroupAdmin;
use Illuminate\Support\Facades\Mail;
use App\Models\Organization\Organization;
use App\Models\General\UserDetail;
use App\Mail\GroupMemberCreated;

use App\Helpers\CommonFunctions;

class GroupController extends Controller
{
    public function getGroupRegistrationData(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

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

        return response()->json([
            'success' => true,
            'message' => 'Group creation data retrieved successfully',
            'data' => [
                'user' => $user,
                'associations' => $associations,
                'region_detail' => $region_detail,
                'regions_arr' => $regions_arr,
                'level_ones' => $level_ones
            ]
        ]);
    }

    public function getGroupTransactions(Request $request, $id)
    {
        $user = User::where('id', $request->user()->id)->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $group = Group::find($id);

        if($group->status == false) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot perform this operation till your group is activated'
            ]);
        }

        if($group) {
            // check whether user has membership
            $membership = DB::table('group_members')->where([
                ['group_id', '=', $group->id],
                ['user_id', '=', $user->id]
            ])->count();

            if ($membership > 0) {
                $all_transactions = Transaction::with('type')->where('group_id', $group->id)->orderBy('id', 'desc')->get();
                $member_transactions = Transaction::with('type')->where([
                    ['user_id', '=', $user->id],
                    ['group_id', '=', $group->id]
                ])->get();

                return response()->json([
                    'success' => true,
                    'message' => 'Transactions retrieved successfully',
                    'data' => [
                        'all_transactions' => $all_transactions,
                        'member_transactions' => $member_transactions
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not a member of this group',
                ]);
            }
        } 
        return response()->json([
            'success' => false,
            'message' => 'Group does not exist'
        ]);
    }

    public function createGroup(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $validator = \Validator::make($request->all(), [
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

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ]);
        }

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
        $group->association_id = $request->association;
        if($request->association != null || $request->association != "") {
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

        $this->saveGroupSettings($user, $group->id);

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

        if($request->association != null || $request->association != "") {
            $association = Organization::where('id', $request->association)->first();

            // update detail in user detail to reflect being associated with association
            DB::table('user_details')->where('user_id', $user->id)->update([
                'org_id' => $association->id
            ]);
        }

        // Add group admin role and delete old ones
        DB::table('role_user')->where('user_id',$request->user_id)->delete();
        //check if user has group-admin role
        if(!$user->hasRole('group-admin')) {
            $user->attachRole(Role::where('name', 'group-admin')->first());
        }

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

        return response()->json([
            'success' => true,
            'message' => 'Group has been created successfully'
        ]);
    }

    public function getUserGroups(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $groups = CommonFunctions::getUserGroups($user);

        /*
        $groups = DB::table('groups')
                      ->join('group_members', 'group_members.group_id', '=', 'groups.id')
                      ->leftjoin('organizations', 'organizations.id', '=', 'groups.association_id')
                      ->leftjoin('organization_details', 'organization_details.org_id', '=', 'organizations.id')
                      ->where('group_members.user_id', '=', $user->id)
                      ->select('groups.*', 'group_members.status as membershipstatus', 'group_members.is_admin as is_admin', 'organization_details.name as association_name')
                      ->get();
        */

        return response()->json([
            'success' => true,
            'groups' => $groups
        ]);
    }

    public function getSingleUserGroup(Request $request, $id)
    {
        $user = User::where('id', $request->user()->id)->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $group = Group::find($id);

        if($group) {
            if($group->status == false) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot perform this operation till your group is activated'
                ], 406);
            }
            else {
                // get group here
                $transactions = Transaction::with('type')->where('group_id', $group->id)->orderBy('id', 'desc')->get();
                $total_members = GroupMember::where('group_id', $group->id)->count();
                $wallet = DB::table('group_wallets')->where('group_id', $group->id)->first();
                $my_contributions = DB::table('group_contributions')->where('user_id', $user->id)->select('amount', 'frequency as no_of_contributions')->first();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Groups retrieved successfully',
                    'data' => [
                        'group' => $group,
                        'balance' => $wallet->balance,
                        'transactions' => $transactions,
                        'total_members' => $total_members, 
                        'my_contributions' => $my_contributions
                    ]
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Group not found'
        ], 404);
    }

    public function makeContribution(Request $request, $id)
    {
        $user = Auth::user();

        $contribution = CommonFunctions::makeContribution($user, $id);

        $response_code = $contribution[0] == true ? 200 : 400;

        return response()->json([                    
            'success' => $contribution[0],
            'message' => $contribution[1]
        ], $response_code);
    }

    public function addMember(Request $request, $id)
    {
        $user = $request->user();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }

        $group = Group::find($id);

        if(!$group) {
            return response()->json([
                'success' => false,
                'message' => 'This group does not exist'
            ]);
        }

        if(! $user->hasRole('group-admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Only group administrators are allowed to add members'
            ]);
        } else if($group->user_id != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You can only add members to groups you own'
            ]);
        }

        if($group->status == false) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot perform this operation till your group is activated'
            ]);
        }

        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'other_names' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'msisdn' => 'required|unique:users,msisdn',
            'password' => 'required|string|min:6|confirmed',
            'gender' => 'required',
            'marital_status' => 'required',
            'doc_type' => 'required',
            'doc_no' => 'required|unique:user_details,doc_no',
            'dob' => 'required',
            'country' => 'required',
            'residence' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'address' => 'required',
            //'income' => 'required',
            //'occupation' => 'required',
            //'identification_document' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = DB::table('users')
                ->join('user_details', 'user_details.id', '=', 'users.id')
                ->where('user_details.user_id', '=', Auth::user()->id)
                ->select('user_details.org_id')
                ->first();

        $password = $this->generatePassword(6);

        $user = new User;
        $user->name = $request->name;
        $user->other_names = $request->other_names;
        $user->email = $request->email;
        $user->msisdn = $this->formatPhoneNumber($request->country, $request->msisdn);
        $user->account_no = $request->account_no;
        $user->password = \Hash::make($password);
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
        $member->group_id = $group->id;
        $member->user_id = $user->id;
        $member->is_admin = false;
        $member->save();

        $detail = new UserDetail();
        $detail->user_id = $user->id;
        if(is_null($data)) {} else {$detail->org_id = $data->org_id;}
        $detail->gender = $request->gender;
        $detail->doc_type = $request->doc_type;
        $detail->dob = date("Y-m-d",strtotime($request->dob));
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

        // Data to send to mail
        $maildata = array(
            'name' => $user->name,
            'password' => $password,
            'email' => $user->email,
            'group' => $group->name,
        );

        // Send Mail Here
        Mail::to($user->email)->send(new GroupMemberCreated($maildata));

        return response()->json([
            'success' => true,
            'message' => 'Group member added successfully'
        ]);

    }

    public function generatePassword( $length = 6 )
    {
        $nums = "123456789";
        $password = substr( str_shuffle( $nums ), 0, $length );
        return $password;
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

    public function saveGroupSettings($user, $id)
    {
        $path = app_path();

        $group = Group::find($id);

        $country = UserDetail::where('user_id', $user->id)->value('country');

        $group_configs = json_encode([]);

        if($country == 177) {
            $group_configs = file_get_contents(app_path(). '/Settings' . '/tz.json');
            $formatted = json_encode(json_decode(preg_replace('!\\r?\\n!', "", $group_configs)));
            $group->group_settings = $formatted;
            $group->save();
            
              // create settings json file
            $fp = fopen(app_path().'/Settings/Groups/' . $id . '.json', 'w+');
            fwrite($fp, $formatted); 
            fclose($fp);
        }

    
      
    }

    public function loadLevelTwo(Request $request, $id)
    {
        $user = $request->user();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }
        
        //dd($id);
        $level_twos = DB::table('level_two')->where('level_one_id', $id)->pluck('name', 'id');
        //dd($subcounties);
        return response()->json([
            'success' => 'true',
            'data' => $level_twos
        ]);
    }

    public function loadLevelThree(Request $request, $id)
    {
        $user = $request->user();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }
        //dd($id);
        $level_threes = DB::table('level_three')->where('level_two_id', $id)->pluck('name', 'id');
        return response()->json([
            'success' => 'true',
            'data' => $level_threes
        ]);
    }
}
