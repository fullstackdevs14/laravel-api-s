<?php

namespace App\Http\Controllers\API\Orders;

use App\ApplicationUser;
use App\Console\Commands\CheckOrders;
use App\Console\Commands\CheckOrdersShareBill;
use App\Events\OrderEvent;
use App\Handlers\FCMNotifications\FCMNotificationsHandler;
use App\Handlers\Orders\OrdersHandler;
use App\Handlers\Telephone\TelephoneHandler;
use App\Http\Controllers\Controller;
use App\OrderInfo;
use App\OrderInfoShareBill;
use App\OrderInfoTemp;
use App\Partner;
use App\PartnerMenu;
use App\Repositories\ApplicationUserRepository;
use App\Repositories\OrderInfoRepository;
use App\Repositories\OrderInfoShareBillRepository;
use App\Repositories\OrderInfoTempRepository;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use JWTAuth;
use Validator;

/**
 * Cette classe gère toutes les action liées aux commandes à partir de l'interface utilisateur.
 *
 * Class ApplicationUserOrderController
 * @package App\Http\Controllers\API\Orders
 */
class ApplicationUserOrderController extends Controller
{
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
     * C'est un model.
     *
     * @var ApplicationUser
     */
    private $applicationUser;

    /**
     * C'est un model.
     *
     * @var PartnerMenu
     */
    private $partnerMenu;

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
     * C'est un dépôt.
     *
     * Gére les actions courantes liées aux commandes.
     *
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * C'est un gestionnaire.
     *
     * Met en forme les numéros de téléphone.
     *
     * @var TelephoneHandler
     */
    private $telephoneHandler;

    /**
     * C'est un model.
     *
     * @var OrderInfoShareBill
     */
    private $orderInfoShareBill;

    /**
     * C'est un dépôt.
     *
     * Gére des actions courantes liées aux commandes.
     *
     * @var OrderInfoRepository
     */
    private $orderInfoRepository;

    /**
     * C'est un dépôt.
     *
     * Gére les actions courantes liées au utilisateurs.
     *
     * @var ApplicationUserRepository
     */
    private $applicationUserRepository;

    /**
     * C'est un dépôt.
     *
     * Gére des actions courantes liées aux
     *
     * @var OrderInfoTempRepository
     */
    private $orderInfoTempRepository;

    /**
     * C'est un gestionnaire.
     *
     * Gére les actions courantes liées au notifications.
     *
     * @var FCMNotificationsHandler
     */
    private $FCMNotificationsHandler;

    /**
     * C'est un dépôt.
     *
     * Gére les actions courantes liées aux commandes partagées.
     *
     * @var OrderInfoShareBillRepository
     */
    private $orderInfoShareBillRepository;

    /**
     * ApplicationUserOrderController constructor.
     * @param Partner $partner
     * @param OrderInfo $orderInfo
     * @param ApplicationUser $applicationUser
     * @param PartnerMenu $partnerMenu
     * @param OrdersHandler $ordersHandler
     * @param OrderInfoTemp $orderInfoTemp
     * @param OrderRepository $orderRepository
     * @param TelephoneHandler $telephoneHandler
     * @param OrderInfoShareBill $orderInfoShareBill
     * @param OrderInfoRepository $orderInfoRepository
     * @param ApplicationUserRepository $applicationUserRepository
     * @param OrderInfoTempRepository $orderInfoTempRepository
     * @param FCMNotificationsHandler $FCMNotificationsHandler
     * @param OrderInfoShareBillRepository $orderInfoShareBillRepository
     */
    public function __construct
    (
        Partner $partner,
        OrderInfo $orderInfo,
        ApplicationUser $applicationUser,
        PartnerMenu $partnerMenu,
        OrdersHandler $ordersHandler,
        OrderInfoTemp $orderInfoTemp,
        OrderRepository $orderRepository,
        TelephoneHandler $telephoneHandler,
        OrderInfoShareBill $orderInfoShareBill,
        OrderInfoRepository $orderInfoRepository,
        ApplicationUserRepository $applicationUserRepository,
        OrderInfoTempRepository $orderInfoTempRepository,
        FCMNotificationsHandler $FCMNotificationsHandler,
        OrderInfoShareBillRepository $orderInfoShareBillRepository
    )
    {
        Config::set('jwt.user', Partner::class);
        Config::set('auth.providers.users.model', ApplicationUser::class);

        $this->partner = $partner;
        $this->orderInfo = $orderInfo;
        $this->applicationUser = $applicationUser;
        $this->partnerMenu = $partnerMenu;
        $this->ordersHandler = $ordersHandler;
        $this->orderInfoTemp = $orderInfoTemp;
        $this->orderRepository = $orderRepository;
        $this->telephoneHandler = $telephoneHandler;
        $this->orderInfoShareBill = $orderInfoShareBill;
        $this->orderInfoRepository = $orderInfoRepository;
        $this->applicationUserRepository = $applicationUserRepository;
        $this->orderInfoTempRepository = $orderInfoTempRepository;
        $this->FCMNotificationsHandler = $FCMNotificationsHandler;
        $this->orderInfoShareBillRepository = $orderInfoShareBillRepository;
    }

