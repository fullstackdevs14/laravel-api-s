<?php

namespace App\Repositories;

use App\PartnerOpenings;

/**
 * Cette classe sert de dépôt et gère les opérations courantes pour le model "PartnerOpenings".
 *
 * Class PartnerOpeningsRepository
 * @package App\Repositories
 */
class PartnerOpeningsRepository
{
    /**
     * C'est un model.
     *
     * @var PartnerOpenings
     */
    private $partnerOpenings;

    /**
     * PartnerOpeningsRepository constructor.
     * @param PartnerOpenings $partnerOpenings
     */
    public function __construct
    (
        PartnerOpenings $partnerOpenings
    )
    {
        $this->partnerOpenings = $partnerOpenings;
    }

    /**
     * Cette fonction créer das horaires d'ouverture par défaut lors de la créaton d'un nouveau partenaire.
     *
     * @param $partner_id
     */
    public function createDefaultOpenings
    (
        $partner_id
    )
    {
        $openings =  new PartnerOpenings;
        $openings->partner_id = $partner_id;
        $openings->monday1 = 'Fermé';
        $openings->monday4 = 'Fermé';
        $openings->tuesday1 = 'Fermé';
        $openings->tuesday4 = 'Fermé';
        $openings->wednesday1 = 'Fermé';
        $openings->wednesday4 = 'Fermé';
        $openings->thursday1 = 'Fermé';
        $openings->thursday4 = 'Fermé';
        $openings->friday1 = 'Fermé';
        $openings->friday4 = 'Fermé';
        $openings->saturday1 = 'Fermé';
        $openings->saturday4 = 'Fermé';
        $openings->sunday1 = 'Fermé';
        $openings->sunday4 = 'Fermé';

        $openings->save();
    }

}