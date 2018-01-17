<?php

namespace App\Listeners;


use App\Handlers\ToolsHandler;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\ApplicationUser;
use Illuminate\Support\Facades\Log;
use App\ApplicationUserEmailConfirmation;
use App\Events\ApplicationUserRegisteredEvent;

/**
 * Cette classe est utilisée pour envoyer un mail lors vérification de l'email lors de l'inscription d'un utilisateur.
 *
 * Class ApplicationUserEmailActivationListener
 * @package App\Listeners
 */
class ApplicationUserEmailActivationListener implements ShouldQueue
{
    /**
     * Injecté via contrôleur.
     * C'est un model.
     * Transmis via l'évènement. Il fournit des informations nécéssaires au bon envoi de l'email.
     *
     * @var ApplicationUser
     */
    private $applicationUser;

    /**
     * Injecté via contrôleur.
     * C'est un model.
     *
     *
     * @var ApplicationUserEmailConfirmation
     */
    private $applicationUserEmailConfirmation;

    /**
     * ApplicationUserEmailActivationListener constructeur.
     * @param ApplicationUserEmailConfirmation $applicationUserEmailConfirmation
     */
    public function __construct
    (
        ApplicationUserEmailConfirmation $applicationUserEmailConfirmation
    )
    {
        $this->applicationUserEmailConfirmation = $applicationUserEmailConfirmation;
    }

    /**
     * Gère l'évènement.
     *
     * @param ApplicationUserRegisteredEvent $event
     */
    public function handle(ApplicationUserRegisteredEvent $event)
    {
        $this->applicationUser = $event->applicationUser;
        $this->token = $this->makeToken();
        $this->registerToken();
        $this->sendMail();
    }

    /**
     * Créer un token.
     *
     * @return string
     */
    private function makeToken()
    {
        return bin2hex(random_bytes(60));
    }

    /**
     * Enregistre le token dans la base de données application.
     */
    private function registerToken()
    {
        $token = new ApplicationUserEmailConfirmation([
            'token' => $this->token,
            'applicationUser_id' => $this->applicationUser->id
        ]);

        $token->save();
    }

    /**
     * Obtient l'url de base renseignées dans le .env.
     *
     * @return mixed
     */
    private function getBaseUrl()
    {
        return Config::get('constants.base_url');
    }

    /**
     * Envoi le mail de vérification de l'email.
     */
    private function sendMail()
    {
        Mail::send('emails.email_validation',
            ['url_token' => 'api/emailConfirmationToken/' . $this->token,
                'applicationUser_name' => $this->applicationUser->firstName,
                'base_url' => ToolsHandler::makeToken(200)
            ], function ($message) {
                $message->to($this->applicationUser->email)->subject('Validation de l\'adresse email - ' . Config::get('constants.company_name'));
            });

        Log::info('success');
    }

}
