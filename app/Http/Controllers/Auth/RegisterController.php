<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\General\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationDetail;
// use App\Mail\NewOrganizationEmail;
use App\Models\General\Wallet;
use App\Models\General\UserDetail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Events\UserCreatedEvent;
use App\Http\Requests\OrganizationRequest;
use App\Traits\Docs\Upload;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Models\General\Group;
use App\Models\General\GroupWallet;
use App\Models\Organization\OrganizationWallet;
use App\SavingsWallet;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers, Upload;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'other_names' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'msisdn' => 'required|unique:users,msisdn',
            'password' => 'required|string|min:6|confirmed',
            'gender' => 'required',
            'marital_status' => 'required',
            'doc_type' => 'required',
            'doc_no' => 'required|unique:user_details,doc_no',
            'dob' => 'required|date',
            'country' => 'required',
            'residence' => 'required',
            'city' => 'required',
            'state' => 'nullable',
            'postal_code' => 'required',
            'address' => 'required',
            //'income' => 'required',
            //'occupation' => 'required',
            //'identification_document' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'other_names' => $data['other_names'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'msisdn' => $this->formatPhoneNumber($data['country'], $data['msisdn']),
        ]);

        $user->attachRole(Role::where('name', 'normal-user')->first());

        // Insert into User Details Table
        $detail = new UserDetail;
        $detail->user_id = $user->id;
        $detail->gender = $data['gender'];
        $detail->marital_status = $data['marital_status'];
        $detail->doc_type = $data['doc_type'];
        $detail->doc_no = $data['doc_no'];
        $detail->dob = $data['dob'];
        //$detail->msisdn = $data['msisdn'];
        $detail->residence = $data['residence'];
        $detail->country = $data['country'];
        $detail->city = $data['city'];
        $detail->state = $data['state'];
        $detail->postal_code = $data['postal_code'];
        $detail->address = $data['address'];
        $detail->income = $data['income'];
        $detail->occupation = $data['occupation'];
        $detail->save();

        if (request('identification_document')) {
            $file = request('identification_document');
            $filename = strtoupper(Str::random(5)) . '.' . $file->getClientOriginalExtension();
            $user->identification_document = $filename;
            $user->save();
            $data['identification_document']->move(
                base_path() . '/public/documents/ids', $filename
            );
        }

        // Update User Wallet Table
        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->balance = 0;
        $wallet->save();

        $savings_wallet = new SavingsWallet;
        $savings_wallet->user_id = $user->id;
        $savings_wallet->balance = 0;
        $savings_wallet->save();

        // Country IDS
        // Nigeria 131
        // Kenya 93
        // Liberia 101
        // Uganda 186
        // Tanzania 177
        // Botswana 25
        // Sierra Leone 159

        $client = new \GuzzleHttp\Client;

        // Push to Tech Advance in Nigeria
