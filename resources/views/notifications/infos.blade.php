@extends('templates.template_panel')

@section('panel-title')
    Résultats - <span style="color: red">/!\ ne pas recharger la page -> deuxième envoi de notification</span>
@endsection

@section('panel-body')
    {!! Html::ListInfo('Nombre de notifications envoyées',$response['numberSuccess']) !!}
    {!! Html::ListInfo('Nombre d\'échecs d\'envoi',$response['numberFailure']) !!}
    {!! Html::ListInfo('Nombre de tokens modifiés en base de donnée',$response['numberModification']) !!}
@endsection

@section('button-back')
    {!! Html::BackButton() !!}
@endsection