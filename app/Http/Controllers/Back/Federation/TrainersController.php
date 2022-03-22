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
use App\Mail\UserActivatedEmail;
use App\Mail\UserDeactivatedEmail;

class TrainersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $admin = Auth::user()->id;
        
        $users = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->join('organizations', 'organizations.id', '=', 'user_details.org_id')
                ->join('role_user','role_user.user_id','=','users.id')
                ->join('roles','roles.id','=','role_user.role_id')
                ->select('users.*')
                ->where(['roles.name' => 'trainer', 'organizations.admin_id' => $admin])
                ->get();

        return view('back/federation/trainer/index', compact('users', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $organization = Organization::where('admin_id', Auth::user()->id)->with('detail')->first();
        $documents = DB::table('documents')->get();
        $countries = DB::table('countries')->whereIn('id', [25, 93, 101, 131, 159, 177, 186,])->get();
        $incomes = DB::table('income_classes')->get();
        $maritals = DB::table('maritals')->get();
        $genders = DB::table('genders')->get();
        $residents = DB::table('resident_types')->get();

        $admin = Auth::user()->id;
        
        $trainers = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->join('organizations', 'organizations.id', '=', 'user_details.org_id')
                ->join('role_user','role_user.user_id','=','users.id')
                ->join('roles','roles.id','=','role_user.role_id')
                ->select('users.*')
                ->where(['roles.name' => 'trainer', 'organizations.admin_id' => $admin])
                ->get();

        return view('back/federation/trainer/create', compact('user', 'organization', 'documents', 'countries', 'incomes', 'maritals', 'genders', 'residents', 'trainers'));
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
            'name' => 'required|string|max:255',
            'other_names' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'msisdn' => 'required|unique:users,msisdn',
            'gender' => 'required',
            'doc_type' => 'required',
            'doc_no' => 'required|unique:user_details,doc_no',
            'dob' => 'required|date',
            'country' => 'required',
            'city' => 'required',
            'address' => 'required',
        ]);

        $password = $this->generatePassword(6);

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
        $user->attachRole(Role::where('name','trainer')->first());

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

        // Data to send to mail
        $maildata = array(
            'name' => $user->name,
            'password' => $password,
            'email' => $user->email,
            'group' => $request->fed_name,
            'trainer' => true
        );

        // Send Mail Here
        Mail::to($user->email)->send(new GroupMemberCreated($maildata));

        // Flash and redirect
        Session::flash('success', 'Trainer added successfully');
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
        $user = User::with('detail')->findOrFail($id);

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

        $groups = Group::where('trainer_id', $user->id)->get();

        return view('back/federation/trainer/show', compact('user', 'detail', 'gender', 'document', 'country', 'residence', 'income', 'documents', 'countries', 'incomes', 'maritals', 'genders', 'residents', 'groups'));
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
            'city' => 'required',
            'address' => 'required',
        ]);

        DB::table('users')->where('id', $user->id)->update([
            'name' => $request->name,
            'other_names' => $request->other_names,
            'email' => $request->email,
            'msisdn' => preg_replace('/^0/','255',$request->msisdn),
        ]);

        DB::table('user_details')->where('user_id', $user->id)->update([
            'income' => $request->income,
            'city' => $request->city,
            'address' => $request->address,
        ]);

        Session::flash('success', 'Trainer details have been updated successfully');
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
        $user = User::findOrFail($id);

        $user->delete();

        Session::flash('success', 'Trainer deleted successfully');
        return redirect()->back();
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

        // Send Mail Here
        Mail::to($email)->send(new SendMemberEmail($maildata));

        // Flash and redirect
        Session::flash('success', 'An email has been successfully sent to ' . $name);
        return redirect()->back();
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
}
