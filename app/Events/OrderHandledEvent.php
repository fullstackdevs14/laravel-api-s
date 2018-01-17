<?php

namespace App\Events;

use App\Partner;
use App\OrderInfo;
use App\ApplicationUser;
use Illuminate\Queue\SerializesModels;

/**
 * Cet événement est appelé quand une commande est terminée, c'est-à-dire soit :
 * – elle a été acceptée,
 * – elle a été déclinée,
 * – elle a expirée.
 *
 *  Cet événement donne les informations nécessaires à la bonne exécution de l'écouteur « ApplicationUserEmailPurchase » pour informer l'utilisateur du statut de sa commande.
 *
 * Class OrderHandledEvent
 * @package App\Events
 */
class OrderHandledEvent
{
    use SerializesModels;

    /**
     * Injecté via le contrôleur.
     * C'est un model.
     * Il fournit les informations nécessaires à la bonne exécution de l'écouteur « ApplicationUserEmailPurchase ».
     *
     * @type ApplicationUser
     */
    public $applicationUser;

    /**
     * Injecté via le contrôleur.
     * C'est un model.
     * Il fournit les informations nécessaires à la bonne exécution de l'écouteur « ApplicationUserEmailPurchase ».
     *
     * @var Partner
     * @type Partner
     */
    public $partner;

    /**
     * Injecté via le contrôleur.
     * C'est un model.
     * Il fournit les informations nécessaires à la bonne exécution de l'écouteur « ApplicationUserEmailPurchase ».
     *
     * @var OrderInfo
     * @type OrderInfo
     */
    public $orderInfo;

    /**
     * Créer une nouvelle instance de l'événement qui est utilisé par l'écouteur « ApplicationUserEmailPurchase ».
     *
     * OrderHandledEvent constructeur
     * @param ApplicationUser $applicationUser
     * @param Partner $partner
     * @param OrderInfo $orderInfo
     */
    public function __construct
    (
        ApplicationUser $applicationUser,
        Partner $partner,
        OrderInfo $orderInfo
    )
    {
        $this->applicationUser = $applicationUser;
        $this->partner = $partner;
        $this->orderInfo = $orderInfo;
    }

}
