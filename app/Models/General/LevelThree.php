<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class LevelThree extends Model
{
    protected $table = 'level_three';

    public function levelTwo()
    {
        return $this->belongsTo('App\Models\General\LevelTwo');
    }

    public function levelFour()
    {
        return $this->hasOne('App\Models\General\LevelFour');
    }
}
