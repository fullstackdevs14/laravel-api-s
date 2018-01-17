@extends('templates.template_panel')

@section('panel-title')
    Modification d'un utilisateur
@endsection

@section('panel-body')
    {{ Form::model($applicationUser, ['route' => ['applicationUser.update', $applicationUser->id], 'method' => 'put', 'class' => 'form-horizontal panel', 'files' => true]) }}

    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            @if($applicationUser->picture != null)
                <div class="avatar-circle">
                    <img style="width: 100px; height: 100px; -webkit-border-radius: 50%; -moz-border-radius: 50%; border-radius: 50%;" src="{{ asset('uploads/application_users_img/' . $applicationUser->picture) }}" />
                </div>
            @else
                <span class="glyphicon glyphicon-user"><i>   -   Pas de photo de profil</i></span>
            @endif
            <br />
            <br />
            {!! Html::ListInfo('Dernière modification', $applicationUser->updated_at) !!}
            {!! Html::ListInfo('Création', $applicationUser->created_at) !!}
            <br />
        </div>
    </div>

    {!! Form::FileInput('picture', $errors, 'Photo de profil') !!}


    {!! Form::Control('text', $errors, 'firstName', 'Prénom', 'Prénom') !!}
    {!! Form::Control('text', $errors, 'lastName', 'Nom', 'Nom') !!}
    {!! Form::Control('email', $errors, 'email', 'E-mail', 'E-mail') !!}
    {!! Form::Control('tel', $errors, 'tel', 'Téléphone', 'Téléphone') !!}
    {!! Form::Control('date', $errors, 'birthday', 'Date de naissance', 'Date de naissance') !!}

    {!! Form::Select2ChoicesSelected('activated', $errors, $applicationUser, 'Actif', 'Inactif', 'Utilisateur actif / inactif') !!}

    {{ Form::submit('Envoyer', ['class' => 'btn btn-default pull-right']) }}

    {{ Form::close() }}

    {{ Form::open(['method' => 'DELETE', 'route' => ['applicationUser.destroy', $applicationUser->id]]) }}
    {{ Form::submit('Supprimer l\'utilisateur', ['class' => 'btn btn-danger', 'onclick' => 'return confirm(\'Voulez-vous vraiment supprimer cet utilisateur ?\')']) }}
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