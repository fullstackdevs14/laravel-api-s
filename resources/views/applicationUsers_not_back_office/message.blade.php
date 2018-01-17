<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>{{ \Illuminate\Support\Facades\Config::get('constants.company_name') }} - message :)</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

    <style>
        body {
            color: #088A9B
        }

        img {
            width: 20%;
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

<div class="container text-center">
    <br>
    <br>
    <br>
    <h1>{{$message}}</h1>

</div>

</body>
</html>
