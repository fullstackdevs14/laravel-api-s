@extends('templates.template_panel')

@section('panel-title')
    Création d'un compte en banque pour les versements (seulement les comptes français)
@endsection

@section('panel-body')
    {{ Form::open(['id' => 'geocomplete', 'route' => ['bank_account.store', $partner->id], 'class' => 'form-horizontal panel',  'files' => true]) }}

    @component('templates.template_panel_inside')
        @slot('title')
            Information sur le détenteur du compte :
        @endslot

        {!! Form::Control('text', $errors,'ownerName', 'Nom du propriéataire du compte', 'Nom du propriétaire du compte') !!}

    @endcomponent

    @component('templates.template_panel_inside')
        @slot('title')
            Informations sur le compte :
        @endslot

        {!! Form::Control('text', $errors, 'iban', 'IBAN', 'IBAN') !!}
        {!! Form::Control('text', $errors, 'bic','BIC', 'IBAN') !!}

    @endcomponent

    @component('templates.template_panel_inside')
        @slot('title')
            Adresse du détenteur du compte :
        @endslot
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="form-group {{ $errors->has('street_number', 'route', 'postalCode', 'city') ? 'has-error' : '' }}">
                    {{ Form::label('gaddress', 'Adresse (uniquement France)') }}
                    {{ Form::text('gaddress', null, ['class' => 'form-control', 'placeholder' => 'N° et rue']) }}
                    {!! $errors->first('street_number', '<small class="help-block">:message</small>') !!}
                    {!! $errors->first('route', '<small class="help-block">:message</small>') !!}
                    {!! $errors->first('postalCode', '<small class="help-block">:message</small>') !!}
                    {!! $errors->first('city', '<small class="help-block">:message</small>') !!}
                </div>
            </div>
        </div>
        <div id="geocomplete">
            {{ Form::hidden('lat', null, ['data-geo' => 'lat', 'value' => '']) }}
            {{ Form::hidden('lng', null, ['data-geo' => 'lng', 'value' => '']) }}
            {{ Form::hidden('street_number', null, ['data-geo' => 'street_number', 'value' => '']) }}
            {{ Form::hidden('route', null, ['data-geo' => 'route', 'value' => '']) }}
            {{ Form::hidden('postalCode', null, ['data-geo' => 'postal_code', 'value' => '']) }}
            {{ Form::hidden('city', null, ['data-geo' => 'locality', 'value' => '']) }}
        </div>
    @endcomponent

    {{ Form::submit('Envoyer', ['class' => 'btn btn-default pull-right']) }}
    {{ Form::close() }}

@endsection

@section('button-back')
    {!! Html::BackButton() !!}
@endsection

@section('script')
    <script src="{{ asset('js/jquery.geocomplete.min.js') }}"></script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUQjZbt7JKZR9PZ3uYNdfMZg2f-6WnMk0&signed-in=true&libraries=places"></script>

    <script type="text/javascript">
        //Geocompletion
        $('#gaddress').geocomplete({
            details: '#geocomplete',
            detailsAttribute: "data-geo",
            types: ["geocode"],
            country: 'fr',
        });
    </script>
@endsection