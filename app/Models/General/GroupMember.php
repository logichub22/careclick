<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    protected $table = 'group_members';

    protected $casts = [
    	'member_data' => 'array',
    ];
}
