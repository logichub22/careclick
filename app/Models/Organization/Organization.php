<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    public function detail()
    {
    	return $this->hasOne('App\Models\Organization\OrganizationDetail', 'org_id');
    }

    public function admin()
    {
    	return $this->belongsTo('App\User', 'admin_id');
    }
}
