<?php

namespace App\Handlers\Orders;

use App\Order;
use App\Partner;
use App\OrderInfo;
use App\OrderInfoTemp;
use App\OrderInfoShareBill;
use Illuminate\Support\Facades\Config;

/**
 * Ce gestionnaire a pour vocation de regrouper l'ensemble des opérations complexes et répétées dans plusieurs contrôleurs à un seul et même endroit.
 * Toutes ces opérations concernent les commandes.
 *
 * Class OrdersHandler
 * @package App\Handlers\Orders
 */
class OrdersHandler
{
    /**
     * Cette méthode prépare un tableau de commande à destination du client de l'utilisateur.
     * Elle fonctionne avec la méthode « getOrdersInfoOfApplicationUserSortBy »  du référentiel « OrderInfoRepository ».
     * Voir : App\Repositories\OrderInfoRepository.
     *
     * @param OrderInfo $orders
     * @return object
     */
    public function prepareArrayForApplicationUserClient
    (
        $orders
    )
    {
        $newArray = [];
        foreach ($orders as $order) {
            $partner = Partner::findOrFail($order->partner_id);
            $accepted = $order->accepted;
            $incident = $order->incident;
            $timestamp = $order->created_at;
            $HHStatus = $order->HHStatus;
            $items = Order::where('order_id', $order->id)->get();

            $amount = $this->makeBillAmount($order->HHStatus, $items);
            if ($order->applicationUser_id_share_bill != null) {
                $amount = $amount / 2;
            }

            if ($order->applicationUser_id_share_bill != null) {
                $orderInfoShareBill = OrderInfoShareBill::where('order_id', $order->id)->get()->first();

                $shareBillAccepted = $orderInfoShareBill['accepted'];
                $shareBillExpired = $orderInfoShareBill['expired'];
            } else {
                $shareBillAccepted = null;
                $shareBillExpired = null;
            }

            array_push(
                $newArray,
                ['id' => $order->id] +
                ['orderId' => $order->orderId] +
                ['applicationUser_id_share_bill' => $order->applicationUser_id_share_bill] +
                ['partner' => $partner] +
                ['amount' => $amount] +
                ['accepted' => $accepted] +
                ['incident' => $incident] +
                ['timestamp' => $timestamp] +
                ['HHStatus' => $HHStatus] +
                ['items' => $items] +
                ['shareBillAccepted' => $shareBillAccepted] +
                ['shareBillExpired' => $shareBillExpired]
            );
        }

        $lastArray = (object)['orders' => $newArray];

        return $lastArray;
    }

    /**
     * Cette méthode prépare un tableau de commande pour le client du partenaire.
     * Elle ne retourne que les commandes qui n'ont pas encore été traitées.
     *
     * @param OrderInfoTemp $orderInfoTemp
     * @return array Orders that are actually not handled.
     */
    public function prepareArrayForPartnerClient
    (
        $orderInfoTemp
    )
    {
        $orders = [];
        foreach ($orderInfoTemp as $order) {
            $order = OrderInfo::findOrFail($order->order_id);
            $order->applicationUser;
            if (isset($order->applicationUser->picture)) {
                $order->applicationUser->picture = Config::get('constants.base_url_application_user') . $order->applicationUser->picture;
            }
            $order->items;
            array_push($orders, $order);
        }
        return $orders;
    }

    /**
     * Cette ode préparent un tableau de commande pour le client du partenaire.
     * Elle ne retourne que les commandes qui ont été traitées.
     *
     * @param OrderInfo $orderInfo
     * @return array Orders that are handled.
     */
    public function prepareArrayForPartnerClientOldOrders
    (
        $orderInfo
    )
    {
        $orders = [];
        foreach ($orderInfo as $order) {
            $order->applicationUser;
            if (isset($order->applicationUser->picture)) {
                $order->applicationUser->picture = Config::get('constants.base_url_application_user') . $order->applicationUser->picture;
            }
            $order->items;
            array_push($orders, $order);
        }
        return array_reverse($orders);
    }

    /**
     * Cette méthode retourne la somme totale de la commande pour un modèle fourni « Order ».
     *
     * @param bool $HHStatus
     * @param array $orders
     * @return int Amount.
     */
    public function makeBillAmount
    (
        $HHStatus,
        $orders
    )
    {
        $amount = 0;
        foreach ($orders as $item) {
            if ($HHStatus == 0) {
                $amount += $item['itemPrice'] * $item['quantity'];
            } else {
                $amount += $item['itemHHPrice'] * $item['quantity'];
            }
        }
        return $amount;
    }

}