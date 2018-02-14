<?php

namespace App\Handlers\Invoices;

use App\ApplicationUser;
use App\ApplicationUserInvoice;
use App\Order;
use App\OrderInfo;
use App\Partner;
use App\PartnerInvoice;
use App\Refund;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use MangoPay\MangoPayApi;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class InvoicesGenerator
{
    private $password = 'mr7%eaB6NeH68xh[cbUMvs#sh7vre{j]XW/;44a%GiCy6FF8{ZL3xRiCQ,KeVmCh';

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
     * @var PartnerInvoice
     */
    private $partnerInvoice;

    /**
     * @var Order
     */
    private $order;
    /**
     * @var VATCalculator
     */
    private $VATCalculator;
    /**
     * @var ApplicationUser
     */
    private $applicationUser;
    /**
     * @var ApplicationUserInvoice
     */
    private $applicationUserInvoice;

    /**
     * InvoicesGenerator constructor.
     * @param Order $order
     * @param Partner $partner
     * @param OrderInfo $orderInfo
     * @param Refund $refund
     * @param MangoPayApi $mangoPayApi
     * @param PartnerInvoice $partnerInvoice
     * @param ApplicationUserInvoice $applicationUserInvoice
     * @param VATCalculator $VATCalculator
     * @param ApplicationUser $applicationUser
     * @internal param PartnerInvoice $invoice
     */
    public function __construct(
        Order $order,
        Partner $partner,
        OrderInfo $orderInfo,
        Refund $refund,
        MangoPayApi $mangoPayApi,
        PartnerInvoice $partnerInvoice,
        ApplicationUserInvoice $applicationUserInvoice,
        VATCalculator $VATCalculator,
        ApplicationUser $applicationUser
    )
    {
        $this->partner = $partner;
        $this->orderInfo = $orderInfo;
        $this->refund = $refund;
        $this->mangoPayApi = $mangoPayApi;
        $this->partnerInvoice = $partnerInvoice;
        $this->order = $order;
        $this->VATCalculator = $VATCalculator;
        $this->applicationUser = $applicationUser;
        $this->applicationUserInvoice = $applicationUserInvoice;
    }

    /**
     * @param $applicationUser_id
     * @param $order_id
     */
    public function generateApplicationUserInvoice($applicationUser_id, $order_id)
    {
        $applicationUser = $this->applicationUser->findOrFail($applicationUser_id);

        $orders = $this->orderInfo
            ->where('applicationUser_id', $applicationUser_id)
            ->where('accepted', true)
            ->where('order_id', $order_id)
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

        $total = 0;
        $vat = 0;
        foreach ($orders as $order) {
            if ($order['HHStatus'] == 1) {
                $total += $order['itemHHPrice'] * $order['quantity'];
                $vat += $this->VATCalculator->get_vat_amount_from_ttc_and_tax(($order['itemHHPrice'] * $order['quantity']), $order['tax']);
            } else {
                $total += $order['itemPrice'] * $order['quantity'];
                $vat += $this->VATCalculator->get_vat_amount_from_ttc_and_tax(($order['itemPrice'] * $order['quantity']), $order['tax']);
            }
        }

        /**
         * Création d'un numéro de facture unique.
         */
        do {
            $result = DB::select('SELECT id FROM application_users_invoices ORDER BY id DESC LIMIT 1');
            if (isset($result) && !empty($result)) {
                $last_insert_id = $result[0]->id + 1;
            } else {
                $last_insert_id = 1;
            }
            $invoice_id = 'C' . Carbon::now()->year . '-' . str_pad($last_insert_id, 7, "0", STR_PAD_LEFT);
        } while (!$this->applicationUserInvoice->where('invoice_id', '=', $invoice_id)->get()->isEmpty());

        /**
         * Enregistre les information de la facture dans la base de données.
         */


        $orderInfo = $this->orderInfo->findOrFail($order_id);

        /**
         * Prise en compte d'une facture partagée.
         */
        if ($orderInfo->payInId_share_bill == null) {

            $this->applicationUserInvoice->create([
                'applicationUser_id' => $applicationUser_id,
                'order_id' => $order_id,
                'invoice_id' => $invoice_id,
                'invoice_type' => 'invoice'
            ])->save();

            $pdf = PDF::loadView(
                'invoices.applicationUser_order_invoice',
                compact('applicationUser', 'invoice_id', 'orders', 'total', 'vat', 'orderInfo'),
                [],
                [
                    'format' => 'A4',
                    'author' => Config::get('constants.company_name'),
                    'subject' => '',
                    'keywords' => 'facture',
                    'creator' => Config::get('constants.company_name'),
                    'display_mode' => 'fullpage',
                    'tempDir' => storage_path('app/public/uploads/invoices')
                ]);
            $pdf->SetProtection(['copy', 'modify'], '', $this->password);
            $pdf->save(storage_path('app/public/uploads/invoices/application_users/') . $invoice_id . '.pdf');
        } else {
            $this->applicationUserInvoice->create([
                'applicationUser_id' => $applicationUser_id,
                'order_id' => $order_id,
                'invoice_id' => $invoice_id,
                'invoice_type' => 'invoice'
            ])->save();

            $total = $total / 2;
            $vat = $vat / 2;
            $pdf = PDF::loadView(
                'invoices.applicationUser_order_invoice',
                compact('applicationUser', 'invoice_id', 'orders', 'total', 'vat', 'orderInfo'),
                [],
                [
                    'format' => 'A4',
                    'author' => Config::get('constants.company_name'),
                    'subject' => '',
                    'keywords' => 'facture',
                    'creator' => Config::get('constants.company_name'),
                    'display_mode' => 'fullpage',
                    'tempDir' => storage_path('app/public/uploads/invoices')
                ]);
            $pdf->SetProtection(['copy', 'modify'], '', $this->password);
            $pdf->save(storage_path('app/public/uploads/invoices/application_users/') . $invoice_id . '.pdf');

            $applicationUser = $this->applicationUser->findOrFail($orderInfo->applicationUser_id_share_bill);

            do {
                $result = DB::select('SELECT id FROM application_users_invoices ORDER BY id DESC LIMIT 1');
                if (isset($result) && !empty($result)) {
                    $last_insert_id = $result[0]->id + 1;
                } else {
                    $last_insert_id = 1;
                }
                $invoice_id = 'C' . Carbon::now()->year . '-' . str_pad($last_insert_id, 7, "0", STR_PAD_LEFT);
            } while (!$this->applicationUserInvoice->where('invoice_id', '=', $invoice_id)->get()->isEmpty());

            $this->applicationUserInvoice->create([
                'applicationUser_id' => $applicationUser->id,
                'order_id' => $order_id,
                'invoice_id' => $invoice_id,
                'invoice_type' => 'invoice'
            ])->save();

            $pdf = PDF::loadView(
                'invoices.applicationUser_order_invoice',
                compact('applicationUser', 'invoice_id', 'orders', 'total', 'vat', 'orderInfo'),
                [],
                [
                    'format' => 'A4',
                    'author' => Config::get('constants.company_name'),
                    'subject' => '',
                    'keywords' => 'facture',
                    'creator' => Config::get('constants.company_name'),
                    'display_mode' => 'fullpage',
                    'tempDir' => storage_path('app/public/uploads/invoices')
                ]);
            $pdf->SetProtection(['copy', 'modify'], '', $this->password);
            $pdf->save(storage_path('app/public/uploads/invoices/application_users/') . $invoice_id . '.pdf');

        }
    }

    /**
     * @param $applicationUser
     * @param $initialInvoice
     * @param $refundResults
     * @param $request
     */
    public function generateApplicationUserCredit
    (
        $applicationUser,
        $initialInvoice,
        $refundResults,
        $request
    )
    {
        $created_at = Carbon::createFromTimestamp($refundResults->CreationDate);

        $orders = $this->orderInfo
            ->where('applicationUser_id', $applicationUser->id)
            ->where('accepted', true)
            ->where('order_id', $initialInvoice->order_id)
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

        $total = 0;
        $vat = 0;
        foreach ($orders as $order) {
            if ($order['HHStatus'] == 1) {
                $total += $order['itemHHPrice'] * $order['quantity'];
                $vat += $this->VATCalculator->get_vat_amount_from_ttc_and_tax(($order['itemHHPrice'] * $order['quantity']), $order['tax']);
            } else {
                $total += $order['itemPrice'] * $order['quantity'];
                $vat += $this->VATCalculator->get_vat_amount_from_ttc_and_tax(($order['itemPrice'] * $order['quantity']), $order['tax']);
            }
        }

        do {
            $result = DB::select('SELECT id FROM application_users_invoices ORDER BY id DESC LIMIT 1');
            if (isset($result) && !empty($result)) {
                $last_insert_id = $result[0]->id + 1;
            } else {
                $last_insert_id = 1;
            }
            $invoice_id = 'C' . Carbon::now()->year . '-' . str_pad($last_insert_id, 7, "0", STR_PAD_LEFT);
        } while (!$this->applicationUserInvoice->where('invoice_id', '=', $invoice_id)->get()->isEmpty());

        $this->applicationUserInvoice->create([
            'applicationUser_id' => $applicationUser->id,
            'order_id' => $initialInvoice->order_id,
            'invoice_id' => $invoice_id,
            'invoice_type' => 'credit'
        ])->save();

        $pdf = PDF::loadView(
            'invoices.applicationUser_order_credit',
            compact('applicationUser', 'initialInvoice', 'request', 'invoice_id', 'created_at', 'total', 'vat'),
            [],
            [
                'format' => 'A4',
                'author' => Config::get('constants.company_name'),
                'subject' => '',
                'keywords' => 'facture',
                'creator' => Config::get('constants.company_name'),
                'display_mode' => 'fullpage',
                'tempDir' => storage_path('app/public/uploads/invoices')
            ]);
        $pdf->SetProtection(['copy', 'modify'], '', $this->password);
        $pdf->save(storage_path('app/public/uploads/invoices/application_users/') . $invoice_id . '.pdf');
    }

    /**
     * @param $start_date
     * @param $end_date
     * @param $partner_id
     * @return array
     */
    public function generatePartnerInvoice($start_date, $end_date, $partner_id)
    {
        $partner = $this->partner->findOrFail($partner_id);

        $bankAccount = current($this->mangoPayApi->Users->GetBankAccounts($partner->mango_id));

        // TODO -- Est-ce que le dd ne bloque pas les autres scripts qui vont être mis à la queue ? SI!
        if ($bankAccount === false) {
            Session::flash('error', 'Aucun compte n\'est renseigné pour ce partenaire .');
            return false;
            //dd('Aucun compte n\'est renseigné pour ce partenaire.');
        }

        /**
         * Récupère toutes les commandes qui sont comprises entre les dates renseignées pour un partenaire.
         */
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

        /**
         * Prépare un tableau ou toutes les commandes ont une date de création.
         */
        $group = [];
        $refund_msg = " Fait l'objet d'un remboursement.";
        foreach ($orders as $order) {
            $refund = $this->refund->where('order_id', $order['order_id'])->first();

            if (isset($refund) && !empty($refund)) {
                if (!isset($group[$order['created_at'] . $refund_msg])) {
                    /**
                     * Vérifie que la commande ne fait pas l'objet d'un remboursement -> sinon le stipule dans le champs date.
                     */
                    $group[$order['created_at'] . $refund_msg] = [];

                }
                array_push($group[$order['created_at'] . $refund_msg], $order);

            } else {
                if (!isset($group[$order['created_at']])) {
                    /**
                     * Vérifie que la commande ne fait pas l'objet d'un remboursement -> sinon le stipule dans le champs date.
                     */
                    $group[$order['created_at']] = [];

                }
                array_push($group[$order['created_at']], $order);
            }
        }

        /**
         * Calcul des totaux.
         */
        $total = 0;
        $commission = 0;
        $vat_collected = 0;
        foreach ($orders as $order) {
            if ($order['HHStatus'] == 1) {
                $total += $order['itemHHPrice'] * $order['quantity'];
                $commission += $order['itemHHPrice'] * $order['quantity'] * $order['fees'] / 100;
                $vat_collected += $this->VATCalculator->get_vat_amount_from_ttc_and_tax(($order['itemHHPrice'] * $order['quantity']), $order['tax']);
            } else {
                $total += $order['itemPrice'] * $order['quantity'];
                $commission += $order['itemPrice'] * $order['quantity'] * $order['fees'] / 100;
                $vat_collected += $this->VATCalculator->get_vat_amount_from_ttc_and_tax(($order['itemPrice'] * $order['quantity']), $order['tax']);
            }
        }

        /**
         * Récupère tous les remboursements réussis des commandes concernées.
         */
        $incidents = [];
        foreach ($orders as $order) {
            $refund = $this->refund
                ->where('order_id', $order['order_id'])
                ->where('success', 1)
                ->get()
                ->toArray();
            /**
             * S'il y a un remboursement est que celui-ci est réussi, on l'ajoute au tableau des remboursements.
             */
            if ($refund != []) {
                foreach ($refund as $item) {
                    if ($item['success'] !== 0) {
                        unset($item);
                    }
                }
                if (!in_array([$refund], $incidents)) {
                    array_push($incidents, [$refund]);
                }
            }
        }

        /**
         * Re calcul des totaux avec les remboursement.
         */
        $refund_amount = 0;
        $refund_comission = 0;
        $refund_vat = 0;

        foreach ($incidents as $incident) {
            foreach ($incident as $refunds) {
                foreach ($refunds as $refund) {
                    $refund_amount += $refund['amount'];

                    $orderInfo = $this->orderInfo->findOrFail($refund['order_id'])->toArray();
                    $orders = $this->order->where('order_id', $refund['order_id'])->get()->toArray();

                    $total_TTC_order = 0;
                    $VAT_order = 0;

                    if ($orderInfo['HHStatus'] == 1) {
                        foreach ($orders as $order) {
                            $total_TTC_order = $order['itemHHPrice'] * $order['quantity'];
                            $VAT_order = $this->VATCalculator->get_vat_amount_from_ttc_and_tax(($order['itemHHPrice'] * $order['quantity']), $order['tax']);
                        }
                    } else {
                        foreach ($orders as $order) {
                            $total_TTC_order = $order['itemPrice'] * $order['quantity'];
                            $VAT_order = $this->VATCalculator->get_vat_amount_from_ttc_and_tax(($order['itemPrice'] * $order['quantity']), $order['tax']);
                        }
                    }
                    $refund_vat += $refund['amount'] / $total_TTC_order * $VAT_order;
                    $refund_comission += $orderInfo['fees'] * $refund['amount'] / 100;
                }
            }
        }

        /**
         * Création d'un numéro de facture unique.
         */
        do {
            $result = DB::select('SELECT id FROM partners_invoices ORDER BY id DESC LIMIT 1');
            if (isset($result) && !empty($result)) {
                $last_insert_id = $result[0]->id + 1;
            } else {
                $last_insert_id = 1;
            }
            $invoice_id = 'P' . Carbon::now()->year . '-' . str_pad($last_insert_id, 5, "0", STR_PAD_LEFT);
        } while (!$this->partnerInvoice->where('invoice_id', '=', $invoice_id)->get()->isEmpty());

        /**
         * Enregistre les information de la facture dans la base de données.
         */
        $this->partnerInvoice->create([
            'partner_id' => $partner->id,
            'invoice_id' => $invoice_id,
            'from' => $start_date,
            'to' => $end_date,
            'invoice_type' => 'invoice'
        ])->save();

        /**
         * Génère et enregistre la facture.
         */
        $pdf = PDF::loadView(
            'invoices.partner_monthly_invoice',
            compact('partner', 'bankAccount', 'group', 'total', 'commission', 'incidents', 'invoice_id', 'vat_collected', 'refund_amount', 'refund_comission', 'refund_vat'),
            [],
            [
                'format' => 'A4',
                'author' => Config::get('constants.company_name'),
                'subject' => '',
                'keywords' => 'facture',
                'creator' => Config::get('constants.company_name'),
                'display_mode' => 'fullpage',
                'tempDir' => storage_path('app/public/uploads/invoices')
            ]);
        $pdf->SetProtection(['copy', 'modify'], '', $this->password);
        $pdf->save(storage_path('app/public/uploads/invoices/partners') . $invoice_id . '.pdf');
        return [
            'invoice_id' => $invoice_id,
            'invoice_path' => storage_path() . '/app/public/uploads/invoices/partners/' . $invoice_id . '.pdf'
        ];
    }

}