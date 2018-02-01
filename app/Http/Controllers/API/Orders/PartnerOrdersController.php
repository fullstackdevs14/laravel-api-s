<?php

namespace App\Http\Controllers\API\Orders;

use App\ApplicationUser;
use App\Events\OrderEvent;
use App\Events\OrderHandledEvent;
use App\Handlers\FCMNotifications\FCMNotificationsHandler;
use App\Handlers\Invoices\InvoicesGenerator;
use App\Handlers\MangoPay\MangoPayHandler;
use App\Handlers\Orders\OrdersHandler;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderInfo;
use App\OrderInfoTemp;
use App\Partner;
use App\Repositories\ApplicationUserRepository;
use App\Repositories\IncidentRepository;
use App\Repositories\NotificationCheckerRepository;
use App\Repositories\OrderInfoRepository;
use App\Repositories\OrderInfoTempRepository;
use App\Repositories\PartnerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use JWTAuth;
use MangoPay\MangoPayApi;

/**
 * Cette classe gère toutes les action liées aux commandes à partir de l'interface partenaire.
 *
 * Class PartnerOrdersController
 * @package App\Http\Controllers\API\Orders
 */
class PartnerOrdersController extends Controller
{

    /**
     * C'est un model.
     *
     * @var Order
     */
    private $order;

    /**
     * C'est un model.
     *
     * @var OrderInfo
     */
    private $orderInfo;

    /**
     * C'est un model.
     *
     * @var ApplicationUser
     */
    private $applicationUser;

    /**
     * Librairie de l'api Mangopay.
     *
     * @var MangoPayApi
     */
    private $mangoPayApi;

    /**
     * C'est un gestionnaire.
     *
     * Gère les actions courantes liées aux commandes.
     *
     * @var OrdersHandler
     */
    private $ordersHandler;

    /**
     * C'est un model.
     *
     * @var OrderInfoTemp
     */
    private $orderInfoTemp;

    /**
     * C'est un gestionnaire.
     *
     * Contrôle les action des plus courante de la librairie Mangopay.
     *
     * @var MangoPayHandler
     */
    private $mangoPayHandler;

    /**
     * C'est un dépôt
     *
     * Gére les actions courantes liées au partenaires.
     *
     * @var PartnerRepository
     */
    private $partnerRepository;

    /**
     * C'est un gestionnaire.
     *
     * Gère les action courantes liées aux incidents.
     *
     * @var IncidentRepository
     */
    private $incidentRepository;

    /**
     * C'est un dépôt.
     *
     * Gère les actions courantes liées aux commandes.
     *
     * @var OrderInfoRepository
     */
    private $orderInfoRepository;

    /**
     * C'est dépôt.
     *
     * Gère les actions courantes liées au utilisateurs.
     *
     * @var ApplicationUserRepository
     */
    private $applicationUserRepository;

    /**
     * C'est un dépôt.
     *
     * Gère les actions courantes liées aux commandes partagées.
     *
     * @var OrderInfoTempRepository
     */
    private $orderInfoTempRepository;

    /**
     * C'est un gestionnaire.
     *
     * Gére les actions courantes liées aux commandes partagées.
     *
     * @var FCMNotificationsHandler
     */
    private $FCMNotificationsHandler;

    /**
     * C'est un gestionnaire.
     *
     * Gère la génération pdf des factures.
     *
     * @var InvoicesGenerator
     */
    private $invoicesGenerator;

    /**
     * C'est un dépôt.
     *
     * Sert à l'enregistrement du status des notification.
     *
     * @var NotificationCheckerRepository
     */
    private $notificationCheckerRepository;

