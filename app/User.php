<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use LaratrustUserTrait;
    use HasApiTokens;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'other_names', 'email', 'password', 'status', 'verified', 'token', 'msisdn', 'avatar', 'customer_username',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     *
     * Boot the model
     *
     */

    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->token = Str::random(40);
        });
    }

    /**
     *
     * Function responsible for verifying user after openning mail sent
     *
     */

    public function hasVerified()
    {
        $this->verified = true;
        $this->status = true;
        $this->token = null;

        $this->save();
    }

    public function organization()
    {
        return $this->hasOne('App\Models\Organization\Organization', 'admin_id');
    }

    public function wallet()
    {
        return $this->hasOne('App\Models\General\Wallet');
    }

    public function detail()
    {
        return $this->hasOne('App\Models\General\UserDetail');
    }

    public function logs()
    {
        return $this->hasMany('App\Models\General\AccessLog');
    }

    public function isFirstSource()
    {
        return \in_array($this->email, [env('FIRSTSOURCE_EMAIL'), env('FIRSTSOURCE_EMAIL2')]);
    }
}
