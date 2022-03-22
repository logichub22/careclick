<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    public function transactions()
    {
        return $this->hasMany('App\Models\General\Transaction');
    }
}
