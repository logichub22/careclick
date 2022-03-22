<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class AdministrativeRegion extends Model
{
    public function country()
    {
        return $this->belongsTo('App\Models\General\Country');
    }
}
