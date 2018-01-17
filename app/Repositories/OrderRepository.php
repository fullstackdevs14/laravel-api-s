<?php

namespace App\Repositories;

use App\Order;

/**
 * Cette classe sert de dépôt et gère les opérations courantes pour le model "Order".
 *
 * Class OrderRepository
 * @package App\Repositories
 */
class OrderRepository
{
    /**
     * C'est un model.
     *
     * @var Order
     */
    private $order;

    /**
     * OrderRepository constructor.
     * @param Order $order
     */
    public function __construct
    (
        Order $order
    )
    {
        $this->order = $order;
    }

    /**
     * Cette fonction enregistre un nouvel item dans la table "orders" de la base de données application.
     *
     * @param $order_id
     * @param $category_id
     * @param $name
     * @param $price
     * @param $HHPrice
     * @param $tax
     * @param $alcohol
     * @param $quantity
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function newOrder
    (
        $order_id,
        $category_id,
        $name,
        $price,
        $HHPrice,
        $tax,
        $alcohol,
        $quantity
    ){
        $order = $this->order->create(
            [
                'order_id' => $order_id,
                'category_id' => $category_id,
                'itemName' => $name,
                'itemPrice' => $price,
                'itemHHPrice' => $HHPrice,
                'tax' => $tax,
                'alcohol' => $alcohol,
                'quantity' => $quantity,
            ]
        );
        $order->save();

        return $order;
    }

}