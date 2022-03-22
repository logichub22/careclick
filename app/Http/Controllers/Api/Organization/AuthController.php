<?php

namespace App\Http\Controllers\Api\Organization;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use App\Models\General\Role;
use Illuminate\Support\Str;
use App\Models\General\Wallet;
use App\Events\UserCreatedEvent;
use App\Models\General\UserDetail;
use App\Models\Organization\OrganizationDetail;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationWallet;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'other_names' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'msisdn' => 'required|unique:users,msisdn',
            'org_name' => 'required',
            'country' => 'required',
            'org_email' => 'required',
            'org_address' => 'required',
            'org_type' => 'required',
            'org_website' => 'required',
            'org_msisdn' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $data = $request->all();
      
        // Create new user model
        $user = User::create([
            'name' => $request->name,
            'other_names' => $request->other_names,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'msisdn' => $this->formatPhoneNumber($request->country, $request->msisdn),
        ]);

        $user->attachRole(Role::where('name', 'admin')->first());
        
        $organization = new Organization;
        $organization->admin_id = $user_id;
    
        $detail = new OrganizationDetail;
        $detail->org_id = $organization->id;
        $detail->name = $data['org_name'];
        $detail->org_email = $data['org_email'];
        $detail->domain = $data['org_website'];
        $detail->country = $data['country'];
        $detail->address = $data['org_address'];
        $detail->org_msisdn = $data['org_msisdn'];
        $detail->is_financial = 2;
        $detail->save();

        $wallet = new OrganizationWallet;
        $wallet->org_id = $organization->id;
        $wallet->balance = 0;
        $wallet->save();

        $token = $user->createToken('JamborowToken')->accessToken;

        event(new UserCreatedEvent($user));

        return response()->json([
            'success' => true,
            'message' => 'Successfully created organization!',
            'user' => $user,
            'organization' => $organization,
            'token' => $token
        ]);
    }
    
    

    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'remember_me' => 'boolean'
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $user = User::where('email', $request->email)->with('roles')->first();

        if ($user) {

            if (\Hash::check($request->password, $user->password)) {
                $token = $user->createToken('JamborowToken')->accessToken;
                return response()->json([
                    'success' => true,
                    'token' => $token,
                    'message' => 'Logged in successfully',
                    'user' => $user
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Password is incorrect'
                ]);
            }
    
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User does not exist'
            ]);
        }


        // if (Auth::attempt($credentials)) // Login successful
        // {
        //     $auth_id = $request->user()->id;
        //     $user = User::where('id', $auth_id)->with('roles')->get()->first();

        //     // create token
        //     $tokenResult = $user->createToken('Personal Access Token');
        //     $token = $tokenResult->token;

        //     if($request->remember_me) {
        //         $token->expires_at = Carbon::now()->addWeeks(1);
        //     }

        //     $token->save();

        //     return response()->json([
        //         'success' => true,
        //         'access_token' => $tokenResult->accessToken,
        //         'token_type' => 'Bearer',
        //         'expires_at' => Carbon::parse(
        //             $tokenResult->token->expires_at
        //         )->toDateTimeString(),
        //         'user' => $user,
        //         'message' => 'Login successful'
        //     ], 201);        
        // }
        // else {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Email or password provided is incorrect'
        //     ], 400); 
        // }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
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

    public function regions()
    {
        
    }
}
