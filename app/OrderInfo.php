<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderInfo extends Model
{
    protected $table = 'orders_info';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'applicationUser_id',
        'partner_id',
        'orderId',
        'HHStatus',
        'accepted',
        'delivered',
        'incident',
        'fees',
        'applicationUser_id_share_bill'
    ];

    public function partner()
    {
        return $this->belongsTo('App\Partner', 'partner_id');
    }

    public function applicationUser()
    {
        return $this->belongsTo('App\ApplicationUser', 'applicationUser_id');
    }

    //used
    public function ordersInfo()
    {
        return $this->hasOne('App\OrderInfoTemp', 'order_id');
    }

    //used
    public function items()
    {
        return $this->hasMany('App\Order', 'order_id');
    }

    //used
    public function incident()
    {
        return $this->hasOne('App\Incident', 'order_id');
    }

    public function memories()
    {
        return $this->hasMany('App\IncidentMonitoring', 'order_id');
    }
}
