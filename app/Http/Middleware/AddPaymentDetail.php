<?php

namespace App\Http\Middleware;

use Closure;

use App\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AddPaymentDetail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Before Middleware 
        $user = Auth::user();

        if (is_null($user->account_number)) {
            Session::flash('error', 'Please add a payment method');
        }
        

        return $next($request);
    }
}
