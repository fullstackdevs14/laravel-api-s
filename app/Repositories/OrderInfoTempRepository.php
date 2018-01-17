<?php

namespace App\Repositories;

use App\OrderInfoTemp;

/**
 * Cette classe sert de dépôt et gère les opérations courantes pour le model "OrderInfoTemp".
 *
 * Class OrderInfoTempRepository
 * @package App\Repositories
 */
class OrderInfoTempRepository
{
    /**
     * C'est un model.
     *
     * @var OrderInfoTemp
     */
    private $orderInfoTemp;

    /**
     * OrderInfoTempRepository constructor.
     * @param OrderInfoTemp $orderInfoTemp
     */
    public function __construct
    (
        OrderInfoTemp $orderInfoTemp
    )
    {
        $this->orderInfoTemp = $orderInfoTemp;
    }

    /**
     * Cette fonction créer un commande dans la table "orders_info_temp" (hors détails des items).
     *
     * @param $order_id
     * @param $orderId
     * @param $applicationUser_id
     * @param $partner_id
     * @param null $applicationUser_id_share_bill
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function newOrderInfoTemp
    (
        $order_id,
        $orderId,
        $applicationUser_id,
        $partner_id,
        $applicationUser_id_share_bill = null
    )
    {
        $orderInfoTemp = $this->orderInfoTemp->create(
            [
                'order_id' => $order_id,
                'orderId' => $orderId,
                'applicationUser_id' => $applicationUser_id,
                'partner_id' => $partner_id,
                'application_user_id_share_bill' => $applicationUser_id_share_bill
            ]
        );
        $orderInfoTemp->save();

        return $orderInfoTemp;
    }

    /**
     * Cette fonction supprime la commande de la table"orders_info_temp" en fontion de l'id de commande renseigné.
     *
     * @param $order_id
     */
    public function deleteWhereIdOfOrderIs
    (
        $order_id
    )
    {
        $this->orderInfoTemp->where('order_id', $order_id)->delete();
    }

}