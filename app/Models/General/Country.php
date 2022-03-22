<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public function levelOne()
    {
        return $this->hasOne('App\Models\General\LevelOne');
    }

    public function administrativeRegion()
    {
        return $this->hasOne('App\Models\General\AdminstrativeRegion');
    }
}
