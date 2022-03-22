<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\General\LoanPackage;
use App\Models\General\Wallet;

class CheckWalletBalance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $minAmount)
    {
        $user = $request->user();
        
        // if ($user->hasRole('normal-user')) {
        //     $balance = Wallet::where('user_id', $user->id)->first();

        //     if ($balance < $minAmount) {
        //         # code...
        //     }
        // }

        return $next($request);
    }
}
