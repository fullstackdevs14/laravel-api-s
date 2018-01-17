<?php

namespace App\Http\Controllers\Back_office\Partners;

use App\Events\PartnerSendInvoiceEvent;
use App\Http\Controllers\Controller;
use App\Invoice;
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
     * @var Invoice
     */
    private $invoice;

    /**
     * C'est le nombre de lignes dans les listes, par page.
     *
     * @var int
     */
    private $nbrPerPage = 15;

    /**
     * InvoiceController constructor.
     * @param Invoice $invoice
     */
    public function __construct
    (
        Invoice $invoice
    )
    {
        $this->invoice = $invoice;
    }

    /**
     * Cette fonction génère un facture pour le mois dernier, pour un partenaire.
     *
     * @param $partner_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function invoiceGenerateLastMonth
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
        $invoices = $this->invoice
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
        $pathToFile = storage_path() . '/app/public/uploads/invoices/' . $invoice_id . '.pdf';
        return response()->file($pathToFile);
    }

}
