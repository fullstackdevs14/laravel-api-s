<?php

namespace App\Http\Controllers\API\Partners;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuRequest;
use App\MenuCategories;
use App\Partner;
use App\PartnerMenu;
use App\Repositories\PartnerMenuRepository;
use App\Repositories\PartnerRepository;
use App\Repositories\ToolsRepository;
use Illuminate\Support\Facades\Config;
use JWTAuth;

/**
 * Cette classe permet aux partenaire de gèrer les différents items présent dans leurs menus.
 *
 * Class PartnerMenuController
 * @package App\Http\Controllers\API\Partners
 */
class PartnerMenuController extends Controller
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
     * @var PartnerMenu
     */
    private $partnerMenu;

    /**
     * C'est un dépôt.
     *
     * Gère les actions courantes liées aux partenaires.
     *
     * @var PartnerRepository
     */
    private $partnerRepository;

    /**
     * C'est un dépôt.
     *
     * Gère les actions courantes liées au menus des partenaires.
     *
     * @var PartnerMenuRepository
     */
    private $partnerMenuRepository;

    /**
     * C'est un dépôt.
     *
     * Gère les actions courantes liées aux outils.
     *
     * @var ToolsRepository
     */
    private $toolsRepository;

    /**
     * PartnerMenuController constructor.
     * @param Partner $partner
     * @param PartnerMenu $partnerMenu
     * @param PartnerRepository $partnerRepository
     * @param PartnerMenuRepository $partnerMenuRepository
     * @param ToolsRepository $toolsRepository
     */
    public function __construct
    (
        Partner $partner,
        PartnerMenu $partnerMenu,
        PartnerRepository $partnerRepository,
        PartnerMenuRepository $partnerMenuRepository,
        ToolsRepository $toolsRepository
    )
    {
        Config::set('jwt.user', Partner::class);
        Config::set('auth.providers.users.model', Partner::class);
        $this->partner = $partner;
        $this->partnerMenu = $partnerMenu;
        $this->partnerRepository = $partnerRepository;
        $this->partnerMenuRepository = $partnerMenuRepository;
        $this->toolsRepository = $toolsRepository;
    }

    /**
     * Cette fonction retourne le menu aux partenaires.
     *
     * --> Récupèration du partenaire à partir du token d'authentification.
     * --> Récupèration et préparation pour l'interface partenaire d'un menu.
     * --> Retourne le menu au format JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMenu()
    {
        $partner = $this->partnerRepository->getPartnerFromToken();
        $menu = $this->partnerMenuRepository->getMenuSortByCategoriesForPartner($partner->id);
        return response()->json($menu, 200);
    }

    /**
     * Cette fonction se charge de modifier un item du menu d'un partenaire à partir de l'interface partenaire.
     *
     * --> Récupère le partenaire à partir du token d'authentification.
     * --> Converti la catégorie de l'item : string -> number (id).
     * --> Enregistre les modifications dans la base de données application.
     * --> Retourne un message de succès JSON.
     *
     * @param MenuRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function modifyItem
    (
        MenuRequest $request
    )
    {
        $partner = $this->partnerRepository->getPartnerFromToken();

        /**
         * Vérification que l'item appartient bien au partenaire.
         */
        $item = $this->partnerMenuRepository->secureFindItemForAPartner($partner->id, $request['id']);

        $categories = MenuCategories::all();

        foreach ($categories as $category) {
            if ($category->category == $request['category']) {
                $request['category_id'] = $category->id;
            }
        }

        $this->partnerMenuRepository->updateItem
        (
            $item->id,
            $partner->id,
            $request['name'],
            $request['quantity'],
            $request['price'],
            $request['HHPrice'],
            $request['tax'],
            $request['alcohol'],
            $request['category_id'],
            $request['ingredients'],
            $request['availability']
        );

        return response()->json(['message' => 'L\'item a bien été modifié.'], 200);

    }

    /**
     * Cette fonction se charge de supprimer l'item du menu d'un partenaire à partir de l'interface d'un partenaire.
     *
     * --> Récupère le partenaire à partir du token d'authentification.
     * --> Récupère l'item par son id.
     * --> Supprime l'item de la base de données application.
     * --> Retourne un message de succès JSON.
     *
     * @param $itemId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteItem($itemId)
    {

        $partner = $this->partnerRepository->getPartnerFromToken();

        // TODO -- Utiliser FindOrFail au lieu de where ?
        $item = $this->partner->findOrFail($partner->id)->menu->where('id', $itemId)->first();
        $this->partnerMenu->findOrFail($item->id)->delete();

        return response()->json([
            'message' => 'L\'item a bien été supprimé.'
        ], 200);
    }

    /**
     * Cette fonction créer un nouvel item à partir de 'linterface partenaire.
     *
     * --> Récupèration du partenaire à partir du token d'authentification.
     * --> Création d'un nouvel item à partir des éléments de la requête.
     * --> Retourne un message de succès JSON.
     *
     * @param MenuRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createItem
    (
        MenuRequest $request
    )
    {
        $partner = $this->partnerRepository->getPartnerFromToken();

        $item = new $this->partnerMenu;

        $categories = MenuCategories::all();

        foreach ($categories as $category) {
            if ($category->category == $request['category']) {
                $request['category_id'] = $category->id;
            }
        }

        $item->partner_id = $partner->id;
        $item->name = $request['name'];
        $item->quantity = $request['quantity'];
        $item->price = $request['price'];
        $item->HHPrice = $request['HHPrice'];
        $item->tax = $request['tax'];
        $item->category_id = $request['category_id'];
        $item->ingredients = $request['ingredients'];
        $item->alcohol = $request['alcohol'];
        $item->availability = $request['availability'];

        $item->save();

        return response()->json([
            'message' => 'L\'item a bien été créé.'
        ], 200);
    }

}
