<?php

namespace App\Http\Controllers\API\ApplicationUsers;

use App\ApplicationUser;
use App\ApplicationUserResetPassword;
use App\Events\ApplicationUserResetPasswordEvent;
use App\Handlers\ToolsHandler;
use App\Http\Controllers\Controller;
use App\Repositories\ApplicationUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Validator;

/**
 * Cette classe gère la réinitialisation du mot de passe de l'utilisateur.
 *
 * Class ResetPasswordController
 * @package App\Http\Controllers\API\ApplicationUsers
 */
class ResetPasswordController extends Controller
{
    /**
     * C'est un model.
     *
     * @var ApplicationUser
     */
    private $applicationUser;

    /**
     * C'est le depôt du model "ApplicationUser".
     *
     * @var ApplicationUserRepository
     */
    private $applicationUserRepository;

    /**
     * C'est un model.
     *
     * @var ApplicationUserResetPassword
     */
    private $applicationUserResetPassword;

    /**
     * ResetPasswordController constructor.
     * @param ApplicationUser $applicationUser
     * @param ApplicationUserRepository $applicationUserRepository
     * @param ApplicationUserResetPassword $applicationUserResetPassword
     */
    public function __construct
    (
        ApplicationUser $applicationUser,
        ApplicationUserRepository $applicationUserRepository,
        ApplicationUserResetPassword $applicationUserResetPassword
    )
    {
        Config::set('jwt.user', ApplicationUser::class);
        Config::set('auth.providers.users.model', ApplicationUser::class);

        $this->applicationUser = $applicationUser;
        $this->applicationUserRepository = $applicationUserRepository;
        $this->applicationUserResetPassword = $applicationUserResetPassword;
    }

    /**
     * Cette fonction reçoit la demande de réinitialisation du mot de passe de la part de l'utilisateur et déclenche le
     * processus de réinitialisation.
     *
     * --> Création d'un token.
     * --> Sauvegarde le token en base de données application.
     * --> Déclenche l'évènement "ApplicationUserResetPasswordEvent" qui envoit un mail à l'utilisateur pour la suite du
     * processus de réinitialisation.
     * --> Retourne un message de succèss en format JSON.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPasswordWithEmail
    (
        Request $request
    )
    {
        $this->validate($request,
            [
                'email' => 'required|exists:application_users,email'
            ]);

        $applicationUser = $this->applicationUser->where('email', $request->email)->get()->first();

        $token = bin2hex(random_bytes(100)); // Donne 200 caractères.

        $applicationUserResetPassword = $this->applicationUserResetPassword->create([
            'applicationUser_id' => $applicationUser->id,
            'token' => $token
        ]);
        $applicationUserResetPassword->save();

        event(new ApplicationUserResetPasswordEvent($applicationUser, $token));

        return response()->json([
            'message' => 'Un email vient de vous être envoyé pour réinitialiser votre mot de passe :)'
        ],200);
    }

    /**
     * Cette fonction retourne le formulaire de mofification d'un mot de passe utilisateur.
     *
     * --> Vérification de la présence du token dans la table "application_users_reset_password". Le token est passé en
     * variable d'url.
     * --> Retourne le formulaire de modification du mot de passe.
     *
     * @param $token
     * @return string
     */
    public function applicationUserResetPasswordForm
    (
        $token
    )
    {
        $rules = [
            'token' => 'required|string|exists:application_users_reset_password,token',
        ];

        $base_url = Config::get('constants.base_url');

        $validation = Validator::make(['token' => $token], $rules);
        if ($validation->fails()) {
            $message = 'Demande de modification du mot de passe expirée ou déjà validée.';
            return view('applicationUsers_not_back_office.message', compact('message', 'base_url'));

        } else {
            return view('applicationUsers_not_back_office.reset_password', compact('base_url', 'token'));
        }
    }

    /**
     *
     *
     * @param Request $request
     * @return string
     */
    public function applicationUserResetPasswordRequest
    (
        Request $request
    )
    {
        $this->validate($request, [
                'password' => 'required|min:8|max:191|confirmed'
            ]
        );

        $rules = [
            'token' => 'required|string|exists:application_users_reset_password,token',
        ];

        $base_url = ToolsHandler::getBaseUrl();

        $validation = Validator::make(['token' => $request->token], $rules);
        if ($validation->fails()) {
            $message = 'Demande invalide ou expirée.';
            return view('applicationUsers_not_back_office.message', compact('message', 'base_url'));
        }

        $tokenDB = $this->applicationUserResetPassword->where('token', $request->token)->get()->first();

        $applicationUser = $this->applicationUser->findOrFail($tokenDB->applicationUser_id);

        $tokenDB->delete();
        $applicationUser->password = bcrypt($request->password);
        $applicationUser->save();

        $message = 'Mot de passe modifié :)';
        return view('applicationUsers_not_back_office.message', compact('message', 'base_url'));
    }

}
