<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $table = 'incidents';

    public $timestamps = true;

    protected $fillable =
        [
            'id',
            'order_id',
            'excuse',
            'status'
        ];

    public function ordersInfo(){
        return $this->belongsTo('App\OrderInfo');
    }

}
