@extends('templates.template_panel_2bodies')

@section('panel-title-1')
    Fiche de remboursement
@endsection

@section('panel-body-1')

    {!!  Html::ListInfo('Date de la commande', $orderInfo->created_at) !!}
    {!!  Html::ListInfo('Numéro de commande', $orderInfo->orderId) !!}
    {!!  Html::ListYesOrNo('Happy hour', $orderInfo->HHStatus) !!}

    @if($orderInfo->applicationUser_id_share_bill != null)
        {!!  Html::ListYesOrNo('Note partagée', true) !!}
    @else
        {!!  Html::ListYesOrNo('Note partagée', false) !!}
    @endif

    <table class="table">
        <thead>
        <tr>
            <th>Order id</th>
            <th>Nom</th>
            <th>Quantité</th>
            <th>Prix</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($items as $item)
            <tr>
                <td style="vertical-align: middle">{{ $item->id }}</td>
                <td style="vertical-align: middle">{{ $item->itemName }}</td>
                <td style="vertical-align: middle">{{ $item->quantity }}</td>
                @if($orderInfo->HHStatus == 1)
                    <td style="vertical-align: middle">{{ $item->itemHHPrice }} €</td>
                @else
                    <td style="vertical-align: middle">{{ $item->itemPrice }} €</td>
                @endif
                @if($orderInfo->HHStatus == 1)
                    <td style="vertical-align: middle">{{ $item->quantity * $item->itemHHPrice }} €</td>
                @else
                    <td style="vertical-align: middle">{{ $item->quantity * $item->itemPrice }} €</td>
                @endif
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td><strong>Déjà remboursé : <strong style="color: red">{{ $sumRefund }} €</strong></strong></td>
            <td colspan="3">Disponible : {{$sum}} € - {{ $sumRefund  }} € = {{ $sum-$sumRefund }} €</td>
            <td><strong>Total : {{ $sum }} €</strong></td>
        </tr>
        </tfoot>
    </table>

    @component('templates.template_panel_inside')
        @slot('title')
            Effectuer un remboursement
        @endslot

        @if($orderInfo->applicationUser_id_share_bill != null)
            {{ Form::open(['route' => 'applicationUser_incident_refund.refund_share_bill','method' => 'POST', $orderInfo->id]) }}
        @else
            {{ Form::open(['route' => 'applicationUser_incident_refund.refund','method' => 'POST', $orderInfo->id]) }}
        @endif

        {{ Form::hidden('order_id', $orderInfo->id) }}

        @if($applicationUser_2 != null)

            {!! Form::SelectFromArray('applicationUser_id', $errors, [$applicationUser_1->id => $applicationUser_1->firstName . ' ' . $applicationUser_1->lastName, $applicationUser_2->id => $applicationUser_2->firstName . ' ' . $applicationUser_2->lastName], 'Utilisateur à rembourser') !!}

        @endif()

        {!! Form::Control('text', $errors, 'amount', 'Montant du remboursement', 'Montant du remboursement') !!}
        {!! Form::Control('textarea', $errors, 'description', 'Indiquer (obligatoirement) le motif du remboursement et les items remboursés', 'Commentaires ...') !!}
        {{ Form::submit('Rembourser', ['class' => 'btn btn-warning pull-right']) }}
        {{ Form::close() }}
    @endcomponent

@endsection

@section('button-back')
    {!!  Html::RouteWithIcon('applicationUser_incident.show', 'Fiche d\'incident', $incident->id, 'btn-default', 'circle-arrow-left') !!}
@endsection

@section('panel-title-2')
    Historique des remboursements
@endsection

@section('panel-body-2')

    @foreach($refunds as $refund)

        @component('templates.template_panel_inside')
            @slot('title')
                Remboursement : {{ $refund->created_at }}
            @endslot

            <div class="card card-inverse" style="background-color: #ededed; padding: 5px">
                <div class="card-block">
                    <h4 class="card-title">Informations : </h4>
                    <p class="card-text">{!! $refund->description !!}</p>
                </div>
            </div>
            {!! Html::ListYesOrNo('Succès', $refund->success) !!}
            {!! Html::ListInfo('Montant', $refund->amount) !!}
            {!! Html::ListInfo('Id de la transaction', $refund->mango_refund_id) !!}

        @endcomponent

    @endforeach

@endsection
