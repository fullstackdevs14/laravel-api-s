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
                            Facture
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
                        Vous trouverez ci join la facture du mois dernier.
                    </div>
                    <br/>
                    Encore merci pour votre collaboration et bonne journée !
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