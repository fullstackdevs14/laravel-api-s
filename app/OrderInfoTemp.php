<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderInfoTemp extends Model
{
    protected $table = 'orders_info_temp';

    public $timestamps = ["created_at"];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'orderId',
        'applicationUser_id',
        'partner_id',
        'application_user_id_share_bill'
    ];

    public function ordersInfo()
    {
        return $this->belongsTo('App\OrderInfo', 'order_id');
    }

    public function applicationUser()
    {
        return $this->belongsTo('ApplicationUser\OrderInfo', 'applicationUser_id');
    }

    public function partner()
    {
        return $this->belongsTo('Partner\OrderInfo', 'partner_id');
    }

}
