<?php

namespace App\Listeners;

use App\ApplicationUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\ApplicationUserResetPasswordEvent;

/**
 * Cette classe se charge d'envoyer l'email de réinitialisation du mots de passe.
 * Cette email contient un lien vers un fomulaire de réinitialisation.
 * Un token est passé en paramètre de ce lien.
 * Le token vit selon la valeur passé à la variable TIME_TO_LIVE_FOR_RESET_PASSWORD_LINKS présente dans la classe de la commande en console "CheckPasswordResetTimeToLive".
 *
 * Class ApplicationUserResetPasswordListener
 * @package App\Listeners
 */
class ApplicationUserResetPasswordListener implements ShouldQueue
{
    /**
     *
     * Stocke le token nécéssaire à la réinitialisation du mot de passe pour le passer à la fonction qui envoi l'email.
     * Ce token est ensuite passé dans les paramètres d'un lien permettant l'accès à un formulaire de réinitialisation du mot de passe.
     * Le lien ne sera valide que si le token est toujours stocké dans la table "application_users_reset_password".
     *
     * @var string
     */
    private $token;

    /**
     * Stocke le model ApplicationUser pour le passer à la fonction qui envoi l'email.
     *
     * @var ApplicationUser
     */
    private $applicationUser;


    /**
     * Gère l'évènement.
     *
     * @param ApplicationUserResetPasswordEvent $event
     */
    public function handle
    (
        ApplicationUserResetPasswordEvent $event
    )
    {
        $this->token = $event->token;
        $this->applicationUser = $event->applicationUser;
        $this->sendMail();
    }


    /**
     * Retourne le nom de domaine attribué à l'application.
     *
     * @return mixed
     */
    private function getBaseUrl()
    {
        return Config::get('constants.base_url');
    }

    /**
     * Envoi l'email de réinitialisation du mot de passe.
     */
    private function sendMail()
    {
        Mail::send(
            'emails.applicationUser_password_reset',
            [
                'applicationUser' => $this->applicationUser,
                'token' => $this->token,
                'base_url' => $this->getBaseUrl()
            ], function ($message) {
            $message->to($this->applicationUser->email)->subject('Réinitialisation du mot de passe.');
        });
    }


}
