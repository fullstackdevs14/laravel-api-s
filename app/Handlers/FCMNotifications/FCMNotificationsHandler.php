<?php

namespace App\Handlers\FCMNotifications;

use App\ApplicationUser;
use App\ApplicationUserNotificationToken;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

/**
 * Gère les méthodes de la librairie LaravelFCM.
 *
 * Class FCMNotificationsHandler
 * @package App\Handlers\FCMNotifications
 */
class FCMNotificationsHandler
{
    /**
     * Envoi une notification à un utilisateur en particulier utilisant le token de la table « application_users_notification_token » pour identifier cet utilisateur.
     * Met à jour la base de données des tokens (supprime les tokens invalides).
     *
     * @param ApplicationUser $applicationUser
     * @param string $title
     * @param string $body
     * @param string $sound
     * @param int $badge
     * @param array|null $data
     * @return array|bool
     * @comment Return : Array is the sum up of failure, success, modification. Bool return false if no token is found for the applicationUser.
     */
    public function sendNotificationToSpecificUser
    (
        ApplicationUser $applicationUser,
        $title,
        $body,
        $sound,
        $badge,
        $data
    )
    {
        $token = ApplicationUserNotificationToken::select('notificationToken')
            ->where('applicationUser_id', $applicationUser->id)
            ->get()
            ->toArray();

        if ($token == []) {
            return false;
        }

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);
        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)->setSound($sound)->setBadge($badge)->setClickAction("FCM_PLUGIN_ACTIVITY");

        $dataBuilder = new PayloadDataBuilder();
        if ($data == null) {
            $dataBuilder->addData(['title' => $title, 'message' => $body]);
        } else {
            $payload = array_merge($data, ['title' => $title, 'message' => $body]);
            $dataBuilder->addData($payload);
        }

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();

        $data = $dataBuilder->build();
        $downstreamResponse = FCM::sendTo($token[0]['notificationToken'], $option, $notification, $data);

        $this->dealWithTokens(
            $downstreamResponse->tokensToDelete(),
            $downstreamResponse->tokensToModify(),
            $downstreamResponse->tokensWithError()
        );

        if ($downstreamResponse->numberSuccess() > 0) {
            $result = true;
        } else {
            $result = false;
        }

        return [
            'result' => $result,
            'numberSuccess' => $downstreamResponse->numberSuccess(),
            'numberFailure' => $downstreamResponse->numberFailure(),
            'numberModification' => $downstreamResponse->numberModification()
        ];
    }

    /**
     * Envoi une notification un groupe d'utilisateurs utilisant un tableau de tokens provenant de la table « application_users_notification_token » pour identifier les utilisateurs.
     * Met à jour la base de données des tokens (supprime les tokens invalides).
     *
     * @param array $tokens
     * @param string $title
     * @param string $body
     * @param string $sound
     * @param int $badge
     * @param null $data
     * @return array|bool
     * @comment Return : Array is the sum up of failure, success, modification. Bool return false if no token is found for the applicationUser.
     */
    public function sendNotificationToGroup
    (
        $tokens,
        $title,
        $body,
        $sound,
        $badge,
        $data

    )
    {
        $optionBuiler = new OptionsBuilder();
        $optionBuiler->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)->setSound($sound)->setBadge($badge)->setClickAction("FCM_PLUGIN_ACTIVITY");

        $dataBuilder = new PayloadDataBuilder();
        if ($data == null) {
            $dataBuilder->addData(['title' => $title, 'message' => $body]);
        } else {
            $payload = array_merge($data, ['title' => $title, 'message' => $body]);
            $dataBuilder->addData($payload);
        }

        $option = $optionBuiler->build();
        $notification = $notificationBuilder->build();


        if ($tokens == []) {
            return false;
        }

        $data = $dataBuilder->build();
        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        $this->dealWithTokens(
            $downstreamResponse->tokensToDelete(),
            $downstreamResponse->tokensToModify(),
            $downstreamResponse->tokensWithError()
        );

        foreach ($downstreamResponse->tokensToRetry() as $token) {
            if ($data !== null) {
                $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
            } else {
                $downstreamResponse = FCM::sendTo($token, $option, $notification);
            }
        }

        return [
            'numberSuccess' => $downstreamResponse->numberSuccess(),
            'numberFailure' => $downstreamResponse->numberFailure(),
            'numberModification' => $downstreamResponse->numberModification()
        ];
    }

    /**
     * Envoi une notification à tous les utilisateurs utilisant un tableau de token provenant de la table « application_users_notification_token » pour identifier les utilisateurs.
     * Met à jour la base de données des tokens (supprime les tokens invalides).
     *
     * @param string $title
     * @param string $body
     * @param string $sound
     * @param int $badge
     * @param null $data
     * @return array|bool
     * @comment Return : Array is the sum up of failure, success, modification. Bool return false if no token is found for the applicationUser.
     */
    public function sendNotificationToEveryone
    (
        $title,
        $body,
        $sound,
        $badge,
        $data
    )
    {
        $optionBuiler = new OptionsBuilder();
        $optionBuiler->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)->setSound($sound)->setBadge($badge)->setClickAction("FCM_PLUGIN_ACTIVITY");

        $dataBuilder = new PayloadDataBuilder();
        if ($data == null) {
            $dataBuilder->addData(['title' => $title, 'message' => $body]);
        } else {
            $payload = array_merge($data, ['title' => $title, 'message' => $body]);
            $dataBuilder->addData($payload);
        }

        $option = $optionBuiler->build();
        $notification = $notificationBuilder->build();

        $tokens = ApplicationUserNotificationToken::pluck('notificationToken')->toArray();

        if ($tokens == []) {
            return false;
        }

        $data = $dataBuilder->build();
        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        $this->dealWithTokens(
            $downstreamResponse->tokensToDelete(),
            $downstreamResponse->tokensToModify(),
            $downstreamResponse->tokensWithError()
        );

        foreach ($downstreamResponse->tokensToRetry() as $token) {
            if ($data !== null) {
                $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
            } else {
                $downstreamResponse = FCM::sendTo($token, $option, $notification);
            }
        }

        return [
            'numberSuccess' => $downstreamResponse->numberSuccess(),
            'numberFailure' => $downstreamResponse->numberFailure(),
            'numberModification' => $downstreamResponse->numberModification()
        ];
    }

    /**
     * Met à jour les token présents dans la table « application_users_notification_token ».
     *
     * @param array $tokensToDelete
     * @param array $tokensToModify
     * @param array $tokensWithErrorToDelete
     * @comment Different variables are array of tokens.
     */
    public function dealWithTokens
    (
        $tokensToDelete,
        $tokensToModify,
        $tokensWithErrorToDelete
    )
    {
        $this->tokensToDelete($tokensToDelete);
        $this->tokensToModify($tokensToModify);
        $this->tokensWithErrorToDelete($tokensWithErrorToDelete);
    }

    /**
     * Supprime les tokens de la table « application_users_notification_token ».
     *
     * @param array $tokensToDelete
     */
    public function tokensToDelete
    (
        $tokensToDelete
    )
    {
        foreach ($tokensToDelete as $token) {
            ApplicationUserNotificationToken::where('notificationToken', $token)->delete();
        }
    }

    /**
     * Met à jour les tokens de la table « application_users_notification_token ».
     *
     * @param array $tokensToModify
     *
     * @comment In array the key is the old token and value the new one.
     */
    public function tokensToModify
    (
        $tokensToModify
    )
    {
        foreach ($tokensToModify as $key => $value) {
            $token = ApplicationUserNotificationToken::where('notificationToken', $key)->get();
            $token->notificationToken = $value;
            $token->update();
        }
    }

    /**
     * Supprime les tokens du tableau où la clé est le token à supprimer et la valeur est l'erreur.
     *
     * @param $tokensWithErrorToDelete
     */
    public function tokensWithErrorToDelete
    (
        $tokensWithErrorToDelete
    )
    {
        foreach ($tokensWithErrorToDelete as $key => $value) {
            ApplicationUserNotificationToken::where('notificationToken', $key)->delete();
        }
    }

}