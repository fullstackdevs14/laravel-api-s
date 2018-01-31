<?php

namespace App\Http\Controllers\Back_office\Partners;

use App\Events\PartnerSendInvoiceEvent;
use App\Handlers\Invoices\InvoicesGenerator;
use App\Http\Controllers\Controller;
use App\PartnerInvoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\Request;

/**
 * Cette classe se charge de gèrer la création et l'affichage des factures pour les partenaires.
 * Elle fait également appel à l'évènement "PartnerSendInvoiceEvent" qui envoi les factures par email au partenaire.
 *
 * Class InvoiceController
 * @package App\Http\Controllers\Back_office\Partners
 */
class InvoiceController extends Controller
{

    /**
     * C'est un model.
     *
     * Il sert à rècupèrer la liste des factures pour un partenaire.
     *
     * @var PartnerInvoice
     */
    private $partnerInvoice;

    /**
     * C'est le nombre de lignes dans les listes, par page.
     *
     * @var int
     */
    private $nbrPerPage = 15;

    /**
     * @var InvoicesGenerator
     */
    private $invoicesGenerator;

    /**
     * InvoiceController constructor.
     * @param PartnerInvoice $partnerInvoice
     * @param InvoicesGenerator $invoicesGenerator
     * @internal param PartnerInvoice $invoice
     */
    public function __construct
    (
        PartnerInvoice $partnerInvoice,
        InvoicesGenerator $invoicesGenerator
    )
    {
        $this->partnerInvoice = $partnerInvoice;
        $this->invoicesGenerator = $invoicesGenerator;
    }

    /**
     * Cette fonction génère un facture pour le mois dernier, pour un partenaire.
     *
     * @param $partner_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function invoiceGenerateLastMonthAndSend
    (
        $partner_id
    )
    {
        event(
            new PartnerSendInvoiceEvent($partner_id,
                (new Carbon('first day of last month'))->toDateString(),
                (new Carbon('last day of last month'))->toDateString()
            ));

        sleep(10);

        return redirect()->route('partner.invoices.index', $partner_id);
    }

    /**
     * Cette fonction génère un facture pour le mois en cours, pour un partenaire.
     *
     * @param $partner_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function invoiceGenerateThisMonthAndSend
    (
        $partner_id
    )
    {
        event(
            new PartnerSendInvoiceEvent($partner_id,
                (new Carbon('first day of this month'))->toDateString(),
                (new Carbon('today'))->toDateString()
            ));

        sleep(10);

        return redirect()->route('partner.invoices.index', $partner_id);
    }

    /**
     * Cette fonction génère un facture pour le mois en cours, pour un partenaire.
     *
     * @param $partner_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function invoiceGenerateThisMonth
    (
        $partner_id
    )
    {
        $this->invoicesGenerator->generatePartnerInvoice(
            (new Carbon('first day of this month'))->format('Y-m-d'),
            (new Carbon('tomorrow'))->format('Y-m-d'),
            $partner_id
        );

        return redirect()->route('partner.invoices.index', $partner_id);
    }

    /**
     * Cette fonction liste les factures enregitrées sur le serveur pour une partenaire.
     *
     * @param Request $request
     * @param $partner_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index
    (
        Request $request,
        $partner_id
    )
    {
        $invoices = $this->partnerInvoice
            ->where('partner_id', $partner_id)
            ->where(function ($query) use ($request, $partner_id) {
                if (($search = $request->get('search'))) {
                    $query->orWhere('invoice_id', 'like', '%' . $search . '%');
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->nbrPerPage);

        $links = $invoices->appends(Input::except('page'))->render();

        return view('partners.invoices.index', compact('invoices', 'partner_id', 'links'));
    }

    /**
     * Cette fonction télécharge une facture enregistrée sur le serveur.
     *
     * @param $invoice_id
     * @return mixed
     */
    public function download
    (
        $invoice_id
    )
    {
        $pathToFile = storage_path() . '/app/public/uploads/invoices/partners' . $invoice_id . '.pdf';
        return response()->file($pathToFile);
    }

}
