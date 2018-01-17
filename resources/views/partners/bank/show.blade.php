@extends('templates.template_panel')

@section('panel-title')
    Informations sur le compte
@endsection

@section('panel-body')

    @if($bankAccount->Id != $partner->mango_bank_id AND $bankAccount->Active == true)
        {!! Html::RouteWithIcon('bank_account.setUsed', 'Utiliser ce compte pour mes remboursements', [$partner->id, $bankAccount->Id], 'btn-primary', 'ok') !!}
    @endif
    @if($bankAccount->Active == 1)
        {!! Html::RouteWithIcon('bank_account.destroy', 'Désactiver', [$partner->id, $bankAccount->Id], 'btn-danger', 'remove') !!}
    @endif

    <br>
    <br>

    {!! Html::ListInfo('Id mangopay du compte', $bankAccount->Id) !!}
    {!! Html::ListInfo('Date d\'enregistrement du compte', $bankAccount->CreationDate) !!}
    {!! Html::ListInfo('Nom du détenteur du compte', $bankAccount->Id) !!}
    {!! Html::ListInfo('Adresse du détenteur du compte', $bankAccount->OwnerAddress->AddressLine1 . ' ' . $bankAccount->OwnerAddress->City . ' ' . $bankAccount->OwnerAddress->PostalCode . ' ' . $bankAccount->OwnerAddress->Country) !!}
    {!! Html::ListInfo('IBAN', $bankAccount->Details->IBAN) !!}
    {!! Html::ListInfo('BIC', $bankAccount->Details->BIC) !!}
    {!! Html::ListYesOrNo('Actif', $bankAccount->Active) !!}

    <p><strong style="color: blue">Pour effectuer des modification sur le compte de ce partenaire, utiliser l'interface Mangopay.</strong></p>

@endsection

@section('button-back')
    {!! Html::BackButton() !!}
@endsection
