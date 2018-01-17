<?php

namespace App\Listeners;

use Excel;
use App\OrderInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use App\Events\PartnerBankTransferEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Cet écouteur a pour but de générer l'e-mail qui vient avec le transfert d'argent du porte-monnaie virtuel du partenaire vers son compte bancaire.
 * Un fichier Excel récapitulant les commandes des 30 derniers jours est également joint à cet e-mail.
 *
 * Class PartnerBankTransferListener
 * @package App\Listeners
 */
class PartnerBankTransferListener implements ShouldQueue
{
    /**
     * Récuperé vie l'évènement.
     * C'est un modèle.
     * Correspond au partenaire auquel on fait le versement.
     *
     * @var Partner
     */
    private $partner;

    /**
     * Récuperé vie l'évènement.
     * Correspond au montant transfèrer sur le compte bancaire du partenaire.
     *
     * @var number
     */
    private $amount;

    /**
     * Injecté via le contrôleur.
     * C'est un modèle.
     * Il est utilisé pour la création du ficier excel joint au mail envoyé.
     *
     * @var OrderInfo
     */
    private $orderInfo;

    /**
     * Injecté via le contrôleur.
     * Cette librairie est utilisée pour créer le fichier excel.
     *
     * @var Carbon
     */
    private $carbon;

    /**
     * Créé une instance de l'écouteur.
     *
     * PartnerBankTransferListener construteur.
     * @param Carbon $carbon
     * @param OrderInfo $orderInfo
     */
    public function __construct
    (
        Carbon $carbon,
        OrderInfo $orderInfo
    )
    {
        $this->carbon = $carbon;
        $this->orderInfo = $orderInfo;
    }

    /**
     * Gère l'événement.
     *
     * @param  PartnerBankTransferEvent $event
     * @return void
     */
    public function handle
    (
        PartnerBankTransferEvent $event
    )
    {
        $this->partner = $event->partner;
        $this->amount = $event->amount;
        $this->sendMail();
    }

    /**
     * Cette fonction récupère la base URL renseignée dans les constantes de l'application.
     *
     * @return mixed
     */
    public function getBaseUrl()
    {
        return Config::get('constants.base_url');
    }

    /**
     * Cette fonction génère le fichier Excel envoyer avec le mail au partenaire.
     *
     * @return mixed
     */
    private function genFile()
    {
        $file = Excel::create('Historique des commandes ' . $this->carbon->now('Europe/Paris')->toDateString(), function ($excel) {
            $excel->setTitle('Historique des commandes');
            $excel->setCreator(Config::get('constants.company_name'))
                ->setCompany($this->partner->name);
            $excel->setDescription('Liste des commandes des 30 derniers jours');
            $excel->sheet('Historique des commandes', function ($sheet) {
                $sheet->fromArray(
                    $this->orderInfo
                        ->where('partner_id', 1)
                        ->where('accepted', true)
                        ->where('delivered', true)
                        ->where('created_at', '>', $this->carbon->subDays(30))
                        ->join('orders', 'orders_info.id', '=', 'orders.order_id')
                        ->get([
                            'order_id',
                            'orderId',
                            'created_at',
                            'itemName',
                            'itemPrice',
                            'itemHHPrice',
                            'HHStatus',
                            'tax',
                            'alcohol',
                            'quantity',
                            'fees',
                            ])
                );
            });
        });
        return $file;
    }

    /**
     *  Cette fonction génère le mail avec toutes ses propriétés et l'envoi au partenaire.
     */
    private function sendMail()
    {
        Mail::send('emails.bank_transfer_partner',
            [
                'partner' => $this->partner,
                'amount' => $this->amount,
                'base_url' => $this->getBaseUrl()
            ], function ($message) {
                $message->to($this->partner->email)->subject('Versement ' . Config::get('constants.company_name'));
                $message->attach($this->genFile()->store("xls", false, true)['full']);
            });
    }

}
