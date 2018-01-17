@extends('templates.template_panel')

@section('panel-title')
    Informations / Modification d'un partenaire
@endsection
@section('panel-body')

    {!! Html::RouteWithIcon('openings.edit', 'Horaires d\'ouverture', $partner->id, 'btn-default', 'time') !!}
    {!! Html::RouteWithIcon('menus.edit', 'Menu', $partner->id, 'btn-default', 'book') !!}
    {!! Html::RouteWithIcon('bank_account.index', 'Comptes bancaires', $partner->id, 'btn-default', 'euro') !!}
    {!! Html::RouteWithIcon('wallet.show', 'Wallet', $partner->id, 'btn-default', 'piggy-bank') !!}
    {!! Html::RouteWithIcon('kyc.index', 'KYC', $partner->id, 'btn-default', 'folder-open') !!}
    {!! Html::RouteWithIcon('partner.invoices.index', 'Factures', $partner->id, 'btn-default', 'list-alt') !!}
    {!! Html::RouteWithIconBlank('mangoPay.partner.details', 'Fiche partner mangopay (besoin d\'un accès à Mangopay)', $partner->id, 'btn-default', 'user') !!}

    <br/>
    <br/>

    {{ Form::model($partner, ['route' => ['partner.update', $partner->id], 'method' => 'put', 'class' => 'form-horizontal panel', 'files' => true]) }}

    @component('templates.template_panel_inside')
        @slot('title')
            Création / modification :
        @endslot
        {!! Html::ListInfo('Date de création', $partner->created_at) !!}
        {!! Html::ListInfo('Dernière modification', $partner->updated_at) !!}
    @endcomponent

    @component('templates.template_panel_inside')
        @slot('title')
            Visibilité dans l'application :
        @endslot
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <img style="width: 100%" class="img-responsive"
                     src="{{ \Illuminate\Support\Facades\Config::get('constants.base_url_partner') . $partner->picture }}">
            </div>
        </div>

        <br/>

        {!! Form::FileInput('picture', $errors, 'Image pour l\'application') !!}
        {!! Form::Control('text', $errors, 'website', 'Site Internet', 'Site Internet') !!}
    @endcomponent

    @component('templates.template_panel_inside')
        @slot('title')
            Informations bar :
        @endslot
        {!! Form::Control('text', $errors, 'ownerFirstName', 'Nom du propriétaire', 'Nom du propriétaire') !!}
        {!! Form::Control('text', $errors, 'ownerLastName', 'Prénom du propriétaire', 'Prénom du propriétaire') !!}
        {!! Form::Control('date', $errors, 'birthday', 'Date de naissance', 'Date de naissance') !!}
        {!! Form::Control('email', $errors, 'email', 'Email', 'Email') !!}

    @endcomponent

    @component('templates.template_panel_inside')
        @slot('title')
            Informations légales :
        @endslot
        {!! Form::Control('text', $errors, 'name', 'Nom du bar', 'Nom du bar') !!}
        {!! Form::SelectFromDBSelected('category', 'category', $errors, $partnerCategories, $partner, 'category', 'category', 'category', 'Categorie du bar') !!}
        {!! Form::Control('tel', $errors, 'tel', 'Téléphone', 'Téléphone') !!}
        {!! Form::Control('text', $errors, 'address', 'N° et rue', 'N° et rue') !!}
        {!! Form::Control('text', $errors, 'postalCode', 'Code postal', 'Code postal') !!}
        {!! Form::Control('text', $errors, 'city', 'Ville', 'Ville') !!}
        {!! Form::Control('text', $errors, 'lat', 'Latitude', 'Latitude') !!}
        {!! Form::Control('text', $errors, 'lng', 'Longitude', 'Longitude') !!}
    @endcomponent

    @component('templates.template_panel_inside')
        @slot('title')
            Informations statut du bar :
        @endslot

        {!! Form::Select2ChoicesSelected('openStatus', $errors, $partner, 'Ouvertes', 'Fermées', 'Commandes ouvertes / fermées') !!}
        {!! Form::Select2ChoicesSelected('HHStatus', $errors, $partner, 'Ouverte', 'Fermée', 'Happy Hour ouverte / fermée') !!}
        {!! Form::Select2ChoicesSelected('activated', $errors, $partner, 'Actif', 'Inactif', 'Partenaire actif / inactif') !!}

    @endcomponent

    @component('templates.template_panel_inside')
        @slot('title')
            Commission :
        @endslot
        <p style="color: red">Modification de l'identifiant désactivée.</p>
        {!! Form::Control('hidden', $errors, 'mango_id', 'Identifiant Mangopay', 'Identifiant Mangopay') !!}
        <div class="row">
            <div class="col-md-offset-1">
                {!! Html::ListInfo('Identifiant Mangopay', $partner->mango_id) !!}
            </div>
        </div>
        {!! Form::Control('number', $errors, 'fees', 'Commission prélevée sur les transactions', 'Commission prélevée sur les transactions') !!}
    @endcomponent


    {{ Form::submit('Envoyer', ['class' => 'btn btn-default pull-right']) }}

    {{ Form::close() }}

    {{ Form::open(['method' => 'DELETE', 'route' => ['partner.destroy', $partner->id]]) }}
    {{ Form::submit('Supprimer le bar', ['class' => 'btn btn-danger', 'onclick' => 'return confirm(\'Voulez-vous vraiment supprimer cet utilisateur ?\')']) }}
    {{ Form::close() }}
@endsection

@section('button-back')
    {!! Html::RouteWithIcon('partner.index', 'Liste des partenaires', null, 'btn-default', 'circle-arrow-left') !!}
@endsection

@section('script')
    <script type="text/javascript">
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