<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationUserResetPassword extends Model
{

    protected $table = 'application_users_reset_password';

    protected $fillable = [
        'applicationUser_id',
        'token'
    ];

}
