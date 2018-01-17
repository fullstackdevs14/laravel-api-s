@extends('emails.layout')
@section('title')
    <table cellspacing="0" cellpadding="0" class="force-full-width"
           style="background-color:#088A9B;">
        <tr>
            <td style="background-color:#088A9B;">

                <table cellspacing="0" cellpadding="0" class="force-full-width">
                    <tr>
                        <td style="font-size:40px; font-weight: 600; color: #ffffff; text-align:center;"
                            class="mobile-spacing">
                            <div class="mobile-br">&nbsp;</div>
                            Versement
                            <br/>
                            {{ $partner->name }}
                            <br/>
                            <br/>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
@endsection
@section('body')
    <table cellspacing="0" cellpadding="0" class="force-full-width" bgcolor="#ffffff">
        <tr>
            <td style="background-color:#ffffff; padding: 10px">

                <br/>
                <br/>

                <table class="force-full-width">
                    <div style="text-align: justify">
                        Bonjour,
                        <br/>
                        <br/>
                        Un remboursement de {{ $amount->Amount/ 100 }} {{ $amount->Currency }} vient d'être éffectué
                        votre compte
                        bancaire.
                        Vous trouverez en pièce jointe un fichier excel avec les commandes
                        {{ \Illuminate\Support\Facades\Config::get('constants.company_name') }} des 30 derniers jours.
                    </div>
                    <br/>
                    Bonne journée !
                    <br/>
                    <br/>
                    L'équipe {{ \Illuminate\Support\Facades\Config::get('constants.company_name') }}.
                    <br/>
                    <br/>
                </table>
            </td>
        </tr>
    </table>
@endsection