<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationChecker extends Model
{
    protected $table = 'notifications_records';

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
            'orderId',
            'notification_status',
            'type'
        ];
}
