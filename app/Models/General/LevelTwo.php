<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class LevelTwo extends Model
{
    protected $table = 'level_two';

    public function levelOne()
    {
        return $this->belongsTo('App\Models\General\LevelOne');
    }

    public function levelThree()
    {
        return $this->hasOne('App\Models\General\LevelThree');
    }
}
