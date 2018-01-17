@extends('templates.template_panel')

@section('panel-title')
    Fiche de carte
@endsection

@section('panel-body')
    {!! Html::ListInfo('Date de création', $card->CreationDate) !!}
    {!! Html::ListInfo('Id de la carte', $card->Id) !!}
    {!! Html::ListInfo('Alias', $card->Alias) !!}
    {!! Html::ListInfo('Type de carte', $card->CardType) !!}
    {!! Html::ListInfo('Fournisseur', $card->CardProvider) !!}
    {!! Html::ListYesOrNo('Désactivation permanente', !$card->Active) !!}
    {!! Html::ListInfo('Valide', $card->Validity) !!}
    {!! Html::ListInfo('Pays', $card->Country) !!}
    {!! Html::ListInfo('Monnaie', $card->Currency) !!}

    {{ Form::open(['method' => 'DELETE', 'route' => ['cards.destroy', $card->Id, $applicationUser_id]]) }}
    {{ Form::submit('Désactiver la carte', ['class' => 'btn btn-danger', 'onclick' => 'return confirm(\'Voulez-vous vraiment désactiver de manière permanente cette carte?\')']) }}
    {{ Form::close() }}
@endsection

@section('button-back')
    {!!  Html::BackButton() !!}
@endsection
