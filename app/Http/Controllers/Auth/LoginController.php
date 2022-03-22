<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\RibyFunctions;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if(is_numeric($request->get('email'))){
            return [
                'msisdn' => $request->get('email'),
                'password' => $request->get('password')
            ];
        }

        return $request->only($this->username(), 'password') + ['verified' => true] + ['status' => true];
    }

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $id = $user->id;
        $name = $user->first_name . ' ' . $user->other_names;
        $ip = $request->getClientIp();
        $time_in = \Carbon\Carbon::now()->toDateTimeString();
        $created_at = \Carbon\Carbon::now()->toDateTimeString();
        $updated_at = $created_at;

        $data = \Location::get($ip);
        // dd($data);
        $country = "Kenya";

        DB::table('access_logs')->insert([
            'ip_address' => $ip,
            'location' => $country,
            'user_id' => $id,
            'access_time' => $time_in,
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ]);

        // Generate Riby Access Token
        if($user->isFirstSource()) RibyFunctions::RibyGenerateToken();
    }

    public function logout(){
        \Illuminate\Support\Facades\Auth::logout();
        return redirect('/');
    }
}
