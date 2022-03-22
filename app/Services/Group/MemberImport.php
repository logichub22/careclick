<?php

namespace App\Services\Group;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Models\General\Role;
use App\Models\General\Wallet;
use App\Models\Organization\OrgUser;
use App\Models\General\GroupMember;
use Illuminate\Support\Facades\Mail;
use App\Mail\GroupMemberCreated;
use Auth;
use App\Models\General\UserDetail;

class MemberImport
{
	protected $users = [];
	protected $valid = true;
	protected $errorRows = [];
	protected $validRows = [];

	public function checkImportData($rows, $header)
	{
		$emails = [];
		$phones = [];

		foreach ($rows as $key => $row) {
			 $row = array_combine($header, $row);

			 // check for correct email
			 if (!$this->checkValidEmail($row['email'])) {
			 	$row['message'] = 'Invalid Email';
			 	$this->errorRows[$key] = $row;
			 	$this->valid = false;
			 } else {
				 $emails[] = $row['email'];
				 $phones[] = $row['msisdn'];
			 }

			//  // check for correct phone
			//  if (!$this->checkValidEmail($row['email'])) {
			//  	$row['message'] = 'Invalid Email';
			//  	$this->errorRows[$key] = $row;
			//  	$this->valid = false;
			//  } else {
			//  	$emails[] = $row['email'];
			//  }
		}

		$exist = $this->checkEmailExist($emails);
		$phoneExist = $this->checkPhoneExist($phones);

		if(count($exist) > 0){
			$this->valid = false;
			$this->addEmailExistErrorMessage($exist, $header, $rows);
		}

		if(count($phoneExist) > 0){
			$this->valid = false;
			$this->addPhoneExistErrorMessage($phoneExist, $header, $rows);
		}

		return $this->valid;
	}

	public function getErrorRows()
	{
		return $this->errorRows;
	}

	private function checkValidEmail($email)
	{
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return false;
		}
		return true;
	}

	private function checkEmailExist($emails)
	{
		return User::whereIn('email', $emails)->get()->pluck('email')->toArray();
	}

	private function checkPhoneExist($phones)
	{
		return User::whereIn('msisdn', $phones)->get()->pluck('msisdn')->toArray();
	}

	private function addEmailExistErrorMessage($exist, $header, $rows)
	{
		foreach ($rows as $key => $row) {
			$row = array_combine($header, $row);

			if (in_array($row['email'], $exist)) {
				$row['message'] = 'Email exists';
				$this->errorRows[$key] = $row;
			}
		}

		return $rows;
	}

	private function addPhoneExistErrorMessage($phoneExist, $header, $rows)
	{
		foreach ($rows as $key => $row) {
			$row = array_combine($header, $row);

			if (in_array($row['msisdn'], $phoneExist)) {
				$row['message'] = 'Phone number exists';
				$this->errorRows[$key] = $row;
			}
		}

		return $rows;
	}

	public function createMembers($header, $rows)
	{
		$data = DB::table('users')
                ->join('user_details', 'user_details.id', '=', 'users.id')
                ->where('user_details.user_id', '=', Auth::user()->id)
                ->select('user_details.org_id')
				->first();

		foreach ($rows as $row) {
            $row = array_combine($header, $row);

            $password = $this->generatePassword(6);

            // Store Member Into Users Table;
	        $user = new User;
	        $user->name = $row['name'];
	        $user->other_names = $row['other_names'];
	        $user->email = $row['email'];
	        $user->msisdn = $row['msisdn'];
	        $user->password = Hash::make($password);
	        $user->status = true;
	        $user->verified = true;
	        $user->save();

            // Attach Role
        	$user->attachRole(Role::where('name','group-member')->first());


            // Create Wallet Entry
            $wallet = new Wallet;
            $wallet->user_id = $user->id;
            $wallet->balance = 0;
            $wallet->save();

			// Store in Org Group Members
			$member = new GroupMember;
			$member->group_id = request('group_id');
			$member->user_id = $user->id;
			// dd($member, $data);
			$member->save();

			$detail = $this->createDetail($row, $data);

			DB::table('user_details')->where('id', $detail->id)->update([
				'user_id' => $user->id,
			]);

            // Data to send to mail
	        $maildata = array(
	            'name' => $user->name,
	            'password' => $password,
	            'email' => $user->email,
	            'group' => request('grp_name'),
							'admin' => Auth::user()->id
	           // 'customer_username' => $uid,
            	//'account_number' => $account,
	        );

	        // Send Mail Here
	        Mail::to($user->email)->send(new GroupMemberCreated($maildata));
        }
	}

	public function generatePassword( $length = 6 )
    {
        $nums = "123456789";
        $password = substr( str_shuffle( $nums ), 0, $length );
        return $password;
	}

	public function createDetail($row, $user)
	{
		$data = DB::table('users')
                ->join('user_details', 'user_details.id', '=', 'users.id')
                ->where('user_details.user_id', '=', Auth::user()->id)
                ->select('user_details.org_id')
				->first();

		$detail = new UserDetail();
		$detail->user_id = 1;
		if(is_null($data)) {} else {$detail->org_id = $data->org_id;}
		$detail->gender = $row['gender'];
		$detail->doc_type = $row['doc_type'];
		$detail->dob = date("Y-m-d", strtotime($row['dob']));
		$detail->doc_no = $row['doc_no'];
		$detail->country = $row['country'];
		$detail->city = $row['city'];
		$detail->address = $row['address'];
		$detail->income = $row['income'];
		$detail->occupation = $row['occupation'];
		$detail->residence = $row['residence'];
		$detail->marital_status = $row['marital_status'];
		$detail->postal_code = $row['postal_code'];
		$detail->save();

		return $detail;
	}
}
