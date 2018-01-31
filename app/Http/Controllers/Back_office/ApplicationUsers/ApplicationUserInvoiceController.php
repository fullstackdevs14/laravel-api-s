<?php

namespace App\Http\Controllers\Back_office\ApplicationUsers;

use App\ApplicationUserInvoice;
use App\Http\Controllers\Controller;

class ApplicationUserInvoiceController extends Controller
{
    /**
     * @var ApplicationUserInvoice
     */
    private $applicationUserInvoice;

    /**
     * ApplicationUserInvoiceController constructor.
     * @param ApplicationUserInvoice $applicationUserInvoice
     */
    public function __construct
    (
        ApplicationUserInvoice $applicationUserInvoice
    )
    {
        $this->applicationUserInvoice = $applicationUserInvoice;
    }

    /**
     * Cette fonction se charge de déclencher le téléchargement de la facture renseignée à partir du back-office.
     *
     * @param $order_id
     * @return mixed
     */
    public function downloadInvoice
    (
        $order_id
    )
    {
        $result = $this->applicationUserInvoice->where('order_id', $order_id)->get()->toArray();
        $invoice_id = reset($result)['invoice_id'];
        $pathToFile = storage_path() . '/app/public/uploads/invoices/application_users/' . $invoice_id . '.pdf';
        return response()->file($pathToFile);
    }

}