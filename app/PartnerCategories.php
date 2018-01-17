<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnerCategories extends Model
{
    protected $table = 'partner_categories';

    protected $fillable = [
        'category'
    ];
}
