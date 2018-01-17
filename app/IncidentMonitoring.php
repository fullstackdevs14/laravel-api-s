<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncidentMonitoring extends Model
{
    protected $table = 'incidents_monitoring';

    public $timestamp = true;

    protected $fillable =
        [
            'order_id',
            'message',
            'phone',
            'email',
            'reimburse'
        ];

    public function ordersInfo(){
        return $this->belongsTo('App\OrderInfo');
    }
}
