<?php

namespace App\Http\Controllers\Back_office\Partners;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuRequest;
use App\Partner;
use App\PartnerMenu;
use App\Repositories\PartnerMenuRepository;
use App\Repositories\ToolsRepository;
use Session;

/**
 * Cette classe regroupe les fonction qui permettent de créer, modifier et supprimer les items de la carte d'un partenaire.
 *
 * Class ItemController
 * @package App\Http\Controllers\Back_office\Partners
 */
class ItemController extends Controller
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
     * C'est un dépot.
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
     * ItemController constructor.
     * @param Partner $partner
     * @param PartnerMenu $partnerMenu
     * @param PartnerMenuRepository $partnerMenuRepository
     * @param ToolsRepository $toolsRepository
     */
    public function __construct
    (
        Partner $partner,
        PartnerMenu $partnerMenu,
        PartnerMenuRepository $partnerMenuRepository,
        ToolsRepository $toolsRepository
    )
    {
        $this->partner = $partner;
        $this->partnerMenu = $partnerMenu;
        $this->partnerMenuRepository = $partnerMenuRepository;
        $this->toolsRepository = $toolsRepository;
    }

    /**
     * Cette fonction retroune le formulaire de création d'un item de carte.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $partner = $this->partner->findOrFail($id);
        $taxes = $this->toolsRepository->getActiveTaxes();
        $categories = $this->toolsRepository->getActiveCategories();
        return view('partners.menu.create',  compact('partner', 'categories', 'taxes'));
    }

    /**
     * Cette fonction enregistre en base de données application un item pour la carte d'un partenaire.
     *
     * @param MenuRequest $request
     * @param $partner_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store
    (
        MenuRequest $request,
        $partner_id
    )
    {
        $this->partnerMenuRepository->newItem(
            $partner_id,
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

        Session::flash('message', "La boisson " . $request['name'] . " a été ajoutée.");
        return redirect()->route('menus.edit', $partner_id);
    }

    /**
     * Cette fonction retourne un formulaire permettant de modifier l'item de la carte d'un partenaire.
     *
     * @param $partner_id
     * @param $item_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit
    (
        $partner_id,
        $item_id
    )
    {
        $item = $this->partnerMenu->findOrFail($item_id);
        $taxes = $this->toolsRepository->getActiveTaxes();
        $categories = $this->toolsRepository->getActiveCategories();
        $partner = $this->partner->findOrFail($partner_id);
        return view('partners.menu.edit',  compact('item', 'partner','categories', 'taxes'));
    }

    /**
     * Cette fonction enregsitre les modification éffectuées sur l'item de la carte d'un partenaire.
     *
     * @param MenuRequest $request
     * @param $partner_id
     * @param $item_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(
        MenuRequest $request,
        $partner_id,
        $item_id
    )
    {
        $this->partnerMenuRepository->updateItem(
            $item_id,
            $partner_id,
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

        Session::flash('message', "La boisson " . $request['name'] . " a été modifiée.");
        return redirect()->route('menus.edit', $partner_id);
    }

    /**
     * Cette fonction supprime un item de la base de données application pour la carte d'un partenaire..
     *
     * @param $partner_id
     * @param $item_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function destroy
    (
        $partner_id,
        $item_id
    )
    {
        $item = $this->partnerMenuRepository->deleteItem($item_id);
        Session::flash('message', "La boisson " . $item['name'] . " a été supprimée.");
        return redirect()->route('menus.edit', $partner_id);
    }

}
