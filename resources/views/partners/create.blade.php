@extends('templates.template_panel')

@section('panel-title')
    Création d'un partenaires
@endsection

@section('panel-body')
    <p style="color: red">Le process KYC débute après que la la fiche partenaire ait été créée. Vous pourrez renseigner
        les documents en cliquant sur l'onglet KYC en haut de la fiche partenaire.</p>

    {{ Form::open(['id' => 'geocomplete', 'route' => 'partner.store', 'class' => 'form-horizontal panel',  'files' => true]) }}

    @component('templates.template_panel_inside')
        @slot('title')
            Visibilité dans l'application :
        @endslot

        {!! Form::Control('text', $errors, 'website', 'Site web', 'Site web') !!}
        {!! Form::FileInput('picture', $errors, 'Image pour l\'application') !!}

    @endcomponent
    @component('templates.template_panel_inside')
        @slot('title')
            Informations représentant legal :
        @endslot
        {!! Form::Control('text', $errors, 'ownerFirstName', 'Prénom du représentant legal', 'Prénom du représentant légal') !!}
        {!! Form::Control('text', $errors, 'ownerLastName', 'Nom du représentant légal', 'Nom du représentant légal') !!}
        {!! Form::SelectFromDB('LegalRepresentativeNationality', 'LegalRepresentativeNationality', $errors, $countries, 'isoAlpha2Code', 'countryOrAreaName', 'Nationalité du représentant légal') !!}
        {!! Form::SelectFromDB('LegalRepresentativeCountryOfResidence', 'LegalRepresentativeCountryOfResidence', $errors, $countries, 'isoAlpha2Code', 'countryOrAreaName', 'Pays de résidence du représentant légal') !!}
        {!! Form::Control('email', $errors, 'LegalRepresentativeEmail', 'Email du représentant légal', 'Email du représentant légal') !!}
        {!! Form::Control('date', $errors, 'birthday', 'Date de naissance', 'Date de naissance') !!}
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="form-group {{ $errors->has('street_number_representative', 'route_representative', 'postalCode_representative', 'city_representative', 'administrative_area_level_2_representative', 'country_representative') ? 'has-error' : '' }}">
                    {{ Form::label('gaddress_representative', 'Adresse ') }}
                    {{ Form::text('gaddress_representative', null, ['class' => 'form-control', 'placeholder' => 'N° et rue']) }}
                    {!! $errors->first('street_number_representative', '<small class="help-block">:message</small>') !!}
                    {!! $errors->first('route_representative', '<small class="help-block">:message</small>') !!}
                    {!! $errors->first('postalCode_representative', '<small class="help-block">:message</small>') !!}
                    {!! $errors->first('city_representative', '<small class="help-block">:message</small>') !!}
                    {!! $errors->first('country_representative', '<small class="help-block">:message</small>') !!}
                </div>
            </div>
        </div>
        <div id="geocomplete_representative">
            {{ Form::hidden('street_number_representative', null, ['data-geo_representative' => 'street_number', 'value' => '']) }}
            {{ Form::hidden('route_representative', null, ['data-geo_representative' => 'route', 'value' => '']) }}
            {{ Form::hidden('postalCode_representative', null, ['data-geo_representative' => 'postal_code', 'value' => '']) }}
            {{ Form::hidden('city_representative', null, ['data-geo_representative' => 'locality', 'value' => '']) }}
            {{ Form::hidden('administrative_area_level_2_representative', null, ['data-geo_representative' => 'administrative_area_level_2', 'value' => '']) }}
            {{ Form::hidden('country_representative', null, ['data-geo_representative' => 'country_short', 'value' => '']) }}
        </div>
        @component('templates.template_panel_inside')
            @slot('title')
                Informations sur le siège :
            @endslot
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="form-group {{ $errors->has('street_number_hq', 'route_hq', 'postalCode_hq', 'city_hq', 'administrative_area_level_2_hq', 'country_hq') ? 'has-error' : '' }}">
                        {{ Form::label('gaddress_hq', 'Adresse') }}
                        {{ Form::text('gaddress_hq', null, ['class' => 'form-control', 'placeholder' => 'N° et rue']) }}
                        {!! $errors->first('street_number_hq', '<small class="help-block">:message</small>') !!}
                        {!! $errors->first('route_hq', '<small class="help-block">:message</small>') !!}
                        {!! $errors->first('postalCode_hq', '<small class="help-block">:message</small>') !!}
                        {!! $errors->first('city_hq', '<small class="help-block">:message</small>') !!}
                        {!! $errors->first('country_hq', '<small class="help-block">:message</small>') !!}
                    </div>
                </div>
            </div>
            <div id="geocomplete_hq">
                {{ Form::hidden('street_number_hq', null, ['data-geo_hq' => 'street_number', 'value' => '']) }}
                {{ Form::hidden('route_hq', null, ['data-geo_hq' => 'route', 'value' => '']) }}
                {{ Form::hidden('postalCode_hq', null, ['data-geo_hq' => 'postal_code', 'value' => '']) }}
                {{ Form::hidden('city_hq', null, ['data-geo_hq' => 'locality', 'value' => '']) }}
                {{ Form::hidden('administrative_area_level_2_hq', null, ['data-geo_hq' => 'administrative_area_level_2', 'value' => '']) }}
                {{ Form::hidden('country_hq', null, ['data-geo_hq' => 'country_short', 'value' => '']) }}
            </div>
        @endcomponent

    @endcomponent
    @component('templates.template_panel_inside')
        @slot('title')
            Informations bar :
        @endslot
        {!! Form::Control('text', $errors, 'name', 'Nom du bar', 'Nom du bar') !!}

        {!! Form::SelectFromDB('category', 'category', $errors, $partnerCategories, 'category', 'category', 'Type de partenaire') !!}
        {!! Form::Control('tel', $errors, 'tel', 'Téléphone', 'Téléphone') !!}
        {!! Form::Control('email', $errors, 'email', 'Email', 'Email') !!}

        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="form-group {{ $errors->has('street_number', 'route', 'postalCode', 'city') ? 'has-error' : '' }}">
                    {{ Form::label('gaddress', 'Adresse') }}
                    {{ Form::text('gaddress', null, ['class' => 'form-control', 'placeholder' => 'N° et rue']) }}
                    {!! $errors->first('street_number', '<small class="help-block">:message</small>') !!}
                    {!! $errors->first('route', '<small class="help-block">:message</small>') !!}
                    {!! $errors->first('postalCode', '<small class="help-block">:message</small>') !!}
                    {!! $errors->first('city', '<small class="help-block">:message</small>') !!}
                </div>
            </div>
        </div>
        <div id="geocomplete_bar">
            {{ Form::hidden('lat', null, ['data-geo' => 'lat', 'value' => '']) }}
            {{ Form::hidden('lng', null, ['data-geo' => 'lng', 'value' => '']) }}
            {{ Form::hidden('street_number', null, ['data-geo' => 'street_number', 'value' => '']) }}
            {{ Form::hidden('route', null, ['data-geo' => 'route', 'value' => '']) }}
            {{ Form::hidden('postalCode', null, ['data-geo' => 'postal_code', 'value' => '']) }}
            {{ Form::hidden('city', null, ['data-geo' => 'locality', 'value' => '']) }}
        </div>
        {!! Form::Control('password', $errors, 'password', 'Mot de passe', 'Mot de passe') !!}
        {!! Form::Control('password', $errors, 'password_confirmation', 'Mot de passe', 'Confirmer le mot de passe') !!}
    @endcomponent

    @component('templates.template_panel_inside')
        @slot('title')
            Commission :
        @endslot
        {!! Form::Control('number', $errors, 'fees', 'Commission prélevée sur les transactions', 'Commission prélevée sur les transactions') !!}
    @endcomponent

    {{ Form::submit('Envoyer', ['class' => 'btn btn-default pull-right']) }}
    {{ Form::close() }}

