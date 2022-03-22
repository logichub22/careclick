<?php

namespace App\Http\Controllers\Api;

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
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

/**
 * @group Authentication
 *
 * API endpoints For Authentication
 */

class AuthController extends Controller
{
    public function register(Request $request)
    {
        return 1233;
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'other_names' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'msisdn' => 'required|unique:users,msisdn',
            'password' => 'required|string|min:6',
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
            return response()->json(['errors' => $validator->errors()->all()], 406);
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

        $user->attachRole(Role::where('name', 'normal-user')->first());

        $detail = new UserDetail;
        $detail->user_id = $user->id;
        $detail->gender = $data['gender'];
        $detail->marital_status = $data['marital_status'];
        $detail->doc_type = $data['doc_type'];
        $detail->doc_no = $data['doc_no'];
        $detail->dob = date("Y-m-d",strtotime($data['dob']));
        $detail->residence = $data['residence'];
        $detail->country = $data['country'];
        $detail->city = $data['city'];
        $detail->state = $data['state'];
        $detail->postal_code = $data['postal_code'];
        $detail->address = $data['address'];
        $detail->income = $data['income'];
        $detail->occupation = $data['occupation'];
        $detail->save();

        if (request('identification_document') && $data['identification_document'] != "") {
            $file = request('identification_document');
            $filename = strtoupper(Str::random(5)) . '.' . $file->getClientOriginalExtension();
            $user->identification_document = $filename;
            $user->save();
            $data['identification_document']->move(
                base_path() . '/public/documents/ids', $filename
            );
        }

        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->balance = 0;
        $wallet->save();

        $token = $user->createToken('JamborowToken')->accessToken;

        event(new UserCreatedEvent($user));

        return response()->json([
            'success' => true,
            'message' => 'Successfully created user!',
            'user' => $user,
            'token' => $token
        ]);
    }
    
    

    /**
     * Login to account
     * 
     * This endpoint enables you to successfully generate access token
     *
     * @bodyParam email string required User's email address. Example: abc@website.com
     * @bodyParam password string required User's password. Example: secret
     * @bodyParam remember_me boolean true|false. No-example
     */
    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'remember_me' => 'boolean'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ], 406);
        }

        if(!Auth::attempt($request->all())){
            return response()->json([
                'success' => false,
                'message' => 'Invalid login credentials.'
            ], 406);
        }

        $user = Auth::user();
        $user['roles'] = $user->roles;
        $token = $user->createToken('JamborowToken');

        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully',
            'token' => $token->accessToken,
            'expires_at' => $token->token->expires_at,
            'user' => $user
        ], 200);


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
