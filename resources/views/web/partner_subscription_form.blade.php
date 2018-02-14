<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>Demande d'inscription partenaire</title>

    {{ Html::favicon( 'img/favicon.ico' ) }}

    <link rel=" stylesheet
    " href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

    <style>
        body {
            color: #088A9B
        }

        img {
            width: 20%;
        }

        #submit {
            background-color: #088A9B;
            color: white
        }

        #submit:hover {
            background-color: lightseagreen;
        }
    </style>

    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    <style>

    </style>

</head>
<body>

<br/>

<div class="container text-center">
    <a href="{{ \Illuminate\Support\Facades\Config::get('constants.company_website') }}"><img
                src="{{\Illuminate\Support\Facades\Config::get('constants.base_url')}}img/Sipper-logo.svg"
                alt="company logo"></a>
</div>

<div class="container">

    <br/>

    <h1>Formulaire d'inscription pour les partenaires</h1>

    <br/>
    <br/>

    {{ Form::open(['id' => 'geocomplete', 'route' => 'partner_subscription.store', 'class' => 'form-horizontal panel', 'files' => true]) }}

    <h2>Type d'inscription : </h2>
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="form-group {!! $errors->has('permanent') ? 'has-error' : ''  !!}">
                <label for="permanent">Inscription permanente <span style="color:red;">*</span></label>
                @if(\Request::old('permanent') == '1')
                    <span style="padding-left: 60px; padding-right: 40px">Oui</span>
                    <input class="form-check-input" name="permanent" type="radio" value="1" checked>
                @else
                    <span style="padding-left: 60px; padding-right: 40px">Oui</span>
                    <input class="form-check-input" name="permanent" type="radio" value="1">
                @endif
                @if(\Request::old('permanent') == '0')
                    <span style="padding-left: 20px; padding-right: 40px">Non</span>
                    <input class="form-check-input" name="permanent" type="radio" value="0" checked>
                @else
                    <span style="padding-left: 20px; padding-right: 40px">Non</span>
                    <input class="form-check-input" name="permanent" type="radio" value="0">
                @endif
                <br/>
                {!! $errors->first('permanent', '<small class="help-block" style="color:red">:message</small>') !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="form-group {!! $errors->has('start_date') ? 'has-error' : ''  !!}">
                <label for="start_date">Date souhaité de début de contrat <span style="color:red;">*</span></label>
                {{ Form::date('start_date', null) }}
                <br/>
                {!! $errors->first('start_date', '<small class="help-block" style="color:red">:message</small>') !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="form-group {!! $errors->has('start_date') ? 'has-error' : ''  !!}">
                <label for="end_date">Date souhaité de fin de contrat</label>
                {{ Form::date('end_date', null) }}
                {!! $errors->first('end_date', '<small class="help-block" style="color:red">:message</small>') !!}
            </div>
        </div>
    </div>

    <h2>Informations représentant légal : </h2>
    {!! Form::Control('text', $errors, 'ownerFirstName', 'Prénom du représentant legal', "Prénom du représentant légal <span style='color:red;'>*</span>") !!}
    {!! Form::Control('text', $errors, 'ownerLastName', 'Nom du représentant légal', "Nom du représentant légal <span style='color:red;'>*</span>") !!}
    {!! Form::SelectFromDB('LegalRepresentativeNationality', 'LegalRepresentativeNationality', $errors, $countries, 'isoAlpha2Code', 'countryOrAreaName', "Nationalité du représentant légal <span style='color:red;'>*</span>") !!}
    {!! Form::SelectFromDB('LegalRepresentativeCountryOfResidence', 'LegalRepresentativeCountryOfResidence', $errors, $countries, 'isoAlpha2Code', 'countryOrAreaName', "Pays de résidence du représentant légal <span style='color:red;'>*</span>") !!}
    {!! Form::Control('email', $errors, 'LegalRepresentativeEmail', 'Email du représentant légal', "Email du représentant légal <span style='color:red;'>*</span>") !!}
    {!! Form::Control('date', $errors, 'birthday', 'Date de naissance', "Date de naissance <span style='color:red;'>*</span>") !!}


    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="form-group {{ $errors->has('street_number_representative', 'route_representative', 'postalCode_representative', 'city_representative', 'administrative_area_level_2_representative', 'country_representative') ? 'has-error' : '' }}">
                <label for="gaddress_representative">Adresse <span style="color:red;">*</span></label>
                {{ Form::text('gaddress_representative', null, ['id' => 'gaddress_representative', 'class' => 'form-control', 'placeholder' => 'N° et rue']) }}
                {!! $errors->first('street_number_representative', '<small class="help-block" style="color:red">:message</small><br/>') !!}
                {!! $errors->first('route_representative', '<small class="help-block" style="color:red">:message</small><br/>') !!}
                {!! $errors->first('postalCode_representative', '<small class="help-block" style="color:red">:message</small><br/>') !!}
                {!! $errors->first('city_representative', '<small class="help-block" style="color:red">:message</small><br/>') !!}
                {!! $errors->first('country_representative', '<small class="help-block" style="color:red">:message</small><br/>') !!}
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

    <h2>Informations siège : </h2>
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="form-group {{ $errors->has('street_number_hq', 'route_hq', 'postalCode_hq', 'city_hq', 'administrative_area_level_2_hq', 'country_hq') ? 'has-error' : '' }}">
                <label for="gaddress_hq">Adresse <span style="color:red;">*</span></label>
                {{ Form::text('gaddress_hq', null, ['id' => 'gaddress_hq', 'class' => 'form-control', 'placeholder' => 'N° et rue']) }}
                {!! $errors->first('street_number_hq', '<small class="help-block" style="color:red">:message</small><br/>') !!}
                {!! $errors->first('route_hq', '<small class="help-block" style="color:red">:message</small><br/>') !!}
                {!! $errors->first('postalCode_hq', '<small class="help-block" style="color:red">:message</small><br/>') !!}
                {!! $errors->first('city_hq', '<small class="help-block" style="color:red">:message</small><br/>') !!}
                {!! $errors->first('country_hq', '<small class="help-block" style="color:red">:message</small><br/>') !!}
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

    <h2>Information sur le bar ou l'évènement :</h2>

    {!! Form::Control('text', $errors, 'name', 'Nom du bar', "Nom du bar <span style='color:red;'>*</span>") !!}

    {!! Form::SelectFromDB('category', 'category', $errors, $partnerCategories, 'category', 'category', "Type de partenaire <span style='color:red;'>*</span>") !!}
    {!! Form::Control('tel', $errors, 'tel', 'Téléphone', "Téléphone <span style='color:red;'>*</span>") !!}
    {!! Form::Control('email', $errors, 'email', 'Email', "Email <span style='color:red;'>*</span>") !!}

    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="form-group {{ $errors->has('street_number', 'route', 'postalCode', 'city') ? 'has-error' : '' }}">
                <label for="gaddress">Adresse <span style="color:red;">*</span></label>
                {{ Form::text('gaddress', null, ['id' => 'gaddress', 'class' => 'form-control', 'placeholder' => 'N° et rue']) }}
                {!! $errors->first('lat', '<small class="help-block" style="color:red">:message</small><br/>') !!}
                {!! $errors->first('lng', '<small class="help-block" style="color:red">:message</small><br/>') !!}
                {!! $errors->first('street_number', '<small class="help-block" style="color:red">:message</small><br/>') !!}
                {!! $errors->first('route', '<small class="help-block" style="color:red">:message</small><br/>') !!}
                {!! $errors->first('postalCode', '<small class="help-block" style="color:red">:message</small><br/>') !!}
                {!! $errors->first('city', '<small class="help-block" style="color:red">:message</small><br/>') !!}
                {!! $errors->first('country', '<small class="help-block" style="color:red">:message</small><br/>') !!}
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

    <h2>Eléments de communication :</h2>
    {!! Form::Control('text', $errors, 'website', 'Site web', "Site web <span style='color:red;'>*</span>") !!}
    {!! Form::FileInput('picture', $errors, "Image pour l'application <span style='color:red;'>*</span>") !!}

    <h2>Documents nécéssaires pour le process kyc :</h2>

    <p>Les documents démandés sont obligatoires pour ouvrir un compte chez Sipper. Ils nous permettent de lutter contre
        la fraude et le blanchiment d'argent.</p>

    {!! Form::FileInput('identity_proof', $errors, 'Pièce d\'identité') !!}
    {!! Form::FileInput('articles_of_association', $errors, 'Status ') !!}
    {!! Form::FileInput('registration_proof', $errors, 'Extrait de K-bis de moins de 3 mois') !!}
    {!! Form::FileInput('address_proof', $errors, 'Preuve d\'adresse ') !!}
    {!! Form::FileInput('shareholder_declaration', $errors, 'Déclaration d\'actionnaire') !!}

    <p>Une déclaration d'actionnaire peut être téléchargée à l'adresse suivante :</p>
    <a href="https://www.mangopay.com/terms/shareholder-declaration/Shareholder_Declaration-EN.pdf" target="_blank">
        https://www.mangopay.com/terms/shareholder-declaration/Shareholder_Declaration-EN.pdf
    </a>

    <br/>
    <br/>

    {!! NoCaptcha::display() !!}
    @if ($errors->has('g-recaptcha-response'))
        <span class="help-block">
        <strong style="color: red">{{ $errors->first('g-recaptcha-response') }}</strong>
    </span>
    @endif

    <br/>
    <br/>

    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <button id="submit" class="btn btn-lg btn-block">Soumettre</button>
        </div>
    </div>

    <br/>
    <br/>

    {{ Form::close() }}

</div>

{!! NoCaptcha::renderJs() !!}

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBUQjZbt7JKZR9PZ3uYNdfMZg2f-6WnMk0&signed-in=true&libraries=places"></script>

<script src="{{ asset('js/jquery.geocomplete.min.js') }}"></script>
<script type="text/javascript">
    //Geocompletion
    $('#gaddress').geocomplete({
        details: '#geocomplete',
        detailsAttribute: "data-geo",
        types: ["geocode"],
        componentRestrictions: {country: "fr"}
    });
    $('#gaddress_hq').geocomplete({
        details: '#geocomplete_hq',
        detailsAttribute: "data-geo_hq",
        types: ["geocode"],
        componentRestrictions: {country: "fr"}
    });
    $('#gaddress_representative').geocomplete({
        details: '#geocomplete_representative',
        detailsAttribute: "data-geo_representative",
        types: ["geocode"],
        componentRestrictions: {country: "fr"}
    });
</script>


<script type="text/javascript">
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

    var elements = document.getElementsByClassName('input-group-btn');
    for (var i = 0; i < elements.length; i++) {
        elements[i].style.marginBottom = '0px';
        elements[i].style.borderBottomLeftRadius = '5px';
        elements[i].style.borderTopLeftRadius = '5px';
        elements[i].style.backgroundColor = '#088A9B';
        elements[i].style.color = 'white';
    }

</script>

</body>
</html>