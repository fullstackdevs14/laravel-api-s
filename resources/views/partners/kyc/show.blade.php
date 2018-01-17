@extends('templates.template_panel')

@section('panel-title')
    Informations document KYC
@endsection

@section('panel-body')

    {!! Html::ListInfo('Identifiant du document' , $kycDoc->Id) !!}
    {!! Html::ListInfo('Date de creation' , \Carbon\Carbon::createFromTimestamp($kycDoc->CreationDate)) !!}
    {!! Html::ListInfo('Type de document' , $kycDoc->Type) !!}
    {!! Html::ListInfo('Statut du document' , $kycDoc->Status) !!}
    @if($kycDoc->RefusedReasonType)
        {!! Html::ListInfo('Type de raison du refus' , $kycDoc->RefusedReasonType) !!}
        {!! Html::ListInfo('Message de raison du refus' , $kycDoc->RefusedReasonMessage) !!}
    @endif

@endsection

@section('button-back')
    {!! Html::BackButton() !!}
@endsection