    /**
     * Cette fonction se charge d'enregistrér (si validée) une commande non partagée.
     *
     * 1--> Test si l'item commandé est présent dans la table "partner_menus".
     * 2--> Vérifie que la quantité commandée est supérieure à 0.
     * 3--> Récupère le partenaire à partir de la commande.
     * 4--> Vérifie que 'partner_menus.partner_id === partner_id' et retourne un message d'erreur JSON si non valide.
     * 5--> Vérifie que le partner est ou non en happy hour et retourne un message d'erreur JSON si non valide.
     * 6--> Vérifie que le partner est ou non ouvert et retourne un message d'erreur JSON si non valide.
     * 7--> Obtient l'utilisateur du token d'authentification.
     * 8--> Vérifie que "mango_card_id" de la table "application_user" n'est pas null ou retourne un message d'erreur JSON.
     * 9--> Effectue les enregistrements dans les tables "OrderInfo", "OrderInfoTemp" et "Order" si la commande est valide.
     * 10--> Prépare et envoi la commande à l'interface partenaire (déclenchement de l'évènement "OrderEvent").
     * 11--> Retourne un masse de succès JSON à l'interface utilisateur.
     *
     *  Les variables de la requête :
     * - id / required|integer|exists:partners_menus,id,
     * - quantity / required|integer|min:1.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse Message of success or failure.
     */
    public function order
    (
        Request $request
    )
    {
        /**
         * Test if les commandes sont présentes dans la bdd et si les quantités sont correctes.
         */
        $rules = [
            'id' => 'required|integer|exists:partners_menus,id',
            'quantity' => 'required|integer|min:1'
        ];
        foreach ($request->orders as $order) {
            $validation = Validator::make($order, $rules);
            if ($validation->fails()) {
                return response()->json([
                    'error' => 'Commande invalide :/',
                ], 422);
            }
        }

        /**
         * Test si les items commandés appartiennent bien au partenaire et prépare un tableau.
         */
        $partner = $this->getPartnerFromOrder($request);

        $items = [];
        foreach ($request->orders as $order) {
            $item = $this->partnerMenu->findOrFail($order['id']);
            $item['quantity'] = $order['quantity'];
            array_push($items, $item);
            $validation = $partner->id == $item->partner_id;
            if (!$validation) {
                return response()->json([
                    'error' => 'Commande invalide :/',
                ], 422);
            }
        }

        /**
         * Vérifie que la commande est bien passée en happy hour ou non.
         */
        if ($partner->HHStatus != $request->HHStatus) {
            if ($partner->HHStatus == 0) {
                return response()->json([
                    'error' => 'Le partenaire n\'est plus en Happy Hour. Nous avons annulé votre commande pour que vous puissiez prendre connaissance des prix hors Happy Hour !',
                ], 423);
            } else {
                return response()->json([
                    'error' => 'Le partenaire est en Happy Hour. Nous avons annulé votre commande pour que vous puissiez découvrir les prix en Happy Hour !',
                ], 423);
            }
        }

        /**
         * Test que le partenaire est bien ouvert ou non.
         */
        if ($partner->openStatus == 0) {
            return response()->json([
                'error' => 'Sorry, le partenaire a cloturé la prise de commande avec ' . Config::get('constants.company_name') . ' :/'
            ], 423);
        }

        /**
         * Si la commande est validée.
         */
        $applicationUser = $this->applicationUserRepository->getApplicationUserFromToken();

        /**
         * Vérifie que le paiement est validé.
         */
        if ($applicationUser->mango_card_id == null) {
            return response()->json([
                'error' => 'Votre moyen de paiement est inactif. Rendez-vous dans le menu de gauche, puis l\'onglet moyen de paiement pour mettre à jours vos moyens de paiement.',
            ], 422);
        }

        $orderId = $this->orderInfoRepository->createNewOrderId();
        $orderInfo = $this->orderInfoRepository->newOrderInfo($applicationUser->id, $partner, $orderId);
        $this->orderInfoTempRepository->newOrderInfoTemp($orderInfo->id, $orderId, $applicationUser->id, $partner['id']);

        foreach ($items as $item) {
            $this->orderRepository->newOrder(
                $orderInfo->id,
                $item['category_id'],
                $item['name'],
                $item['price'],
                $item['HHPrice'],
                $item['tax'],
                $item['alcohol'],
                $item['quantity']
            );
        }

        /**
         * Prépare le tableau des commandes pour l'interface du partenaire.
         */
        $orderInfoTemp = $this->orderInfoTemp->where('partner_id', $partner->id)->get();
        $orders = $this->ordersHandler->prepareArrayForPartnerClient($orderInfoTemp);
        event(new OrderEvent(['orders' => $orders, 'partner_id' => $partner->id]));

        return response()->json([
            'message' => 'Commande réussie ! Celle-ci est maintenant en attente de validation par le barman. Si elle n\'est pas validée dans les ' . CheckOrders::TIME_TO_LIVE_FOR_ORDERS . ' min, elle sera automatiquement annulée !',
        ], 200);
    }

