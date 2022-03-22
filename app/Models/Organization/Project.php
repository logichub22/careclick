<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function organization()
    {
        return $this->belongsTo('App\Models\Organization\OrganizationDetail');
    }
}
