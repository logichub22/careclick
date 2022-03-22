<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

class OrganizationDetail extends Model
{
   	protected $table = 'organization_details';

   	public function organization()
   	{
   		return $this->belongsTo('App\Models\Organization\Organization', 'org_id');
   	}

   	public function project()
    {
    	return $this->hasOne('App\Models\Organization\Project');
    }
}
