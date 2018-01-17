<?php

namespace App\Http\Controllers\API\ApplicationUsers;

use App\ApplicationUser;
use App\ApplicationUserReplaceEmail;
use App\Events\ApplicationUserReplaceEmailEvent;
use App\Handlers\ToolsHandler;
use App\Http\Controllers\Controller;
use App\Repositories\ApplicationUserRepository;
use App\Repositories\ToolsRepository;
use Illuminate\Http\Request;

/**
 * Cette classe a pour rôle de permettre à l'utilisateur de modifier son email à partir de son interface.
 *
 * Class EmailReplaceController
 * @package App\Http\Controllers\API\ApplicationUsers
 */
class EmailReplaceController extends Controller
{
    /**
     * C'est un model.
     *
     * @var ApplicationUserReplaceEmail
     */
    private $applicationUserReplaceEmail;

    /**
     * C'est un model.
     *
     * @var ApplicationUserRepository
     */
    private $applicationUserRepository;

    /**
     * C'est un dépôt.
     *
     * @var ToolsRepository
     */
    private $toolsRepository;

    /**
     * C'est un model.
     *
     * @var ApplicationUser
     */
    private $applicationUser;

    /**
     * EmailReplaceController constructor.
     * @param ApplicationUserReplaceEmail $applicationUserReplaceEmail
     * @param ApplicationUserRepository $applicationUserRepository
     * @param ToolsRepository $toolsRepository
     * @param ApplicationUser $applicationUser
     */
    public function __construct
    (
        ApplicationUserReplaceEmail $applicationUserReplaceEmail,
        ApplicationUserRepository $applicationUserRepository,
        ToolsRepository $toolsRepository,
        ApplicationUser $applicationUser
    )
    {
        $this->applicationUserReplaceEmail = $applicationUserReplaceEmail;
        $this->applicationUserRepository = $applicationUserRepository;
        $this->toolsRepository = $toolsRepository;
        $this->applicationUser = $applicationUser;
    }

    /**
     * Cette fonction recoit le nouvel email quelle va stocker en base données application jusqu'à ce que celui-ci soit
     * validé par l'utilisateur.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function newEmailRequest
    (
        Request $request
    )
    {
        $this->validate($request, [
            'email' => 'required|email|unique:application_users_email_replace,email|unique:application_users,email'
        ]);

        $applicationUser = $this->applicationUserRepository->getApplicationUserFromToken();

        $token = ToolsHandler::makeToken(200);

        $this->applicationUserReplaceEmail->create([
            'applicationUser_id' => $applicationUser->id,
            'email' => $request->email,
            'token' => $token
        ]);

        event(new ApplicationUserReplaceEmailEvent($applicationUser, $request->email, $token));

        return response()->json(['message' => 'Un email de confirmation du nouvel email vient de vous être envoyé. Si celui-ci n\'est pas validé dans les 2 heures, votre email restera inchangé.'], 200);
    }

    /**
     * Cette fonction gère la validation du remplacement de l'email.
     *
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function newEmailConfirmation
    (
        $token
    )
    {
        $applicationUserReplaceEmail = $this->applicationUserReplaceEmail->where('token', $token)->first();
        $base_url = ToolsHandler::getBaseUrl();
        if ($applicationUserReplaceEmail === null) {
            $message = 'Demande expirée ou déjà utilisée :/';
            return view('applicationUsers_not_back_office.message', compact('message', 'base_url'));
        }

        $applicationUser = $this->applicationUser->findOrFail($applicationUserReplaceEmail->applicationUser_id);
        $applicationUser->email = $applicationUserReplaceEmail->email;
        $applicationUser->update();

        $applicationUserReplaceEmail->delete();

        $message = 'Email modifié :)';
        return view('applicationUsers_not_back_office.message', compact('message', 'base_url'));

    }

}