@endsection

@section('button-back')
    {!! Html::RouteWithIcon('partner.index', 'Liste des partenaires', null, 'btn-default', 'circle-arrow-left') !!}
@endsection

@section('script')
    <script src="{{ asset('js/jquery.geocomplete.min.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUQjZbt7JKZR9PZ3uYNdfMZg2f-6WnMk0&signed-in=true&libraries=places"></script>
    <script type="text/javascript">
        //Geocompletion
        $('#gaddress').geocomplete({
            details: '#geocomplete',
            detailsAttribute: "data-geo",
            types: ["geocode"]
        });
        $('#gaddress_hq').geocomplete({
            details: '#geocomplete_hq',
            detailsAttribute: "data-geo_hq",
            types: ["geocode"]
        });
        $('#gaddress_representative').geocomplete({
            details: '#geocomplete_representative',
            detailsAttribute: "data-geo_representative",
            types: ["geocode"]
        });
        //Button
        $(function () {
            // We can attach the `fileselect` event to all file inputs on the page
            $(document).on('change', ':file', function () {
                var input = $(this),
                    numFiles = input.get(0).files ? input.get(0).files.length : 1,
                    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [numFiles, label]);
            });
            // We can watch for our custom `fileselect` event like this
            $(document).ready(function () {
                $(':file').on('fileselect', function (event, numFiles, label) {
                    var input = $(this).parents('.input-group').find(':text'),
                        log = numFiles > 1 ? numFiles + ' fichiers sélectionnés' : label;
                    if (input.length) {
                        input.val(log);
                    } else {
                        if (log) alert(log);
                    }
                });
            });
        });
    </script>
@endsection