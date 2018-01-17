@extends('templates.template_panel')

@section('panel-title')
    Création d'un incident
@endsection

@section('panel-body')
    {{ Form::open(['route' => 'applicationUser_incident.store', 'class' => 'form-horizontal panel']) }}
    {!! Form::hidden('order_id', $order_id) !!}
    {!! Form::SelectFromDB('excuse', 'excuse', $errors, $excuses, 'excuse', 'excuse', 'Choisir le motif de l\'incident') !!}

    {{ Form::submit('Envoyer', ['class' => 'btn btn-default pull-right']) }}
    {{ Form::close() }}
@endsection