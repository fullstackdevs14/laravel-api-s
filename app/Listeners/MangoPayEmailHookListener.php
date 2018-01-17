<?php

namespace App\Listeners;

use App\Events\MangoPayHookEvent;
use Carbon\Carbon;
use Config;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

/**
 * Cette classe se charge d'envoyer un e-mail lorsque un hook a été déclenché par MangoPay.
 * L'administrateur est ainsi prévenu lorsqu'un événement inhabituel se passe du côté de l'API MangoPay.
 *
 * Class MangoPayEmailHookListener
 * @package App\Listeners
 */
class MangoPayEmailHookListener implements ShouldQueue
{
    /**
     * Les paramètres envoyés dans le mail sont :
     * - resourceId,
     * - eventType,
     * - date.
     *
     * @var
     */
    public $params;

    /**
     * Créé une instance de l'écouteur.
     *
     * MangoPayEmailHookListener constructor.
     */
    public function __construct()
    {
    }

    /**
     * Gère l'événement.
     *
     * @param MangoPayHookEvent $event
     */
    public function handle
    (
        MangoPayHookEvent $event
    )
    {
        $this->params = $event->params;
        $this->sendMail();
    }

    /**
     * Cette fonction génère l'e-mail qui est envoyé lorsque mon Copé appelle l'URL de l'API Application  lorsqu'un hook est déclenché.
     */
    private function sendMail()
    {
        Mail::send(
            'emails.mangoPay_hook',
            ['request' => $this->params,
            ], function ($message) {
            $message->to(Config::get('constants.mail_admin'))
                ->subject('/!\ HOOK ' . $this->params['eventType'] . ' ' . Carbon::createFromTimestamp($this->params['date']));
        });
    }

}