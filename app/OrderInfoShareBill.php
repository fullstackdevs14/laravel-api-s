<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderInfoShareBill extends Model
{
    use SoftDeletes;

    protected $table = 'orders_info_share_bill';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'applicationUser_id_1',
        'applicationUser_id_2',
        'partner_id',
        'orderId',
        'order_id'
    ];
}
