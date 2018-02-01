<?php

namespace App\Console\Commands;

use App\ApplicationUser;
use App\Handlers\FCMNotifications\FCMNotificationsHandler;
use App\OrderInfo;
use App\OrderInfoShareBill;
use App\Repositories\NotificationCheckerRepository;
use App\Repositories\OrderInfoShareBillRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Cette classe est utilisée pour supprimer les commandes dans la table « orders_info_share_bill », qui ont une date de création supérieure à la constante « TIME_TO_LIVE_FOR_ORDERS ».
 * Met toutes les commandes non traitées à false dans la colonne « accepted » de la table « orders_info ».
 * Les commandes présentes dans la table « orders_info_share_bill » n'iront jamais dans la table « orders_info_temp » si elles sont traitées par cette classe (commande expirée).
 * Envoi une notification aux utilisateurs pour les prévenir que leur demande de partager l'addition a expirée.
 *
 *
 * Class CheckOrdersShareBill
 * @package App\Console\Commands
 */
class CheckOrdersShareBill extends Command
{
    /**
     * Correspond au temps en minutes avant qu'une demande de partage soit expirée.
     * Cette constante est également utilisée dans les notifications envoyées à l'utilisateur grâce au contrôleur « ApplicationUserOrdersController ».
     */
    const TIME_TO_LIVE_FOR_ORDERS = 15;


    /**
     * Nom et signature de la commande en console.
     *
     * @var string
     */
    protected $signature = 'ordersShareBill:check';

    /**
     * Description de la commande en console.
     *
     * @var string
     */
    protected $description = 'Supprime les demande de partage supérieures à TIME_TO_LIVE_FOR_ORDERS.';

    /**
     * Injecté via le contrôleur.
     * C'est un model.
     * Ce model est utilisé pour mettre la colonne « accepted » à false dans la table « orders_info ».
     *
     * @var OrderInfo
     * @type OrderInfo
     */
    private $orderInfo;

    /**
     * Injecté via le contrôleur.
     * C'est un model.
     * Ce model est utilisé pour renseigner le gestionnaire « FCMNotificationsHandler » et envoyer une push notification à un utilisateur en particulier.
     *
     * @var ApplicationUser
     * @type ApplicationUser
     */
    private $applicationUser;

    /**
     * Injecté via le contrôleur.
     * C'est un model.
     * Ce model est utilisé pour trouver une commande dans la table « orders_info_share_bill », qui n'est pas :
     * – acceptée,
     * - expirée,
     * - plus vieille que la constante « TIME_TO_LIVE_FOR_ORDERS ».
     *
     * @var OrderInfoShareBill
     * @type OrderInfoShareBill
     */
    private $orderInfoShareBill;

    /**
     * Injecté via le contrôleur.
     * C'est un gestionnaire.
     * Ce gestionnaire est utilisé pour envoyer des push notifications.
     *
     * @var FCMNotificationsHandler
     */
    private $FCMNotificationsHandler;

    /**
     * Injecté via le contrôleur.
     * C'est un référentiel.
     * Ce référentiel est utilisé pour renseigner la commande comme non acceptée (à false) dans la colonne « accepted » de la table « order_info_share_bill ».
     *
     * @var OrderInfoShareBillRepository
     * @type OrderInfoShareBill
     */
    private $orderInfoShareBillRepository;

    /**
     * C'est un dépôt.
     *
     * Sert à l'enregistrement du status des notification.
     *
     * @var NotificationCheckerRepository
     */
    private $notificationCheckerRepository;

    /**
     * Créer une nouvelle instance de commande.
     *
     * CheckOrdersShareBill constructeur.
     * @param OrderInfo $orderInfo
     * @param ApplicationUser $applicationUser
     * @param OrderInfoShareBill $orderInfoShareBill
     * @param FCMNotificationsHandler $FCMNotificationsHandler
     * @param OrderInfoShareBillRepository $orderInfoShareBillRepository
     * @param NotificationCheckerRepository $notificationCheckerRepository
     */
    public function __construct
    (
        OrderInfo $orderInfo,
        ApplicationUser $applicationUser,
        OrderInfoShareBill $orderInfoShareBill,
        FCMNotificationsHandler $FCMNotificationsHandler,
        OrderInfoShareBillRepository $orderInfoShareBillRepository,
        NotificationCheckerRepository $notificationCheckerRepository
    )
    {
        parent::__construct();
        $this->orderInfo = $orderInfo;
        $this->applicationUser = $applicationUser;
        $this->orderInfoShareBill = $orderInfoShareBill;
        $this->FCMNotificationsHandler = $FCMNotificationsHandler;
        $this->orderInfoShareBillRepository = $orderInfoShareBillRepository;
        $this->notificationCheckerRepository = $notificationCheckerRepository;
    }

    /**
     * Exécute les instructions liées à la commande console.
     */
    public function handle()
    {
        $orders = $this->orderInfoShareBill
            ->where('expired', 0)
            ->where('accepted', 0)
            ->where('created_at', '<', Carbon::now()->subMinutes(CheckOrdersShareBill::TIME_TO_LIVE_FOR_ORDERS))
            ->get();

        foreach ($orders as $order) {

            $ordersInfo = $this->orderInfo->findOrFail($order->order_id);
            $ordersInfo->accepted = 0;
            $ordersInfo->update();

            $this->orderInfoShareBillRepository->orderNotAcceptedInTime($order->id);

            $applicationUser1 = $this->applicationUser->findOrFail($ordersInfo->applicationUser_id);
            $applicationUser2 = $this->applicationUser->findOrFail($ordersInfo->applicationUser_id_share_bill);

            $result = $this->FCMNotificationsHandler->sendNotificationToSpecificUser
            (
                $applicationUser1,
                'Commande annulée',
                'Désolé commande partagée n\'a pas été acceptée à temps. Elle a été annulée.',
                'default',
                0,
                null
            );
            $this->notificationCheckerRepository->newNotificationChecker($applicationUser1->id, $ordersInfo->partner_id, $ordersInfo->id, $result['result'], 'share_expire');

            $result = $this->FCMNotificationsHandler->sendNotificationToSpecificUser
            (
                $applicationUser2,
                'Commande annulée',
                'Désolé commande partagée n\'a pas été acceptée à temps. Elle a été annulée.',
                'default',
                0,
                null
            );
            $this->notificationCheckerRepository->newNotificationChecker($applicationUser2->id, $ordersInfo->partner_id, $ordersInfo->id, $result['result'], 'share_expire');

        }
    }

}
