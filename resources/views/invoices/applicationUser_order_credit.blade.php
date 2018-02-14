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
                    - Avoir n° {{ $invoice_id }}</h3>
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
            {{ $created_at }}<br>
        </td>
    </tr>
</table>

<br/>
<br/>

<h3><strong>Détails de l'avoir :</strong></h3>
<table>
    <thead>
    <tr class="slight-bg-color">
        <td width="30%" class="text-left"><strong>Description</strong></td>
        <td width="15%" class="text-center"><strong>N° de la facture d'origine</strong></td>
        <td width="15%" class="text-center"><strong>Date de la facture d'origine</strong></td>
        <td width="10%" class="text-center"><strong>Montant de la facture d'origine</strong></td>
        <td width="10%" class="text-center"><strong>TVA de la facture d'origine</strong></td>
        <td width="10%" class="text-center"><strong>% de remise sur la facture d'origine</strong></td>
        <td width="10%" class="text-center"><strong>Montant de l'avoir</strong></td>
    </tr>
    </thead>
    <tr>
        <td class="text-left">Motif : {{ $request->description }}</td>
        <td class="text-center"> {{ $initialInvoice->invoice_id }} </td>
        <td class="text-center"> {{ $initialInvoice->created_at }} </td>
        <td class="text-center"> {{ number_format($total, 2) }} €</td>
        <td class="text-center"> {{ number_format($vat, 2) }} €</td>
        <td class="text-center"> {{ number_format((100 * $request->amount / $total) , 2) }} %</td>
        <td class="text-center"> {{ number_format($request->amount,2) }} €</td>
    </tr>
    <tbody>
    <tr class="no-border">
        <td colspan="5" class="no-border"></td>
        <td class="text-center no-border">Total HT</td>
        <td class="text-center no-border">
            - {{ number_format(($request->amount - ($vat * $request->amount / $total)), 2)}}
            €
        </td>
    </tr>
    <tr class="no-border">
        <td colspan="5" class="no-border"></td>
        <td class="text-center no-border">Total TVA</td>
        <td class="text-center no-border">- {{ number_format(($vat * $request->amount / $total), 2) }} €</td>
    </tr>
    <tr class="no-border">
        <td colspan="5" class="no-border"></td>
        <td class="text-center no-border">Total TTC</td>
        <td class="text-center no-border">- {{ number_format($request->amount, 2) }} €</td>
    </tr>
    </tbody>
</table>

<htmlpagefooter name="page-footer">
    {PAGENO}
</htmlpagefooter>

</body>

</html>