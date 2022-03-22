<?php

namespace App\Http\Controllers\Back\Federation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use App\Models\General\Group;
use DB;
use Auth;
use App\Models\Organization\Organization;
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
        $groups = DB::table('groups')
                ->join('organizations', 'organizations.id', '=', 'groups.org_id')
                ->select('groups.*')
                ->where('organizations.admin_id', '=', $user->id)
                ->get();

        $membergroups = DB::table('groups')
                      ->join('group_members', 'group_members.group_id', '=', 'groups.id')
                      ->where('group_members.user_id', '=', $user->id)
                      ->select('groups.*', 'group_members.status as memberstatus')
                      ->get();

        return view('back/federation/group/index', compact('groups', 'membergroups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organization = Organization::where('admin_id', Auth::user()->id)->first();
        return view('back/federation/group/create', compact('organization'));
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
            'account_no' => 'nullable|numeric|unique:groups,account_no',
        ]);

        $group = new Group();
        $group->name = $request->name;
        $group->comment = $request->comment;
        $group->org_id = $request->org_id;
        $group->account_no = $request->account_no;
        $group->save();

        $wallet = new GroupWallet;
        $wallet->group_id = $group->id;
        $wallet->balance = 0;
        $wallet->save();

        // Add group admin role
        $user = Auth::user();
        //$user->attachRole(Role::where('name', 'group-admin')->first());

        Session::flash('success', $group->name . ' group has been created successfully');
        return redirect()->route('groups.show', $group->id);
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
        $members = DB::table('users')
                ->join('group_members', 'group_members.user_id', '=', 'users.id')
                ->orderBy('created_at','desc')
                ->select('users.*')
                ->where(array("group_members.group_id" => $group->id))
                ->take(5)
                ->get();

        $wallet = GroupWallet::where('group_id', $group->id)->first();

        return view('back/federation/group/show', compact('group', 'members', 'wallet'));
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

        return view('back/federation/group/invite', compact('group'));
    }

    public function getAddMember($id)
    {
        $group = Group::findOrFail($id);

        $user = Auth::user();

        $organization = DB::table('organizations')
                      ->join('organization_details', 'organization_details.org_id', '=', 'organizations.id')
                      ->where('organizations.admin_id', '=' , $user->id)
                      ->select('organization_details.*')
                      ->first();

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
        
        return view('back/federation/group/add-member', compact('group', 'organization', 'members', 'documents', 'countries', 'fields', 'incomes', 'maritals', 'genders', 'residents'));
    }

    public function postAddMember(Request $request)
    {
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
        $user->msisdn = $request->msisdn;
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
        
        return response()->json([
            'success' => 'Members successfully imported into your group',
        ]);
    }

    public function generateTemplate(Request $request)
    {
        $user = Auth::user();

        $organization = DB::table('organizations')
                    ->join('organization_details', 'organization_details.org_id', '=', 'organizations.id')
                    ->where('organizations.admin_id', '=', $user->id)
                    ->select('organization_details.*')
                    ->first();

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

        return view('back/federation/group/member', compact('user', 'group', 'detail', 'gender', 'document', 'country', 'residence', 'income', 'member', 'data', 'documents', 'countries', 'incomes', 'maritals', 'genders', 'residents'));
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
        $password = substr( str_shuffle( $nums ), 0, $length );
        return $password;
    }

    public function downloadCsvTemplate()
    {
        $path = base_path() . '/public/templates/grouptemplate.csv';

        return response()->download($path);
    }

    public function groupSettings($id)
    {
        $group = Group::findOrFail($id);

        return view('back/federation/group/group-settings', compact('group'));
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

        return view('back/federation/group/messaging', compact('group', 'members'));
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
}
