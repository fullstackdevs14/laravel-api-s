<?php

namespace App\Listeners;

use App\Events\PartnerSendInvoiceEvent;
use App\Handlers\Invoices\InvoicesGenerator;
use App\Handlers\ToolsHandler;
use App\Partner;
use Config;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use PDF;

/**
 * Cette classe se charge de :
 * - créer une facture pdf,
 * - stocker la facture,
 * - l'envoyer par email.
 *
 * Class PartnerSendInvoiceListener
 * @package App\Listeners
 */
class PartnerSendInvoiceListener implements ShouldQueue
{
    /**
     * Injecté via le contrôleur.
     * Facade.
     * Utilisée pour créer le PDF.
     *
     * @var PDF
     */
    private $PDF;

    /**
     * Injecté via contrôleur.
     * C'est un model.
     * Il recupère les informations en base données pour les fournir à l'email.
     *
     * @var Partner
     */
    private $partner;

    /**
     * Tableau contenant les informations nécéssaire au bon fonctionnement de l'écouteur :
     * - partner_id,
     * - start_date,
     * - end_date.
     *
     * @var array
     */
    private $event;

    /**
     * Utilisé pour des questions de portée de variable au lors de l'utilisation de Mail.
     *
     * @var Partner
     */
    private $partnerForMessage;

    /**
     * Utilisé pour des questions de portée de variable au lors de l'utilisation de Mail.
     *
     * @var number
     */
    private $invoice_infoForMessage;

    /**
     * @var InvoicesGenerator
     */
    private $invoicesGenerator;

    /**
     * PartnerSendInvoiceListener constructor.
     * @param PDF $PDF
     * @param Partner $partner
     * @param InvoicesGenerator $invoicesGenerator
     */
    public function __construct
    (
        PDF $PDF,
        Partner $partner,
        InvoicesGenerator $invoicesGenerator
    )
    {
        $this->PDF = $PDF;
        $this->partner = $partner;
        $this->invoicesGenerator = $invoicesGenerator;
    }

    /**
     * Gère l'évènement.
     * Créer la factur au format pdf.
     * L'enregistre sur le serveur et l'envoi par mail.
     *
     * @param  PartnerSendInvoiceEvent $event
     * @return void
     */
    public function handle
    (
        PartnerSendInvoiceEvent $event
    )
    {
        $this->event = $event;
        $this->partnerForMessage = $this->partner->findOrFail($event->partner_id);
        $this->invoice_infoForMessage = $this->invoicesGenerator->generatePartnerInvoice($event->start_date, $event->end_date, $event->partner_id);

        Mail::send('emails.partner_invoice',
            [
                'partner' => $this->partnerForMessage,
                'invoice' => $this->invoice_infoForMessage['invoice_id'],
                'base_url' => ToolsHandler::getBaseUrl()
            ], function ($message) {
                $message->to($this->partnerForMessage->email);
                $message->subject('Facture ' . Config::get('constants.company_name') . ' du ' . $this->event->start_date . ' au ' . $this->event->end_date);
                $message->attach($this->invoice_infoForMessage['invoice_path'], ['as' => $this->invoice_infoForMessage['invoice_id'], 'mime' => 'pdf']);
            });
    }

}
