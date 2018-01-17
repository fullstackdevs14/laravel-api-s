<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Cet événement est appelé quand les commandes valides de la table « orders_info » sont modifiées.
 * Elles récupèrent les commandes non traitées par le partenaire et non expirées et les envoi aux partenaires via les outils Socket.io et laravel-echo-server.
 *
 * Class OrderEvent
 * @package App\Events
 */
class OrderEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Les paramètres envoyé via le server de web socket sont :
     * - la liste préparée des commandes pour le client du partenaire,
     * - le partner_id.
     *
     * @var
     */
    private $data;

    /**
     * OrderEvent constructeur.
     * @param $data
     */
    public function __construct
    (
        $data
    )
    {
        $this->data = $data;
    }

    /**
     * Cette fonction diffuse sur le canal privé : partner-"partner_id"  les informations nécessaires à la récupération des commandes en cours par le client du partenaire.
     * Le client du partenaire écoute ce canal à travers le port 6001 en localhost sur el serveur (API et webapp du partenaire sont sur la même machine).
     *
     */
    public function broadcastOn()
    {
        return new PrivateChannel('partner-' . $this->data['partner_id']);
    }

    /**
     * @return @var data
     */
    public function broadcastWith()
    {
        return $this->data;
    }

}
