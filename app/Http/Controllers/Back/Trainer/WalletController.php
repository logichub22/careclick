<?php

namespace App\Http\Controllers\Back\Trainer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\General\Wallet;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationWallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function personal()
    {
    	$user = Auth::user();
    	$balance = Wallet::where('user_id', $user->id)->pluck('balance')->toArray();
        $bal = number_format(implode($balance));

    	return view('back/federation/wallet/personal', compact('bal'));
    }

    public function organization()
    {
    	$user = Auth::user();
    	$organization = DB::table('organizations')->where('admin_id', $user->id)->pluck('id')->toArray();
    	$id = implode($organization);
    	$balance = OrganizationWallet::where('org_id', $id)->pluck('balance')->toArray();
        $bal = number_format(implode($balance));

    	return view('back/federation/wallet/organization', compact('bal'));
    }
}
