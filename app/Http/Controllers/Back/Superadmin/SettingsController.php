<?php

namespace App\Http\Controllers\Back\Superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\General\Setting;
use App\Models\General\SettingCategory;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use DB;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = DB::table('countries')->whereIn('id', [25, 93, 101, 131, 159, 177, 186,])->get();
        return view('back/superadmin/setting/index', compact('countries'));
    }

    public function addCategory(Request $request)
    {
        $request->validate([
            'catname' => 'required|unique:setting_categories,name',
        ]);

        $setting = new SettingCategory();
        $setting->name = $request->catname;
        $setting->save();

        Session::flash('success', 'New setting category created successfully');
        return redirect()->back();
    }

    public function regionSettings(Request $request)
    {
        $countries = DB::table('countries')->whereIn('id', [25, 93, 101, 131, 159, 177, 186,])->get();
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
            'name' => 'required|unique:settings,name',
            'category' => 'required',
            'description' => 'required',
        ]);

        $setting = new Setting();
        $setting->name = $request->name;
        $setting->description = $request->description;
        $setting->category = $request->category;
        $setting->save();

        Session::flash('success', 'New setting created successfully');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $setting = Setting::findOrFail($id);
        $name = implode(SettingCategory::where('id', $setting->category)->pluck('name')->toArray());
        return view('back/superadmin/setting/show', compact('setting', 'name'));
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
        $setting = Setting::findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                Rule::unique('settings')->ignore($setting->id),
            ],
            'description' => 'required',
        ]);

        $setting->name = $request->name;
        $setting->description = $request->description;
        $setting->save();

        Session::flash('success', 'Update Success!');
        return redirect()->back();
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

    public function deactivateSetting($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->update([
            'status' => false,
        ]);

        Session::flash('success', 'Deactivated successfully!');
        return redirect()->back();
    }

    public function activateSetting($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->update([
            'status' => true,
        ]);

        Session::flash('success', 'Activated successfully!');
        return redirect()->back();
    }
}
