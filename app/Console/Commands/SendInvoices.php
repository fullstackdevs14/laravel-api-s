<?php

namespace App\Console\Commands;

use App\Events\PartnerSendInvoiceEvent;
use App\Partner;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Cette class est un commande en console dont le but est de déclencher la création, le stockage et l'envoi par mail d'une facture pour les partenaires.
 *
 * Class SendInvoices
 * @package App\Console\Commands
 */
class SendInvoices extends Command
{
    /**
     * Le nom et la signature la commande en console.
     *
     * @var string
     */
    protected $signature = 'send:invoices';

    /**
     * La description de la commande en console.
     *
     * @var string
     */
    protected $description = 'Envoi les factures aux partenaires actifs.';

    /**
     * Injecté via le contrôleur.
     * C'est un modélè.
     * Il est ici utilisé pour lister l'ensemble des partenaires pour déclencher un envoi de factures.
     *
     * @var Partner
     */
    private $partner;

    /**
     * Créer une instance de la commande.
     *
     * SendInvoices constructeur.
     * @param Partner $partner
     */
    public function __construct
    (
        Partner $partner
    )
    {
        parent::__construct();
        $this->partner = $partner;
    }

    /**
     * Execute la commande en console.
     *
     * Selectionne tous les partenaires actifs et récupère les ids.
     * Pour chaque, déclenche l'évènement PartnerSendInvoice, qui envoi la facture par mail et la stocke sur le serveur.
     *
     * @return mixed
     */
    public function handle()
    {
        $partners = $this->partner
            ->where('activated', 1)
            ->get(['id'])
            ->toArray();

        if ($partners != []) {
            foreach ($partners as $partner) {
                event(
                    new PartnerSendInvoiceEvent($partner['id'],
                        (new Carbon('first day of last month'))->toDateString(),
                        (new Carbon('last day of last month'))->toDateString()
                    ));
            }
        }
    }

}
