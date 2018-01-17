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
        }

        body .text-center {
            text-align: center;
        }

        body .no-border {
            border: none;
        }

    </style>
</head>

<body>

<htmlpageheader name="page-header">
    <h3>{{ \Illuminate\Support\Facades\Config::get('constants.company_name') }} - Facture n° {{ $invoice_id }}</h3>
</htmlpageheader>

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

<h3><strong>Liste des commandes passées par l'application :</strong></h3>
<table style="page-break-after: always;">
    <thead>
    <tr>
        <td width="12.5%"><strong>Date</strong></td>
        <td width="12.5%" class="text-center"><strong>Item</strong></td>
        <td width="9%" class="text-center"><strong>Happy Hour</strong></td>
        <td width="9%" class="text-center"><strong>Prix</strong></td>
        <td width="9%" class="text-center"><strong>Quantité</strong></td>
        <td width="9%" class="text-center"><strong>Total</strong></td>
        <td width="9%" class="text-center"><strong>Com %</strong></td>
        <td width="9%" class="text-center"><strong>Com € TTC</strong></td>
        <td width="9%" class="text-center"><strong>TVA</strong></td>
        <td width="9%" class="text-center"><strong>Collectée</strong></td>
    </tr>
    </thead>
    <tbody>
    @foreach($group as $date =>$orders)
        <tr>
            <td class="no-border" colspan="3"><strong>{{ $date }}</strong></td>
            <td class="no-border"></td>
            <td class="no-border"></td>
            <td class="no-border"></td>
            <td class="no-border"></td>
            <td class="no-border"></td>
            <td class="no-border"></td>
            <td class="no-border"></td>
        </tr>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order['orderId'] }}</td>
                <td class="text-center">{{ $order['itemName'] }}</td>
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
                    <td class="text-center">{{ number_format($order['itemHHPrice'] * $order['quantity'], 2) }}
                        €
                    </td>
                @else
                    <td class="text-center">{{ number_format($order['itemPrice'] * $order['quantity'], 2) }}
                        €
                    </td>
                @endif
                <td class="text-center">{{ $order['fees'] }}</td>
                @if($order['HHStatus'] == 1)
                    <td class="text-center">{{ number_format($order['itemHHPrice'] * $order['quantity'] / 100, 2) }}
                        €
                    </td>
                @else
                    <td class="text-center">{{ number_format($order['itemPrice'] * $order['quantity'] / 100, 2) }}
                        €
                    </td>
                @endif
                <td class="text-center">{{ $order['tax'] }} %</td>
                @if($order['HHStatus'] == 1)
                    <td class="text-center">{{ number_format($order['itemHHPrice'] * $order['tax'] / 100, 2) }}
                        €
                    </td>
                @else
                    <td class="text-center">{{ number_format($order['itemPrice'] * $order['tax'] / 100, 2) }}
                        €
                    </td>
                @endif
            </tr>

        @endforeach
    @endforeach
    </tbody>

</table>

<h3><strong>Liste des incidents avec remboursement :</strong></h3>
<table style="page-break-after: always;">
    <thead>
    <tr>
        <td width="23%"><strong>Date</strong></td>
        <td width="9%"><strong>Id</strong></td>
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

<h1 class="text-center">Total : {{ number_format($total, 2) }} €</h1>
<h1 class="text-center">Total (-) commission : {{ number_format($total - $commission, 2) }} €</h1>
<h1 class="text-center">Commission TTC {{ number_format($commission, 2) }} €</h1>
<h1 class="text-center">T.V.A. 20 % {{ number_format($commission * 0.2, 2) }} €</h1>
<h1 class="text-center">Commission HT {{ number_format($commission - $commission * 0.2, 2) }} €</h1>

<br/>
<br/>

<h1 class="text-center">TVA collectée {{ number_format($VATCollected, 2) }} €</h1>

<htmlpagefooter name="page-footer">
    {PAGENO}
</htmlpagefooter>

</body>

</html>