<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class PartnerSendInvoiceEvent
{
    use SerializesModels;

    /**
     * Sert à identifier le partenaire auquel on envoi une facture.
     *
     * @var id du partenaire.
     */
    public $partner_id;

    /**
     * Date des premiers éléments affichés sur la facture.
     *
     * @var date
     */
    public $start_date;

    /**
     * Date des derniers éléments affichés sur la facture.
     *
     * @var date
     */
    public $end_date;

    /**
     * Créer une nouvelle instance de l'évènement.
     *
     * PartnerSendInvoiceEvent constructor.
     * @param $partner_id
     * @param $start_date
     * @param $end_date
     */
    public function __construct
    (
        $partner_id,
        $start_date,
        $end_date
    )
    {
        $this->partner_id = $partner_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

}
