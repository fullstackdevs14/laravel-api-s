@extends('templates.template_panel_2bodies')

@section('panel-title-1')
    Détails de l'incident
@endsection

@section('panel-body-1')

    {!! Html::RouteWithIcon('applicationUser_incident_handled.handled', 'Incident Traité', $incident->id, 'btn-success', 'ok') !!}
    {!! Html::RouteWithIcon('applicationUser_incident_handled.opened', 'Incident réouvert', $incident->id, 'btn-warning', 'remove') !!}
    {!! Html::RouteWithIcon('applicationUser_incident_handled.urgent', 'Incident urgent', $incident->id, 'btn-danger', 'fire') !!}

    @if($payIn->Status != "NO TRANSACTION" && $payIn->Status != "FAILED")
        {!! Html::RouteWithIcon('applicationUser_incident_refund.show', 'Remboursement depuis le compte du bar', ['order_id' => $orderInfo->id, 'origin' => 'partner'], 'btn-primary pull-right', 'fire') !!}
    @endif

    <br />
    <br />

    @if($payIn->Status != "NO TRANSACTION" && $payIn->Status != "FAILED")
        {!! Html::RouteWithIcon('applicationUser_incident_refund.show', 'Remboursement depuis le compte application', ['order_id' => $orderInfo->id, 'origin' => 'application'], 'btn-warning pull-right disabled', 'fire') !!}
    @endif

    <br/>
    <br/>
    <br/>

    @component('templates.template_panel_inside')
        @slot('title')
            Détails de l'incident
        @endslot
        {!! Html::ListInfo('Numéro de commande', $orderInfo->orderId) !!}
        {!! Html::ListInfo('Date de création', $incident->created_at) !!}
        {!! Html::ListInfo('Dernière modification', $incident->updated_at) !!}
        {!! Html::ListYesOrNo('Traité', $incident->status) !!}
        {!! Html::ListInfo('PayIn id', $orderInfo->payInId) !!}
        @if($orderInfo->applicationUser_id_share_bill != null)
            {!!  Html::ListYesOrNo('Note partagée', true) !!}
            {!! Html::ListInfo('PayIn id', $orderInfo->applicationUser_id_share_bill) !!}
        @else
            {!!  Html::ListYesOrNo('Note partagée', false) !!}
        @endif

        @if($payIn->Status != "NO TRANSACTION" && $payIn->Status != "FAILED")
            {!! Html::ListInfo('Id de transaction mangoPay', $payIn->Id) !!}
        @endif

        @if($payIn->Status != "FAILED")
            {{ Form::open(['route' => 'applicationUser_incident.update', 'class' => 'form-horizontal panel']) }}

            {!! Form::hidden('order_id', $orderInfo->id) !!}

            {!! Form::SelectFromDBSelected('excuse', 'excuse', $errors, $excuses, $incident, 'excuse', 'excuse', 'excuse', 'Modifier le motif de l\'incident') !!}

            {{ Form::submit('Modifier', ['class' => 'btn btn-default pull-right']) }}
            {{ Form::close() }}
        @elseif($incident->excuse == null)
            <strong style="color: red">{!! Html::ListInfo('Message d\'erreur', 'Pas de message d\'erreur enregistré lors de l\'incident. Se reporter au Dashboard Mangopay.') !!}</strong>
        @else
            {!! Html::ListInfo('Message d\'erreur', $incident->excuse) !!}

        @endif

        @if($payIn->Status === 'NO TRANSACTION')
            <strong style="color: blue">Aucune transaction n'a été éffectuée pour cette commande. Pas de remboursement
                possible.</strong>
        @endif
        @if($payIn->Status === 'FAILED')
            <strong style="color: blue">La transaction a échoué pour cette commande. Pas de remboursement
                possible.</strong>
            <p style="color: orange; font-style: italic">Lors de l'échec d'une commande partagée, il est nécéssaire de
                vérifier sur le dashboard manogopay si les deux transactions ont échouées.</p>
        @endif

    @endcomponent

@endsection

@section('button-back')
    {!! Html::RouteWithIcon('applicationUser_incidents.index', 'Liste des incidents', $orderInfo->applicationUser_id, 'btn-default', 'circle-arrow-left') !!}
@endsection



@section('panel-title-2')
    Suivi
@endsection()

@section('panel-body-2')

    @component('templates.template_panel_inside')
        @slot('title')
            Nouveau mémo :
        @endslot

        {{ Form::open(['route' => 'applicationUser_incident_memo.create']) }}
        {{ Form::hidden('order_id', $orderInfo->id) }}
        {{ Form::hidden('applicationUser_incident_id', $incident->id) }}

        {!! Form::Control('textarea', $errors, 'message', 'Noter les actions éffectuées auprès du client.', 'Mémo') !!}
        {!! Form::RadioYesOrNo($errors, 'email', 'Envoi d\'un email : ') !!}
        {!! Form::RadioYesOrNo($errors, 'phone', 'Appel téléphonique : ') !!}
        {!! Form::RadioYesOrNo($errors, 'reimburse', 'Remboursement : ') !!}

        {{ Form::submit('Enregistrer', ['class' => 'btn btn-default pull-right']) }}
        {{ Form::close() }}
    @endcomponent

    @component('templates.template_panel_inside')
        @slot('title')
            Historique du suivi
        @endslot

        @if($memories->isEmpty())
            Pas de mémo.
        @else
            @foreach($memories as $memo)
                @component('templates.template_panel_inside')
                    @slot('title')
                        Mémo : {{ $memo->created_at }}
                    @endslot
                    <div class="card card-inverse" style="background-color: #ededed; padding: 5px">
                        <div class="card-block">
                            <h4 class="card-title">Message : </h4>
                            <p class="card-text">{{ $memo->message }}</p>
                        </div>
                    </div>
                    {!! Html::ListYesOrNo('Envoi d\'un email', $memo->email) !!}
                    {!! Html::ListYesOrNo('Appel téléphonique', $memo->phone) !!}
                    {!! Html::ListYesOrNo('Remboursement', $memo->reimburse) !!}
                @endcomponent
            @endforeach
        @endif

    @endcomponent

@endsection