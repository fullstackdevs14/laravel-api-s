<?php

namespace App\Http\Controllers\API\Partners;

use App\Http\Controllers\Controller;
use App\Http\Requests\OpeningsRequest;
use App\Partner;
use App\Repositories\PartnerRepository;
use Illuminate\Support\Facades\Config;
use JWTAuth;

/**
 * Cette classe permet aux partenaire de gèrer les horaires d'ouverture en base de données application.
 *
 * -->
 *
 * Class PartnerOpeningsController
 * @package App\Http\Controllers\API\Partners
 */
class PartnerOpeningsController extends Controller
{
    /**
     * C'est un model.
     *
     * @var Partner
     */
    private $partner;

    /**
     * C'est un dépôt.
     *
     * Gère les actions courantes liées aux partenaires.
     *
     * @var PartnerRepository
     */
    private $partnerRepository;

    /**
     * PartnerOpeningsController constructor.
     * @param Partner $partner
     * @param PartnerRepository $partnerRepository
     */
    public function __construct
    (
        Partner $partner,
        PartnerRepository $partnerRepository
    )
    {
        Config::set('jwt.user', Partner::class);
        Config::set('auth.providers.users.model', Partner::class);
        $this->partner = $partner;
        $this->partnerRepository = $partnerRepository;
    }

    /**
     * Cette fonction retourne les horaires d'ouverture d'un partenaire à l'interface partenaire.
     *
     * --> Récupèration du partenaire à partir du token d'authentification.
     * --> Récupèration des horaires d'ouverture du partenaire.
     * --> Retourne les horaires au format JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOpenings()
    {
        $partner = $this->partnerRepository->getPartnerFromToken();

        $openings = $partner->openings;

        unset($openings->id, $openings->created_at, $openings->updated_at);

        return response()->json([
            'openings' => $openings,
        ], 200);
    }

    /**
     * Cette fonction permet la modification des horaires d'ouverture d'un partenaire à partir de l'interface partenaire.
     *
     * --> Récupération du partenaire à partir du token d'authentification.
     * --> Récupération des horaires d'ouverture du partenaire.
     * --> Modification des horaires en base de données application.
     * --> Retourne un message de succès JSON.
     *
     * @param OpeningsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function modifyOpenings
    (
        OpeningsRequest $request
    )
    {
        $partner = $this->partnerRepository->getPartnerFromToken();

        $openings = $this->partner->findOrFail($partner->id)->openings;

        $openings->monday1 = $request['monday1'];
        $openings->monday2 = $request['monday2'];
        $openings->monday3 = $request['monday3'];
        $openings->monday4 = $request['monday4'];
        $openings->tuesday1 = $request['tuesday1'];
        $openings->tuesday2 = $request['tuesday2'];
        $openings->tuesday3 = $request['tuesday3'];
        $openings->tuesday4 = $request['tuesday4'];
        $openings->wednesday1 = $request['wednesday1'];
        $openings->wednesday2 = $request['wednesday2'];
        $openings->wednesday3 = $request['wednesday3'];
        $openings->wednesday4 = $request['wednesday4'];
        $openings->thursday1 = $request['thursday1'];
        $openings->thursday2 = $request['thursday2'];
        $openings->thursday3 = $request['thursday3'];
        $openings->thursday4 = $request['thursday4'];
        $openings->friday1 = $request['friday1'];
        $openings->friday2 = $request['friday2'];
        $openings->friday3 = $request['friday3'];
        $openings->friday4 = $request['friday4'];
        $openings->saturday1 = $request['saturday1'];
        $openings->saturday2 = $request['saturday2'];
        $openings->saturday3 = $request['saturday3'];
        $openings->saturday4 = $request['saturday4'];
        $openings->sunday1 = $request['sunday1'];
        $openings->sunday2 = $request['sunday2'];
        $openings->sunday3 = $request['sunday3'];
        $openings->sunday4 = $request['sunday4'];

        $openings->update();

        return response()->json([
            'message' => 'Les horaires d\'ouverture ont bien été modifiés.'
        ], 200);
    }

}
