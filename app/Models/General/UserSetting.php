<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $table = 'user_settings';

    protected $casts = [
    	'available_settings' => 'array',
    ];
}
