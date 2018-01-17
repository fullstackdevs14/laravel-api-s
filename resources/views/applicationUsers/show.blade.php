@extends('templates.template_panel')

@section('panel-title')
    Fiche d'utilisateur
@endsection

@section('panel-body')
    <div class="col-sm-6">
        <p>
        @if($applicationUser->picture != null)
            <div class="avatar-circle">
                <img style="width: 100px; height: 100px; -webkit-border-radius: 50%; -moz-border-radius: 50%; border-radius: 50%;"
                     src="{{ \Illuminate\Support\Facades\Config::get('constants.base_url_application_user'). $applicationUser->picture }}"/>
            </div>
        @else
            <span class="glyphicon glyphicon-user"><i>   -   Pas de photo de profil</i></span>
        @endif
        <br/>

        {!! Html::ListInfo('Date d\'inscription', $applicationUser->created_at) !!}
        {!! Html::ListInfo('Dernière modification', $applicationUser->updated_at) !!}
        {!! Html::ListInfo('Prénom', $applicationUser->firstName) !!}
        {!! Html::ListInfo('Nom', $applicationUser->lastName) !!}
        {!! Html::ListInfo('Email', $applicationUser->email) !!}
        {!! Html::ListInfo('Téléphone', $applicationUser->tel) !!}
        {!! Html::ListInfo('Date de naissance', $applicationUser->birthday) !!}
        {!! Html::ListYesOrNo('Actif', $applicationUser->activated) !!}
        {!! Html::ListInfo('Mango id', $applicationUser->mango_id) !!}
    </div>
    <div class="col-sm-6">

        {!! Html::RouteWithIcon('applicationUser.orders_list', 'Historique des commandes', $applicationUser->id, 'btn-default', 'barcode') !!}
        <br/>
        <br/>
        {!! Html::RouteWithIcon('cards.index', 'Liste de moyens de paiement', $applicationUser->id, 'btn-default', 'credit-card') !!}
        <br/>
        <br/>
        {!! Html::RouteWithIcon('applicationUser_incidents.index', 'Liste des incidents', $applicationUser->id, 'btn-default', 'exclamation-sign') !!}
        <br/>
        <br/>
        {!! Html::RouteWithIconBlank('mangoPay.application_user.details', 'Fiche utilisateur mangopay (besoin d\'un accès à Mangopay)', $applicationUser->id, 'btn-default', 'user') !!}

    </div>
@endsection

@section('button-back')
    {!! Html::BackButton() !!}
@endsection