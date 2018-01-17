<?php

namespace App\Repositories;

use App\Incident;
use App\IncidentMonitoring;

/**
 * Cette classe sert de dépôt et gère les opérations courantes pour le model "Incident".
 *
 * Class IncidentRepository
 * @package App\Repositories
 */
class IncidentRepository
{
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
     * IncidentRepository constructor.
     * @param Incident $incident
     * @param IncidentMonitoring $incidentMonitoring
     */
    public function __construct
    (
        Incident $incident,
        IncidentMonitoring $incidentMonitoring
    )
    {
        $this->incident = $incident;
        $this->incidentMonitoring = $incidentMonitoring;
    }

    /**
     * Cette fonction créer un nouvel incident en base de données application dans la table "incidents".
     *
     * @param $order_id
     * @param $excuse
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function newIncident
    (
        $order_id,
        $excuse
    )
    {
        $incident = $this->incident->create([
            'order_id' => $order_id,
            'excuse' => $excuse,
            'status' => 0
        ]);
        $incident->save();

        return $incident;
    }

    /**
     * Cette fonction met à jour le message de description de l'incident en fonction d'une liste déjà enregistré dans la base de données application.
     *
     * @param $incident_id
     * @param $excuse
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function updateIncidentMessage
    (
        $incident_id,
        $excuse
    )
    {
        $incident = $this->incident->findOrFail($incident_id);
        $incident->excuse = $excuse;
        $incident->update();

        return $incident;
    }

    /**
     * Cette fonction enregistre un nouveau mémo pour pour un incident en base de données application dans la table "incidents_monitoring".
     *
     * @param $order_id
     * @param $message
     * @param $email
     * @param $phone
     * @param $reimburse
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function newMemo
    (
        $order_id,
        $message,
        $email,
        $phone,
        $reimburse
    )
    {
        $incidentMonitoring = $this->incidentMonitoring->create([
            'order_id' => $order_id,
            'message' => $message,
            'email' => $email,
            'phone' => $phone,
            'reimburse' => $reimburse
        ]);
        $incidentMonitoring->save();

        return $incidentMonitoring;
    }

    /**
     * Cette fonction enregistre un incident comme traité dans la table "incidents de la base de données application.
     *
     * @param $incident_id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function incidentHandled
    (
        $incident_id
    )
    {
        $incident = $this->incident->findOrFail($incident_id);
        $incident->status = 1;
        $incident->update();

        return $incident;
    }

    /**
     * Cette fonction enregistre un incident comme ouvert en base de données application dans la tables "incidents".
     *
     * @param $incident_id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function incidentOpened
    (
        $incident_id
    )
    {
        $incident = $this->incident->findOrFail($incident_id);
        $incident->status = 0;
        $incident->update();

        return $incident;
    }

    /**
     * Cette fonction enregistre un incident comme urgent dans la tables "incidents de la base de données application.
     *
     * @param $incident_id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function incidentUrgent
    (
        $incident_id
    )
    {
        $incident = $this->incident->findOrFail($incident_id);
        $incident->status = null;
        $incident->update();

        return $incident;
    }

}