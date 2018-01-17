@extends('templates.template_panel')

@section('panel-title')
    Création d'une notification
@endsection

@section('panel-body')
    @component('templates.template_panel_inside')
        @slot('title')
            Informations notification :
        @endslot
        {{ Form::open(['route' => 'notification.send', 'class' => 'form-horizontal panel']) }}

        {!! Form::Control('text', $errors, 'title', 'Titre de la notification', 'Titre de la notification') !!}
        {!! Form::Control('text', $errors, 'body', 'Corps de la notification', 'Corps de la notification') !!}

        {{ Form::submit('Envoyer', ['class' => 'btn btn-default pull-right']) }}
        {{ Form::close() }}
    @endcomponent

@endsection

@section('button-back')
    {!! Html::BackButton() !!}
@endsection