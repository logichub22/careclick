<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
