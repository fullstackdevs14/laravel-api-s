<?php

namespace App\Http\Controllers\API\ApplicationUsers;

use App\ApplicationUser;
use App\ApplicationUserNotificationToken;
use App\Http\Controllers\Controller;
use App\Repositories\ApplicationUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use JWTAuth;

/**
 * Cette classe sert à enregistrer les utilisateurs dans la base de données application grâce à un token, pour les
 * identifier lors de l'envoi d'une notification.
 *
 * Utilisation de Fire Cloud Messaging. Les variable de communication entre l'api Firebase et l'api application sont
 * présentes dans le .env.
 *
 * Class NotificationController
 * @package App\Http\Controllers\API\ApplicationUsers
 */
class NotificationController extends Controller
{

    /**
     * Dépot du model "ApplicationUser".
     *
     * @var ApplicationUserRepository
     */
    private $applicationUserRepository;

    /**
     * C'est un model.
     *
     * @var ApplicationUserNotificationToken
     */
    private $applicationUserNotificationToken;

    /**
     * NotificationController constructor.
     * @param ApplicationUserRepository $applicationUserRepository
     * @param ApplicationUserNotificationToken $applicationUserNotificationToken
     */
    public function __construct
    (
        ApplicationUserRepository $applicationUserRepository,
        ApplicationUserNotificationToken $applicationUserNotificationToken
    )
    {
        Config::set('jwt.user', ApplicationUser::class);
        Config::set('auth.providers.users.model', ApplicationUser::class);
        $this->applicationUserRepository = $applicationUserRepository;
        $this->applicationUserNotificationToken = $applicationUserNotificationToken;
    }

    /**
     * Cette fonction enregistre l'utilisateur dans la base de données application, plus précisement dans le table :
     * "application_users_notification_token", pour l'identifier aurpès de l'api Fire Cloud Messaging (Google) lors de
     * l'envoi d'une push notification.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register
    (
        Request $request
    )
    {

        $this->validate($request, [
            'notificationToken' => 'required|string',
        ]);

        $applicationUser = $this->applicationUserRepository->getApplicationUserFromToken();

        $this->applicationUserNotificationToken->where('applicationUser_id', $applicationUser->id)->delete();

        $token = new ApplicationUserNotificationToken([
            'applicationUser_id' => $applicationUser->id,
            'notificationToken' => $request->input('notificationToken'),
        ]);

        $token->save();

        return response()->json([
            'message' => "Msg from server: notificationToken saved !",
        ], 201);
    }

}