    /**
     * PartnerOrdersController constructor.
     * @param Order $order
     * @param OrderInfo $orderInfo
     * @param ApplicationUser $applicationUser
     * @param MangoPayApi $mangoPayApi
     * @param OrdersHandler $ordersHandler
     * @param OrderInfoTemp $orderInfoTemp
     * @param MangoPayHandler $mangoPayHandler
     * @param PartnerRepository $partnerRepository
     * @param IncidentRepository $incidentRepository
     * @param OrderInfoRepository $orderInfoRepository
     * @param ApplicationUserRepository $applicationUserRepository
     * @param OrderInfoTempRepository $orderInfoTempRepository
     * @param FCMNotificationsHandler $FCMNotificationsHandler
     * @param InvoicesGenerator $invoicesGenerator
     * @param NotificationCheckerRepository $notificationCheckerRepository
     */
    public function __construct
    (
        Order $order,
        OrderInfo $orderInfo,
        ApplicationUser $applicationUser,
        MangoPayApi $mangoPayApi,
        OrdersHandler $ordersHandler,
        OrderInfoTemp $orderInfoTemp,
        MangoPayHandler $mangoPayHandler,
        PartnerRepository $partnerRepository,
        IncidentRepository $incidentRepository,
        OrderInfoRepository $orderInfoRepository,
        ApplicationUserRepository $applicationUserRepository,
        OrderInfoTempRepository $orderInfoTempRepository,
        FCMNotificationsHandler $FCMNotificationsHandler,
        InvoicesGenerator $invoicesGenerator,
        NotificationCheckerRepository $notificationCheckerRepository
    )
    {
        Config::set('jwt.user', Partner::class);
        Config::set('auth.providers.users.model', Partner::class);

        $this->order = $order;
        $this->orderInfo = $orderInfo;
        $this->applicationUser = $applicationUser;
        $this->mangoPayApi = $mangoPayApi;
        $this->ordersHandler = $ordersHandler;
        $this->orderInfoTemp = $orderInfoTemp;
        $this->mangoPayHandler = $mangoPayHandler;
        $this->partnerRepository = $partnerRepository;
        $this->incidentRepository = $incidentRepository;
        $this->orderInfoRepository = $orderInfoRepository;
        $this->applicationUserRepository = $applicationUserRepository;
        $this->orderInfoTempRepository = $orderInfoTempRepository;
        $this->FCMNotificationsHandler = $FCMNotificationsHandler;
        $this->invoicesGenerator = $invoicesGenerator;
        $this->notificationCheckerRepository = $notificationCheckerRepository;
    }

    /**
     * Cette fonction récupère toutes les commandes non traitées en base de données application pour un partenaire grâce
     * à son token d'authentification. Elle prépare enseuite les données pour les envoyer à l'interface partenaire.
     *
     * @var string $token
     * @return \Illuminate\Http\JsonResponse Array of orders ready for the partner client inteface.
     */
    public function getOrders()
    {
        $partner = $this->partnerRepository->getPartnerFromToken();
        $orderInfoTemp = $this->orderInfoTemp->where('partner_id', $partner->id)->get();
        $orders = $this->ordersHandler->prepareArrayForPartnerClient($orderInfoTemp);

        return response()->json(['orders' => $orders], 200);
    }

    /**
     * Cette fonction récupère toutes les commandes pour un partenaire grâce à son token d'authentification. Elle les
     * prépare ensuite pour l'interface du partenaire.
     *
     * @return \Illuminate\Http\JsonResponse Array of orders ready for the partner client inteface.
     */
    public function getOldOrders()
    {
        $partner = $this->partnerRepository->getPartnerFromToken();
        $orderInfo = $this->orderInfo->where('partner_id', $partner->id)->get();
        $orders = $this->ordersHandler->prepareArrayForPartnerClientOldOrders($orderInfo);

        return response()->json(['orders' => $orders], 200);
    }

    /**
     * Cette fonction récupère toutes les commandes qui match l'orderId envoyer dans la requête.
     *
     * Variables dans la requête :
     * - search input / string|number.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse Array of orders ready for the partner client inteface.
     */
    public function searchOrder
    (
        Request $request
    )
    {
        $this->validate($request,
            [
                'search' => 'string',
            ]);

        $partner = $this->partnerRepository->getPartnerFromToken();
        $orderInfo = $this->orderInfo->where(function ($query) use ($request) {
            if (($search = $request->get('search'))) {
                $query->orWhere('orderID', 'like', '%' . $search . '%');
            }
        })
            ->where('partner_id', $partner->id)->get();
        $orders = $this->ordersHandler->prepareArrayForPartnerClientOldOrders($orderInfo);

        return response()->json(['orders' => $orders], 200);
    }

