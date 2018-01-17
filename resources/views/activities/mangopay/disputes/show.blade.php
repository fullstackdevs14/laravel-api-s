@extends('templates.template_panel')

@section('panel-title')
    Informations de contestation
@endsection


@section('panel-body')
    {!! Html::RouteWithIconBlank('mangoPay.disputes.submit', 'Soumettre une preuve d\'achat (besoin d\'un accès à Mangopay)', $dispute->Id, 'btn-default', 'file') !!}

    <br>
    <br>

    @component('templates.template_panel_inside')
        @slot('title')
            Création / dead-line :
        @endslot
        {!! Html::ListInfo('Date de création', \Carbon\Carbon::createFromTimestamp($dispute->CreationDate)) !!}
        {!! Html::ListInfo('Dead line', \Carbon\Carbon::createFromTimestamp($dispute->ContestDeadlineDate)) !!}
    @endcomponent

    @component('templates.template_panel_inside')
        @slot('title')
            Type de contestation / statut :
        @endslot
        {!! Html::ListInfo('Identifiant de la contestation', $dispute->Id) !!}
        {!! Html::ListInfo('Identifiant de la transaction initiale', $dispute->InitialTransactionId) !!}
        {!! Html::ListInfo('Type de la transaction initiale', $dispute->InitialTransactionType) !!}
        {!! Html::ListInfo('Type de dispute', $dispute->DisputeType) !!}
        {!! Html::ListInfo('Statut de la contestation', $dispute->Status) !!}
        {!! Html::ListInfo('Raison de la contestation', $dispute->DisputeReason->DisputeReasonType) !!}
        {!! Html::ListInfo('Message', $dispute->DisputeReason->DisputeReasonMessage) !!}
    @endcomponent

    @component('templates.template_panel_inside')
        @slot('title')
            Sommes concernées :
        @endslot
        {!! Html::ListInfo('Montant de la contestation', $dispute->DisputedFunds->Amount / 100 . ' €') !!}
    @endcomponent

@endsection

@section('button-back')
    {!! Html::BackButton() !!}
@endsection