<?php

namespace App\Http\Controllers\Back\Individual;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use Session;
use Image;
use App\Models\General\Loan;
use App\Models\General\Group;
use App\Models\General\Wallet;
use App\Models\General\Transaction;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminPasswordChanged;

class PageController extends Controller
{
    public function getDashboard()
    {
        $user = Auth::user();
        $loans = Loan::where('user_id', $user->id)->orderBy('created_at', 'desc')->take(5)->get();

        $loan_count = Loan::where('user_id', $user->id)->count();

        $group_count = DB::table('groups')
                      ->join('group_members', 'group_members.group_id', '=', 'groups.id')
                      ->where('group_members.user_id', '=', $user->id)
                      ->count();

        $wallet = Wallet::where('user_id', $user->id)->first();

        $savings_wallet = DB::table('savings_wallet')->where('user_id', $user->id)->first();
        // dd($savings_wallet);
        $packages = DB::table('loan_packages')->where('user_id', $user->id)->pluck('id');

        $transactions = Transaction::where('user_id', $user->id)->orderBy('created_at', 'desc')->take(5)->get();

        $currency =  DB::table('user_details')
                    ->join('currencies', 'country_id', '=', 'user_details.country')
                    ->where('user_details.user_id', '=', $user->id)
                    ->select('currencies.prefix')
                    ->first();
        // dd($currency);

    	return view('back/individual/pages/dashboard', compact('loans', 'transactions', 'wallet', 'savings_wallet', 'user', 'loan_count','group_count', 'currency'));
    }

    public function manageProfile()
    {
    	$user = Auth::user();

    	$detail = DB::table('users')
                      ->join('user_details', 'user_details.user_id', '=', 'users.id')
                      ->where('user_details.user_id', '=' , $user->id)
                      ->first();

        $organization = DB::table('user_details')
                      ->join('organization_details', 'organization_details.id', '=', 'user_details.org_id')
                      ->where('organization_details.id', '=', $detail->org_id)
                      ->select('organization_details.name')
                      ->first();

        $credit_score = DB::table('users')
                        ->join('loans', 'loans.user_id', '=', 'users.id')
                        ->where('loans.user_id', '=', $user->id)
                        ->select('loans.borrower_credit_score')
                        ->first();

        $groups = DB::table('groups')
                    ->join('users', 'users.id', '=', 'groups.user_id')
                    ->where('groups.user_id', '=', $user->id)
                    ->get();

        $membergroups = DB::table('groups')
                    ->join('group_members', 'group_members.group_id', '=', 'groups.id')
                    ->where('group_members.user_id', '=', $user->id)
                    ->get();

        $wallet = Wallet::where('user_id', $user->id)->first();

        $documents = DB::table('documents')->get();
        $incomes = DB::table('income_classes')->get();
        $income = implode(DB::table('income_classes')->where('id', $detail->income)->pluck('name')->toArray());
        $gender = implode(DB::table('genders')->where('id', $detail->gender)->pluck('name')->toArray());
        $doc = implode(DB::table('documents')->where('id', $detail->doc_type)->pluck('name')->toArray());
        $marital = implode(DB::table('maritals')->where('id', $detail->marital_status)->pluck('name')->toArray());
        $residence = implode(DB::table('resident_types')->where('id', $detail->residence)->pluck('name')->toArray());
        $country = implode(DB::table('countries')->where('id', $detail->country)->pluck('name')->toArray());

    	return view('back/individual/pages/profile', compact('detail', 'user', 'documents', 'incomes', 'income', 'gender', 'doc', 'marital', 'residence', 'country', 'organization', 'groups', 'membergroups', 'wallet', 'credit_score'));
    }

    /**
     * Change User Avatar
     * @return \Illuminate\Http\Response
     */
    public function postChangeAvatar (Request $request)
    {
         // Validate the request...
        $this->validate($request, [
            'avatar' => 'required|image',
            ]);

        if($request->hasFile('avatar'))
        {
            $user = Auth::user();

            $old = $user->avatar;

            if ($old !== "avatar.png") {
                unlink(public_path('img/avatars/' . $old));
            }

            $image = $request->file('avatar');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(64, 64)->save(public_path('/img/avatars/' . $filename));

            $user->avatar = $filename;
            $user->save();
        }

        Session::flash('success', 'Your avatar was changed successfully!');
        return redirect()->back();
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->all();

        $myuser = DB::table('users')
                ->join('user_details', 'user_details.id', '=', 'users.id')
                ->where('user_details.user_id', '=', $user->id)
                ->select('users.*')
                ->first();

        $request->validate([
            'name' => 'required|string|max:255',
            'other_names' => 'required|string|max:255',
            'email' => [
                'required','string', 'email','max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'msisdn' => [
                'required',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'sometimes|confirmed',
            'city' => 'required',
            'state' => 'sometimes',
            'postal_code' => 'required',
            'address' => 'required',
            'occupation' => 'required',
            'doc_no' => [
                'required',
                Rule::unique('user_details')->ignore($myuser->id),
            ],
            'doc_type' =>'required',
        ]);

        //dd($request);

        DB::table('users')->where('id', $user->id)->update([
            'name' => $request->name,
            'other_names' => $request->other_names,
            'email' => $request->email,
            'msisdn' => $request->msisdn,
        ]);

        if ($data['password']) {
            $user->password = bcrypt($request->password);
            $user->save();
            $maildata = array(
                'name' => $user->name,
                'password' => $user->password,
                'email' => $user->email,
            );
            Mail::to($user->email)->send(new AdminPasswordChanged($maildata));
            Auth::logoutOtherDevices($user->password);
        }

        $oldId = $user->identification_document;
        if ($request->hasFile('identification_document')) {
            $file = request('identification_document');
            if (file_exists(public_path('documents/ids/' . $oldId))) {
                if (! is_null($oldId)) {
                    unlink(public_path('documents/ids/' . $oldId));
                }
            }
            $filename = strtoupper(Str::random(5)) . '.' . $file->getClientOriginalExtension();
            $user->identification_document = $filename;
            $user->save();
            $request->identification_document->move(
                base_path() . '/public/documents/ids', $filename
            );
        }

        DB::table('user_details')->where('user_id', $user->id)->update([
            'occupation' => $request->occupation,
            'income' => $request->income,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'doc_type' => $request->doc_type,
            'doc_no' => $request->doc_no,
        ]);

        Session::flash('success', 'Your details have been updated successfully');
        return redirect()->back();
    }

    public function getPreferences(Request $request)
    {
        return view('back/individual/pages/preferences');
    }

    public function transactions()
    {
        $user = Auth::user();

        $transactions = DB::table('transactions')->where('user_id', $user->id)->orderBy('id', 'desc')->get();

        return view('back/individual/transaction/index', compact('transactions', 'user'));
    }

    public function getUnreadNotifications(Request $request)
    {
        $user = $request->user();
        $notifications = $user->unreadNotifications()->paginate(10);
        return view('back/individual/pages/unreadnotifs', compact('notifications'));
    }
}
