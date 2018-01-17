<?php

namespace App\Http\Controllers\Back_office\Notifications;

use App\ApplicationUser;
use App\ApplicationUserNotificationToken;
use App\Handlers\FCMNotifications\FCMNotificationsHandler;
use App\Http\Controllers\Controller;
use App\OrderInfo;
use App\Partner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Session;

/**
 * Cette classe gère l'envoi des notifications à partir de l'interface d'administration.
 *
 * Class NotificationController
 * @package App\Http\Controllers\Back_office\Notifications
 */
class NotificationController extends Controller
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
     * C'est un gestionnaire.
     *
     * Gère les action courantes liées au push notifications.
     *
     * @var FCMNotificationsHandler
     */
    private $FCMNotificationsHandler;

    /**
     * NotificationController constructor.
     * @param Partner $partner
     * @param OrderInfo $orderInfo
     * @param ApplicationUser $applicationUser
     * @param FCMNotificationsHandler $FCMNotificationsHandler
     */
    public function __construct
    (
        Partner $partner,
        OrderInfo $orderInfo,
        ApplicationUser $applicationUser,
        FCMNotificationsHandler $FCMNotificationsHandler
    )
    {
        $this->partner = $partner;
        $this->orderInfo = $orderInfo;
        $this->applicationUser = $applicationUser;
        $this->FCMNotificationsHandler = $FCMNotificationsHandler;
    }

    /**
     * Cette fonction retourne le formulaire permettant l'envoi d'une notification à tous les utilisateurs connectés.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function notificationForm()
    {
        return view('notifications.form');
    }

    /**
     * Cette fonction déclenche l'envoi d'une notification à tous les utilisateurs connectés.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function notificationSend
    (
        Request $request
    )
    {
        $this->validate($request, [
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        $response = $this->FCMNotificationsHandler->sendNotificationToEveryone
        (
            $request['title'],
            $request['body'],
            'default',
            0,
            null
        );

        if ($response === false) {
            Session::flash('error', "Pas de token enregistré pour les notifications !");
            return view('success');
        }

        Session::flash('message', "Notification envoyée !");

        return view('notifications.infos', compact('response'));
    }

    /**
     * Cette fonction retourne le formulaire permettant l'envoi d'une notification à des utilisateurs ciblés en fonction
     * de leur dernière commande chez un partenaire.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function targetedGroupNotificationForm()
    {
        $partners = $this->partner->all();
        return view('notifications.targeted.form_group', compact('partners'));
    }

    /**
     * Cette fonction déclenche l'envoi d'une notification à des utilisateurs ciblés en fonction de leur dernière
     * commande chez un partenaire.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function targetedGroupNotificationSend
    (
        Request $request
    )
    {
        $this->validate($request, [
            'partner_id' => 'required|exists:partners,id',
            'period' => Rule::in(['1-week', '2-week', '3-week', '1-month', '2-month', '3-month', '4-month']),
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        $period = $this->setNumericPeriod($request['period']);

        $orderInfo = $this->orderInfo->where('partner_id', $request['partner_id'])
            ->where('created_at', '>', Carbon::today()->subDay($period))
            ->get();

        $test = count($orderInfo);
        $tokens = [];
        if ($test != 0) {
            foreach ($orderInfo as $order) {
                $token = $this->applicationUser->findOrFail($order->applicationUser_id)
                    ->notificationToken()
                    ->get(['notificationToken'])
                    ->toArray();

                if (array_key_exists(0, $token) AND $token != false) {
                    array_push($tokens, $token[0]['notificationToken']);
                }
            }
        }

        $tokens = array_unique($tokens);

        $response = $this->FCMNotificationsHandler->sendNotificationToGroup(
            $tokens,
            $request['title'],
            $request['body'],
            'default',
            0,
            null);


        if ($response === false) {
            Session::flash('error', "Pas de token enregistré pour les notifications !");
            return view('success');
        }

        Session::flash('message', "Notifications envoyées !");

        return view('notifications.infos', compact('response'));
    }

    /**
     * @param $period
     * @return int
     */
    private function setNumericPeriod
    (
        $period
    )
    {
        switch ($period) {
            case "1-week":
                $period = 7;
                break;
            case "2-week":
                $period = 14;
                break;
            case "3-week":
                $period = 21;
                break;
            case "1-month";
                $period = 30;
                break;
            case "2-month";
                $period = 60;
                break;
            case "3-month";
                $period = 90;
                break;
            case "4-month";
                $period = 120;
                break;
        }
        return $period;
    }

    /**
     * Cette fonction retourne le formulaire permettant l'envoi d'une notification à un utilisateur ciblé.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function targetedUserNotificationForm()
    {
        $applicationUsers = ApplicationUserNotificationToken::all();

        $emails = [];

        foreach ($applicationUsers as $applicationUser) {
            array_push($emails, $applicationUser->applicationUser()->get(['email'])->first()->email);
        }

        $emails;

        return view('notifications.targeted.form_user', compact('emails'));
    }

    /**
     * Cette fonction déclenche l'envoi d'une notification à un utilisateur ciblé.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function targetedUserNotificationSend
    (
        Request $request
    )
    {
        $this->validate($request, [
            'email' => 'exists:application_users,email',
            'title' => 'required|string',
            'body' => 'required|string'
        ]);

        $applicationUser = $this->applicationUser->where('email', $request['email'])->first();

        $response = $this->FCMNotificationsHandler->sendNotificationToSpecificUser(
            $applicationUser,
            $request['title'],
            $request['body'],
            'default',
            0,
            null);


        if ($response === false) {
            Session::flash('error', "Pas de token enregistré pour les notifications !");
            return view('success');
        }

        Session::flash('message', "Notifications envoyées !");

        return view('notifications.infos', compact('response'));
    }

}