    /**
     * Cette fonction se charge (si validée) les demandes de commande partagée.
     *
     * --> Vérifie que le n° de téléphone est français et ajoute le préfixe "+33".
     * --> Vérifie que la quantité commandée est supérieure à 0.
     * --> Récupère le partenaire à partir de la commande.
     * --> Vérifie que 'partner_menus.partner_id === partner_id' et retourne un message d'erreur JSON si non valide.
     * --> Vérifie que le partner est ou non en happy hour et retourne un message d'erreur JSON si non valide.
     * --> Vérifie que le partner est ou non ouvert et retourne un message d'erreur JSON si non valide.
     * --> Obtient l'utilisateur 1 du token d'authentification et l'utilisateur à partir du n° de téléphone.
     * --> Vérifie que "mango_card_id" de la table "application_user" n'est pas null ou retourne un message d'erreur JSON.
     * Cette vérification est éffectuée pour les deux utilisateurs.
     * --> Effectue les enregistrements dans les tables "OrderInfo", "OrderInfoShareBill" et "Order" si la commande est valide.
     * --> Envoi une notification à l'utilisateur 2 pour demander le partage de l'addition.
     * --> Prévient l'utilisateur 1 par retour JSON que la demande de partage est ben effectuée.
     *
     * Les variables de la requête :
     * - tel / equired|phone:FR
     * - id / required|integer|exists:partners_menus,id,
     * - quantity / required|integer|min:1.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse Message of success or failure.
     */
    public function orderShareBill
    (
        Request $request
    )
    {
        $this->warnInDevMod();

        /**
         * Vérifie le n° de téléphone.
         */
        $rule = [
            'tel' => 'required|phone:FR',
        ];
        $validation = Validator::make($request->all(), $rule);
        if ($validation->fails()) {
            return response()->json([
                'error' => 'Numéro de téléphone nul :/',
            ], 423);
        }

        $tel = $this->telephoneHandler->frenchNumberFormat($request->tel);

        if (!$this->applicationUser->where('tel', $tel)->exists()) {
            return response()->json([
                'error' => 'Le numéro de téléphone renseigné ne correspond pas à nos enregistrements :/',
            ], 423);
        }

        /**
         * Test si les items commandés appartiennent bien au partenaire et prépare un tableau.
         */
        $rules = [
            'id' => 'required|integer|exists:partners_menus,id',
            'quantity' => 'required|integer|min:1'
        ];
        foreach ($request->orders as $order) {
            $validation = Validator::make($order, $rules);
            if ($validation->fails()) {
                return response()->json([
                    'error' => 'Commande invalide :/',
                ], 422);
            }
        }

        /**
         *
         * Test si les items commandés appartiennent bien au partenaire et prépare un tableau.
         */
        $partner = $this->getPartnerFromOrder($request);

        $items = [];
        foreach ($request->orders as $order) {
            $item = $this->partnerMenu->findOrFail($order['id']);
            $item['quantity'] = $order['quantity'];
            array_push($items, $item);
            $validation = $partner->id == $item->partner_id;
            if (!$validation) {
                return response()->json([
                    'error' => 'Commande invalide :/',
                ], 422);
            }
        }

        /**
         * Vérifie que la commande est bien passée en happy hour ou non.
         */
        if ($partner->HHStatus != $request->HHStatus) {
            if ($partner->HHStatus == 0) {
                return response()->json([
                    'error' => 'Le partenaire n\'est plus en Happy Hour. Nous avons annulé votre commande pour que vous puissiez prendre connaissance des prix hors Happy Hour !',
                ], 423);
            } else {
                return response()->json([
                    'error' => 'Le partenaire est en Happy Hour. Nous avons annulé votre commande pour que vous puissiez découvrir les prix en Happy Hour !',
                ], 423);
            }
        }

        /**
         * Test que le partenaire est bien ouvert ou non.
         */
        if ($partner->openStatus == 0) {
            return response()->json([
                'error' => 'Sorry, le partenaire a cloturé la prise de commande avec ' . Config::get('constants.company_name') . ' :/'
            ], 423);
        }

        /**
         * Si la commande est validée.
         */
        $user1 = $this->applicationUserRepository->getApplicationUserFromToken();
        $user2 = $this->applicationUser->where('tel', $tel)->get()->first();

        /**
         * Vérifie que le paiement est validé.
         */
        if ($user1->mango_card_id == null) {
            return response()->json([
                'error' => 'Votre moyen de paiement est inactif. Rendez-vous dans le menu de gauche, puis l\'onglet moyen de paiement pour mettre à jours vos moyens de paiement.',
            ], 422);
        }
        if ($user2->mango_card_id == null) {
            return response()->json([
                'error' => 'Le moyen de paiement de l\'utilisateur avec lequel vous souhatez partager votre addition est inactif. Il doit renseigner un moyen de paiement valide avant de pourvoir partager l\'addition.',
            ], 422);
        }

        $orderId = $this->orderInfoRepository->createNewOrderId();
        $orderInfo = $this->orderInfoRepository->newOrderInfo($user1->id, $partner, $orderId, $user2->id);
        /**
         * Stocke les commandes dans la table "OrderInfoTemp".
         */
        $this->orderInfoShareBillRepository->newOrderInfoShareBill($user1, $user2, $partner, $orderInfo->id, $orderId);

        foreach ($items as $item) {
            $this->orderRepository->newOrder(
                $orderInfo->id,
                $item['category_id'],
                $item['name'],
                $item['price'],
                $item['HHPrice'],
                $item['tax'],
                $item['alcohol'],
                $item['quantity']
            );
        }

        $result = $this->FCMNotificationsHandler->sendNotificationToSpecificUser(
            $user2,
            'Partage d\'addition',
            "L'utilisateur " . ucfirst($user1->firstName) . ' ' . ucfirst($user1->lastName) . " souhaite partager une addition avec vous. Vous pouvez voir cela dans l'historique des commandes. En haut dans le menu de gauche.",
            'default',
            0,
            ['share_bill' => $orderInfo->id]
        );

        if (!$result) {
            return response()->json([
                'message' => "L'utilisateur sélectionné pour le partage de l'addition ne semble pas être être connecté. La demande a tout de même été envoyée."
            ], 200);
        }

        //TODO - Supprimer si la notification n'est pas envoyée.

        return response()->json([
            'message' => 'Une fois le partage de votre commande validé par l\'autre utilisateur, le bar recevra celle-ci. Si elle n\'est pas validée dans les ' . CheckOrdersShareBill::TIME_TO_LIVE_FOR_ORDERS . ' min, elle sera automatiquement annulée !'
        ], 200);
    }

