<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $table = 'payouts';

    public $timestamps = true;

    protected $fillable =
        [
            'partner_id',
            'amount',
            'success',
            'description',
            'mango_payout_id'
        ];

}
