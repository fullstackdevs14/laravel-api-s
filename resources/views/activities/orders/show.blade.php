@extends('templates.template_panel')

@section('panel-title')
    Détails de la commande
@endsection

@section('panel-body')

    @if($orderInfo->incident == "0")
        {!! Html::RouteWithIcon('applicationUser_incident.create', 'Créer un incident', $orderInfo->id, 'btn-danger pull-right', 'fire') !!}
    @else
        {!! Html::RouteWithIcon('applicationUser_incident.show', 'Voir l\'incident', $incident->id, 'btn-warning pull-right', 'fire') !!}
    @endif

    {!! Html::ListInfo('Date de la commande', $orderInfo->created_at) !!}
    {!! Html::ListInfo('Numéro de commande', $orderInfo->orderId) !!}
    {!! Html::ListInfo('Partenaire', $partner->name) !!}
    {!! Html::ListInfo('Utilisateur', $applicationUser->firstName . ' ' . $applicationUser->lastName ) !!}
    {!! Html::ListInfo('Identifiant', $applicationUser->email ) !!}

    @if($orderInfo->applicationUser_id_share_bill)
        {!! Html::ListYesOrNo('Commande partagée', true ) !!}
    @else()
        {!! Html::ListYesOrNo('Commande partagée', false ) !!}
    @endif

    @if($applicationUser_2 != null)
        {!! Html::ListInfo('Avec', $applicationUser_2->firstName . ' ' . $applicationUser_2->lastName ) !!}
        {!! Html::ListInfo('Identifiant', $applicationUser_2->email) !!}
    @endif

    <table class="table">
        <thead>
        <tr>
            <th></th>
            <th>Prix de la boisson</th>
            <th>Quantité</th>
            <th>Taxe</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $category => $items)
            <tr>
                <td><strong>{{ $category }}</strong></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->itemName }}</td>
                    @if($orderInfo->HHStatus == 0)
                        <td>{{ $item->itemPrice }} € TTC</td>
                    @else
                        <td>{{ $item->itemHHPrice }} € TTC</td>
                    @endif
                    <td>{{ $item->quantity }} consommation(s)</td>
                    <td>{{ $item->tax }} %</td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td><strong>Total</strong></td>
            <td><strong>{{ $sum }} € TTC</strong></td>
            <td><strong>{{ $quantity }} consommation(s)</strong></td>
            <td><strong>{{ number_format($tax_excluding_sum, 2) }} € HT</strong></td>
        </tr>
        </tfoot>
    </table>
    {!! Html::ListYesOrNo('Commande en happy hour', $orderInfo->HHStatus) !!}
    {!! Html::ListStatus($orderInfo->accepted) !!}
    {!! Html::ListYesOrNo('Délivrée', $orderInfo->delivered) !!}
    {!! Html::ListYesOrNo('Incident', $orderInfo->incident) !!}

    {{ link_to_route('applicationUser_invoice.download', 'Télécharger la facture',  $orderInfo->id, ['class' => 'btn btn-default pull-right', 'target' => '_blank']) }}

@endsection

@section('button-back')
    {!! Html::BackButton() !!}
@endsection