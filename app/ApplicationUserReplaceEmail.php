<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationUserReplaceEmail extends Model
{
    protected $table = 'application_users_email_replace';

    protected $fillable = [
        'applicationUser_id',
        'email',
        'token'
    ];
}
