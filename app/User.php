<?php

namespace Greenalert;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username', 'password',
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
     * ROLES
     * -----------
     * 0: Unassigned user
     * 1: Super Administrator
     * 2: Suspended
     * 3: Deleted
     */

    public function sync()
    {
        return $this->hasMany('Greenalert\Sync');
    }

    public function subscriptions()
    {
        return $this->hasMany('Greenalert\Subscription');
    }

}
