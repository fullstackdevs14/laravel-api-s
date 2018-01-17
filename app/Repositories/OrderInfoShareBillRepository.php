<?php

namespace App\Repositories;

use App\ApplicationUser;
use App\OrderInfoShareBill;
use App\Partner;

/**
 * Cette classe sert de dépôt et gère les opérations courantes pour le model "OrderInfo".
 *
 * Class OrderInfoShareBillRepository
 * @package App\Repositories
 */
class OrderInfoShareBillRepository
{
    /**
     * @var OrderInfoShareBill
     */
    private $orderInfoShareBill;

    /**
     * OrderInfoShareBillRepository constructor.
     * @param OrderInfoShareBill $orderInfoShareBill
     */
    public function __construct
    (
        OrderInfoShareBill $orderInfoShareBill
    )
    {
        $this->orderInfoShareBill = $orderInfoShareBill;
    }

    /**
     * @param ApplicationUser $applicationUser1
     * @param ApplicationUser $applicationUser2
     * @param Partner $partner
     * @param $order_id
     * @param $orderId
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function newOrderInfoShareBill
    (
        ApplicationUser $applicationUser1,
        ApplicationUser $applicationUser2,
        Partner $partner,
        $order_id,
        $orderId
    )
    {
        $orderInfoShareBill = $this->orderInfoShareBill->create([
            'applicationUser_id_1' => $applicationUser1->id,
            'applicationUser_id_2' => $applicationUser2->id,
            'partner_id' => $partner->id,
            'order_id' => $order_id,
            'orderId' => $orderId,
            'accepted' => 0,
            'expired' => 0
        ]);
        $orderInfoShareBill->save();

        return $orderInfoShareBill;
    }

    /**
     * @param $share_bill_order_id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function orderNotAcceptedInTime
    (
        $share_bill_order_id
    )
    {
        $orderInfoShareBill = $this->orderInfoShareBill->findOrFail($share_bill_order_id);
        $orderInfoShareBill->expired = 1;
        $orderInfoShareBill->update();

        return $orderInfoShareBill;
    }

}