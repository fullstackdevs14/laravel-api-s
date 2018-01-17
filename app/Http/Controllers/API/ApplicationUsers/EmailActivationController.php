<?php

namespace App\Http\Controllers\API\ApplicationUsers;

use App\ApplicationUser;
use App\ApplicationUserEmailConfirmation;
use Config;
use Illuminate\Routing\Controller;
use Validator;

/**
 * Cette classe se charge de la validation de l'email d'un utilisateur.
 *
 * /!\ l'email de validation d'un email est déclenché avec l'évènement "ApplicationUserRegisteredEvent" lors de l'inscription d'un utilisateur.
 *
 * Class EmailActivationController
 * @package App\Http\Controllers\API\ApplicationUsers
 */
class EmailActivationController extends Controller
{
    /**
     * C'est un model.
     *
     * @var ApplicationUser
     */
    private $applicationUser;

    /**
     * C'est un model.
     *
     * @var ApplicationUserEmailConfirmation
     */
    private $applicationUserEmailConfirmation;

    /**
     * EmailActivationController constructor.
     * @param ApplicationUser $applicationUser
     * @param ApplicationUserEmailConfirmation $applicationUserEmailConfirmation
     */
    public function __construct
    (
        ApplicationUser $applicationUser,
        ApplicationUserEmailConfirmation $applicationUserEmailConfirmation
    )
    {
        $this->applicationUser = $applicationUser;
        $this->applicationUserEmailConfirmation = $applicationUserEmailConfirmation;
    }


    /**
     * Cette fonction est appellée via le formulaire de validation de l'email.
     * Si le token présent dans l'email de validation est toujours valide, le lien présent de le formulaire de
     * validation appel cette fonction qui va affectée la valeur true à la colonne "email_validation" de la table "application_users".
     * Dans ce même cas, le token de confirmation de l'email est supprimé (rendant le formulaire utilisable qu'une fois.
     *
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function confirmEmail
    (
        $token
    )
    {
        $rules = [
            'token' => 'required|string|exists:application_users_email_validation,token',
        ];

        $base_url = Config::get('constants.base_url');

        $validation = Validator::make(['token' => $token], $rules);
        if ($validation->fails()) {
            $message = 'Validation de l\'email déjà éffectuée.';
            return view('applicationUsers_not_back_office.message', compact('message', 'base_url'));
        } else {
            $token_info = $this->applicationUserEmailConfirmation->where('token', $token)->firstOrFail();
            $applicationUser = $this->applicationUser->findOrFail($token_info->applicationUser_id);
            $applicationUser->email_validation = 1;
            $applicationUser->save();
            $token_info->delete();

            $message = 'Compte activé :)';
            return view('applicationUsers_not_back_office.message', compact('message', 'base_url'));
        }
    }

}
