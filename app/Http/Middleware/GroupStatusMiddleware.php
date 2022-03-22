<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;
use App\Models\General\Group;
use DB;

class GroupStatusMiddleware
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
        $user = Auth::user();
        $groups = DB::table('groups')->where('user_id', $user->id)->get();

        if(!is_null($groups)) {
            foreach($groups as $group) {
                if($group->status == false) {
                    Session::flash('error', 'You can not perform any further operations since your group is inactive');
                    return redirect()->back();
                }
            }
        }
        return $next($request);
    }
}
