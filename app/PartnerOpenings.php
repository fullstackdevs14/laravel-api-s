<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnerOpenings extends Model
{

    protected $table = 'partners_openings';

    protected $fillable = [
        'monday1',
        'monday2',
        'monday3',
        'monday4',
        'tuesday1',
        'tuesday2',
        'tuesday3',
        'tuesday4',
        'wednesday1',
        'wednesday2',
        'wednesday3',
        'wednesday4',
        'thursday1',
        'thursday2',
        'thursday3',
        'thursday4',
        'friday1',
        'friday2',
        'friday3',
        'friday4',
        'saturday1',
        'saturday2',
        'saturday3',
        'saturday4',
        'sunday1',
        'sunday2',
        'sunday3',
        'sunday4'];
    
    protected $hidden = [
        'partner_id'
    ];

    public function partner()
    {
        return $this->belongsTo('App\Partner');
    }
}