    /**
     * Cette fonction se charge d'obtenir le partenaire à partir d'une commande.
     *
     * Les variables de la requête :
     * - (array) orders :
     * -- (object) with id and quantity
     * - HHStatus boolean
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model Is the partner model.
     */
    private function getPartnerFromOrder
    (
        Request $request
    )
    {
        $itemMenu = $this->partnerMenu->findOrFail($request->orders[0]['id']);
        $partner = $this->partner->findOrFail($itemMenu['partner_id']);

        return $partner;
    }

    /**
     * Cette fonction se charge de retourner en JSON les commandes d'un utilisateur.
     *
     * --> Obtient l'utilisteur à partir du token d'authenfication.
     * --> Récupère les commandes pour cet utilisateur.
     * --> Prépare les commandes pour l'interface utilisateur.
     * --> Envoi les commandes au format JSON.
     *
     * @return \Illuminate\Http\JsonResponse Return prepared orders for applicationUser client interface.
     */
    public function getOrders()
    {
        $applicationUser = $this->applicationUserRepository->getApplicationUserFromToken();

        $orders = $this->orderInfoRepository->getOrdersInfoOfApplicationUserSortBy($applicationUser->id, 'created_at');

        $preparedOrders = $this->ordersHandler->prepareArrayForApplicationUserClient($orders);

        return response()->json($preparedOrders, 200);
    }

