<?php

namespace App\Repositories;

use App\NotificationChecker;
use App\OrderInfo;

/**
 * Cette classe est un gestionnaire.
 *
 * Class NotificationCheckerRepository
 * @package App\Repositories
 */
class NotificationCheckerRepository
{
    /**
     * @var NotificationChecker
     */
    private $notificationChecker;

    /**
     * NotificationCheckerRepository constructor.
     * @param NotificationChecker $notificationChecker
     */
    public function __construct
    (
        NotificationChecker $notificationChecker
    )
    {
        $this->notificationChecker = $notificationChecker;
    }

    public function newNotificationChecker
    (
        $applicationUser_id,
        $partner_id,
        $order_id,
        $notification_status,
        $type
    )
    {
        if (empty($notification_status)) {
            $notification_status = false;
        }

        $notificationChecker = $this->notificationChecker->create([
            'applicationUser_id' => $applicationUser_id,
            'partner_id' => $partner_id,
            'order_id' => $order_id,
            'orderId' => OrderInfo::findOrFail($order_id)->orderId,
            'notification_status' => $notification_status,
            'type' => $type
        ]);
        $notificationChecker->save();

        return $notificationChecker;
    }

}