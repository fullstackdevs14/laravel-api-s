<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Partner extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'tel',
        'ownerFirstName',
        'ownerLastName',
        'password',
        'name',
        'category',
        'address',
        'postalCode',
        'city',
        'lat',
        'lng',
        'openStatus',
        'HHStatus',
        'picture',
        'website',
        'mango_id',
        'mango_bank_id',
        'fees'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function openings(){
        return $this->hasOne('App\PartnerOpenings');
    }

    public function menu(){
        return $this->hasMany('App\PartnerMenu');
    }

    public function ordersInfoTemp(){
        return $this->hasMany('App\OrderInfoTemp');
    }

}
