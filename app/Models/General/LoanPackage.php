<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class LoanPackage extends Model
{
    protected $table = 'loan_packages';

    protected $casts = [
        'extra_info' => 'array',
    ];
}
