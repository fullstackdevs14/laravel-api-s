@extends('emails.layout')
@section('title')
<table cellspacing="0" cellpadding="0" class="force-full-width">
    <tr>
        <td style="background-color:#088A9B;">

            <table cellspacing="0" cellpadding="0" class="force-full-width">
                <tr>
                    <td style="font-size:40px; font-weight: 600; color: #ffffff; text-align:center;"
                        class="mobile-spacing">
                        <div class="mobile-br">&nbsp;</div>
                        Hello {{ $applicationUser->firstName }} !<br>
                        Merci de confirmer ta demande de changement d'email.
                        <br>
                        <br>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
@endsection
@section('body')
<table cellspacing="0" cellpadding="0" class="force-full-width">
    <tr>
        <td style="background-color:#FFFFFF; text-align: center">
            <p>Si vous n'avez pas demandé à recevoir cet email, merci de nous le signaler à
                l'adresse suivante : <a style="color: blue"
                                        href="mailto:{{ \Illuminate\Support\Facades\Config::get('constants.mail_main') }}">{{ \Illuminate\Support\Facades\Config::get('constants.mail_main') }}</a>
            </p>

            <p><a style="color: blue; font-size: larger"
                  href="{{$base_url}}api/applicationUserNewEmailConfirmation/{{$token}}">Confirmer mon nouvel email.</a>
            </p>

            <p>Cette demande de modification est valable deux heures. Elle sera
                automatiquement détruite une fois ce délai passé. Vous pouvez effectuer une
                nouvelle demande quand vous le souhaitez.</p>
        </td>
    </tr>
</table>
@endsection