    /**
     * La fonction traite de nombreux cas :
     *
     * - 1 La variable orders_info.applicationUser_id_share_bill EST NULLE.
     * --> Récupère le partenaire à partir du token d'authentification.
     * --> Supprime la commande de la table temporaire : "orders_info_temp".
     * --> Prépare les variables pour la méthode payIn : ($applicationUser, $partner, $orderInfo, $amount).
     * --> Exécute la méthode payIn.
     *
     * --> Si ECHEC de payIn :
     * ---> Enregistre la commande comme acceptée dans la table "orders_info" avec la colonne "incident" à true.
     * ---> Créer un nouvel incident dans la table "incidents" avec la variable "ResultMessage" comme enregistrement
     * dans la colonne "excuses" (ResultMessage = réponse payIn).
     * ---> Prévient l'utilisateur de la situation via une push notification (FCMNotificationHandler) puis enregistrement du status de la notification en bdd..
     * ---> /!\ enregistre "application_users.mango_card_id" à null.
     * ---> Prépare le tableau des commandes pour l'interface du partenaire.
     * ---> Déclenche l'évènement "orderHandledEvent" et envoi une confirmation de commande à l'utilisateur.
     * ---> Envoi un message de succès en JSON au partenaire.
     * --> Si SUCCES :
     * ---> Enregistre la commande comme acceptée dans la table "orders_info".
     * ---> Prévient l'utilisateur de la situation via une push notification (FCMNotificationHandler) puis enregistrement du status de la notification en bdd..
     * ---> Déclenche l'évènement "orderHandledEvent" et envoi une confirmation de commande REUSSIE à l'utilisateur.
     * ---> Envoi un message d'erreur en JSON au partenaire.
     *
     * - 2 Si la variable orders_info.applicationUser_id_share_bill EST PRESENTE.
     * --> Récupère la partenaire à partir du token d'authentification.
     * --> Supprime la commande de la table temporaire : "orders_info_temp".
     * --> Prépare les variables pour la méthode payIn : ($applicationUser, $partner, $orderInfo, $amount).
     * --> Exécute la méthode payIn pour chacun des utilisateurs.
     * --> Si ECHEC de payIn :
     * ---> Enregistre la commande comme acceptée dans la table "orders_info" avec la colonne "incident" à true.
     * ---> Créer un nouvel incident dans la table "incidents" avec la variable "ResultMessage" comme enregistrement
     * dans la colonne "excuses".
     * ---> Créer un nouvel incident dans la table "incidents" avec la variable "ResultMessage" comme enregistrement
     * dans la colonne "excuses" (ResultMessage = réponse payIn).
     * ---> Prévient l'utilisateur à l'initiative de la demande de partage de la situation (échec de paiement) avec une
     * push notification (FCMNotificationHandler) puis enregistrement du status de la notification en bdd.
     * ---> Prépare le tableau des commandes pour l'envoyer à l'interface partenaire.
     * ---> Déclenche l'évènement "orderHandledEvent" et envoi une confirmation de commande ECHOUEE aux utilisateurs.
     * ---> Envoi un message de succès en JSON au partenaire.
     * --> Si SUCCES :
     * ---> Enregistre la commande comme acceptée dans la table "orders_info".
     * ---> Génère une facture.
     * ---> Prévient les utilisateurs de la situation via une push notification (FCMNotificationHandler) puis enregistrement du status de la notification en bdd.
     * ---> Déclenche l'évènement "orderHandledEvent" et envoi une confirmation de commande REUSSIE aux utilisateurs.
     * ---> Envoi un message de succès en JSON au partenaire.
     *
     * Variables dans la requête :
     * - order_id / required|numeric|exists:orders_info_temp,order_id,
     * - applicationUser_id / required|numeric|exists:application_users,id.
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse Message for the partner.
     */
    public function acceptOrder
    (
        Request $request
    )
    {
        $this->validate($request,
            [
                'order_id' => 'required|numeric|exists:orders_info_temp,order_id',
                'applicationUser_id' => 'required|numeric|exists:application_users,id'
            ],
            [
                'order_id.exists' => 'La commande à expirée avant d\'être acceptée OU la commande a déjà été traitée :/'
            ]
        );

        /**
         * SI LA COMMANDE N'EST PAS PARTAGEE.
         */
        if ($this->orderInfo->findOrFail($request->order_id)->applicationUser_id_share_bill === null) {

            $partner = $this->partnerRepository->getPartnerFromToken();
            $this->orderInfoTempRepository->deleteWhereIdOfOrderIs($request['order_id']);
            $orderInfo = $this->orderInfo->findOrFail($request['order_id']);
            $orders = $this->order->where('order_id', $request['order_id'])->get()->toArray();
            $amount = $this->ordersHandler->makeBillAmount($orderInfo['HHStatus'], $orders);
            $applicationUser = $this->applicationUser->findOrFail($orderInfo->applicationUser_id);

            $result = $this->mangoPayHandler->payIn($this->mangoPayApi, $applicationUser, $partner, $orderInfo, $amount);

            /**
             * SI ECHEC.
             */
            if ($result->Status != 'SUCCEEDED') {
                $this->orderInfoRepository->acceptedWithIncidentFindOrFail($request['order_id']);
                $this->incidentRepository->newIncident($request['order_id'], $result->ResultMessage);

                $notificationStatus = $this->FCMNotificationsHandler->sendNotificationToSpecificUser
                (
                    $applicationUser,
                    'Echec de commande !',
                    'Nous n\'avons pas pu prélever votre compte, la commande a été annulée !',
                    'default',
                    0,
                    null
                );
                $this->notificationCheckerRepository->newNotificationChecker($applicationUser->id, $orderInfo->partner_id, $orderInfo->id, $notificationStatus['result'], 'payment_failure');

                $this->applicationUserRepository->setMangoCardIdToNull($orderInfo->applicationUser_id);

                $orderInfoTemp = $this->orderInfoTemp->where('partner_id', $partner->id)->get();
                $orders = $this->ordersHandler->prepareArrayForPartnerClient($orderInfoTemp);
                event(new OrderEvent(['orders' => $orders, 'partner_id' => $partner->id]));
                event(new  OrderHandledEvent($applicationUser, $partner, $orderInfo));

                if ($notificationStatus === false) {
                    return response()->json([
                        'error' => 'Nous n\'avons pas réussi à facturer votre client :/ La commande est ANNULÉE! Client NON prévenu.',
                    ], 422);
                } else {
                    return response()->json([
                        'error' => 'Nous n\'avons pas réussi à facturer votre client :/ La commande est ANNULÉE! Client prévenu.',
                    ], 422);
                }
            }

            $this->orderInfoRepository->acceptedWithoutIncidentFindOrFail($request['order_id']);

            $this->invoicesGenerator->generateApplicationUserInvoice($applicationUser->id, $orderInfo->id);

            $result = $this->FCMNotificationsHandler->sendNotificationToSpecificUser
            (
                $applicationUser,
                'Commande acceptée !',
                'Votre commande ' . $orderInfo->orderId . ' vient d\'être acceptée et est en cours de préparation.',
                'default',
                0,
                null
            );
            $this->notificationCheckerRepository->newNotificationChecker($applicationUser->id, $orderInfo->partner_id, $orderInfo->id, $result['result'], 'accept');

            event(new  OrderHandledEvent($applicationUser, $partner, $orderInfo));

            return response()->json([
                'message' => 'La commande à bien été prise en compte et sera facturée à votre client !'
            ], 200);

            /**
             * SI LA COMMANDE EST PARTAGEE.
             */
        } else {

            $partner = $this->partnerRepository->getPartnerFromToken();
            $this->orderInfoTempRepository->deleteWhereIdOfOrderIs($request['order_id']);
            $orderInfo = $this->orderInfo->findOrFail($request['order_id']);
            $orders = $this->order->where('order_id', $request['order_id'])->get()->toArray();
            $amount = $this->ordersHandler->makeBillAmount($orderInfo['HHStatus'], $orders);

            $applicationUser_1 = $this->applicationUser->findOrFail($orderInfo->applicationUser_id);
            $applicationUser_2 = $this->applicationUser->findOrFail($orderInfo->applicationUser_id_share_bill);

            $result_1 = $this->mangoPayHandler->payInShared_1($this->mangoPayApi, $applicationUser_1, $partner, $orderInfo, $amount / 2);
            $result_2 = $this->mangoPayHandler->payInShared_2($this->mangoPayApi, $applicationUser_2, $partner, $orderInfo, $amount / 2);

            if ($result_1->Status != 'SUCCEEDED' OR $result_2->Status != 'SUCCEEDED') {
                $this->orderInfoRepository->acceptedWithIncidentFindOrFail($request['order_id']);

                $this->incidentRepository->newIncident($request['order_id'], 'Shared Order with Payin\'s ids : User_1 -> ' . $result_1->Id . ' User_2 -> ' . $result_2->Id);

                $notificationStatus = $this->FCMNotificationsHandler->sendNotificationToSpecificUser
                (
                    $applicationUser_1,
                    'Echec de commande !',
                    'Une erreur est survenue lors du paiement. La commande a été annulée.',
                    'default',
                    0,
                    null
                );
                $this->notificationCheckerRepository->newNotificationChecker($applicationUser_1->id, $orderInfo->partner_id, $orderInfo->id, $notificationStatus['result'], 'payment_failure');

                $orderInfoTemp = $this->orderInfoTemp->where('partner_id', $partner->id)->get();
                $orders = $this->ordersHandler->prepareArrayForPartnerClient($orderInfoTemp);
                event(new OrderEvent(['orders' => $orders, 'partner_id' => $partner->id]));
                event(new  OrderHandledEvent($applicationUser_1, $partner, $orderInfo));

                if ($notificationStatus === false) {
                    return response()->json([
                        'error' => 'Nous n\'avons pas réussi à facturer votre client :/ La commande est ANNULÉE! Client NON prévenu.',
                    ], 422);
                } else {
                    return response()->json([
                        'error' => 'Nous n\'avons pas réussi à facturer votre client :/ La commande est ANNULÉE! Client prévenu.',
                    ], 422);
                }

            }

            $this->orderInfoRepository->acceptedWithoutIncidentFindOrFail($request['order_id']);

            $this->invoicesGenerator->generateApplicationUserInvoice($applicationUser_1->id, $orderInfo->id);

            $result = $this->FCMNotificationsHandler->sendNotificationToSpecificUser
            (
                $applicationUser_1,
                'Commande acceptée !',
                'Votre commande ' . $orderInfo->orderId . ' vient d\'être acceptée et est en cours de préparation.',
                'default',
                0,
                null
            );
            $this->notificationCheckerRepository->newNotificationChecker($applicationUser_1->id, $orderInfo->partner_id, $orderInfo->id, $result['result'], 'accept');

            $result = $this->FCMNotificationsHandler->sendNotificationToSpecificUser
            (
                $applicationUser_2,
                'Commande acceptée !',
                'Votre commande ' . $orderInfo->orderId . ' vient d\'être acceptée et est en cours de préparation.',
                'default',
                0,
                null
            );
            $this->notificationCheckerRepository->newNotificationChecker($applicationUser_2->id, $orderInfo->partner_id, $orderInfo->id, $result['result'], 'accept');

            event(new  OrderHandledEvent($applicationUser_1, $partner, $orderInfo));

            return response()->json([
                'message' => 'La commande à bien été prise en compte et sera facturée à votre client !'
            ], 200);

        }
    }

