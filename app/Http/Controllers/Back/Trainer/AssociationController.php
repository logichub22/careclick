<?php

namespace App\Http\Controllers\Back\Trainer;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationDetail;
use App\Models\General\Wallet;
use App\Models\General\UserDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Mail\OrganizationActivatedEmail;
use App\Mail\OrganizationDeactivatedEmail;
use App\Http\Requests\OrganizationRequest;
use Illuminate\Support\Str;
use App\Models\Organization\OrganizationWallet;
use App\Models\General\Role;
use App\Events\UserCreatedEvent;
use App\Mail\UserCreatedEmail;
use App\Mail\SendMemberEmail;
use App\Jobs\ProcessAssociationActivation;
use App\Jobs\ProcessAssociationDeactivation;
use App\Models\General\Group;

class AssociationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $organization = DB::table('organizations')->where('admin_id', $user->id)->first();

        $organizations = DB::table('organizations')
                      ->join('organization_details', 'organization_details.org_id', '=', 'organizations.id')
                      ->join('users', 'users.id', '=', 'organizations.admin_id')
                      ->select('organization_details.*', 'users.name as fname', 'users.other_names as lname', 'users.email as useremail', 'users.msisdn as userphone')
                      ->where('organizations.federation', $organization->id)
                      ->get();

        return view('back/federation/association/index', compact('organizations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        $countries = DB::table('countries')->whereIn('id', [25, 93, 101, 131, 159, 177, 186,])->get();

        $federation = DB::table('organizations')
                      ->join('organization_details', 'organization_details.org_id', '=', 'organizations.id')
                      ->where('organizations.admin_id', '=' , $user->id)
                      ->first();

        $genders = DB::table('genders')->get();

        return view('back/federation/association/create', compact('user', 'federation', 'countries', 'genders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' =>'required|unique:organization_details,name',
            'org_msisdn' => 'nullable|unique:organization_details,org_msisdn',
            'msisdn' => 'required|unique:users,msisdn',
            'address' => 'required',
            'domain' => 'nullable|active_url',
            'first_name' => 'required',
            'other_names' => 'required',
            'is_financial' => 'required',
            'org_email' => 'nullable|unique:organization_details,org_email',
            'password' => 'nullable|confirmed',
            'permit' => 'nullable|file|max:2000',
            'tax' => 'nullable|file|max:2000',
        ]);

        $password = $this->generatePassword(6);
        $user = new User;
        $user->name = $request->first_name;
        $user->other_names = $request->other_names;
        $user->email = $request->email;
        $user->msisdn = $this->formatPhoneNumber($request->country_id, $request->msisdn);
        $user->password = bcrypt($password);
        $user->status = true;
        $user->verified = true;
        $user->save();

        $id = $user->id;

        // Create new organization
        $organization = new Organization;
        $organization->admin_id = $user->id;
        $organization->federation = $request->org_id;
        $organization->save();

        // Insert into organization details
        $detail = new OrganizationDetail;
        $detail->org_id = $organization->id;
        $detail->name = $request->name;
        $detail->domain = $request->domain;
        $detail->is_financial = 1;
        $detail->address = $request->address;
        $detail->country = $request->country_id;
        $detail->org_email = $request->org_email;
        $detail->project_id = $organization->id;
        $detail->org_msisdn = $request->org_msisdn != null ? $this->formatPhoneNumber($request->country, $request->org_msisdn) : $request->org_msisdn;
        $detail->status = true;
        $detail->save();

        if ($request->hasFile('tax')) {
            $file = request('tax');
            $filename = strtoupper(Str::random(5)) . '.' . $file->getClientOriginalExtension();
            $detail->tax_certificate = $filename;
            $detail->save();
            $request->tax->move(
                base_path() . '/public/documents/tax', $filename
            );
        }

        if ($request->hasFile('permit')) {
            $file = request('permit');
            $filename = strtolower(Str::random(3)) . '.' . $file->getClientOriginalExtension();
            $detail->permit_file = $filename;
            $detail->save();
            $request->permit->move(
                base_path() . '/public/documents/permit', $filename
            );
        }

        // Attach admin or service provider role role
        $user->attachRole(Role::where('name','admin')->first());

        // Save Docs
        //$this->uploadOrgDocs($id);

        // Update User Wallet Table
        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->balance = 0;
        $wallet->save();

        $wallet = new OrganizationWallet();
        $wallet->org_id = $organization->id;
        $wallet->balance = 0;
        $wallet->save();

        //Fire event
        // Data to send to mail
        $maildata = array(
            'name' => $user->name,
            'password' => $password,
            'organization' => $detail->name,
            'email' => $user->email,
            'admin' => true
        );

        // Send Mail Here
        Mail::to($user->email)->send(new UserCreatedEmail($maildata));

        Session::flash('success', 'Association added successfully');
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
        $org = Organization::findOrFail($id);

        $organization = DB::table('organizations')
                      ->join('organization_details', 'organization_details.org_id', '=', 'organizations.id')
                      ->join('users', 'users.id', '=', 'organizations.admin_id')
                      ->where('organizations.id', $org->id)
                      ->select('organization_details.*', 'users.name as fname', 'users.other_names as lname', 'users.email as useremail', 'users.msisdn as userphone', 'organizations.status')
                      ->first();

        $members = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->where('user_details.org_id', '=', $org->id)
                ->orderBy('users.created_at', 'desc')
                ->select('users.*')
                ->get();

        // Groups
        $groups = DB::table('groups')
                ->join('users', 'users.id', '=', 'groups.user_id')
                ->where('groups.association_id', '=', $organization->org_id)
                ->select('groups.*', 'users.name as firstname', 'users.other_names as othernames')
                ->orderBy('created_at', 'desc')
                ->get();

        return view('back/federation/association/show', compact('organization', 'members', 'groups'));
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
        //
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

    public function deactivate(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);
        //$data = $request->all();

        $data = DB::table('organizations')
                      ->join('users', 'users.id', '=', 'organizations.admin_id')
                      ->where('organizations.id', $organization->id)
                      ->select('users.email', 'users.name', 'users.other_names')
                      ->first();


        $users = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->where('user_details.org_id', '=', $organization->id)
                ->select('users.email', 'users.name', 'users.other_names')
                ->get();

        // Send Mail
        $maildata = [
            'organization' => $request->organization,
            'name' => $data->name,
        ];

        DB::table('organizations')->where('id', $organization->id)->update([
            'status' => false,
        ]);

        Mail::to($data)->send(new OrganizationDeactivatedEmail($maildata));

        ProcessAssociationDeactivation::dispatch($organization);

        Session::flash('success', $request->organization . ' has been successfully deactivated');
        return redirect()->back();


    }

    public function activate(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);
        //$data = $request->all();

        $data = DB::table('organizations')
                      ->join('users', 'users.id', '=', 'organizations.admin_id')
                      ->where('organizations.id', $organization->id)
                      ->select('users.email', 'users.name', 'users.other_names')
                      ->first();

        $users = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->where('user_details.org_id', '=', $organization->id)
                ->select('users.email', 'users.name', 'users.other_names')
                ->get();

        $maildata = [
            'organization' => $request->organization,
            'name' => $data->name,
        ];

        // $user = DB::table('users')->where('email', $data->email)->first();

        DB::table('organizations')->where('id', $organization->id)->update([
            'status' => true,
        ]);

        Mail::to($data)->send(new OrganizationActivatedEmail($maildata));

        ProcessAssociationActivation::dispatch($organization);
        
        Session::flash('success', $request->organization . ' has been successfully activated');
        return redirect()->back();
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
            'sender' => 'Federation Administrator',
        );

        Mail::to($email)->send(new SendMemberEmail($maildata));

        // Flash and redirect
        Session::flash('success', 'An email has been successfully sent to ' . $name);
        return redirect()->back();
    }

    public function viewGroup($id)
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

        return view('back/federation/association/viewgroup', compact('group', 'members', 'coordinator', 'regions_arr', 'trainingofficer'));
    }

    public function sendEmailGroups(Request $request)
    {
        $coordinatorname = $request->membername;
        $subject = $request->subject;
        $message = $request->message;
        $adminemail = $request->email;

        // Data to send to mail
        $maildata = array(
            'name' => $name,
            'subject' => $subject,
            'message' => $message,
            'email' => $email,
            'sender' => 'Federation Administrator',
        );

        Mail::to($email)->send(new SendMemberEmail($maildata));

        // Flash and redirect
        Session::flash('success', 'An email has been successfully sent to ' . $name);
        return redirect()->back();
    }
}
