<?php

namespace App\Http\Controllers\Back_office\Partners;

use App\Handlers\MangoPay\MangoPayHandler;
use App\Http\Controllers\Controller;
use App\Partner;
use App\Repositories\ToolsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use MangoPay\MangoPayApi;
use Validator;

/**
 * Cette classe gère les compte en banques des partenaires. ELle permet de passer via l'interface d'administration application
 * pour gèrer l'api Mangopay.
 *
 * Class BankAccountController
 * @package App\Http\Controllers\Back_office\Partners
 */
class BankAccountController extends Controller
{
    /**
     * C'est un model.
     *
     * @var Partner
     */
    private $partner;

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
     * BankAccountController constructor.
     * @param Partner $partner
     * @param MangoPayApi $mangoPayApi
     * @param MangoPayHandler $mangoPayHandler
     * @param ToolsRepository $toolsRepository
     */
    public function __construct
    (
        Partner $partner,
        MangoPayApi $mangoPayApi,
        MangoPayHandler $mangoPayHandler,
        ToolsRepository $toolsRepository
    )
    {
        $this->partner = $partner;
        $this->mangoPayApi = $mangoPayApi;
        $this->mangoPayHandler = $mangoPayHandler;
        $this->toolsRepository = $toolsRepository;
    }

    /**
     * Cette fonction retourne une vue listant les comptes en banque enregistés pour un partenaire.
     *
     * @param $partner_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index
    (
        $partner_id
    )
    {
        $partner = $this->partner->findOrFail($partner_id);
        $bankAccounts = $this->mangoPayApi->Users->GetBankAccounts($partner->mango_id);
        foreach ($bankAccounts as $account) {
            $account->CreationDate = Carbon::createFromTimestamp($account->CreationDate);
        }
        return view('partners.bank.index', compact('bankAccounts', 'partner'));
    }

    /**
     * Cette fonction retourne un formulaire permettant la création d'un compte en banque pour un partenaire dans l'api
     * Mangopay.
     *
     * @param $partner_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create
    (
        $partner_id
    )
    {
        $partner = $this->partner->findOrFail($partner_id);
        return view('partners.bank.create', compact('partner'));
    }

    /**
     * Cette fonction enregistre un nouveau compte en banque pour un partenaire dans l'api Mangopay.
     * /!\ Ceete fonction n'enregistre pas le compte en banque comme utilisé dans la base de données application.
     *
     * @param Request $request
     * @param $partner_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store
    (
        Request $request,
        $partner_id
    )
    {
        $partner = $this->partner->findOrFail($partner_id);

        $this->validate($request,
            [
                'ownerName' => 'required|string',
                'street_number' => 'required',
                'iban' => 'required|iban',
                'bic' => 'required|bic',
                'route' => 'required',
                'postalCode' => 'required|max:255',
                'city' => 'required|max:255',
            ]);

        $result = $this->mangoPayHandler->createBankAccountIBAN
        (
            $this->mangoPayApi,
            $partner->mango_id,
            $request['iban'],
            $request['bic'],
            $request['ownerName'],
            $request['street_number'] . ' ' . $request['route'],
            $request['city'],
            $request['postalCode'],
            'FR'
        );

        if (array_key_exists('error', $result)) {
            Session::flash('error', $result['error']);
            return back();
        }

        Session::flash('message', 'Le compte bancaire a bien été créé.');

        $partnerCategories = $this->toolsRepository->getPartnerCategories();
        $partner = $this->partner->findOrFail($partner_id);
        $partner->birthday = Carbon::createFromTimestampUTC($this->mangoPayApi->Users->Get($partner->mango_id)->LegalRepresentativeBirthday)->format('Y-m-d');
        return view('partners.edit', compact('partner', 'partnerCategories'));
    }

    /**
     * Cette fonction retourne une vue contetant le détail d'un compte en banque.
     *
     * @param $partner_id
     * @param $bankAccount_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show
    (
        $partner_id,
        $bankAccount_id
    )
    {
        $partner = $this->partner->findOrFail($partner_id);
        $bankAccount = $this->mangoPayApi->Users->GetBankAccount($partner->mango_id, $bankAccount_id);
        return view('partners.bank.show', compact('bankAccount', 'partner'));
    }

    /**
     * Cette fonction désactive un compte en banque dans l'api Mangopay.
     * /!\ Cette action est irréversible.
     *
     * @param $partner_id
     * @param $bankAccount_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function destroy
    (
        $partner_id,
        $bankAccount_id
    )
    {
        $partner = $this->partner->findOrFail($partner_id);
        $this->mangoPayHandler->deactivateBankAccount($this->mangoPayApi, $partner->mango_id, $bankAccount_id);

        Session::flash('message', 'Le compte a bien été désactivé');
        Session::flash('error', 'Attention, si ce compte était utilisé pour les remboursements, le partenaire ne sera plus remboursé.');
        return redirect()->route('bank_account.index', $partner_id);
    }

    /**
     * Cette fonction enregistre dans la base de données application, un compte enregistrés sur l'api Mangopay, comme utilisé.
     * Lors d'un transfert d'un wallet partenaire vers son compte en banque, c'est ce compte en banque qui sera utilisé.
     *
     * @param $partner_id
     * @param $bankAccount_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function setBankAccountAsUsed
    (
        $partner_id,
        $bankAccount_id
    )
    {
        $partner = $this->partner->findOrFail($partner_id);
        $bankAccount = $this->mangoPayApi->Users->GetBankAccount($partner->mango_id, $bankAccount_id);
        if($bankAccount->Active == true)
        {
            $this->mangoPayHandler->setUsedBankInPartnerBdd($partner, $bankAccount_id);
            Session::flash('message', 'Le compte utilisé pour les versements a bien été modifié');
        } else {
            Session::flash('error', 'Le compte utilisé pour les versements n\'a pas été modifié car celui-ci est inactif.');
        }
        return redirect()->route('bank_account.index', $partner_id);
    }

}
