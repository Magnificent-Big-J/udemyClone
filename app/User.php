<?php

namespace App;

use App\Entities\Learning;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Redis;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use Notifiable, Learning, Billable;
    protected $with = ['subscriptions'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','username','confirm_token'
    ];
    public function isConfirmed()
    {
        return $this->confirm_token == null;
    }

    public function confirmed()
    {
        $this->confirm_token = null;
        $this->save();
    }
    public function isAdmin()
    {
        return in_array($this->email, config('elearning.administrators'));
    }

    public function getRouteKeyName()
    {
        return 'username';
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
