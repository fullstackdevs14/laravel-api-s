<?php

namespace App\Http\Controllers\Back_office\Partners;

use App\Http\Controllers\Controller;
use App\Partner;
use App\Repositories\PartnerMenuRepository;

/**
 * Cette classe sert à l'affichage du menu d'un partenaire.
 *
 * Class MenuController
 * @package App\Http\Controllers\Back_office\Partners
 */
class MenuController extends Controller
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
     * Gère les actions courantes liées au menu d'un partenaire.
     *
     * @var PartnerMenuRepository
     */
    private $partnerMenuRepository;

    /**
     * MenuController constructor.
     * @param Partner $partner
     * @param PartnerMenuRepository $partnerMenuRepository
     */
    public function __construct
    (
        Partner $partner,
        PartnerMenuRepository $partnerMenuRepository
    )
    {
        $this->partner = $partner;
        $this->partnerMenuRepository = $partnerMenuRepository;
    }

    /**
     * Cette classe retourne une vue listant les items de la carte d'un partenaire.
     *
     * @param $partner_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit
    (
        $partner_id
    )
    {
        $partner = $this->partner->findOrFail($partner_id);
        $menu = $this->partnerMenuRepository->getMenuSortByCategories($partner_id);

        return view('partners.menu.index', compact('menu', 'partner'));
    }

}
