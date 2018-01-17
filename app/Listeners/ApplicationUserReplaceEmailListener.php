<?php

namespace App\Listeners;

use App\ApplicationUser;
use App\Handlers\ToolsHandler;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

/**
 * Cette classe se charge d'envoyer l'email de confirmation du nouvel email renseigné.
 *
 * Class ApplicationUserReplaceEmail
 * @package App\Listeners
 */
class ApplicationUserReplaceEmailListener implements ShouldQueue
{
    /**
     * C'est un model.
     *
     * @var ApplicationUser
     */
    private $applicationUser;

    /**
     * Nouvel email.
     *
     * @var string
     */
    private $email;

    /**
     * Token de confirmation du nouvel email.
     *
     * @var string
     */
    private $token;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     */
    public function handle
    (
        $event
    )
    {
        $this->applicationUser = $event->applicationUser;
        $this->email = $event->email;
        $this->token = $event->token;
        $this->sendMail();
    }

    /**
     * Envoi l'email de confirmation du mot de passe.
     */
    private function sendMail()
    {
        Mail::send(
            'emails.applicationUser_email_replace',
            [
                'applicationUser' => $this->applicationUser,
                'token' => $this->token,
                'base_url' => ToolsHandler::getBaseUrl()
            ], function ($message) {
            $message->to($this->email)->subject('Confirmation du nouvel email.');
        });
    }

}
