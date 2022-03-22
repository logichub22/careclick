<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

function checkLanguageSetting()
{
    if (Auth::check()) {
        // $user = Auth::user();

        // $setting = DB::table('lang_settings')->where('user_id', $user->id)->first();

        // if(!is_null($setting)) {
        //     App::setLocale($setting->lang);
        // }

        $locale = Session::get('userlang');
        App::setLocale($locale);

        dd($locale);

        // dd($locale);
    }
}