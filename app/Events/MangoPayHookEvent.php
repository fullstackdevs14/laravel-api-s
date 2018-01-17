<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

/**
 * Cet événement est appelé et envoie un e-mail quand un hook de la plate-forme Mangopay a été déclenché et a appelé l'URL dédiée de l'API Application.
 * Voir : App\Http\Controllers\Back_office\Activities\MangoPay\HooksController@triggerErrorEvent
 *
 * Class MangoPayHookEvent
 * @package App\Events
 */
class MangoPayHookEvent
{
    use SerializesModels;

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
     * Créer une nouvelle instance de l'événement et envoie celle-ci à l'écouteur « MangoPayEmailHookListener ».
     * Attention : ne pas oublier que les événements sont liés aux écouteurs via le provider « EventServiceProvider » de LARAVEL.
     *
     * MangoPayHookEvent constructeur.
     * @param $params
     */
    public function __construct
    (
        $params
    )
    {
        $this->params = $params;
    }

}
