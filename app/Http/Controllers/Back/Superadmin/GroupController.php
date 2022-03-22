<?php

namespace App\Http\Controllers\Back\Superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\General\Group;
use Illuminate\Support\Facades\DB;
use App\Models\General\GroupWallet;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = 'user';
        $groups = DB::table('groups')->get();
        
        return view('back/superadmin/group/index', compact('groups', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = 'user';
        $group = Group::findOrFail($id);

        $members = DB::table('users')
                ->join('group_members', 'group_members.user_id', '=', 'users.id')
                ->orderBy('created_at','desc')
                ->select('users.*')
                ->where(array("group_members.group_id" => $group->id))
                ->get();

        if(is_null($group->org_id))
            $admin = DB::table('users')
                    ->join('groups','groups.user_id','=','users.id')
                    ->select('users.*')
                    ->where('groups.id', $group->id)
                    ->first();
        else {
            $admin = DB::table('groups')
                    ->join('organizations','organizations.id','=','groups.org_id')
                    ->join('users', 'users.id','=','organizations.admin_id')
                    ->select('users.*')
                    ->where('groups.id', $group->id)
                    ->first();
        }

        $wallet = GroupWallet::where('group_id', $group->id)->first();

        return view('back/superadmin/group/show', compact('group', 'members', 'wallet', 'admin', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
