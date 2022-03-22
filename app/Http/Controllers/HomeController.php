<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('service-provider') || $user->hasRole('admin')) {
        return redirect()->route('organization.dashboard');
        } elseif ($user->hasRole('normal-user') || $user->hasRole('organization-user') || $user->hasRole('group-member') || $user->hasRole('group-admin')) {
            return redirect()->route('user.dashboard');
        } elseif ($user->hasRole('superadmin')) {
            return redirect()->route('super.dashboard');
        } elseif ($user->hasRole('trainer')) {
            return redirect()->route('trainer.dashboard');
        } elseif($user->hasRole('super-organization-admin')) {
            return redirect()->route('federation.dashboard');
        }
        // else{
        //     return redirect()->route('user.dashboard');
        // }
    }
}
