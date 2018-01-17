<?php

namespace App\Repositories;

use App\Partner;
use Config;
use JWTAuth;

/**
 * Cette classe gère toutes les actions courantes liées aux partenaires.
 *
 * Class PartnerRepository
 * @package App\Repositories
 */
class PartnerRepository
{
    private $partner;

    public function __construct
    (
        Partner $partner
    )
    {
        $this->partner = $partner;
    }

    /**
     * Cette fonction retourne le partenaire à partir du token d'authentification envoyé dans la requête.
     *
     * @return mixed
     */
    public function getPartnerFromToken()
    {
        $partner = JWTAuth::parseToken()->authenticate();
        return $partner;
    }

    /**
     * Cette fonction retourne le partenaire à partir du token d'authentification envoyé dans la requête.
     * Elle remplace le nom de l'image par une url renvoyant vers l'image sur le serveur.
     *
     * @return mixed
     */
    public function getPartnerFromTokenWithPreparedPictureUrl()
    {
        $partner = JWTAuth::parseToken()->authenticate();
        if (isset($partner['picture'])) {
            $partner['picture'] = Config::get('constants.base_url_partner') . $partner['picture'];
        }
        return $partner;
    }

    /**
     * Cette fonction met à jour un partenaire.
     *
     * @param $partner_id
     * @param $request
     * @param $filename
     * @return mixed
     */
    public function updatePartner
    (
        $partner_id,
        $request,
        $filename
    )
    {
        $partner = $this->partner->findOrFail($partner_id);

        $partner->email = $request->email;
        $partner->tel = $request->tel;
        $partner->ownerFirstName = $request->ownerFirstName;
        $partner->ownerLastName = $request->ownerLastName;
        $partner->name = $request->name;
        $partner->category = $request->category;
        $partner->address = $request->address;
        $partner->city = $request->city;
        $partner->postalCode = $request->postalCode;
        $partner->lat = $request->lat;
        $partner->lng = $request->lng;
        $partner->website = $request->website;
        $partner->mango_id = $request->mango_id;
        $partner->fees = $request->fees;
        $partner->openStatus = $request->openStatus;
        $partner->HHStatus = $request->HHStatus;
        $partner->activated = $request->activated;
        $partner->picture = $filename;

        $partner->update();

        return $partner;
    }

    /**
     * Cette fonction invalide le token d'authentification passé dans la requête.
     *
     * @return mixed
     */
    public function invalidateToken()
    {
        $result = JWTAuth::invalidate(JWTAuth::getToken());
        return $result;
    }

}