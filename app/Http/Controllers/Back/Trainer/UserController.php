<?php

namespace App\Http\Controllers\Back\Trainer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\General\UserDetail;
use App\Models\General\Role;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use App\Http\Requests\UserRequest;
use App\Models\General\Wallet;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedEmail;
use App\Services\User\UserImport;
use Image;
use App\Models\General\Document;
use App\Mail\UserActivatedEmail;
use App\Mail\UserDeactivatedEmail;
use App\Models\General\GroupMember;
use Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admin = Auth::user()->id;
        $users = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->join('organizations', 'organizations.id', '=', 'user_details.org_id')
                ->select('users.*')
                ->where(array("organizations.admin_id" => $admin))
                ->get();
    
        return view('back/federation/user/index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        $organization = DB::table('organizations')
                      ->join('organization_details', 'organization_details.org_id', '=', 'organizations.id')
                      ->where('organizations.admin_id', '=' , $user->id)
                      ->select('organization_details.*')
                      ->first();

        $documents = Document::all();
        $countries = DB::table('countries')->whereIn('id', [25, 93, 101, 131, 159, 177, 186,])->get();
        $incomes = DB::table('income_classes')->get();
        $maritals = DB::table('maritals')->get();
        $genders = DB::table('genders')->get();
        $residents = DB::table('resident_types')->get();
        
        return view('back/federation/user/create', compact('organization', 'documents', 'countries', 'incomes', 'maritals', 'genders', 'residents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $password = $this->generatePassword(6);

        // Store into DB;
        $user = new User;
        $user->name = $request->name;
        $user->other_names = $request->other_names;
        $user->email = $request->email;
        $user->msisdn = $request->msisdn;
        $user->password = Hash::make($password);
        $user->status = true;
        $user->verified = true;
        //$user->identification_document = $request->identification_document;
        $user->save();

        if ($request->hasFile('identification_document')) {
            $file = request('identification_document');
            $filename = strtoupper(Str::random(5)) . '.' . $file->getClientOriginalExtension();
            $user->identification_document = $filename;
            $user->save();
            $request->identification_document->move(
                base_path() . '/public/documents/ids', $filename
            );
        }

        // Attach Role
        $user->attachRole(Role::where('name','organization-user')->first());

        // Store into user detail
        $orguser = new UserDetail;
        $orguser->user_id = $user->id;
        $orguser->org_id = $request->org_id;
        $orguser->gender = $request->gender;
        $orguser->doc_type = $request->doc_type;
        $orguser->doc_no = $request->doc_no;
        $orguser->country = $request->country;
        $orguser->city = $request->city;
        $orguser->address = $request->address;
        $orguser->income = $request->income;
        $orguser->occupation = $request->occupation;
        $orguser->marital_status = $request->marital_status;
        $orguser->residence = $request->residence;
        $orguser->dob = $request->dob;
        $orguser->save();

        // Create Wallet Entry
        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->balance = 0;
        $wallet->save();

        $client = new \GuzzleHttp\Client;

        $organization = DB::table('organizations')->where('admin_id', Auth::user()->id)->first();

        $group = DB::table('groups')->where('org_id', $organization->id)->first();

        // Store in default organization group
        $newMbr = new GroupMember();
        $newMbr->group_id = $group->id;
        $newMbr->user_id = $user->id;
        $newMbr->status = true;
        $newMbr->save();

        // Create Customer
        if ($orguser->country == 131) {
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
                    'gender' => strtoupper($orguser->gender),
                    'address' => $orguser->address,
                    'hometown' => $orguser->city,
                    'occupation' => $orguser->occupation,
                    'nationality_id' => $orguser->country,
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
            'organization' => $request->organization,
            'email' => $user->email,
        );

        // Send Mail Here
        Mail::to($user->email)->send(new UserCreatedEmail($maildata));

        // Flash and redirect
        Session::flash('success', $user->name . ' has been added to your organization');
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        $detail = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->where('user_details.user_id', '=', $user->id)
                ->select('user_details.*')
                ->first();

        $countries = DB::table('countries')->whereIn('id', [25, 93, 101, 131, 159, 177, 186,])->get();

        $owned = DB::table('groups')->where('user_id', $user->id)->get();

        $membergroups = DB::table('groups')
                      ->join('group_members', 'group_members.group_id', '=', 'groups.id')
                      ->where('group_members.user_id', '=', $user->id)
                      ->select('groups.*', 'group_members.status as memberstatus', 'group_members.created_at')
                      ->get();

        $date = DB::table('groups')
                   ->join('group_members', 'group_members.group_id', '=', 'groups.id')
                   ->where('group_members.user_id', '=', $user->id)
                   ->pluck('group_members.created_at')
                   ->toArray();
    
        $created = implode($date);

        $transactions = DB::table('transactions')->where('user_id', $user->id)->get();

        //dd($detail);
        $gender = implode(DB::table('genders')->where('id', $detail->gender)->pluck('name')->toArray());
        $document = implode(DB::table('documents')->where('id', $detail->doc_type)->pluck('name')->toArray());
        $country = implode(DB::table('countries')->where('id', $detail->country)->pluck('name')->toArray());
        $income = implode(DB::table('income_classes')->where('id', $detail->income)->pluck('name')->toArray());
        $residence = implode(DB::table('resident_types')->where('id', $detail->residence)->pluck('name')->toArray());

        $documents = DB::table('documents')->get();

        $incomes = DB::table('income_classes')->get();
        $maritals = DB::table('maritals')->get();
        $genders = DB::table('genders')->get();
        $residents = DB::table('resident_types')->get();

        return view('back/federation/user/show', compact('user', 'detail', 'country', 'document', 'countries', 'documents', 'owned', 'transactions', 'membergroups','incomes', 'maritals', 'genders', 'residents', 'gender', 'income', 'residence'));
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
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'other_names' => 'required',
            'email' => [
                'required', 
                Rule::unique('users')->ignore($user->id),
            ],
            'msisdn' => [
                'required', 
                Rule::unique('users')->ignore($user->id),
            ],
            'city' => 'required',
            'income' => 'required|numeric',
            'address' => 'required',
            'occupation' => 'required',
        ]);

        //dd($request->file('identification_document'));

        $user->name = $request->name;
        $user->other_names = $request->other_names;
        $user->email = $request->email;
        $user->msisdn = $request->msisdn;
        $user->customer_username = $user->customer_username;
        $user->save();

        if ($request->hasFile('identification_document')) {
            $file = request('identification_document');
            $filename = strtoupper(Str::random(5)) . '.' . $file->getClientOriginalExtension();
            $user->identification_document = $filename;
            $user->save();
            $request->identification_document->move(
                base_path() . '/public/documents/ids', $filename
            );
        }

        $data = DB::table('user_details')->where('user_id', $user->id)->first();

        DB::table('user_details')->where('user_id', $user->id)->update([
            'user_id' => $user->id,
            'org_id' => $data->org_id,
            'gender' => $data->gender,
            'doc_type' => $data->doc_type,
            'doc_no' => $data->doc_no,
            'country' => $data->country,
            'occupation' => $request->occupation,
            'income' => $request->income,
            'city' => $request->city,
            'address' => $request->address,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);

        Session::flash('success', 'User updated successfully');
        return redirect()->back();
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

    public function activate(Request $request, $id)
    {
        $user = $request->user_id;
        $email = $request->email;
        $name = $request->name;

        DB::table('users')->where('id', $user)->update(['status' => true]);

        $maildata = [
            'name' => $name,
        ];

        //Send Mail
        Mail::to($email)->send(new UserActivatedEmail($maildata));

        Session::flash('success', 'Account activated successfully');
        return redirect()->back();
    }

    public function deactivate(Request $request, $id)
    {
        $user = $request->user_id;
        $email = $request->email;
        $name = $request->name;

        DB::table('users')->where('id', $user)->update(['status' => false]);

        $maildata = [
            'name' => $name,
        ];

        //Send Mail
        Mail::to($email)->send(new UserDeactivatedEmail($maildata));

        Session::flash('success', 'Account deactivated successfully');
        return redirect()->back();
    }

    public function generatePassword( $length = 6 )
    {
        $nums = "123456789";
        $password = substr( str_shuffle( $nums ), 0, $length );
        return $password;
    }

    /**
     * Create multiple user accounts
     *
     * @return \Illuminate\Http\Response
     */
    public function importUsers(Request $request, UserImport $userImport)
    {
        $request->validate([
            'file' => 'required',
        ]);

        $file = request('file');

        // Read File Contents
        $path = $file->getRealPath();
        $rows = array_map('str_getcsv', file($path));
        $total = count($rows);

        $header = array_shift($rows);

        if (!$userImport->checkImportData($rows, $header)) {
            $request->session()->flash('error-rows', $userImport->getErrorRows());
            return response()->json([
                'uploaderror' => 'Error in upload. Correct and re-upload',
                'errors' => Session::get('error-rows'),
            ]);
        } 

        $userImport->createUsers($header, $rows);
        return response()->json([
            'success' => 'Users successfully imported into your organization',
        ]);
    }

    /**
     * Change User Avatar
     * @return \Illuminate\Http\Response
     */
    public function postChangeAvatar (Request $request) 
    {
         // Validate the request...
        $this->validate($request, [
            'avatar' => 'required|image',
            ]);
            
        if($request->hasFile('avatar'))
        {
            $user = Auth::user();

            $old = $user->avatar;

            if ($old !== "avatar.png") {
                unlink(public_path('img/avatars/' . $old));
            }

            $image = $request->file('avatar');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(64, 64)->save(public_path('/img/avatars/' . $filename));

            $user->avatar = $filename;
            $user->save();
        }
        // $user->notify(new UserUpdatedNotification($user));
        Session::flash('success', 'Your avatar was changed successfully!');
        return redirect()->back();
    }

    public function streamPermit()
    {
        $user = Auth::user();

        $url = base_path() . '/public/documents/permit/';

        $path = $url . Auth::user()->permit . '.pdf';

        if (file_exists($path)) {
            return response()->file($path);
        } else {
            Session::flash('error', 'You have no permit file');
            return redirect()->back();
        }
        
    }

    public function streamTax()
    {
        $user = Auth::user();

        $url = base_path() . '/public/documents/tax/';

        $path = $url . Auth::user()->id . '.pdf';

        if (file_exists($path)) {
            return response()->file($path);
        } else {
            Session::flash('error', 'You have no tax certificate file');
            return redirect()->back();
        }

        return response()->file($path);
    }

    public function streamId()
    {
        $user = User::findOrFail($id);

        $url = base_path() . '/public/documents/ids/';

        $path = $url . $user->identification_document;

        if (file_exists($path)) {
            return response()->file($path);
        } else {
            Session::flash('error', 'This user has no identification document uploaded');
            return redirect()->back();
        }
        
    }

    public function generateTemplate(Request $request)
    {
        $user = Auth::user();

        $organization = DB::table('organizations')
                    ->join('organization_details', 'organization_details.org_id', '=', 'organizations.id')
                    ->where('organizations.admin_id', '=', $user->id)
                    ->select('organization_details.*')
                    ->first();

        //$thisgroup = Group::where('org_id', $organization->id)->first();

        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',   
            'Content-type' => 'text/csv',   
            'Content-Disposition' => 'attachment; filename=org_template.csv', 
            'Expires' => '0',   
            'Pragma' => 'public'
        ];

        $mycolumns = [];

        $user_columns = User::all(['name', 'other_names', 'email', 'msisdn', 'account_no'])->toArray();
        $detail_columns = UserDetail::all(['gender', 'marital_status', 'doc_type', 'doc_no', 'dob', 'country', 'residence', 'occupation', 'income', 'city', 'state', 'postal_code', 'address'])->toArray();
       // $group_columns = Group::where('org_id', $organization->id)->pluck('group_data');

       // $newd = $group_columns[0];
        
        // if (!is_null($newd)) {
        //     $newd = array_keys($newd);
        // }

        array_unshift($user_columns, array_keys($user_columns[0]));
        array_unshift($detail_columns, array_keys($detail_columns[0]));
        $columns = array_values($user_columns[0]);
        $columns2 = array_values($detail_columns[0]);

        array_push($mycolumns, array_merge($columns, $columns2));

        $callback = function() use ($mycolumns) 
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $mycolumns[0]);
            fclose($file);            
        };

        return Response::stream($callback, 200, $headers);
    }
}
