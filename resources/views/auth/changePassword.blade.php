@extends('layouts.app')

@section('content')
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">Changer de mot de passe</div>
            <div class="panel-body">

                <form class="form-horizontal" role="form" method="POST" action="{{ route('changePassword') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ Session::has('message') ? ' has-error' : '' }}">
                        <label for="check_password" class="col-md-4 control-label">Ancien Mot de passe</label>

                        <div class="col-md-6">
                            <input id="check_password" type="password" class="form-control" name="check_password" required>

                            @if (Session::has('message'))
                                <span class="help-block">
                                        <strong>{{ Session::get('message') }}</strong>
                                    </span>
                            @endif

                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="col-md-4 control-label">Nouveau mot de passe</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control" name="password" required>

                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password-confirm" class="col-md-4 control-label">Confirmer le mot de passe</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-warning">
                                Changer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
