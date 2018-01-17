<?php

namespace App\Repositories;

use App\ApplicationUser;
use Config;
use JWTAuth;

/**
 * Cette classe sert de dépôt et gère les opérations courantes pour le model "ApplicationUser".
 *
 * Class ApplicationUserRepository
 * @package App\Repositories
 */
class ApplicationUserRepository
{
    /**
     * C'est un model.
     *
     * @var ApplicationUser
     */
    private $applicationUser;

    /**
     * ApplicationUserRepository constructor.
     * @param ApplicationUser $applicationUser
     */
    public function __construct
    (
        ApplicationUser $applicationUser
    )
    {
        $this->applicationUser = $applicationUser;
    }

    /**
     * Cette fonction retourne l'utilisateur au format JSON pour toute requête avec les élèments d'authentification bearer.
     *
     * @return mixed
     */
    public function getApplicationUserFromToken()
    {
        return JWTAuth::parseToken()->authenticate();
    }

    /**
     * Cette fonction retourne l'utilisateur au format JSON pour toute requête avec les élèment d'authentification bearer.
     * En plus de la fonction précédente elle prépare l'url de l'image de profil de l'utilisateur.
     *
     * @return mixed
     */
    public function getApplicationUserFromTokenWithPreparedPictureUrl()
    {
        $applicationUser = JWTAuth::parseToken()->authenticate();

        if(isset($applicationUser->picture)){
            $applicationUser->picture = Config::get('constants.base_url_application_user') .  $applicationUser->picture;
        }

        return $applicationUser;
    }

    /**
     * Cette fonction met a jour la colonne "mango_card_id" de la tabel "application_users" en base de données application
     * en inscrivant "null". Ainsi l'utilisateur n'a plus de carte de crédit valable enregistrée en base de données application.
     *
     * @param $applicationUser_id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function setMangoCardIdToNull
    (
        $applicationUser_id
    )
    {
        $applicationUser = $this->applicationUser->findOrFail($applicationUser_id);
        $applicationUser->mango_card_id = null;
        $applicationUser->update();

        return $applicationUser;
    }

}