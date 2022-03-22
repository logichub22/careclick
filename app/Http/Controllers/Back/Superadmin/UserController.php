<?php

namespace App\Http\Controllers\Back\Superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Mail\SendMemberEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('back/superadmin/user/index', compact('users'));
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
        $user = User::findOrFail($id);

        $detail = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->where('user_details.user_id', '=', $user->id)
                ->select('user_details.*')
                ->first();

        $owned = DB::table('groups')->where('user_id', $user->id)->get();

        $membergroups = DB::table('groups')
                      ->join('group_members', 'group_members.group_id', '=', 'groups.id')
                      ->where('group_members.user_id', '=', $user->id)
                      ->select('groups.*', 'group_members.status as memberstatus', 'group_members.created_at')
                      ->get();

        $date = DB::table('groups')
                   ->join('group_members', 'group_members.group_id', '=', 'groups.id')
                   ->where('group_members.user_id', '=', $user->id)
                   ->pluck('group_members.created_at')
                   ->toArray();
    
        $created = implode($date);

        $transactions = DB::table('transactions')->where('user_id', $user->id)->get();

        //dd($detail);
        @$gender = implode(DB::table('genders')->where('id', $detail->gender)->pluck('name')->toArray());
        @$document = implode(DB::table('documents')->where('id', $detail->doc_type)->pluck('name')->toArray());
        @$country = implode(DB::table('countries')->where('id', $detail->country)->pluck('name')->toArray());
        @$income = implode(DB::table('income_classes')->where('id', $detail->income)->pluck('name')->toArray());
        @$residence = implode(DB::table('resident_types')->where('id', $detail->residence)->pluck('name')->toArray());

        $loans = DB::table('loans')->where('user_id', $user->id)->get();

        // return view('back/superadmin/user/show', compact('user', 'detail', 'country', 'document', 'countries', 'documents', 'owned', 'transactions', 'membergroups','incomes', 'maritals', 'genders', 'residents', 'gender', 'income', 'residence', 'loans'));
        return view('back/superadmin/user/show', compact('user', 'detail', 'country', 'document', 'owned', 'transactions', 'membergroups', 'gender', 'income', 'residence', 'loans'));
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

    public function sendMemberEmail(Request $request)
    {
        $name = $request->member_name;
        $subject = $request->subject;
        $message = $request->message;
        $email = $request->email;

        // Data to send to mail
        $maildata = array(
            'name' => $name,
            'subject' => $subject,
            'message' => $message,
            'email' => $email,
            'sender' => 'Jamborow Support',
        );

        // Send Mail Here
        Mail::to($email)->send(new SendMemberEmail($maildata));

        // Flash and redirect
        Session::flash('success', 'An email has been successfully sent to ' . $name);
        return redirect()->back();
    }
}
