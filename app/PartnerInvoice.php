<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnerInvoice extends Model
{
    protected $table = 'partners_invoices';

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
            'partner_id',
            'invoice_id',
            'from',
            'to'
        ];
}
