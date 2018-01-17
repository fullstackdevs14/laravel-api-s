@extends('templates.template_panel')

@section('panel-title')
    Création d'une notification ciblée
@endsection

@section('panel-body')
    @component('templates.template_panel_inside')
        @slot('title')
            Envoyer une notification à un utilisateur en cherchant son email :
        @endslot
        {{ Form::open(['route' => 'targeted_user.notification.send', 'class' => 'form-horizontal panel']) }}

        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="form-group {!! $errors->has('email') ? 'has-error' : '' !!}">
                    <label for="email">Choisir un utilisateur (par son email) : </label>
                    <select name="email" class="form-control form-control-lg selectpicker" multiple data-live-search="true" title="Choisir un email" data-max-options="1">
                        @foreach($emails as $email)
                            <option value="{{ $email }}">{{ $email }}</option>
                        @endforeach
                    </select>
                    {{ $errors->first('email', '<small class="help-block">:message</small>') }}
                </div>
            </div>
        </div>

        {!! Form::Control('text', $errors, 'title', 'Titre de la notification', 'Titre de la notification') !!}
        {!! Form::Control('text', $errors, 'body', 'Corps de la notification', 'Corps de la notification') !!}

        {{ Form::submit('Envoyer', ['class' => 'btn btn-default pull-right']) }}
        {{ Form::close() }}
    @endcomponent

@endsection

@section('button-back')
    {!! Html::BackButton() !!}
@endsection

@section('script')
    <script>
        $(function() {
            $('.selectpicker').selectpicker({
                size: 1
            });
        });
    </script>
@endsection