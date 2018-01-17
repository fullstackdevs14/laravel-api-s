<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class ApplicationUser extends Authenticatable
{

    protected $table = 'application_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'tel',
        'birthday',
        'picture',
        'password',
        'active_payment',
        'mango_id',
        'mango_card_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'activated',
    ];

    public function notificationToken(){
        return $this->hasOne('App\ApplicationUserNotificationToken', 'applicationUser_id');
    }

    public function emailConfirmationToken(){
        return $this->hasOne('App\ApplicationUserEmailConfirmation', 'applicationUser_id');
    }

    public function ordersInfo(){
        return $this->hasMany('App\OrderInfo', 'applicationUser_id');
    }
}
