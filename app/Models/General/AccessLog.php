<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    protected $table = 'access_logs';

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
