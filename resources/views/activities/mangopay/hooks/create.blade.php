@extends('templates.template_panel')

@section('panel-title')
    Création d'un hook
@endsection

@section('panel-body')
    {{ Form::open(['route' => 'mangoPay.hooks.store', 'class' => 'form-horizontal panel']) }}

    @component('templates.template_panel_inside')
        @slot('title')
            Création d'un hook
        @endslot

        {!! Form::SelectFromDB('eventType', 'eventType', $errors, $hooks, 'hook', 'hook', 'Choisir le type de hook à mettre en place') !!}

    @endcomponent


    {{ Form::submit('Créer', ['class' => 'btn btn-default pull-right']) }}
    {{ Form::close() }}

@endsection

@section('button-back')
    {!! Html::BackButton() !!}
@endsection