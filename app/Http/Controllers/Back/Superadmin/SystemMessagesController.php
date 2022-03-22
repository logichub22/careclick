<?php

namespace App\Http\Controllers\Back\Superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\General\SystemMessage;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class SystemMessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = 'user';
        $messages = SystemMessage::all();

        return view('back/superadmin/config/index', compact('messages', 'user'));
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
        $request->validate([
            'name' => 'required|unique:system_messages,name',
            'description' => 'required',
        ]);

        $message = new SystemMessage();
        $message->name = $request->name;
        $message->description = $request->description;
        $message->status = true;
        $message->save();

        Session::flash('success', 'Message has been added successfully');
        return redirect()->route('configs.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = 'user';
        $message = SystemMessage::findOrFail($id);

        return view('back/superadmin/config/edit', compact('message', 'user'));
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
        $message = SystemMessage::findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                Rule::unique('system_messages')->ignore($message->id),
            ],
            'description' => 'required'
        ]);

        $message->name = $request->name;
        $message->description = $request->description;
        $message->status = true;
        $message->save();

        Session::flash('success', 'Message has been updated successfully');
        return redirect()->route('configs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $message = SystemMessage::findOrFail($id);

        $message->delete();

        Session::flash('success', 'Message has been deleted successfully');
        return redirect()->route('configs.index');
    }
}
