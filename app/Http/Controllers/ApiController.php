<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);
        }
        
        $user = User::where('email', $request->email)->with(['roles', 'detail'])->get()->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'data' => 'User not registered'
            ], 404);
        }

        if ($user && \Hash::check($request->password, $user->password)) // The passwords match...
        {
            return response()->json([
                'success' => true,
                'data' => $user
            ], 201);        
        }
        else {
            return response()->json([
                'success' => false,
                'data' => 'Your password is incorrect'
            ], 400); 
        }
    }
}
