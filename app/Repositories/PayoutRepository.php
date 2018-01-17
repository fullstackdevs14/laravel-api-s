<?php

namespace App\Repositories;

use App\Payout;

/**
 * Cette class gère les méthode lié au modèle Payout.
 *
 * Class PayoutRepository
 * @package App\Repositories
 */
class PayoutRepository
{

    /**
     * C'est un modèle.
     *
     * @var Payout
     */
    private $payOut;

    public function __construct
    (
        Payout $payOut
    )
    {
        $this->payOut = $payOut;
    }

    /**
     * Cette méthode enregistre un versement au partner dans la bdd application.
     *
     * @param $partner_id
     * @param $amount
     * @param $success
     * @param $description
     * @param $mango_payout_id
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function create
    (
        $partner_id,
        $amount,
        $success,
        $description,
        $mango_payout_id
    )
    {
        $payOut = $this->payOut->create([
            'partner_id' => $partner_id,
            'amount' => $amount,
            'success' => $success,
            'description' => $description,
            'mango_payout_id' => $mango_payout_id
        ]);
        $payOut->save();

        return $payOut;
    }

}