@extends('templates.template_panel')

@section('panel-title')
    Création d'un utilisateur
@endsection

@section('panel-body')
    {{ Form::open(['route' => 'applicationUser.store', 'class' => 'form-horizontal panel', 'files' => true]) }}

    {!! Form::FileInput('picture', $errors, 'Photo de profil') !!}
    {!! Form::Control('text', $errors, 'firstName', 'Prénom', 'Prénom') !!}
    {!! Form::Control('text', $errors, 'lastName', 'Nom', 'Nom') !!}
    {!! Form::Control('email', $errors, 'email', 'E-mail', 'E-mail') !!}
    {!! Form::Control('tel', $errors, 'tel', 'Téléphone', 'Téléphone') !!}
    {!! Form::Control('date', $errors, 'birthday', 'Date de naissance', 'Date de naissance') !!}
    {!! Form::Control('password', $errors, 'password', 'Mot de passe', 'Mot de passe') !!}
    {!! Form::Control('password', $errors, 'password_confirmation', 'Confirmer le mot de passe', 'Confirmer le mot de passe') !!}

    {{ Form::submit('Envoyer', ['class' => 'btn btn-default pull-right']) }}
    {{ Form::close() }}

@endsection

@section('button-back')
    {!! Html::RouteWithIcon('applicationUser.index', 'Retour', null, 'btn-default', 'circle-arrow-left') !!}
@endsection

@section('script')
    <script type="text/javascript">
        //Button
        $(function() {
            // We can attach the `fileselect` event to all file inputs on the page
            $(document).on('change', ':file', function() {
                var input = $(this),
                    numFiles = input.get(0).files ? input.get(0).files.length : 1,
                    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [numFiles, label]);
            });
            // We can watch for our custom `fileselect` event like this
            $(document).ready( function() {
                $(':file').on('fileselect', function(event, numFiles, label) {

                    var input = $(this).parents('.input-group').find(':text'),
                        log = numFiles > 1 ? numFiles + ' fichiers sélectionnés' : label;

                    if( input.length ) {
                        input.val(log);
                    } else {
                        if( log ) alert(log);
                    }
                });
            });
        });
    </script>
@endsection