@extends('templates.template_panel')

@section('search-bar')
    {!! Form::SearchBarWithParam('search', 'partner.invoices.index', $partner_id, 'Chercher une facture') !!}
@endsection

@section('panel-title')
    Liste des factures
@endsection

@section('panel-body')
    {!! Html::RouteWithIcon('partner.invoices.generateLastMonth', 'Génèrer une facture pour le mois dernier (avec envoi)', $partner_id, 'btn-default', 'open-file') !!}
    {!! Html::RouteWithIcon('partner.invoices.generateThisMonth', 'Génèrer une facture pour le mois en cours (avec envoi)', $partner_id, 'btn-default', 'open-file') !!}

    <br/>
    <br/>

    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Date de création</th>
            <th>De</th>
            <th>A</th>
            <th>Id</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($invoices as $invoice)
            <tr>
                <td style="vertical-align: middle">{{ $invoice->id }}</td>
                <td style="vertical-align: middle" class="text-default">{{ $invoice->created_at }}</td>
                <td style="vertical-align: middle" class="text-default">{{ $invoice->from }}</td>
                <td style="vertical-align: middle" class="text-default">{{ $invoice->to }}</td>
                <td style="vertical-align: middle" class="text-default">{{ $invoice->invoice_id }}</td>
                <td style="vertical-align: middle">{{ link_to_route('partner.invoices.download', 'télécharger',  $invoice->invoice_id, ['class' => 'btn btn-default', 'target' => '_blank']) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@section('links')
    {{ $links }}
@endsection

@section('button-back')
    {!! Html::RouteWithIcon('partner.edit', 'Partenaire', $partner_id, 'btn-default', 'circle-arrow-left') !!}
@endsection