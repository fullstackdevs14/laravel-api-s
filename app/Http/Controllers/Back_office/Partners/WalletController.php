<?php

namespace App\Http\Controllers\Back_office\Partners;

use App\Events\PartnerBankTransferEvent;
use App\Handlers\MangoPay\MangoPayHandler;
use App\Http\Controllers\Controller;
use App\Partner;
use App\Repositories\PayoutRepository;
use Illuminate\Support\Facades\Session;
use MangoPay\MangoPayApi;

/**
 * Cette classe gère les versements aux partenaires.
 *
 * Class WalletController
 * @package App\Http\Controllers\Back_office\Partners
 */
class WalletController extends Controller
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
     * Gère les actions courantes liées aux remboursements.
     *
     * @var PayoutRepository
     */
    private $payoutRepository;

    /**
     * WalletController constructor.
     * @param Partner $partner
     * @param MangoPayApi $mangoPayApi
     * @param MangoPayHandler $mangoPayHandler
     * @param PayoutRepository $payoutRepository
     */
    public function __construct
    (
        Partner $partner,
        MangoPayApi $mangoPayApi,
        MangoPayHandler $mangoPayHandler,
        PayoutRepository $payoutRepository
    )
    {
        $this->partner = $partner;
        $this->mangoPayApi = $mangoPayApi;
        $this->mangoPayHandler = $mangoPayHandler;
        $this->payoutRepository = $payoutRepository;
    }

    /**
     * Cette fonction retourne une vue avec le détail concernat le wallet du partenaire.
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
        $wallet = current($this->mangoPayApi->Users->GetWallets($partner->mango_id));

        return view('partners.wallet.show', compact('wallet', 'partner'));
    }

    /**
     * Cette fonction déclenche un versement du wallet du partenaire vers son compte en banque.
     *
     * @param $partner_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function walletPayOut
    (
        $partner_id
    )
    {
        //TODO - Le fonctionnement de cette fonction peut être grandement amélioré.
        $partner = $this->partner->findOrFail($partner_id);
        $wallet = current($this->mangoPayApi->Users->GetWallets($partner->mango_id));
        $initialAmount = $wallet->Balance;
        $result = $this->mangoPayHandler->payOut($this->mangoPayApi, $partner, $wallet);
        sleep(10);

        $payOut = $this->mangoPayHandler->getPayOut($this->mangoPayApi, $result->Id);

        $partner = $this->partner->findOrFail($partner_id);
        $wallet = current($this->mangoPayApi->Users->GetWallets($partner->mango_id));

        if ($wallet->Balance->Amount > 0) {
            $success = false;
            Session::flash('error', "Une erreur inconnue ne permet pas le remboursement. Vous pourvez vous rendre sur la plateforme Mangopay et découvrir l'erreur avec l'id suivant: " . $result->Id);
        } else {
            $success = true;
            event(new PartnerBankTransferEvent($partner, $initialAmount));
            Session::flash('message', "Le remboursement a bien été éffectué.");
        }

        $this->payoutRepository->create($partner_id, $initialAmount->Amount / 100, $success, $payOut->ResultMessage, $payOut->Id);

        return redirect()->route('wallet.show', $partner_id);
    }

}
