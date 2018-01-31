<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style>

        @page {
            header: page-header;
            footer: page-footer;
        }

        body {
            font-size: 10px !important;
        }

        body table {
            width: 100%;
            border-collapse: collapse;
        }

        body td, body tr {
            border: 1px solid black;
            padding: 3px;
        }

        body .text-center {
            text-align: center;
        }

        body .text-right {
            text-align: right;

        body .text-left {
            text-align: left;
        }

        body .no-border {
            border: none;
        }

        .slight-bg-color {
            background-color: rgba(8, 138, 155, 0.2) !important;
        }

        .sum-up td {
            width: 50%;
            font-size: x-large;
        }


    </style>
</head>

<body>

<htmlpageheader name="page-header">
    <table>
        <tr class="no-border">
            <td class="no-border"><h3>{{ \Illuminate\Support\Facades\Config::get('constants.company_name') }}
                    - Facture n° {{ $invoice_id }}</h3>
            </td>
            <td class="no-border" style="text-align: right; vertical-align: text-top;">
                <img src="{{ public_path('img/Sipper-logo.svg') }}" alt="logo"
                     width="4%">
            </td>
        </tr>
    </table>
</htmlpageheader>

<table style="margin-top: 10px">
    <tr class="no-border">
        <td class="no-border" valign="top" width="70%">
            <strong>Facturé à :</strong><br>
            {{ $partner->name }}<br>
            {{ $partner->address }}<br>
            {{ $partner->postalCode }}<br>
            {{ $partner->city }}<br>

            <br/>

            <strong>Représenté par :</strong><br>
            {{ $partner->ownerFirstName }} {{ $partner->ownerLastName }}<br>

            <br/>

            <strong>Reversement des sommes dues :</strong><br>
            IBAN : {{ $bankAccount->Details->IBAN }}<br>
            {{ $partner->email }}<br>
        </td>
        <td class="no-border" valign="top" width="30%">
            <strong>Facturé par :</strong><br>
            {{ \Illuminate\Support\Facades\Config::get('constants.company_name') }}<br>
            {!! \Illuminate\Support\Facades\Config::get('constants.company_address') !!}<br>
            SIRET : {{ \Illuminate\Support\Facades\Config::get('constants.company_siret') }}<br>
            {{ \Illuminate\Support\Facades\Config::get('constants.company_legal_form') }}<br>
            N° TVA : {{ \Illuminate\Support\Facades\Config::get('constants.company_tva_number') }}<br>

            <br/>

            <strong>Date d'émission:</strong><br>
            {{ \Carbon\Carbon::now() }}<br>
        </td>
    </tr>
</table>

<br/>
<br/>

<h3><strong>Liste des commandes passées par l'application :</strong></h3>
<table style="page-break-after: always;">
    <thead>
    <tr class="slight-bg-color">
        <td width="12.5%"><strong>Date</strong></td>
        <td width="12.5%" class="text-center"><strong>Item</strong></td>
        <td width="9%" class="text-center"><strong>Happy Hour</strong></td>
        <td width="9%" class="text-center"><strong>Prix TTC</strong></td>
        <td width="9%" class="text-center"><strong>Quantité</strong></td>
        <td width="9%" class="text-center"><strong>Total TTC</strong></td>
        <td width="9%" class="text-center"><strong>TVA</strong></td>
        <td width="9%" class="text-center"><strong>Collectée</strong></td>
        <td width="9%" class="text-center"><strong>Com %</strong></td>
        <td width="9%" class="text-center"><strong>Com € TTC</strong></td>
    </tr>
    </thead>
    <tbody>
    @foreach($group as $date =>$orders)
        <!--Date-->
        <tr>
            <td class="no-border" colspan="10"><strong>{{ $date }}</strong></td>
        </tr>
        @foreach($orders as $order)
            <tr>
                <!--Numéro de commande-->
                <td>{{ $order['orderId'] }}</td>

                <!--Item-->
                <td class="text-center">{{ $order['itemName'] }}</td>

                <!--Happy Hour ?-->
                @if($order['HHStatus'] == 1)
                    <td class="text-center">Oui</td>
                @else
                    <td class="text-center">Non</td>
                @endif

                <!--Prix TTC-->
                @if($order['HHStatus'] == 1)
                    <td class="text-center">{{ $order['itemHHPrice'] }} €</td>
                @else
                    <td class="text-center">{{ $order['itemPrice'] }} €</td>
                @endif

                <!--Quantité-->
                <td class="text-center">{{ $order['quantity'] }}</td>

                <!--Total TTC-->
                @if($order['HHStatus'] == 1)
                    <td class="text-center">{{ number_format($order['itemHHPrice'] * $order['quantity'], 2) }}
                        €
                    </td>
                @else
                    <td class="text-center">{{ number_format($order['itemPrice'] * $order['quantity'], 2) }}
                        €
                    </td>
                @endif

                <!--% TVA-->
                <td class="text-center">{{ $order['tax'] }} %</td>

                <!--TVA collecté-->
                @if($order['HHStatus'] == 1)
                    <td class="text-center">{{ number_format(\App\Handlers\Invoices\VATCalculator::get_vat_amount_from_ttc_and_tax(($order['itemHHPrice'] * $order['quantity']),$order['tax']), 2) }}
                        €
                    </td>
                @else
                    <td class="text-center">{{ number_format(\App\Handlers\Invoices\VATCalculator::get_vat_amount_from_ttc_and_tax(($order['itemPrice'] * $order['quantity']),$order['tax']), 2) }}
                        €
                    </td>
                @endif

                <!--% Commission-->
                <td class="text-center">{{ $order['fees'] }}</td>

                <!--Commission TTC-->
                @if($order['HHStatus'] == 1)
                    <td class="text-center">{{ number_format($order['itemHHPrice'] * $order['quantity'] * $order['fees'] / 100, 2) }}
                        €
                    </td>
                @else
                    <td class="text-center">{{ number_format($order['itemPrice'] * $order['quantity'] * $order['fees'] / 100, 2) }}
                        €
                    </td>
                @endif
            </tr>

        @endforeach
    @endforeach
    </tbody>

</table>

<h3><strong>Liste des incidents avec remboursement (avoir) :</strong></h3>

<table style="page-break-after: always;">
    <thead>
    <tr class="slight-bg-color">
        <td width="23%"><strong>Date</strong></td>
        <td width="9%"><strong>N° commande</strong></td>
        <td width="9%"><strong>Montant</strong></td>
        <td width="59%"><strong>Description</strong></td>
    </tr>
    </thead>
    <tbody>
    @foreach($incidents as $incident)
        @foreach($incident as $refunds)
            @foreach($refunds as $refund)
                <tr>
                    <td><strong>{{ $refund['created_at'] }}</strong></td>
                    <td>{{ \App\OrderInfo::where('id', $refund['order_id'])->get(['orderId'])->first()->orderId }}</td>
                    <td>{{ number_format($refund['amount'], 2) }} €</td>
                    <td class="text-justify">{!! $refund['description'] !!}</td>
                </tr>
            @endforeach
        @endforeach
    @endforeach

    </tbody>
</table>

<table>
    <tr class="no-border">
        <td class="no-border" valign="top" width="70%">
            <strong>Facturé à :</strong><br>
            {{ $partner->name }}<br>
            {{ $partner->address }}<br>
            {{ $partner->postalCode }}<br>
            {{ $partner->city }}<br>

            <br/>

            <strong>Représenté par :</strong><br>
            {{ $partner->ownerFirstName }} {{ $partner->ownerLastName }}<br>

            <br/>

            <strong>Reversement des sommes dues :</strong><br>
            IBAN : {{ $bankAccount->Details->IBAN }}<br>
            {{ $partner->email }}<br>
        </td>
        <td class="no-border" valign="top" width="30%">
            <strong>Facturé par :</strong><br>
            {{ \Illuminate\Support\Facades\Config::get('constants.company_name') }}<br>
            {!! \Illuminate\Support\Facades\Config::get('constants.company_address') !!}<br>
            SIRET : {{ \Illuminate\Support\Facades\Config::get('constants.company_siret') }}<br>
            {{ \Illuminate\Support\Facades\Config::get('constants.company_legal_form') }}<br>
            N° TVA : {{ \Illuminate\Support\Facades\Config::get('constants.company_tva_number') }}<br>

            <br/>

            <strong>Date d'émission:</strong><br>
            {{ \Carbon\Carbon::now() }}<br>
        </td>
    </tr>
</table>

<br/>
<br/>

<table class="sum-up">
    <tr>
        <td class="text-right">CA TTC :</td>
        <td class="text-left">{{ number_format($total, 2) }} €</td>
    </tr>
    <tr>
        <td class="text-right">Montant des avoirs :</td>
        <td class="text-left">{{ number_format( $refund_amount, 2) }} €</td>
    </tr>
    <tr>
        <td class="text-right">CA net TTC :</td>
        <td class="text-left">{{ number_format($total - $refund_amount, 2) }} €</td>
    </tr>
    <tr>
        <td class="text-right">Commission TTC :</td>
        <td class="text-left">{{ number_format($commission - $refund_comission, 2) }} €</td>
    </tr>
    <tr>
        <td class="text-right">CA net TTC (-) commission TTC :</td>
        <td class="text-left">{{ number_format(($total - $refund_amount) - ($commission - $refund_comission), 2) }}€
        </td>
    </tr>
    <tr>
        <td class="text-right">T.V.A. 20 % sur commission :</td>
        <td class="text-left">{{ number_format(\App\Handlers\Invoices\VATCalculator::get_vat_amount_from_ttc_and_tax(($commission - $refund_comission), 20), 2) }}
            €
        </td>
    </tr>
    <tr>
        <td class="text-right"><strong>Commission HT :</strong></td>
        <td class="text-left">
            <strong>{{ number_format(\App\Handlers\Invoices\VATCalculator::get_ht_amount_from_ttc_and_tax(($commission - $refund_comission), 20), 2) }}
                €</strong></td>
    </tr>
    <tr>
        <td class="text-right">TVA collectée (par vos soins) :</td>
        <td class="text-left">{{ number_format($vat_collected - $refund_vat, 2) }} €</td>
    </tr>

</table>

<br/>
<br/>

<htmlpagefooter name="page-footer">
    {PAGENO}
</htmlpagefooter>

</body>

</html>