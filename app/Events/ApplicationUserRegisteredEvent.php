<?php

namespace App\Events;

use App\ApplicationUser;
use Illuminate\Queue\SerializesModels;

/**
 * Cet événement est déclenché quand un utilisateur vient de s'inscrire via le client de l'utilisateur.
 * Il donne les informations nécessaires au bon fonctionnement de l'écouteur « ApplicationUserEmailActivation ».
 *
 * Class ApplicationUserRegisteredEvent
 * @package App\Events
 */
class ApplicationUserRegisteredEvent
{
    use SerializesModels;

    /**
     * Injecté il y a le contrôleur.
     * C'est un model.
     * Il fournit les informations nécessaires au bon fonctionnement de l'écouteur « ApplicationUserEmailActivation  ».
     *
     * @var ApplicationUser
     * @type ApplicationUser
     */
    public $applicationUser;

    /**
     * Créer une nouvelle instance de l'événement qui est utilisé par l'écouteur « ApplicationUserEmailActivation ».
     *
     * ApplicationUserRegisteredEvent constructor.
     * @param ApplicationUser $applicationUser
     */
    public function __construct
    (
        ApplicationUser $applicationUser
    )
    {
        $this->applicationUser = $applicationUser;
    }

}
