<?php

namespace App\Http\Controllers\API\ApplicationUsers;

use App\ApplicationUser;
use App\Http\Controllers\Controller;
use App\MenuCategories;
use App\Partner;
use App\PartnerOpenings;
use Illuminate\Support\Facades\Config;
use JWTAuth;
use Symfony\Component\HttpFoundation\Request;

/**
 * Cette classe se charge d'obtenir pour l'interface utilisateur toutes les données concernant les partenaires.
 * L'ensemble des fonctions sont accessibles au utilisateurs authetifiés.
 *
 * Class ApplicationUserPartnersController
 * @package App\Http\Controllers\API\ApplicationUsers
 */
class ApplicationUserPartnersController extends Controller
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
     * @var PartnerOpenings
     */
    private $partnerOpenings;

    /**
     * ApplicationUserPartnersController constructor.
     * @param Partner $partner
     * @param PartnerOpenings $partnerOpenings
     */
    public function __construct
    (
        Partner $partner,
        PartnerOpenings $partnerOpenings
    )
    {
        Config::set('jwt.user', ApplicationUser::class);
        Config::set('auth.providers.users.model', ApplicationUser::class);
        $this->partner = $partner;
        $this->partnerOpenings = $partnerOpenings;
    }

    /**
     * Se charge d'obtenir tous les partenaires actifs et de les retourner à l'interface utilisateur.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPartners()
    {
        $partners = Partner::select([
            'id',
            'name',
            'tel',
            'category',
            'address',
            'city',
            'postalCode',
            'lat',
            'lng',
            'picture',
            'openStatus',
            'HHStatus',
            'website',
        ])
            ->where('activated', 1)
            ->get();

        foreach ($partners as $value) {
            $value['picture'] = Config::get('constants.base_url_partner') . $value['picture'];
        }

        return response()->json(['partners' => $partners], 200);
    }

    /**
     * Se charge d'obtenir un partenaire actif et de le retourné à l'interface utilisateur.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPartner
    (
        Request $request
    )
    {
        $this->validate($request,
            [
                'partner_id' => 'required|integer|exists:partners,id'
            ]);

        $partner = Partner::select([
            'id',
            'name',
            'tel',
            'category',
            'address',
            'city',
            'postalCode',
            'lat',
            'lng',
            'picture',
            'openStatus',
            'HHStatus',
            'website',
        ])
            ->where('activated', 1)
            ->where('id', $request['partner_id'])
            ->get();

        $partner[0]['picture'] = Config::get('constants.base_url_partner') . $partner[0]['picture'];

        return response()->json(['partner' => $partner], 200);
    }

    /**
     * Se charge d'obtenir les partenaires actifs en fonctions de critère de recherche spécifiques et de les retourner à l'interface utilisateur.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchPartners
    (
        Request $request
    )
    {

        $this->validate($request, [
            'search' => 'nullable|string',
        ]);

        $partners = $this->partner->where(function ($query) use ($request) {
            if (($search = $request->get('search'))) {
                $query->orWhere('name', 'like', '%' . $search . '%');
                $query->orWhere('tel', 'like', '%' . $search . '%');
                $query->orWhere('category', 'like', '%' . $search . '%');
                $query->orWhere('address', 'like', '%' . $search . '%');
                $query->orWhere('city', 'like', '%' . $search . '%');
                $query->orWhere('postalCode', 'like', '%' . $search . '%');
            }
        })->where('activated', 1)->get();

        if ($request->search == null) {
            $partners = Partner::select([
                'id',
                'name',
                'tel',
                'category',
                'address',
                'city',
                'postalCode',
                'lat',
                'lng',
                'picture',
                'openStatus',
                'HHStatus',
                'website',
            ])->where('activated', 1)->get();
        }

        foreach ($partners as $value) {
            $value['picture'] = Config::get('constants.base_url_partner') . $value['picture'];
        }

        return response()->json(['partners' => $partners], 200);
    }

    /**
     * Se charge de retourner tous les items disponibles d'un partenaire à l'interface utilisateur.
     *
     * Le retour sous JSON de la liste des items fait l'objet d'une préparation particulière pour être lue par l'interface utilisateur.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSortMenu
    (
        Request $request
    )
    {
        $this->validate($request, [
            'partner_id' => 'required|integer|exists:partners,id'
        ]);

        $partner = $this->partner->findOrFail($request['partner_id']);

        $HHStatus = $partner['HHStatus'];

        $menu = $partner
            ->menu()
            ->where('availability', 1)
            ->get()
            ->sortBy(function ($item) {
                return $item->category_id . '-' . $item->name;
            })
            ->groupBy('category_id');

        $categories = MenuCategories::all();

        $newMenu = [];
        foreach ($menu as $category => $items) {
            foreach ($categories as $cat) {
                if ($category == $cat->id) {
                    $newMenu[$cat->category] = $items;
                }
            }
        }

        $menu = $newMenu;

        $newMenu = [];
        foreach ($menu as $key => $value) {
            array_push($newMenu, ['category' => $key] + ['items' => $value]);
        }

        $lastMenu = (object)['drinks' => $newMenu];

        foreach ($lastMenu->drinks as $category) {
            foreach ($category['items'] as $item) {
                $item['price'] = floatval($item['price']);
                $item['HHPrice'] = floatval($item['HHPrice']);
            }
        }

        return response()->json([
            "menu" => $lastMenu,
            'HHStatus' => $HHStatus,
        ], 200);

    }

    /**
     * Se charge de retourner les horaires d'un partenaire à l'interface utilisateur.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSchedule
    (
        Request $request
    )
    {
        $this->validate($request, [
            'partner_id' => 'required|integer|exists:partners,id'
        ]);

        $schedule = $this->partnerOpenings->where('partner_id', $request['partner_id'])->get();
        return response()->json($schedule, 200);
    }

}