    /**
     * Cette fonction enregistre la commande comme délivrée dans la table "in orders_info.delivered".
     * Envoi d'une notification à l'utilisateur qui a passé la commande (FCMNotificationHandler).
     * Envoi d'un message JSON de succès à l'interface du partenaire.
     *
     *  Variables dans la requête :
     * - order_id / required|numeric|exists:orders_info,id,
     * - applicationUser_id / required|numeric|exists:application_users,id.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse Message for the partner.
     */
    public function deliverOrder
    (
        Request $request
    )
    {
        $this->validate($request,
            [
                'order_id' => 'required|numeric|exists:orders_info,id',
                'applicationUser_id' => 'required|numeric|exists:application_users,id'
            ]);

        $this->orderInfoRepository->deliveredWithoutIncidentFindOrFail($request['order_id']);

        $orderInfo = $this->orderInfo->findOrFail($request['order_id']);
        $applicationUser = $this->applicationUser->findOrFail($request['applicationUser_id']);

        $result = $this->FCMNotificationsHandler->sendNotificationToSpecificUser
        (
            $applicationUser,
            'Commande prête !',
            'Votre commande est prête :) Numéro de commande: ' . $orderInfo->orderId,
            'default',
            0,
            ['behavior' => $orderInfo->orderId]
        );
        $this->notificationCheckerRepository->newNotificationChecker($applicationUser->id, $orderInfo->partner_id, $orderInfo->id, $result['result'], 'ready');

        return response()->json([
            'message' => 'La commande à bien été enregistrée comme délivrée ! Nous avons prévenue votre client que la commande est prête :)'
        ], 200);
    }

