@extends('templates.template_panel')
@section('panel-title')
    Wallet du partenaire
@endsection

@section('panel-body')

    @if($partner->mango_bank_id !== null AND $wallet->Balance->Amount > 0)
    {!! Html::RouteWithIcon('wallet.payOut', 'déclencher remboursement', $partner->id, 'btn-danger', 'share') !!}
    <br>
    <br>
    @endif

    {!! Html::ListInfo('Id du wallet', $wallet->Id) !!}
    {!! Html::ListInfo('Date de création', \Carbon\Carbon::createFromTimestamp($wallet->CreationDate)) !!}
    {!! Html::ListInfo('Montant actuellement sur le wallet', $wallet->Balance->Amount/100) !!}

@endsection

@section('button-back')
    {!! Html::RouteWithIcon('partner.edit', 'Partenaire', $partner->id, 'btn-default', 'circle-arrow-left') !!}
@endsection
