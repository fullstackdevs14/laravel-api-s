<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartnerMenu extends Model
{
    use SoftDeletes;

    protected $table = 'partners_menus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'partner_id',
        'category_id',
        'name',
        'price',
        'HHPrice',
        'quantity',
        'tax',
        'alcohol',
        'ingredients',
        'availability'
    ];

    protected $hidden = [];

    public function partner(){
        return $this->belongsTo('App\Partner');
    }

}
