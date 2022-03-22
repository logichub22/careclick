<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['name', 'description', 'status'];
}
