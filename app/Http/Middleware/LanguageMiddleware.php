<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Session;
use Auth;
use DB;
class LanguageMiddleware
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
        // checkLanguageSetting();
        $locale = Session::get('userlang');
        //dd($locale);
        $user = Auth::user()->id;

        if(is_null($locale)){
            $user_lang = DB::table('lang_settings')->where('user_id', $user)->first();
            if(!is_null($user_lang)){


              $locale = $user_lang->lang;
              Session::put('userlang',$locale);

            }

            else{

                $locale = "en";
                Session::put('userlang',$locale);
            }


        }

        App::setLocale($locale);

            return $next($request);
    }
}
