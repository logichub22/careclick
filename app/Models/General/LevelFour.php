<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class LevelFour extends Model
{
    protected $table = 'level_four';

    public function levelThree()
    {
        return $this->belongsTo('App\Models\General\LevelThree');
    }
}
