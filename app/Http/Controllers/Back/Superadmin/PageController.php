<?php

namespace App\Http\Controllers\Back\Superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Organization\Organization;
use App\Models\General\Loan;
use App\Models\General\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\AdminPasswordChanged;
use Illuminate\Validation\Rule;
use Image;

class PageController extends Controller
{
    public function getDashboard()
    {
        $user = Auth::user();
    	$users = User::all();
        $organizations = Organization::all();
        $groups = Group::all();
        $loans = Loan::all();
    	return view('back/superadmin/pages/dashboard', compact('users', 'organizations', 'groups','loans', 'user'));
    }

    public function manageProfile()
    {
    	$user = Auth::user();

    	return view('back/superadmin/pages/profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->all();

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
            'password' => 'sometimes|confirmed',
        ]);

        //dd($request->password);

        $user->name = $data['name'];
        $user->other_names = $data['other_names'];
        $user->email = $data['email'];
        $user->msisdn = $data['msisdn'];
        $user->save();

        $maildata = [
            'name' => $user->name,
        ];

        if($data['password']) {
            $user->password = Hash::make($request->password);
            $user->save();
            Mail::to($user->email)->send(new AdminPasswordChanged($maildata));
            Auth::logoutOtherDevices($user->password);
        }

        Session::flash('success', 'You have successfully updated your details');
        return redirect()->back();
    }

    
    /**
     * Change User Avatar
     * @return \Illuminate\Http\Response
     */
    public function postChangeAvatar(Request $request) 
    {
         // Validate the request...
        $request->validate([
            'avatar' => 'required',
        ]);

        //dd($request);
            
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

    public function transactions() 
    {
        $user = Auth::user();
        $transactions = DB::table('users')
                      ->join('transactions', 'transactions.user_id', '=', 'users.id')
                      ->select('transactions.*', 'users.name', 'users.other_names')
                      ->get();

        return view('back/superadmin/pages/transactions', compact('transactions', 'user'));
    }

    public function accessLogs(Request $request)
    {
        $user = 'user';
        $logs = DB::table('access_logs')
                ->join('users', 'users.id','=', 'access_logs.user_id')
                ->orderBy('created_at', 'desc')
                ->select('access_logs.*', 'users.name', 'users.other_names', 'users.email', 'users.msisdn')
                ->get();

        //dd($logs);

        return view('back/superadmin/pages/logs', compact('logs', 'user'));
    }

    public function getSettings(Request $request)
    {
        $currencies = DB::table('currencies')->get();
        return view('back/superadmin/pages/settings', compact('currencies'));
    }
}
