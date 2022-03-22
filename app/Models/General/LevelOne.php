<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class LevelOne extends Model
{
    protected $table = 'level_one';

    public function country()
    {
        return $this->belongsTo('App\Models\General\Country');
    }

    public function levelTwo()
    {
        return $this->hasOne('App\Models\General\LevelTwo');
    }
}
