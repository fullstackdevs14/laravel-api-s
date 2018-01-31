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
            {{ $applicationUser->firstName }}  {{ $applicationUser->lastName }}
            <br/>
            <br/>
            <br/>
            @if($orderInfo->applicationUser_id_share_bill != null)
                <p>Cette commande à été partagée avec un autre utilisateur.</p>
            @endif

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
            {{ $orderInfo->updated_at }}<br>
        </td>
    </tr>
</table>

<br/>
<br/>

<h3><strong>Détails de la commande :</strong></h3>
<table>
    <thead>
    <tr class="slight-bg-color">
        <td width="12.5%" class="text-left"><strong>Item</strong></td>
        <td width="9%" class="text-center"><strong>Happy Hour</strong></td>
        <td width="9%" class="text-center"><strong>Prix TTC</strong></td>
        <td width="9%" class="text-center"><strong>Quantité</strong></td>
        <td width="9%" class="text-center"><strong>Total TTC</strong></td>
        <td width="9%" class="text-center"><strong>TVA</strong></td>
        <td width="9%" class="text-center"><strong>Total HT</strong></td>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td class="text-left">{{ $order['itemName'] }}</td>

            @if($order['HHStatus'] == 1)
                <td class="text-center">Oui</td>
            @else
                <td class="text-center">Non</td>
            @endif

            @if($order['HHStatus'] == 1)
                <td class="text-center">{{ $order['itemHHPrice'] }} €</td>
            @else
                <td class="text-center">{{ $order['itemPrice'] }} €</td>
            @endif

            <td class="text-center">{{ $order['quantity'] }}</td>

            @if($order['HHStatus'] == 1)
                <td class="text-center">{{ number_format($order['itemHHPrice'] * $order['quantity'], 2) }}€</td>
            @else
                <td class="text-center">{{ number_format($order['itemPrice'] * $order['quantity'], 2) }}€</td>
            @endif

            <td class="text-center">{{ $order['tax'] }} %</td>

            @if($order['HHStatus'] == 1)
                <td class="text-center">{{ number_format(\App\Handlers\Invoices\VATCalculator::get_ht_amount_from_ttc_and_tax(($order['itemHHPrice'] * $order['quantity']), $order['tax']),2) }}
                    €
                </td>
            @else
                <td class="text-center">{{ number_format(\App\Handlers\Invoices\VATCalculator::get_ht_amount_from_ttc_and_tax(($order['itemPrice'] * $order['quantity']), $order['tax']),2) }}
                    €
                </td>
            @endif
        </tr>
    @endforeach
    @if($orderInfo->applicationUser_id_share_bill != null)
        <tr>
            <td class="text-left" colspan="3">Les notes partagées ont un surcoût de 20 centimes.</td>
            <td class="text-center">1</td>
            <td class="text-center">0,20 €</td>
            <td class="text-center">20.00 %</td>
            <td class="text-center">{{ number_format(\App\Handlers\Invoices\VATCalculator::get_ht_amount_from_ttc_and_tax(0.20,20), 2) }}
                €
            </td>
        </tr>
    @endif
    <tr class="no-border">
        <td colspan="5" class="no-border"></td>
        <td class="text-center no-border">Total HT</td>
        <td class="text-center no-border">
            @if($orderInfo->applicationUser_id_share_bill == null)
                {{number_format($total - $vat,2)}}
            @else
                {{number_format($total - $vat + App\Handlers\Invoices\VATCalculator::get_ht_amount_from_ttc_and_tax(0.20,20),2)}}
            @endif
            €
        </td>
    </tr>
    <tr class="no-border">
        <td colspan="5" class="no-border"></td>
        <td class="text-center no-border">Total TVA</td>
        <td class="text-center no-border">
            @if($orderInfo->applicationUser_id_share_bill == null)
                {{ number_format($vat, 2) }}
            @else
                {{ number_format($vat + App\Handlers\Invoices\VATCalculator::get_ht_amount_from_ttc_and_tax(0.20,20), 2) }}
            @endif
            €
        </td>
    </tr>
    <tr class="no-border">
        <td colspan="5" class="no-border"></td>
        <td class="text-center no-border">Total TTC</td>
        <td class="text-center no-border">
            @if($orderInfo->applicationUser_id_share_bill == null)
                {{ number_format($total, 2) }}
            @else
                {{ number_format($total + 0.20, 2) }}
            @endif
            €
        </td>
    </tr>
    </tbody>
</table>

<htmlpagefooter name="page-footer">
    {PAGENO}
</htmlpagefooter>

</body>

</html>