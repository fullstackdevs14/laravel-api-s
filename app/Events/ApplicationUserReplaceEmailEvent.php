<?php

namespace App\Events;

use App\ApplicationUser;
use Illuminate\Queue\SerializesModels;

class ApplicationUserReplaceEmailEvent
{
    use SerializesModels;

    /**
     * C'est un model.
     *
     * @var ApplicationUser
     */
    public $applicationUser;

    /**
     * Cet email est l'email de remplacement. Il est utilisé pour l'envoi d'un email de confirmation du nouvel email.
     *
     * @var string
     */
    public $email;

    /**
     * Token utilisé pour identification du changement d'email d'un utilisateur.
     *
     * @var string
     */
    public $token;

    /**
     * Create a new event instance.
     *
     * ApplicationUserReplaceEmail constructor.
     * @param ApplicationUser $applicationUser
     * @param $email
     * @param $token
     */
    public function __construct
    (
        ApplicationUser $applicationUser,
        $email,
        $token
    )
    {
        $this->applicationUser = $applicationUser;
        $this->email = $email;
        $this->token = $token;
    }

}
