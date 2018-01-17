<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $table = 'refunds';

    public $timestamp = true;

    protected $fillable =
        [
            'applicationUser_id',
            'partner_id',
            'order_id',
            'incident_id',
            'amount',
            'success',
            'description',
            'mango_refund_id',
        ];

    public function ordersInfo(){
        return $this->belongsTo('App\OrderInfo');
    }

    public function incident(){
        return $this->belongsTo('App\Incident');
    }
}
