<?php

namespace App\Console\Commands;

use App\ApplicationUser;
use App\Events\OrderEvent;
use App\Events\OrderHandledEvent;
use App\Handlers\FCMNotifications\FCMNotificationsHandler;
use App\Handlers\Orders\OrdersHandler;
use App\OrderInfoTemp;
use App\Partner;
use App\Repositories\NotificationCheckerRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Cette classe est utilisée pour supprimer les commandes « orders » dans la table « orders_info_temp », qui ont une date de création supérieure la constante « TIME_TO_LIVE_FOR_ORDERS ».
 * Met toutes les commandes non traitées à false dans la colonne « accepted » de la table « orders_info ».
 * Une fois les commandes expirées traitées, les commandes restantes sont envoyées à l'interface du partenaire.
 * Une fois les commandes expirées traitées, une notification et un mail est envoyé à l'utilisateur pour le prévenir que les commandes expirées ont été traitées et sont considérées comme décliner.
 *
 * @package App\Console\Commands
 */
class CheckOrders extends Command
{
    /**
     * Correspond au temps en minutes avant qu'une commande soit considérée comme expirée.
     * Cette constante est également utilisée dans les notifications envoyées à l'utilisateur grâce au contrôleur « ApplicationUserOrdersController ».
     */
    const TIME_TO_LIVE_FOR_ORDERS = 1;

    /**
     * Nom et signature de la commande en console.
     *
     * @var string
     */
    protected $signature = 'orders:check';

    /**
     * Description de la commande en console.
     *
     * @var string
     */
    protected $description = 'Supprime les commande supérieures à TIME_TO_LIVE_FOR_ORDERS.';

    /**
     * Injecté via le constructeur.
     * C'est un model.
     * Ce model fournit les informations nécessaires à l'événement « OrderHandledEvent », qui fournit ensuite les informations nécessaires à l'écouteur « ApplicationUserEmailPurchaseConfirmation ».
     *
     * @var Partner
     * @type Partner
     */
    private $partner;

    /**
     * Injecté via le constructeur.
     * C'est un model.
     * Ce model fournit les informations nécessaires à l'événement « OrderHandledEvent », qui fournit ensuite les informations nécessaires à l'écouteur « ApplicationUserEmailPurchaseConfirmation ».
     * Ce model fournit les informations nécessaires aux gestionnaires « FCMNotificationsHandler » pour envoyer des notifications.
     *
     * @var ApplicationUser
     * @type ApplicationUser
     */
    private $applicationUser;

    /**
     * Injecté via le constructeur.
     * C'est un model.
     * Ce model est utilisé pour traiter les commandes expirées.
     * Ce model est utilisé pour préparer les commandes à envoyer à l'interface du partenaire via l'événement « OrderEvent ».
     *
     * @var OrderInfoTemp
     * @type OrderInfoTemp
     */
    private $orderInfoTemp;

    /**
     * Injecté via le constructeur.
     * Ce gestionnaire contient l'ensemble des méthodes nécessaires à l'envoi de push notifications.
     *
     * @var FCMNotificationsHandler
     * @type FCMNotificationsHandler
     */
    private $FCMNotificationsHandler;

    /**
     * C'est un gestionnaire.
     *
     * Est utilisé pour préparer le retour des commandes pour l'interface du partenaire.
     *
     * @var OrdersHandler
     */
    private $ordersHandler;

    /**
     * C'est un dépôt.
     *
     * Sert à l'enregistrement du status des notification.
     *
     * @var NotificationCheckerRepository
     */
    private $notificationCheckerRepository;

    /**
     * Créer une instance de nouvelle commande.
     *
     * CheckOrders constructor.
     * @param Partner $partner
     * @param ApplicationUser $applicationUser
     * @param OrderInfoTemp $orderInfoTemp
     * @param FCMNotificationsHandler $FCMNotificationsHandler
     * @param OrdersHandler $ordersHandler
     * @param NotificationCheckerRepository $notificationCheckerRepository
     */
    public function __construct
    (
        Partner $partner,
        ApplicationUser $applicationUser,
        OrderInfoTemp $orderInfoTemp,
        FCMNotificationsHandler $FCMNotificationsHandler,
        OrdersHandler $ordersHandler,
        NotificationCheckerRepository $notificationCheckerRepository
    )
    {
        parent::__construct();
        $this->partner = $partner;
        $this->applicationUser = $applicationUser;
        $this->orderInfoTemp = $orderInfoTemp;
        $this->FCMNotificationsHandler = $FCMNotificationsHandler;
        $this->ordersHandler = $ordersHandler;
        $this->notificationCheckerRepository = $notificationCheckerRepository;
    }

    /**
     * Ensemble des instructions exécutées par la commande en console.
     */
    public function handle()
    {
        $orders = $this->orderInfoTemp->where('created_at', '<', Carbon::now()->subMinutes(CheckOrders::TIME_TO_LIVE_FOR_ORDERS))->get();

        foreach ($orders as $order) {

            $orderToUpdate = $order->ordersInfo->findOrFail($order->order_id);
            $orderToUpdate->accepted = 0;
            $orderToUpdate->update();

            /**
             * Suppression de la commande dans la table temporaire.
             */
            $this->orderInfoTemp->findOrFail($order->id)->delete();

            /**
             * Réinitialisation des commandes sur l'interface du partenaire.
             */
            $orderInfoTemp = $this->orderInfoTemp->where('partner_id', $order->ordersInfo->partner_id)->get();
            $orders = $this->ordersHandler->prepareArrayForPartnerClient($orderInfoTemp);
            event(new OrderEvent(['orders' => $orders, 'partner_id' => $order->ordersInfo->partner_id]));

            /**
             * Envoi d'un email à l'utilisateur.
             */
            $applicationUser = $this->applicationUser->findOrFail($order->ordersInfo->applicationUser_id);
            $partner = $this->partner->findOrfail($order->ordersInfo->partner_id);
            event(new OrderHandledEvent($applicationUser, $partner, $order->ordersInfo));

            /**
             * Envoi d'une notification à l'utilisateur.
             */
            $result = $this->FCMNotificationsHandler->sendNotificationToSpecificUser
            (
                $applicationUser,
                'Commande annulée',
                'Désolé le barmen n\'a pas pu s\'occuper de votre commande.',
                'default',
                0,
                null
            );
            $this->notificationCheckerRepository->newNotificationChecker($applicationUser->id, $partner->id, $order->order_id, $result['result'], 'decline_expire');
        }
    }

}
