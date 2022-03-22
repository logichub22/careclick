<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $casts = [
        'group_data' => 'array',
        'group_settings' => 'array',
    ];
}
