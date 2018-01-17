<?php

namespace App\Events;

use App\ApplicationUser;
use Illuminate\Queue\SerializesModels;

/**
 * Cet événement est appelé quand un utilisateur a demandé un renouvellement de mot de passe.
 * Il fournit les informations nécessaires au bon fonctionnement de l'écouteur « ApplicationUserPassword ».
 *
 * Class ApplicationUserResetPasswordEvent
 * @package App\Events
 */
class ApplicationUserResetPasswordEvent
{
    use SerializesModels;

    /**
     * Inject via le contrôleur.
     * C'est un model.
     * Il fournit les informations nécessaires à l'écouteur « ApplicationUserPasswordResetListener ».
     *
     * Is the applicationUser asking for the new password.
     *
     * @var ApplicationUser
     * @type ApplicationUser
     */
    public $applicationUser;

    /**
     * Cette chaîne de caractères est le token enregistré dans la table « application_users_reset_password ».
     * Elle est nécessaire à la soumission d'un nouveau mot de passe.
     * Elle est envoyée à l'utilisateur via l'écouteur « ApplicationUserPasswordResetListener ».
     *
     * @var token
     * @type string
     */
    public $token;

    /**
     *  Créer une nouvelle instance l'événement et l'envoie à l'écouteur « ApplicationUserPasswordResetListener ».
     *
     * ApplicationUserResetPasswordEvent constructor.
     * @param ApplicationUser $applicationUser
     * @param $token
     */
    public function __construct
    (
        ApplicationUser $applicationUser,
        $token
    )
    {
        $this->applicationUser = $applicationUser;
        $this->token = $token;
    }

}
