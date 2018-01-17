<?php

namespace App\Listeners;

use App\ApplicationUser;
use App\Events\OrderHandledEvent;
use App\Handlers\Orders\OrdersHandler;
use App\Handlers\ToolsHandler;
use App\Order;
use App\OrderInfo;
use App\Partner;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Config;
use Mail;

/**
 * Cette classe se charge d'envoyer un reçu par email après une commande d'un utilisateur.
 *
 * Class ApplicationUserEmailPurchaseConfirmationListener
 * @package App\Listeners
 */
class ApplicationUserEmailPurchaseConfirmationListener implements ShouldQueue
{
    /**
     * Sert à stocker les commandes pour les utiliser dans les différentes fonction de l'écouteur.
     *
     * @var Liste des commandes.
     */
    private $orders;

    /**
     * C'est un model.
     *
     * @var Partner
     */
    private $partner;

    /**
     * C'est un model.
     *
     * @var OrderInfo
     */
    private $orderInfo;

    /**
     * Montant de la facture.
     *
     * @var int
     */
    private $billAmount;

    /**
     * C'est un model.
     *
     * @var ApplicationUser
     */
    private $applicationUser;

    /**
     * C'est un gestionnaire.
     *
     * @var OrdersHandler
     */
    private $ordersHandler;

    /**
     * C'est un model.
     *
     * @var ApplicationUser
     */
    private $applicationUserModel;

    /**
     * Créer l'écouteur.
     *
     * ApplicationUserEmailPurchaseConfirmationListener constructor.
     * @param ApplicationUser $applicationUserModel
     * @param OrdersHandler $ordersHandler
     */
    public function __construct
    (
        ApplicationUser $applicationUserModel,
        OrdersHandler $ordersHandler
    )
    {
        $this->ordersHandler = $ordersHandler;
        $this->applicationUserModel = $applicationUserModel;
    }

    /**
     * Gère l'évènement.
     *
     * @param OrderHandledEvent $event
     */
    public function handle
    (
        OrderHandledEvent $event
    )
    {
        $this->applicationUser = $event->applicationUser;
        $this->partner = $event->partner;
        $this->orderInfo = $event->orderInfo;
        $this->orders = $this->getOrders($event->orderInfo->id);
        $this->billAmount = $this->ordersHandler->makeBillAmount($event->orderInfo->HHStatus, $this->orders);
        $this->sendMail();
    }

    /**
     * Retourne l'ensemble des commandes pour une order_id.
     *
     * @param $order_id
     * @return \Illuminate\Support\Collection
     */
    private function getOrders($order_id)
    {
        return Order::where('order_id', $order_id)->get();
    }

    /**
     * Envoi l'email de confirmation de commande à l'utilisateur / les utilisateurs ayant commandé.
     */
    private function sendMail()
    {
        if ($this->orderInfo->applicationUser_id_share_bill == null) {
            Mail::send(
                'emails.applicationUser_receipt',
                ['applicationUser' => $this->applicationUser,
                    'partner' => $this->partner,
                    'orderInfo' => $this->orderInfo,
                    'orders' => $this->orders,
                    'billAmount' => $this->billAmount,
                    'base_url' => ToolsHandler::getBaseUrl()
                ], function ($message) {
                $message->to($this->applicationUser->email)->subject(Config::get('constants.company_name')  . ' - reçu : ' . $this->partner->name . ' / ' . $this->orderInfo->created_at);
            });
        } else {
            Mail::send(
                'emails.applicationUser_receipt',
                ['applicationUser' => $this->applicationUser,
                    'partner' => $this->partner,
                    'orderInfo' => $this->orderInfo,
                    'orders' => $this->orders,
                    'billAmount' => $this->billAmount / 2 + 0.20,
                    'base_url' => ToolsHandler::getBaseUrl()
                ], function ($message) {
                $message->to($this->applicationUser->email)->subject(Config::get('constants.company_name')  . ' reçu note partagée : ' . $this->partner->name . ' / ' . $this->orderInfo->created_at);
            });
            Mail::send(
                'emails.applicationUser_receipt',
                ['applicationUser' => $this->applicationUserModel->findOrFail($this->orderInfo->applicationUser_id_share_bill),
                    'partner' => $this->partner,
                    'orderInfo' => $this->orderInfo,
                    'orders' => $this->orders,
                    'billAmount' => $this->billAmount / 2 + 0.20,
                    'base_url' => ToolsHandler::getBaseUrl()
                ], function ($message) {
                $message->to($this->applicationUserModel->findOrFail($this->orderInfo->applicationUser_id_share_bill)->email)->subject(Config::get('constants.company_name')  . ' reçu note partagée : ' . $this->partner->name . ' / ' . $this->orderInfo->created_at);
            });
        }
    }

}
