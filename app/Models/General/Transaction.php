<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function type()
    {
        return $this->belongsTo('App\Models\General\TransactionType');
    }
}
