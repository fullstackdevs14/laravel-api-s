@extends('templates.template_panel')

@section('panel-title')
    Création d'une notification ciblée
@endsection

@section('panel-body')
    @component('templates.template_panel_inside')
        @slot('title')
            A un bar avec pour cible les utilisateur ayant commandé sur la période :
        @endslot
        {{ Form::open(['route' => 'targeted_group.notification.send', 'class' => 'form-horizontal panel']) }}

        {!! Form::SelectFromDB('partner_id', 'partner_id', $errors, $partners, 'id', 'name', 'Choisir le partenaire (cliquer sur le champ et taper son nom)') !!}

        {!! Form::SelectFromArray('period', $errors, [
        '1-week' => 'Une semaine',
         '2-week' => ' Deux semaines',
          '3-week' => ' Trois semaines',
           '1-month' => 'Un mois',
            '2-month' => 'Deux mois',
             '3-month' => 'Trois mois',
              '4-month' => 'Quatre mois'
              ], 'Choisir la période') !!}


        {!! Form::Control('text', $errors, 'title', 'Titre de la notification', 'Titre de la notification') !!}
        {!! Form::Control('text', $errors, 'body', 'Corps de la notification', 'Corps de la notification') !!}

        {{ Form::submit('Envoyer', ['class' => 'btn btn-default pull-right']) }}
        {{ Form::close() }}
    @endcomponent

@endsection

@section('button-back')
    {!! Html::BackButton() !!}
@endsection