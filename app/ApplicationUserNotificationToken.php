<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationUserNotificationToken extends Model
{
    protected $table = 'application_users_notification_token';

    protected $fillable = [
        'applicationUser_id',
        'notificationToken'];

    public function applicationUser()
    {
        return $this->belongsTo('App\ApplicationUser', 'applicationUser_id');
    }
    
}