//        if ($detail->country == 131) {
//          // Create Customer
//            $response = $client->request('POST', 'http://104.131.174.54:7171/api/v1.0/customers', [
//                'headers' => [
//                    'api-key' => 'oksSJXwSB6dz9j3qYo6qiNv2iQbubyok',
//                    'Content-Type' => 'application/json'
//                ],
//                'auth' => [
//                    null,
//                    'oksSJXwSB6dz9j3qYo6qiNv2iQbubyok'
//                ],
//                'body' => json_encode([
//                    'email' => $user->email,
//                    'phone' => $user->msisdn,
//                    'first_name' => $user->name,
//                    'other_name' => $user->other_names,
//                    //'dob' => $newdob,
//                    'gender' => strtoupper($detail->gender),
//                    'address' => $detail->address,
//                    'hometown' => $detail->city,
//                    'town' => $detail->town,
//                    'occupation' => $detail->occupation,
//                    'nationality_id' => $detail->country,
//                ])
//            ]);
//
//            $customer = json_decode($response->getBody(), true);
//
//            $fullname = $user->name . ' ' . $user->other_names;
//
//            $myres = $customer['your_response'];
//
//            $uid = $myres['username'];
//
//            DB::table('users')->where('id', $user->id)->update(['customer_username' => $uid]);
//
//            //Create new account for customer
//            $res = $client->request('POST', 'http://104.131.174.54:7171/api/v1.0/accounts', [
//                'headers' => [
//                    'api-key' => 'oksSJXwSB6dz9j3qYo6qiNv2iQbubyok',
//                    'Content-Type' => 'application/json'
//                ],
//                'auth' => [
//                    null,
//                    'oksSJXwSB6dz9j3qYo6qiNv2iQbubyok'
//                ],
//                'body' => json_encode([
//                    'account_category_id' => 2,
//                    'account_type_id' => 1,
//                    'customer_uid' => $uid,
//                    'name' => strtoupper($fullname),
//                ])
//            ]);
//
//            $newcus = json_decode($res->getBody(), true);
//            $newres = $newcus['your_response'];
//            $account = $newres['account_number'];
//
//            DB::table('users')->where('id', $user->id)->update(['account_no' => $account]);
//        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.jamborow-datastore.utu.io/v1/customers",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: application/json",
                "Authorization: Basic jamborow:a1ba06ca5254",
                "Content-Type: application/json",
            ),
        ]);

        $response_curl = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        return $user;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        //Fire event
        event(new UserCreatedEvent($user));

        return redirect()->route('account.created');
    }

    public function verifyEmail($token)
    {
       // $userretrieved = User::whereToken($token)->firstOrFail();
       //$user_details = User::whereToken($token)->first()->id;

        User::whereToken($token)->firstOrFail()->hasVerified();

        Session::flash('success', 'Your email has been verified. Enter your details below to proceed');

        return redirect()->route('login');
    }

    /**
     * Create a new organization user
     *
     * @return \App\User
     */

    public function registerOrganization(OrganizationRequest $request)
    {

       // dd($request);

        // Create organization admin and insert into users table
        $user = new User;
        $user->name = $request->first_name;
        $user->other_names = $request->other_names;
        $user->email = $request->email;
        $user->msisdn = $request->msisdn;
        $user->status = true;
        $user->verified = true;
        $user->password = bcrypt($request->password);

        $user->save();

        $id = $user->id;

        // Create new organization
        $organization = new Organization;
        $organization->admin_id = $user->id;
        $organization->verified = true;
        $organization->status = true;
        $organization->save();

        // Insert into organization details
        $detail = new OrganizationDetail;
        $detail->org_id = $organization->id;
        $detail->name = $request->name;
        $detail->domain = $request->domain;
        $detail->is_financial = $request->is_financial;
        $detail->address = $request->address;
        $detail->country = $request->country;
        $detail->org_email = $request->org_email;
        $detail->project_id = $organization->id;
        $detail->org_msisdn = $request->org_msisdn == null ? $this->formatPhoneNumber($request->country, $request->org_msisdn) : $request->org_msisdn;
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
        if ($detail->is_financial == 1) {
            $user->attachRole(Role::where('name','admin')->first());
        } else if ($detail->is_financial == 0) {
            $user->attachRole(Role::where('name','service-provider')->first());
        } else if ($detail->is_financial == 2) {
            $user->attachRole(Role::where('name','super-organization-admin')->first());
        }

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
        
        
        $savings_wallet = new SavingsWallet;
        $savings_wallet->org_id = $user->id;
        $savings_wallet->balance = 0;
        $savings_wallet->save();

        // Create default group for Organization
        $group = new Group();
        $group->name = $detail->name;
        $group->comment = 'A default savings group for ' .$detail->name;
        $group->user_id = $user->id;
        $group->org_id = $organization->id;
        $group->account_verified = true;
        $group->save();

        $groupWallet = new GroupWallet();
        $groupWallet->group_id = $group->id;
        $groupWallet->balance = 0;
        $groupWallet->save();

        //$client = new \GuzzleHttp\Client;
        // if ($detail->country == 131) {
        //     // Create Customer
        //     $response = $client->request('POST', 'http://127.0.0.1:8000/api/v1.0/customers', [
        //         'headers' => [
        //             'api-key' => 'dzPr62CWYcmafbu8PEefJXjgePu33d7XKh6Udez2',
        //             'Content-Type' => 'application/json'
        //         ],
        //         'auth' => [
        //             null,
        //             'dzPr62CWYcmafbu8PEefJXjgePu33d7XKh6Udez2'
        //         ],
        //         'body' => json_encode([
        //             'email' => $user->email,
        //             'phone' => $user->msisdn,
        //             'first_name' => $user->name,
        //             'other_name' => $user->other_names,
        //             'address' => $detail->address,
        //         ])
        //     ]);
        //
        //     $customer = json_decode($response->getBody(), true);
        //
        //     $fullname = $user->name . ' ' . $user->other_names;
        //
        //     $myres = $customer['your_response'];
        //
        //     $uid = $myres['username'];
        //
        //     DB::table('users')->where('id', $user->id)->update(['customer_username' => $uid]);
        //
        //     //Create new account for customer
        //     $res = $client->request('POST', 'http://127.0.0.1:8001/api/v1.0/accounts', [
        //         'headers' => [
        //             'api-key' => 'dzPr62CWYcmafbu8PEefJXjgePu33d7XKh6Udez2',
        //             'Content-Type' => 'application/json'
        //         ],
        //         'auth' => [
        //             null,
        //             'dzPr62CWYcmafbu8PEefJXjgePu33d7XKh6Udez2'
        //         ],
        //         'body' => json_encode([
        //             'account_category_id' => 2,
        //             'account_type_id' => 2,
        //             'customer_uid' => $uid,
        //             'name' => strtoupper($fullname),
        //         ])
        //     ]);
        //
        //     $newcus = json_decode($res->getBody(), true);
        //     $newres = $newcus['your_response'];
        //     $account = $newres['account_number'];
        //
        //     DB::table('users')->where('id', $user->id)->update(['account_no' => $account]);
        // }
        //
        // //Fire event
        // event(new UserCreatedEvent($user));
        return redirect()->route('reg.success');
        // // Log in user
        // $this->guard()->login($user);
        // // Redirect
        // return $this->registered($request, $user)
        //                 ?: redirect($this->redirectPath());
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
}
