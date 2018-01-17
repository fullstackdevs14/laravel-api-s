@extends('templates.template_panel')

@section('panel-title')
    Modification des horaire d'ouverture : {{ $partner->name }}
@endsection

@section('panel-body')

    {{ Form::model($partnerOpenings, ['route' => ['openings.update', $partner->id], 'method' => 'put', 'class' => 'form-horizontal panel']) }}

    <br />

    @component('templates.template_panel_inside')
        @slot('title')
            Lundi
        @endslot
        {!! Form::SelectFromDBSelected('monday1', 'monday1', $errors, $openings, $partnerOpenings, 'openings', 'monday1', 'openings', 'Ouverture am*') !!}
        {!! Form::SelectFromDBSelected('monday2', 'monday2', $errors, $openings, $partnerOpenings, 'openings', 'monday2', 'openings', 'Fermeture am') !!}
        {!! Form::SelectFromDBSelected('monday3', 'monday3', $errors, $openings, $partnerOpenings, 'openings', 'monday3', 'openings', 'Ouverture pm') !!}
        {!! Form::SelectFromDBSelected('monday4', 'monday4', $errors, $openings, $partnerOpenings, 'openings', 'monday4', 'openings', 'Fermeture pm*') !!}
    @endcomponent
    @component('templates.template_panel_inside')
        @slot('title')
            Mardi
        @endslot
        {!! Form::SelectFromDBSelected('tuesday1', 'tuesday1', $errors, $openings, $partnerOpenings, 'openings', 'tuesday1', 'openings', 'Ouverture am*') !!}
        {!! Form::SelectFromDBSelected('tuesday2', 'tuesday2', $errors, $openings, $partnerOpenings, 'openings', 'tuesday2', 'openings', 'Fermeture am') !!}
        {!! Form::SelectFromDBSelected('tuesday3', 'tuesday3', $errors, $openings, $partnerOpenings, 'openings', 'tuesday3', 'openings', 'Ouverture pm') !!}
        {!! Form::SelectFromDBSelected('tuesday4', 'tuesday4', $errors, $openings, $partnerOpenings, 'openings', 'tuesday4', 'openings', 'Fermeture pm*') !!}
    @endcomponent
    @component('templates.template_panel_inside')
        @slot('title')
            Mercredi
        @endslot
        {!! Form::SelectFromDBSelected('wednesday1', 'wednesday1', $errors, $openings, $partnerOpenings, 'openings', 'wednesday1', 'openings', 'Ouverture am*') !!}
        {!! Form::SelectFromDBSelected('wednesday2', 'wednesday2', $errors, $openings, $partnerOpenings, 'openings', 'wednesday2', 'openings', 'Fermeture am') !!}
        {!! Form::SelectFromDBSelected('wednesday3', 'wednesday3', $errors, $openings, $partnerOpenings, 'openings', 'wednesday3', 'openings', 'Ouverture pm') !!}
        {!! Form::SelectFromDBSelected('wednesday4', 'wednesday4', $errors, $openings, $partnerOpenings, 'openings', 'wednesday4', 'openings', 'Fermeture pm*') !!}
    @endcomponent
    @component('templates.template_panel_inside')
        @slot('title')
            Jeudi
        @endslot
        {!! Form::SelectFromDBSelected('thursday1', 'thursday1', $errors, $openings, $partnerOpenings, 'openings', 'thursday1', 'openings', 'Ouverture am*') !!}
        {!! Form::SelectFromDBSelected('thursday2', 'thursday2', $errors, $openings, $partnerOpenings, 'openings', 'thursday2', 'openings', 'Fermeture am') !!}
        {!! Form::SelectFromDBSelected('thursday3', 'thursday3', $errors, $openings, $partnerOpenings, 'openings', 'thursday3', 'openings', 'Ouverture pm') !!}
        {!! Form::SelectFromDBSelected('thursday4', 'thursday4', $errors, $openings, $partnerOpenings, 'openings', 'thursday4', 'openings', 'Fermeture pm*') !!}
    @endcomponent
    @component('templates.template_panel_inside')
        @slot('title')
            Vendredi
        @endslot
        {!! Form::SelectFromDBSelected('friday1', 'friday1', $errors, $openings, $partnerOpenings, 'openings', 'friday1', 'openings', 'Ouverture am*') !!}
        {!! Form::SelectFromDBSelected('friday2', 'friday2', $errors, $openings, $partnerOpenings, 'openings', 'friday2', 'openings', 'Fermeture am') !!}
        {!! Form::SelectFromDBSelected('friday3', 'friday3', $errors, $openings, $partnerOpenings, 'openings', 'friday3', 'openings', 'Ouverture pm') !!}
        {!! Form::SelectFromDBSelected('friday4', 'friday4', $errors, $openings, $partnerOpenings, 'openings', 'friday4', 'openings', 'Fermeture pm*') !!}
    @endcomponent
    @component('templates.template_panel_inside')
        @slot('title')
            Samedi
        @endslot
        {!! Form::SelectFromDBSelected('saturday1', 'saturday1', $errors, $openings, $partnerOpenings, 'openings', 'saturday1', 'openings', 'Ouverture am*') !!}
        {!! Form::SelectFromDBSelected('saturday2', 'saturday2', $errors, $openings, $partnerOpenings, 'openings', 'saturday2', 'openings', 'Fermeture am') !!}
        {!! Form::SelectFromDBSelected('saturday3', 'saturday3', $errors, $openings, $partnerOpenings, 'openings', 'saturday3', 'openings', 'Ouverture pm') !!}
        {!! Form::SelectFromDBSelected('saturday4', 'saturday4', $errors, $openings, $partnerOpenings, 'openings', 'saturday4', 'openings', 'Fermeture pm*') !!}
    @endcomponent
    @component('templates.template_panel_inside')
        @slot('title')
            Dimanche
        @endslot
        {!! Form::SelectFromDBSelected('sunday1', 'sunday1', $errors, $openings, $partnerOpenings, 'openings', 'sunday1', 'openings', 'Ouverture am*') !!}
        {!! Form::SelectFromDBSelected('sunday2', 'sunday2', $errors, $openings, $partnerOpenings, 'openings', 'sunday2', 'openings', 'Fermeture am') !!}
        {!! Form::SelectFromDBSelected('sunday3', 'sunday3', $errors, $openings, $partnerOpenings, 'openings', 'sunday3', 'openings', 'Ouverture pm') !!}
        {!! Form::SelectFromDBSelected('sunday4', 'sunday4', $errors, $openings, $partnerOpenings, 'openings', 'sunday4', 'openings', 'Fermeture pm*') !!}
    @endcomponent


    {{ Form::submit('Envoyer', ['class' => 'btn btn-default pull-right']) }}
    {{ Form::close() }}

@endsection

@section('button-back')
    {!! Html::RouteWithIcon('partner.edit', 'Partenaire', $partner->id, 'btn-default', 'circle-arrow-left') !!}
@endsection