@extends('templates.template_panel')

@section('panel-title')
    Modification d'une boisson
@endsection

@section('panel-body')
    {{ Form::model($item, ['route' => ['item.update', $partner->id, $item->id], 'method' => 'put', 'class' => 'form-horizontal panel']) }}
    {!! Form::Control('text', $errors, 'name', 'Nom de la boisson', 'Nom') !!}
    {!! Form::SelectFromDBSelected('category_id', 'category_id', $errors, $categories, $item, 'id', 'category_id', 'category', 'Type de boisson') !!}
    {!! Form::Control('text', $errors, 'quantity', 'Quantité en cl', 'Quantité en cl') !!}
    {!! Form::Control('text', $errors, 'price', 'Prix TTC', 'Prix TTC') !!}
    {!! Form::Control('text', $errors, 'HHPrice', 'Prix TTC en HH', 'Prix TTC en HH') !!}
    {!! Form::SelectFromDBSelected('tax', 'tax', $errors, $taxes, $item, 'per_cent', 'tax', 'per_cent', 'Choisir une TVA') !!}
    {!! Form::Select2ChoicesSelected('alcohol', $errors, $item, 'Avec alcool', 'Sans alcool', 'Avec ou sans alcool') !!}
    {!! Form::Control('textarea', $errors, 'ingredients', 'Ingrédients', 'Ingrédients') !!}
    {!! Form::Select2ChoicesSelected('availability', $errors, $item, 'Disponible', 'Indisponible', 'Boisson en stock') !!}
    {{ Form::submit('Envoyer', ['class' => 'btn btn-default pull-right']) }}
    {{ Form::close() }}

@endsection

@section('button-back')
    {!! Html::RouteWithIcon('menus.edit', 'Menu',  $partner->id, 'btn-default', 'circle-arrow-left') !!}
@endsection