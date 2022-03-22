<?php

namespace App\Traits\Docs;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Models\General\Role;
use App\Models\General\Wallet;
use App\Models\Organization\OrgUser;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedEmail;

trait Upload
{
	private function uploadOrgDocs($id)
	{
		$permit = $id . '.' . 
        Input::file('permit')->getClientOriginalExtension();

        Input::file('permit')->move(
            base_path() . '/public/documents/permit', $permit
        );

        $tax = $id . '.' . 
        Input::file('tax')->getClientOriginalExtension();

        Input::file('tax')->move(
            base_path() . '/public/documents/tax', $tax
        );
	}
}