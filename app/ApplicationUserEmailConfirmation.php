<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationUserEmailConfirmation extends Model
{
    protected $table = 'application_users_email_validation';
    public $primary = 'token';

    protected $fillable = [
        'applicationUser_id',
        'token'
    ];

    public function applicationUser()
    {
        return $this->belongsTo('App\ApplicationUser');
    }
}
