<?php

namespace App\Http\Controllers\API\ApplicationUsers;

use App\ApplicationUser;
use App\Http\Controllers\Controller;
use App\Repositories\ApplicationUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use JWTAuth;

/**
 * Cette classe gère les envois de message aux administrateur de l'interface client vers les mails de l'application.
 *
 * Class MessagesController
 * @package App\Http\Controllers\API\ApplicationUsers
 */
class MessagesController extends Controller
{
    /**
     * C'est un model.
     *
     * @var ApplicationUser
     */
    private $applicationUser;

    /**
     * Dépot du model "ApplicationUser".
     *
     * @var ApplicationUserRepository
     */
    private $applicationUserRepository;

    /**
     * MessagesController constructor.
     * @param ApplicationUser $applicationUser
     * @param ApplicationUserRepository $applicationUserRepository
     */
    public function __construct
    (
        ApplicationUser $applicationUser,
        ApplicationUserRepository $applicationUserRepository
    )
    {
        Config::set('jwt.user', ApplicationUser::class);
        Config::set('auth.providers.users.model', ApplicationUser::class);
        $this->applicationUser = $applicationUser;
        $this->applicationUserRepository = $applicationUserRepository;
    }

    /**
     * Gère l'envoi d'un mail de demande d'aide à partir de l'interface utilisateur.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function helpMessage
    (
        Request $request
    )
    {
        $this->validate($request, [
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        $this->applicationUser = $this->applicationUserRepository->getApplicationUserFromToken();

        Mail::send('emails.help_message',
            [
                'subject' => $request->subject,
                'body' => $request->body,
                'applicationUser' => $this->applicationUser
            ], function ($message) {
                $message->to(Config::get('constants.mail_main'))->subject('Message de demande d\'aide de : ' . $this->applicationUser->firstName . ' ' . $this->applicationUser->lastName);
            });

        return response()->json([
            'message' => 'Le message a bien été envoyé.',
        ], 200);
    }

    /**
     * Gère l'envoi d'un mail de signalement d'un problème à partir de l'interface utilisateur.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function issueMessage
    (
        Request $request
    )
    {
        $this->validate($request, [
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        $this->applicationUser = $this->applicationUserRepository->getApplicationUserFromToken();

        Mail::send('emails.help_message',
            [
                'subject' => $request->subject,
                'body' => $request->body,
                'applicationUser' => $this->applicationUser
            ], function ($message) {
                $message->to(Config::get('constants.mail_main'))->subject('Signalisation d\'un problem de : ' . $this->applicationUser->firstName . ' ' . $this->applicationUser->lastName);
            });

        return response()->json([
            'message' => 'Le message a bien été envoyé.',
        ], 200);
    }

}
