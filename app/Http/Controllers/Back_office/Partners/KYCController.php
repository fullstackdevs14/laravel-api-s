<?php

namespace App\Http\Controllers\Back_office\Partners;

use App\Http\Controllers\Controller;
use App\Partner;
use MangoPay\MangoPayApi;

/**
 * Cette classe regroupe différentes fonction permettant l'affichage pou suivi des status du process KYC.
 *
 * Class KYCController
 * @package App\Http\Controllers\Back_office\Partners
 */
class KYCController extends Controller
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
     * KYCController constructor.
     * @param Partner $partner
     * @param MangoPayApi $mangoPayApi
     */
    public function __construct
    (
        Partner $partner,
        MangoPayApi $mangoPayApi
    )
    {
        $this->partner = $partner;
        $this->mangoPayApi = $mangoPayApi;
    }

    /**
     * Cette fonction retourne une vue listants les différents documents envoyés à l'api Mangopay dans le cadre du process KYC.
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
        $kycDocs = $this->mangoPayApi->Users->GetKycDocuments($partner->mango_id);

        return view('partners.kyc.index', compact('partner', 'kycDocs'));

    }

    /**
     * Cette fonction retourne une vue détaillant le statut d'un document envoyé à l'api Mangopay dans le cadre du process KYC.
     *
     * @param $partner_id
     * @param $kycDoc_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show
    (
        $partner_id,
        $kycDoc_id
    )
    {
        $partner = $this->partner->findOrFail($partner_id);
        $kycDoc = $this->mangoPayApi->Users->GetKycDocument($partner->mango_id, $kycDoc_id);

        return view('partners.kyc.show', compact('partner', 'kycDoc'));
    }

    /**
     * Cette fonction redirige vers la page de l'api mangopay permettant l'enregistrement pour un partenaire d'un document
     * dans le cadre du process KYC.
     *
     * @param $partner_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create
    (
        $partner_id
    )
    {
        $partner = $this->partner->findOrFail($partner_id);
        if(env('MANGOPAY_ENV') == "PRODUCTION"){
            return redirect('https://dashboard.mangopay.com/Operations/UploadKycDocument?userId=' . $partner->mango_id);
        } else {
            return redirect('https://dashboard.sandbox.mangopay.com/Operations/UploadKycDocument?userId=' . $partner->mango_id);
        }
    }

    /**
     * Cetet fonction permet le téléchargement d'un document demandé par Mangopay dans le cadre du process KYC.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function downloadShareholderDeclaration()
    {
        return redirect('https://www.mangopay.com/terms/shareholder-declaration/Shareholder_Declaration-EN.pdf');
    }
}
