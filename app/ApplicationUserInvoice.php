<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationUserInvoice extends Model
{
    protected $table = 'application_users_invoices';

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
            'order_id',
            'invoice_id',
        ];
}
