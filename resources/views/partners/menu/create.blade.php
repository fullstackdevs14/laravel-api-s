@extends('templates.template_panel')

@section('panel-title')
    Création d'une boisson
@endsection

@section('panel-body')
    {{ Form::open(['route' => ['item.store', $partner->id], 'class' => 'form-horizontal panel']) }}
    {!! Form::Control('text', $errors, 'name', 'Nom de la boisson', 'Nom') !!}
    {!! Form::SelectFromDB('category', 'category_id', $errors, $categories, 'id', 'category', 'Type de boisson') !!}
    {!! Form::Control('text', $errors, 'quantity', 'Quantité en cl', 'Quantité en cl') !!}
    {!! Form::Control('text', $errors, 'price', 'Prix TTC', 'Prix TTC') !!}
    {!! Form::Control('text', $errors, 'HHPrice', 'Prix TTC en HH', 'Prix TTC en HH') !!}
    {!! Form::SelectFromDB('tax', 'tax', $errors, $taxes, 'per_cent', 'per_cent', 'Choisir une TVA') !!}
    {!! Form::Select2Choices('alcohol', $errors, 'Avec alcool', 'Sans alcool', 'Avec ou sans alcool') !!}
    {!! Form::Control('textarea', $errors, 'ingredients', 'Ingrédients', 'Ingrédients') !!}
    {!! Form::Select2Choices('availability', $errors, 'Disponible', 'Indisponible', 'Boisson en stock') !!}
    {{ Form::submit('Envoyer', ['class' => 'btn btn-default pull-right']) }}
    {{ Form::close() }}
@endsection

@section('button-back')
    {!! Html::BackButton() !!}
@endsection
