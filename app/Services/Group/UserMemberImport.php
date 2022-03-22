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

class UserMemberImport
{
	protected $users = [];
	protected $valid = true;
	protected $errorRows = [];
	protected $validRows = [];

	public function checkImportData($rows, $header)
	{
		$emails = [];
		$numbers = [];
		$accounts = [];

		foreach ($rows as $key => $row) {
			 $row = array_combine($header, $row);

			 // check for corrtect email
			 if (!$this->checkValidEmail($row['email'])) {
			 	$row['message'] = 'Invalid Email';
			 	$this->errorRows[$key] = $row;
			 	$this->valid = false;
			 } else {
			 	$emails[] = $row['email'];
			 }
		}

		$exist = $this->checkEmailExist($emails);

		if(count($exist) > 0){
			$this->valid = false;
			$this->addEmailExistErrorMessage($exist, $header, $rows);
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

	public function createMembers($header, $rows)
	{
		foreach ($rows as $row) {
            $row = array_combine($header, $row);

            $password = $this->generatePassword(6);

            // Store Member Into Users Table;
	        $user = new User;
	        $user->name = $row['name'];
	        $user->other_names = $row['other names'];
	        $user->email = $row['email'];
	        $user->msisdn = $row['phone number'];
	        $user->password = Hash::make($password);
	        $user->status = true;
	        $user->verified = true;
	        $user->save();

            // Attach Role
        	$user->attachRole(Role::where('name','group-member')->first());

            // // Store into org_user
            // $orguser = new OrgUser;
            // $orguser->user_id = $user->id;
            // $orguser->org_id = Input::get('org_id');
            // $orguser->save();

            // Create Wallet Entry
            $wallet = new Wallet;
            $wallet->user_id = $user->id;
            $wallet->balance = 0;
            $wallet->save();

            // Store in Org Group Members
	        $member = new GroupMember;
	        $member->group_id = Input::get('grp_id');
	        $member->org_id = Input::get('g_id');
	        $member->user_id = $user->id;
	        $member->gender = ucfirst($row['gender']);
	        //$member->doc_type = $request->doc_type;
	        $member->doc_no = $row['id_no'];
	        //$member->country = ucfirst($row['country']);
	        $member->city = ucfirst($row['city']);
	        $member->address = $row['address'];
	        $member->income = $row['income'];
	        $member->occupation = ucfirst($row['occupation']);
	        $member->save();

	         $client = new \GuzzleHttp\Client;

	        // Create Customer
	        $response = $client->request('POST', 'http://104.131.174.54:7171/api/v1.0/customers', [
	            'headers' => [
	                'api-key' => 'oksSJXwSB6dz9j3qYo6qiNv2iQbubyok',
	                'Content-Type' => 'application/json'
	            ],
	            'auth' => [
	                null, 
	                'oksSJXwSB6dz9j3qYo6qiNv2iQbubyok'
	            ],
	            'body' => json_encode([
	                'email' => $user->email,
	                'phone' => $user->msisdn,
	                'first_name' => $user->name,
	                'other_name' => $user->other_names,
	                //'dob' => $newdob,
	                'gender' => strtoupper($member->gender),
	                'address' => $member->address,
	                'hometown' => $member->city,
	                'occupation' => $member->occupation,
	                //'nationality_id' => $member->country,
	            ])
	        ]);

	        $customer = json_decode($response->getBody(), true);

	        $fullname = $user->name . ' ' . $user->other_names;

	        $myres = $customer['your_response'];

	        $uid = $myres['username'];

	        DB::table('users')->where('id', $user->id)->update(['customer_username' => $uid]);        

	        //Create new account for customer
	        $res = $client->request('POST', 'http://104.131.174.54:7171/api/v1.0/accounts', [
	            'headers' => [
	                'api-key' => 'oksSJXwSB6dz9j3qYo6qiNv2iQbubyok',
	                'Content-Type' => 'application/json'
	            ],
	            'auth' => [
	                null, 
	                'oksSJXwSB6dz9j3qYo6qiNv2iQbubyok'
	            ],
	            'body' => json_encode([
	                'account_category_id' => 2,
	                'account_type_id' => 1,
	                'customer_uid' => $uid,
	                'name' => strtoupper($fullname),
	            ])
	        ]);

	        $newcus = json_decode($res->getBody(), true);
	        $newres = $newcus['your_response'];
	        $account = $newres['account_number'];

	        DB::table('users')->where('id', $user->id)->update(['account_no' => $account]);

            // Data to send to mail
	        $maildata = array(
	            'name' => $user->name,
	            'password' => $password,
	            'email' => $user->email,
	            'group' => Input::get('grp_name'),
	            'customer_username' => $uid,
            	'account_number' => $account,
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
}