    /**
     * Cette fonction est déclenchée par une notfication envoyée sur l'interface de l'utilisateur, pour que l'interface
     * télécharge la commande partagée (afin que l'utilisateur puisse l'accepter ou non).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSharedOrder
    (
        Request $request
    )
    {
        //TODO: Vérifier si le token d'authentification est nécéssaire ou non?
        $this->validate($request,
            [
                'order_id' => 'exists:orders_info_share_bill,order_id'
            ]);

        $orderInfo = $this->orderInfo->where('id', $request->order_id)->get();


        $preparedOrders = $this->ordersHandler->prepareArrayForApplicationUserClient($orderInfo);

        return response()->json($preparedOrders, 200);
    }

    /**
     * Cette fonction se charge d'accepter une demannde de partage pour un utilisateur.
     *
     * --> Vérifie que l'id de la commande partagée est présent dans la table "orders_info_share_bill".
     * --> Récupère l'utilisateur à partir du token d'authentification.
     * --> Récupère la commande paratagé dans la table "orders_info_share_bill".
     * --> Vérifie l'éxprition avant acceptation et envoi un message d'erreur ou de succès en JSON.
     * --> Enregistre la commande comme accetpé dans la table "orders_info_share_bill".
     * --> Enregistre la commande dans la table "OrderInfoTemp".
     * --> Envoi une notification à l'utilisateur pour le prévenir que la commande est acceptée.
     * --> Prépare et envoi la commande à l'interface du partenaire.
     * --> Retourne un message JSON de succès à l'utilisateur 2.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptSharedOrder
    (
        Request $request
    )
    {
        $this->validate($request,
            [
                'order_id' => 'exists:orders_info_share_bill,order_id'
            ]);

        $applicationUser = $this->applicationUserRepository->getApplicationUserFromToken();

        $orderInfoShareBill = $this->orderInfoShareBill
            ->where('applicationUser_id_2', $applicationUser->id)
            ->where('order_id', $request->order_id)
            ->get()->first();

        if ($orderInfoShareBill->expired == true) {
            return response()->json([
                'error' => 'Le partage de la commande a expiré :/ Vous devez répondre à une demande de partage dans un délai de ' . CheckOrders::TIME_TO_LIVE_FOR_ORDERS . ' minutes.',
            ], 422);
        }
        if ($orderInfoShareBill->accepted == true) {
            return response()->json([
                'error' => 'Il semble qu\'il y ait une erreur puisque cette demande de partage a déjà été validée :/',
            ], 422);
        }

        $orderInfoShareBill->accepted = 1;
        $orderInfoShareBill->update();

        $this->orderInfoTempRepository->newOrderInfoTemp(
            $orderInfoShareBill->order_id,
            $orderInfoShareBill->orderId,
            $orderInfoShareBill->applicationUser_id_1,
            $orderInfoShareBill->partner_id,
            $orderInfoShareBill->applicationUser_id_2);

        //TODO: Vérifier si l'id du partenaire est sécurisé dans le code ci dessous.

        $this->FCMNotificationsHandler->sendNotificationToSpecificUser(
            $this->applicationUser->findOrFail($orderInfoShareBill->applicationUser_id_1),
            'Partage accepté',
            'Votre demande de partager une note a bien été acceptée. Le bar va recevoir votre commande.',
            'default',
            0,
            null);

        $orderInfoTemp = $this->orderInfoTemp->where('partner_id', $orderInfoShareBill->partner_id)->get();
        $orders = $this->ordersHandler->prepareArrayForPartnerClient($orderInfoTemp);
        event(new OrderEvent(['orders' => $orders, 'partner_id' => $orderInfoShareBill->partner_id]));

        return response()->json([
            'message' => 'Commande réussie ! Celle-ci est maintenant en attente de validation par le barman. Si elle n\'est pas validée dans les ' . CheckOrders::TIME_TO_LIVE_FOR_ORDERS . '  min, elle sera automatiquement annulée !',
        ], 200);
    }

    /**
     * Cette fonction se charge de refuser une demande de partage pour un utilisateur.
     *
     * --> Vérifie que l'id de la commande partagée est présent dans la table "orders_info_share_bill".
     * --> Récupère l'utilisateur à partir du token d'authentification.
     * --> Récupère la commande paratagé dans la table "orders_info_share_bill".
     * --> Vérifie l'éxprition avant acceptation et envoi un message d'erreur ou de succès en JSON.
     * --> Enregistre la commande comme accetpé dans la table "orders_info_share_bill".
     * --> Enregistre la commande dans la table "OrderInfoTemp".
     * --> Envoi une notification à l'utilisateur pour le prévenir que la commande est acceptée.
     * --> Prépare et envoi la commande à l'interface du partenaire.
     * --> Retourne un message JSON de succès à l'utilisateur 2.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refuseSharedOrder
    (
        Request $request
    )
    {
        $this->validate($request,
            [
                'order_id' => 'exists:orders_info_share_bill,order_id'
            ]);

        $applicationUser = $this->applicationUserRepository->getApplicationUserFromToken();

        $orderInfoShareBill = $this->orderInfoShareBill
            ->where('applicationUser_id_2', $applicationUser->id)
            ->where('order_id', $request->order_id)
            ->get()->first();

        if ($orderInfoShareBill->accepted == true) {
            return response()->json([
                'error' => 'Il semble qu\'il y ait une erreur puisque cette demande de partage a déjà été validée :/',
            ], 422);
        }

        $orderInfoShareBill->expired = 1;
        $orderInfoShareBill->update();

        $orderInfo = $this->orderInfo->findOrFail($orderInfoShareBill->order_id);
        $orderInfo->accepted = 0;
        $orderInfo->update();

        $this->FCMNotificationsHandler->sendNotificationToSpecificUser(
            $this->applicationUser->findOrFail($orderInfoShareBill->applicationUser_id_1),
            'Partage refusé',
            'Votre demande de partager une note a été refusée. Le bar ne recevra pas votre commande.',
            'default',
            0,
            null);

        return response()->json([
            'message' => 'Le partage de commande a bien été refusé. Aucune commande ne sera passée.',
        ], 200);
    }

}