    /**
     * Cette fonction supprime la commande de la table temporaire : "orders_info_temp.
     * Enregistrement de la commande comme déclinée  dans la table "orders_info".
     * Envoi d'une notification à l'utilisateur qui a passé la commande (FCMNotificationHandler) puis enregistre le status de la notification en bdd.
     * Prépare et envoi les commandes restantes à l'interface partenaire.
     * Déclenche l'évènement "OrderHandledEvent" qui envoi le reçu à l'utilisateur.
     * Envoi un message JSON de succès à l'interface partenaire.
     *
     *  Variables dans la requête :
     * - order_id / required|numeric|exists:orders_info,id,
     * - applicationUser_id / required|numeric|exists:application_users,id.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse Message for the partner.
     */
    public function declineOrder
    (
        Request $request
    )
    {
        $this->validate
        (
            $request,
            [
                'order_id' => 'required|numeric|exists:orders_info,id',
                'applicationUser_id' => 'required|numeric|exists:application_users,id'
            ]
        );

        $partner = $this->partnerRepository->getPartnerFromToken();

        $this->orderInfoTempRepository->deleteWhereIdOfOrderIs($request['order_id']);
        $this->orderInfoRepository->declinedWithoutIncidentFindOrFail($request['order_id']);

        $orderInfo = $this->orderInfo->findOrFail($request['order_id']);

        $applicationUser = $this->applicationUser->findOrFail($request['applicationUser_id']);

        $result = $this->FCMNotificationsHandler->sendNotificationToSpecificUser
        (
            $applicationUser,
            'Commande déclinée !',
            'Votre commande ' . $orderInfo->orderId . ' a été déclinée par le barman. Il est possible que celui-ci soit très occupé :/',
            'default',
            0,
            null
        );
        $this->notificationCheckerRepository->newNotificationChecker($applicationUser->id, $orderInfo->partner_id, $orderInfo->id, $result['result'], 'ready');

        $orderInfoTemp = $this->orderInfoTemp->where('partner_id', $partner->id)->get();
        $orders = $this->ordersHandler->prepareArrayForPartnerClient($orderInfoTemp);
        event(new OrderEvent(['orders' => $orders, 'partner_id' => $partner->id]));
        event(new OrderHandledEvent($applicationUser, $partner, $orderInfo));

        return response()->json([
            'message' => 'La commande à bien été déclinée !'
        ], 200);
    }

}
