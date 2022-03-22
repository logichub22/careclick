<?php

namespace App\Http\Controllers\Back\Superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Organization\Organization;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\OrganizationActivatedEmail;
use App\Mail\OrganizationDeactivatedEmail;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = 'user';
        $organizations = DB::table('organizations')
                      ->join('organization_details', 'organization_details.org_id', '=', 'organizations.id')
                      ->join('users', 'users.id', '=', 'organizations.admin_id')
                      ->select('organization_details.*', 'users.name as fname', 'users.other_names as lname', 'users.email as useremail',
                      'users.msisdn as userphone')
                      ->get();

        return view('back/superadmin/organization/index', compact('organizations', 'user'));
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
        $org = Organization::findOrFail($id);

        $organization = DB::table('organizations')
                      ->join('organization_details', 'organization_details.org_id', '=', 'organizations.id')
                      ->join('users', 'users.id', '=', 'organizations.admin_id')
                      ->where('organizations.id', $org->id)
                      ->select('organization_details.*', 'users.name as fname', 'users.other_names as lname', 'users.email as useremail', 'users.msisdn as userphone', 'organizations.status')
                      ->first();

        $members = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->where('user_details.org_id', '=', $org->id)
                ->orderBy('users.created_at', 'desc')
                ->select('users.*')
                ->get();

        return view('back/superadmin/organization/show', compact('organization', 'members', 'user'));
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

    public function deactivate(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);
        //$data = $request->all();

        // $data = DB::table('organizations')
        //               ->join('users', 'users.id', '=', 'organizations.admin_id')
        //               ->where('organizations.id', $organization->id)
        //               ->select('users.email')
        //               ->first();

        $users = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->where('user_details.org_id', '=', $organization->id)
                ->select('users.email', 'users.name', 'users.other_names')
                ->get();

        //$user = DB::table('users')->where('email', $data->email)->first();

        DB::table('organizations')->where('id', $organization->id)->update([
            'status' => false,
        ]);

        foreach ($users as $user) {
            DB::table('users')->where('id', $user->id)->update([
                'status' => false,
            ]);

            // Send Mail
            $maildata = [
                'organization' => $request->organization,
                'name' => $user->name . ' ' . $user->other_names,
            ];
    
            Mail::to($user->email)->send(new OrganizationDeactivatedEmail($maildata));
        }

        Session::flash('success', $request->organization . ' has been successfully deactivated');
        return redirect()->back();


    }

    public function activate(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);
        //$data = $request->all();

        // $data = DB::table('organizations')
        //               ->join('users', 'users.id', '=', 'organizations.admin_id')
        //               ->where('organizations.id', $organization->id)
        //               ->select('users.email')
        //               ->first();

        $users = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->where('user_details.org_id', '=', $organization->id)
                ->select('users.email', 'users.name', 'users.other_names')
                ->get();

        // $user = DB::table('users')->where('email', $data->email)->first();

        DB::table('organizations')->where('id', $organization->id)->update([
            'status' => true,
        ]);

        foreach ($users as $user) {
            DB::table('users')->where('id', $user->id)->update([
                'status' => true,
            ]);

            // Send Mail
            $maildata = [
                'organization' => $request->organization,
                'name' => $user->name . ' ' . $user->other_names,
            ];
    
            // Notify Admin
            Mail::to($user->email)->send(new OrganizationActivatedEmail($maildata));
        }
        
        Session::flash('success', $request->organization . ' has been successfully activated');
        return redirect()->back();


    }
}
