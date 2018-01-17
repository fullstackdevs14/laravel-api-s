<?php

namespace App\Repositories;

use App\OrderInfo;
use App\Partner;

/**
 * Cette classe sert de dépôt et gère les opérations courantes pour le model "OrderInfo".
 *
 * Class OrderInfoRepository
 * @package App\Repositories
 */
class OrderInfoRepository
{
    /**
     * C'est un model.
     *
     * @var OrderInfo
     */
    private $orderInfo;

    /**
     * OrderInfoRepository constructor.
     * @param OrderInfo $orderInfo
     */
    public function __construct
    (
        OrderInfo $orderInfo
    )
    {
        $this->orderInfo = $orderInfo;
    }

    /**
     * Cette fonction retourne un model avec toutes les informations concernant une commande (hors détails des items commandés).
     *
     * @param $orderInfo_id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function findById
    (
        $orderInfo_id
    )
    {
        return $this->orderInfo->findOrFail($orderInfo_id);
    }

    /**
     * Cette fonction retourne un model avec toutes les informations concernant les commandes d'un utilisateur
     * (hors détails des items commandés), classé en fonction de ce qui est renseigné dans la variable "sortedBy",
     * exemple : "created_at".
     *
     * @param $applicationUser_id
     * @param $sortedBy
     * @return static
     */
    public function getOrdersInfoOfApplicationUserSortBy
    (
        $applicationUser_id,
        $sortedBy
    )
    {
        $orders = $this->orderInfo
            ->where('applicationUser_id', $applicationUser_id)
            ->orWhere('applicationUser_id_share_bill', $applicationUser_id)
            ->get()
            ->sortByDesc($sortedBy);

        return $orders;
    }

    /**
     * Cette fonction créer un récapitulatif pour une nouvelle commande dans la table "orders_info" en base de données application.
     *
     * @param $applicationUser_id
     * @param Partner $partner
     * @param $orderId
     * @param null $applicationUser_id_share_bill
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function newOrderInfo
    (
        $applicationUser_id,
        Partner $partner,
        $orderId,
        $applicationUser_id_share_bill = null
    )
    {
        $orderInfo = $this->orderInfo->create(
            [
                'applicationUser_id' => $applicationUser_id,
                'partner_id' => $partner->id,
                'orderId' => $orderId,
                'HHStatus' => $partner->HHStatus,
                'accepted' => null,
                'delivered' => 0,
                'incident' => 0,
                'fees' => $partner->fees,
                'applicationUser_id_share_bill' => $applicationUser_id_share_bill
            ]
        );
        $orderInfo->save();

        return $orderInfo;
    }

    /**
     * Cette fonction créer un nouvel identifiant de commande pour une nouvelle comande.
     *
     * @return string
     */
    public function createNewOrderId()
    {
        do {
            $orderId = strtoupper(substr(uniqid(), 8, 11));
        } while (!$this->orderInfo->where('orderId', '=', $orderId)->get()->isEmpty());

        return $orderId;
    }

    /**
     * Cette fonction modifie une commande comme acceptée SANS incident dans la table "orders_info" de la base de
     * données application.
     *
     * @param $orderInfo_id
     */
    public function acceptedWithoutIncidentFindOrFail
    (
        $orderInfo_id
    )
    {
        $order = $this->orderInfo->findOrFail($orderInfo_id);
        $order->accepted = 1;
        $order->update();
    }

    /**
     * Cette fonction modifie une commande comme acceptée AVEC incident dans la table "orders_info" de la base de
     * données application.
     *
     * @param $orderInfo_id
     */
    public function acceptedWithIncidentFindOrFail
    (
        $orderInfo_id
    )
    {
        $order = $this->orderInfo->findOrFail($orderInfo_id);
        $order->accepted = 1;
        $order->incident = 1;
        $order->update();
    }

    /**
     * Cette fonction modifie une commande comme ayant un incident dans la table "orders_info" de la base de
     * données application.
     *
     * @param $orderInfo_id
     */
    public function createIncidentFindOrFail
    (
        $orderInfo_id
    )
    {
        $order = $this->orderInfo->findOrFail($orderInfo_id);
        $order->incident = 1;
        $order->update();
    }

    /**
     * Cette fonction modifie une commande comme déclinée SANS incident dans la table "orders_info" de la base de
     * données application.
     *
     * @param $orderInfo_id
     */
    public function declinedWithoutIncidentFindOrFail
    (
        $orderInfo_id
    )
    {
        $order = $this->orderInfo->findOrFail($orderInfo_id);
        $order->accepted = 0;
        $order->update();
    }

    /**
     * Cette fonction modifie une commande comme délivrée SANS incident dans la table "orders_info" de la base de
     * données application.
     *
     * @param $orderInfo_id
     */
    public function deliveredWithoutIncidentFindOrFail
    (
        $orderInfo_id
    )
    {
        $order = $this->orderInfo->findOrFail($orderInfo_id);
        $order->delivered = 1;
        $order->update();
    }

}