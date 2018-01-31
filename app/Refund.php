<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $table = 'refunds';

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

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
