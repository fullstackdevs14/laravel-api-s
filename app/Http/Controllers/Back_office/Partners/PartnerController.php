<?php

namespace App\Http\Controllers\Back_office\Partners;

use App\Handlers\MangoPay\MangoPayHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerRequest;
use App\Partner;
use App\PartnerMenu;
use App\Repositories\PartnerOpeningsRepository;
use App\Repositories\PartnerRepository;
use App\Repositories\ToolsRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Image;
use MangoPay\MangoPayApi;
use Session;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PartnerController
 * @package App\Http\Controllers\Back_office\Partners
 */
class PartnerController extends Controller
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
     * Gère les action courantes liées au partenaires.
     *
     * @var PartnerRepository
     */
    private $partnerRepository;

    /**
     * C'est un dépôt.
     *
     * Gère les actions courantes liées aux horaires d'ouverture d'un partenaire.
     *
     * @var PartnerOpeningsRepository
     */
    private $partnerOpeningsRepository;

    /**
     * Librairie de l'api Mangopay.
     *
     * @var MangoPayApi
     */

    private $mangoPayApi;

    /**
     * C'est un gestionnaire.
     *
     * Gère les actions courantes liées au paiements.
     *
     * @var MangoPayHandler
     */
    private $mangoPayHandler;

    /**
     * C'est un dépôt.
     *
     * Gère les actions courantes liées aux outils.
     *
     * @var ToolsRepository
     */
    private $toolsRepository;

    /**
     * C'est le nombre de lignes dans les listes, par page.
     *
     * @var int
     */
    private $nbrPerPage = 15;

    /**
     * PartnerController constructor.
     * @param Partner $partner
     * @param PartnerMenu $partnerMenu
     * @param PartnerRepository $partnerRepository
     * @param PartnerOpeningsRepository $partnerOpeningsRepository
     * @param MangoPayApi $mangoPayApi
     * @param MangoPayHandler $mangoPayHandler
     * @param ToolsRepository $toolsRepository
     */
    public function __construct
    (
        Partner $partner,
        PartnerMenu $partnerMenu,
        PartnerRepository $partnerRepository,
        PartnerOpeningsRepository $partnerOpeningsRepository,
        MangoPayApi $mangoPayApi,
        MangoPayHandler $mangoPayHandler,
        ToolsRepository $toolsRepository
    )
    {
        $this->partner = $partner;
        $this->partnerMenu = $partnerMenu;
        $this->partnerRepository = $partnerRepository;
        $this->partnerOpeningsRepository = $partnerOpeningsRepository;
        $this->mangoPayApi = $mangoPayApi;
        $this->mangoPayHandler = $mangoPayHandler;
        $this->toolsRepository = $toolsRepository;
    }

    /**
     * Cette fonction retourne une vue listant les partenaires enregsitrés dans la base de données application.
     *
     * @param Request $request
     * @return mixed
     */
    public function index
    (
        Request $request
    )
    {
        $partners = $this->partner->where(function ($query) use ($request) {
            if (($search = $request->get('search'))) {
                $query->orWhere('email', 'like', '%' . $search . '%');
                $query->orWhere('name', 'like', '%' . $search . '%');
                $query->orWhere('city', 'like', '%' . $search . '%');
                $query->orWhere('postalCode', 'like', '%' . $search . '%');
                $query->orWhere('tel', 'like', '%' . $search . '%');
                $query->orWhere('category', 'like', '%' . $search . '%');
            }
        })->paginate($this->nbrPerPage);

        $links = $partners->appends(Input::except('page'))->render();

        return view('partners.index', compact('partners', 'links'));
    }

    /**
     * Cette fonction retourne le formulaire permettant la création d'un partenaire en base de données application.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $partnerCategories = $this->toolsRepository->getPartnerCategories();
        $countries = $this->toolsRepository->getCountries();
        return view('partners.create', compact('partnerCategories', 'countries'));
    }

    /**
     * Cette fonction enregistre le nouveau partenaire en base de données application.
     *
     * @param PartnerRequest $request
     * @return mixed
     */
    public function store
    (
        PartnerRequest $request
    )
    {
        $mango = $this->mangoPayHandler->createLegalUser(
            $this->mangoPayApi,
            $request['name'],
            $request['email'],
            $request['LegalRepresentativeEmail'],
            $request['ownerFirstName'],
            $request['ownerLastName'],
            strtotime("2:00", Carbon::createFromFormat('Y-m-d', $request['birthday'])->timezone('UTC')->timestamp),
            $request['LegalRepresentativeNationality'],
            $request['LegalRepresentativeCountryOfResidence'],
            $request['street_number_representative'] . ' ' . $request['route_representative'],
            $request['city_representative'],
            $request['administrative_area_level_2_representative'],
            $request['postalCode_representative'],
            $request['country_representative'],
            $request['street_number_hq'] . ' ' . $request['route_hq'],
            $request['city_hq'],
            $request['administrative_area_level_2_hq'],
            $request['postalCode_hq'],
            $request['country_hq']
        );

        $this->mangoPayHandler->createWallet($this->mangoPayApi, $mango->Id, 'Wallet du partenaire ' . $request['name'], 'EUR');

        if ($request->hasFile('picture')) {
            $picture = $request->file('picture');
            $filename = time() . '.' . $picture->getClientOriginalExtension();
            Image::make($picture)->save(storage_path('app/public/uploads/partners_img/' . $filename));
        }

        $partner = $this->partnerRepository->newPartner(
            $request['email'],
            $request['tel'],
            $request['ownerFirstName'],
            $request['ownerLastName'],
            $request['password'],
            $request['name'],
            $request['category'],
            $request['street_number'] . ' ' . $request['route'],
            $request['city'],
            $request['postalCode'],
            $request['lat'],
            $request['lng'],
            $filename,
            $request['website'],
            $mango->Id,
            null,
            $request['fees']
        );

        $this->partnerOpeningsRepository->createDefaultOpenings($partner->id);

        Session::flash('message', "Le partenaire " . $request->input('name') . " a été enregistré.");

        $partners = $this->partner->paginate($this->nbrPerPage);

        $links = $partners->appends(Input::except('page'))->render();

        return redirect()->route('partner.index', compact('partners', 'links'));
    }

    /**
     * Cette fonction retourne une vue avec le détail concerant un partenaire.
     *
     * @param $partner_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show
    (
        $partner_id
    )
    {
        $partner = $this->partner->findOrFail($partner_id);
        return view('partners.show', compact('partner'));
    }

    /**
     * Cette fonction retourne un formulaire permettant la modification d'un partenaire.
     *
     * @param $partner_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit
    (
        $partner_id
    )
    {
        $partnerCategories = $this->toolsRepository->getPartnerCategories();
        $partner = $this->partner->findOrFail($partner_id);
        $partner->birthday = Carbon::createFromTimestampUTC($this->mangoPayApi->Users->Get($partner->mango_id)->LegalRepresentativeBirthday)->format('Y-m-d');
        return view('partners.edit', compact('partner', 'partnerCategories'));
    }

    /**
     * Cette fonction enregistre en base de données application les modifications apportées à un partenaire.
     *
     * @param PartnerRequest $request
     * @param $partner_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update
    (
        PartnerRequest $request,
        $partner_id
    )
    {
        $this->mangoPayHandler->updateLegalUser(
            $this->mangoPayApi,
            $request['mango_id'],
            $request['name'],
            $request['email'],
            $request['ownerFirstName'],
            $request['ownerLastName'],
            $request['birthday'],
            $legalRepresentativeNationality = "FR",
            $legalRepresentativeCountryOfResidence = "FR"
        );

        if ($request->hasFile('picture')) {
            $picture = $request->file('picture');
            $filename = time() . '.' . $picture->getClientOriginalExtension();
            Image::make($picture)->save(storage_path('app/public/uploads/partners_img/' . $filename));
        } else {
            $filename = false;
        }

        $this->partnerRepository->updatePartner($partner_id, $request, $filename);

        Session::flash('message', "Le partenaire " . $request->input('name') . " a été modifié.");

        $partners = $this->partner->paginate($this->nbrPerPage);

        $links = $partners->appends(Input::except('page'))->render();

        return redirect()->route('partner.index', compact('partners', 'links'));
    }

    /**
     * Cette fonction supprime un partenaire de la base de données application.
     *
     * @param $partner_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy
    (
        $partner_id
    )
    {
        $partner = $this->partner->findOrFail($partner_id);

        $partner->delete();

        Session::flash('message', "Le partenaire " . $partner->name . " a été supprimé.");

        $partners = $this->partner->paginate($this->nbrPerPage);

        $links = $partners->appends(Input::except('page'))->render();

        return redirect()->route('partner.index', compact('partners', 'links'));
    }

    /**
     * cette fonction ouvre l'interface Mangopay sur la page d'administration d'un partenaire.
     *
     * @param $partner_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function showMangoPayUserDetails
    (
        $partner_id
    )
    {
        $partner = $this->partner->findOrFail($partner_id);
        if (env('MANGOPAY_ENV') == "PRODUCTION") {
            return redirect('https://dashboard.mangopay.com/Users/' . $partner->mango_id);
        } else {
            return redirect('https://dashboard.sandbox.mangopay.com/Users/' . $partner->mango_id);
        }
    }

}
