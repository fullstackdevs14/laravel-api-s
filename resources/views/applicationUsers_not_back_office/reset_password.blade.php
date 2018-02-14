<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>Renouvellement du mot de passe</title>
    {{ Html::favicon( 'img/favicon.ico' ) }}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
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

</head>
<body>

<div class="container text-center">
    <a href="{{ \Illuminate\Support\Facades\Config::get('constants.company_website') }}"><img src="{{$base_url}}img/Sipper-logo.svg" alt="company logo"></a>
</div>

<div class="container">

    <h1>Changer mon mot de passe</h1>


    {{ Form::open(['route' => 'applicationUser.renewPassword', 'class' => 'form-horizontal panel']) }}

    <input type="hidden" name="token" value="{{$token}}">

    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <label class="control-label">Mot de passe : </label>

        <input type="password" class="form-control" name="password">

        @if ($errors->has('password'))
            <span class="help-block">
                                        <strong style="color: red">{{ $errors->first('password') }}</strong>
                                    </span>
        @endif
    </div>

    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
        <label class="control-label">Confirmation du mot de passe : </label>

        <input type="password" class="form-control" name="password_confirmation">

        @if ($errors->has('password_confirmation'))
            <span class="help-block">
                                        <strong style="color: red">{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
        @endif
    </div>

    <button id="submit" class="btn btn-lg btn-block">Changer</button>
    {{ Form::close() }}

</div>

</body>
</html>
