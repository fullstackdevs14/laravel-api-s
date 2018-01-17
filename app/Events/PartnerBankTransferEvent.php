<?php

namespace App\Events;

use App\Partner;
use Illuminate\Queue\SerializesModels;

/**
 * Cette class sert à déclencher l'email informant le partenaire qu'un tranfert a été fait à partir de son porte monnaie virtuel vers son compte en banque.
 *
 * Class PartnerBankTransferEvent
 * @package App\Events
 */
class PartnerBankTransferEvent
{
    use SerializesModels;


    /**
     * Injecté via le contrôleur.
     * C'est un model.
     * Correspond au partenaire auquel on fait le versement.
     *
     * @var Partner
     */
    public $partner;

    /**
     * Correspond au montant transfèrer sur le compte bancaire du partenaire.
     *
     * @var number
     */
    public $amount;

    /**
     * Créé une nouvelle instance de l'évènement.
     *
     * PartnerBankTransferEvent consctructeur.
     * @param Partner $partner
     * @param $amount
     */
    public function __construct
    (
        Partner $partner,
        $amount
    )
    {
        $this->partner = $partner;
        $this->amount = $amount;
    }

}


