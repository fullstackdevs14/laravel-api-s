<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    //protected $table = 'orders';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'orderId',
        'category_id',
        'itemName',
        'itemPrice',
        'itemHHPrice',
        'tax',
        'alcohol',
        'quantity',
    ];

    public function order(){
        return $this->belongsTo('App\OrderInfo');
    }
}