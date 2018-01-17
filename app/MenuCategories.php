<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuCategories extends Model
{
    protected $table = 'menu_categories';

    protected $fillable =
        [
        'category',
        'active'
        ];

}
