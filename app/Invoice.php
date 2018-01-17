<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';

    public $timestamps = true;

    protected $fillable =
        [
            'partner_id',
            'invoice_id',
            'from',
            'to'
        ];
}
