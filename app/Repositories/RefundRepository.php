<?php

namespace App\Repositories;

use App\Incident;
use App\IncidentMonitoring;
use App\Refund;

/**
 * Cette classe sert de dépôt et gère les opérations courantes pour le model "Refund".
 *
 * Class RefundRepository
 * @package App\Repositories
 */
class RefundRepository
{
    /**
     * C'est un model.
     *
     * @var Refund
     */
    private $refund;

    /**
     * C'est un model.
     *
     * @var Incident
     */
    private $incident;

    /**
     * C'est un model.
     *
     * @var IncidentMonitoring
     */
    private $incidentMonitoring;

    /**
     * RefundRepository constructor.
     * @param Refund $refund
     * @param Incident $incident
     * @param IncidentMonitoring $incidentMonitoring
     */
    public function __construct
    (
        Refund $refund,
        Incident $incident,
        IncidentMonitoring $incidentMonitoring
    )
    {
        $this->refund = $refund;
        $this->incident = $incident;
        $this->incidentMonitoring = $incidentMonitoring;
    }

    /**
     * Cette fonction créer un nouvel enregistrement de remboursement dans la table "refunds" de la base de données application.
     *
     * @param $applicationUser_id
     * @param $partner_id
     * @param $order_id
     * @param $incident_id
     * @param $amount
     * @param $status
     * @param $description
     * @param $mango_refund_id
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function newRefund
    (
        $applicationUser_id,
        $partner_id,
        $order_id,
        $incident_id,
        $amount,
        $status,
        $description,
        $mango_refund_id
    )
    {
        $refund = $this->refund->create([
            'applicationUser_id' => $applicationUser_id,
            'partner_id' => $partner_id,
            'order_id' => $order_id,
            'incident_id' => $incident_id,
            'amount' => $amount,
            'success' => $status,
            'description' => $description,
            'mango_refund_id' => $mango_refund_id
        ]);
        $refund->save();

        return $refund;
    }

}