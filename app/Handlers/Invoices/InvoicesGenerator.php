<?php

namespace App\Handlers\Invoices;

use App\Invoice;
use App\OrderInfo;
use App\Partner;
use App\Refund;
use Illuminate\Support\Facades\Config;
use MangoPay\MangoPayApi;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class InvoicesGenerator
{
    /**
     * @var Partner
     */
    private $partner;

    /**
     * @var OrderInfo
     */
    private $orderInfo;

    /**
     * @var Refund
     */
    private $refund;

    /**
     * @var MangoPayApi
     */
    private $mangoPayApi;

    /**
     * @var Invoice
     */
    private $invoice;
    private $partnerForMessage;
    private $invoice_idForMessage;

    public function __construct(
        Partner $partner,
        OrderInfo $orderInfo,
        Refund $refund,
        MangoPayApi $mangoPayApi,
        Invoice $invoice

    )
    {
        $this->partner = $partner;
        $this->orderInfo = $orderInfo;
        $this->refund = $refund;
        $this->mangoPayApi = $mangoPayApi;
        $this->invoice = $invoice;
    }

    public function generatePartnerInvoice($start_date, $end_date, $partner_id)
    {
        $partner = $this->partner->findOrFail($partner_id);
        $this->partnerForMessage = $partner;

        $bankAccount = current($this->mangoPayApi->Users->GetBankAccounts($partner->mango_id));

        // TODO -- Est-ce que le dd ne bloque pas les autres scripts qui vont être mis à la queue ?
        if ($bankAccount === false) {
            dd('Aucun compte n\'est renseigné pour ce partenaire.');
        }

        $orders = $this->orderInfo
            ->where('partner_id', $partner_id)
            ->where('accepted', true)
            ->where('created_at', '<=', $end_date)
            ->where('created_at', '>=', $start_date)
            ->join('orders', 'orders_info.id', '=', 'orders.order_id')
            ->get([
                'order_id',
                'orderId',
                'created_at',
                'itemName',
                'itemPrice',
                'itemHHPrice',
                'HHStatus',
                'tax',
                'alcohol',
                'quantity',
                'fees',
            ])->toArray();

        $group = [];
        foreach ($orders as $order) {
            if (!isset($group[$order['created_at']])) {
                $group[$order['created_at']] = [];
            }
            array_push($group[$order['created_at']], $order);
        }

        $total = 0;
        $commission = 0;
        $VATCollected = 0;

        foreach ($orders as $order) {
            if ($order['HHStatus'] == 1) {
                $total += $order['itemHHPrice'] * $order['quantity'];
                $commission += $order['itemHHPrice'] * $order['quantity'] * $order['fees'] / 100;
                $VATCollected += $order['itemHHPrice'] * $order['quantity'] * $order['tax'] / 100;
            } else {
                $total += $order['itemPrice'] * $order['quantity'];
                $commission += $order['itemPrice'] * $order['quantity'] * $order['fees'] / 100;
                $VATCollected += $order['itemPrice'] * $order['quantity'] * $order['tax'] / 100;
            }
        }

        $incidents = [];
        foreach ($orders as $order) {
            $refund = $this->refund
                ->where('order_id', $order['order_id'])
                ->where('success', 1)
                ->get()
                ->toArray();
            if ($refund != []) {
                array_push($incidents, [$refund]);
            }
        }

        do {
            $invoice_id = strtoupper(substr(uniqid(), 8, 11));
        } while (!$this->invoice->where('invoice_id', '=', $invoice_id)->get()->isEmpty());

        $this->invoice_idForMessage = $invoice_id;

        $this->invoice->create([
            'partner_id' => $partner->id,
            'invoice_id' => $invoice_id,
            'from' => $start_date,
            'to' => $end_date
        ])->save();

        $pdf = PDF::loadView(
            'invoices.partner_monthly_invoice',
            compact('partner', 'bankAccount', 'group', 'total', 'commission', 'incidents', 'invoice_id', 'VATCollected'),
            [],
            [
                'format' => 'A4',
                'author' => Config::get('constants.company_name'),
                'subject' => '',
                'keywords' => 'facture',
                'creator' => Config::get('constants.company_name'),
                'display_mode' => 'fullpage'
            ]);
        $pdf->SetProtection(['copy', 'modify'], '', '=lihjqcr98');

        $pdf->save('storage/app/public/uploads/invoices/' . $invoice_id . '.pdf');

        return [
            'invoice_id' => $invoice_id,
            'invoice_path' => storage_path() . '/app/public/uploads/invoices/' . $invoice_id . '.pdf'
        ];
    }

}