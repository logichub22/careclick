<?php

namespace App\Http\Controllers\Back\Federation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\General\Account;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function index()
    {
    	$user = Auth::user();

    	$accounts = DB::table('bank_accounts')
    			->join('users', 'users.id', '=', 'bank_accounts.id')
    			->where('bank_accounts.user_id', $user->id)
    			->select('bank_accounts.*')
    			->get();

    	return view('back/federation/payment/index', compact('accounts'));
    }

    public function addAccount(Request $request)
    {
    	$request->validate([
    		'account_no' => 'required|numeric',
    	]);

    	$user = $request->user();

    	$account = new Account;
    	$account->user_id = $user->id;
    	$account->account_no = $request->account_no;
    	$account->save();

    	//check
 
    	$useraccounts = Account::where('user_id', $user->id)->get();

    	// if (is_null($useraccounts)) {
    	// 	$account->is_primary = true;
    	// }

    	Session::flash('success', 'Account number has been added successfully');
    	return redirect()->back();
    }
}
