@extends('emails.layout')

@section('title')
    <table cellspacing="0" cellpadding="0" class="force-full-width" style="background-color:#088A9B;">
        <tr>
            <td style="background-color:#088A9B;">

                <table cellspacing="0" cellpadding="0" class="force-full-width">
                    <tr>
                        <td style="font-size:40px; font-weight: 600; color: #ffffff; text-align:center;"
                            class="mobile-spacing">
                            <div class="mobile-br">&nbsp;</div>
                            Hello {{ $applicationUser->firstName }} !<br>
                            Voici la / les facture(s) demandée(s).
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
    <table cellspacing="0" cellpadding="0" class="force-full-width" bgcolor="#ffffff">
        <tr>
            <td style="background-color:#ffffff; padding: 10px">
                <br/>
                <p>Vous trouverez ci-joint les factures concernant la commande n° {{ $orderInfo->orderId }}.</p>
                <br/>
                <p>L'équipe {{ \Illuminate\Support\Facades\Config::get('constants.company_name') }}.</p>
            </td>
        </tr>
    </table>

    <table cellspacing="0" cellpadding="0" width="600" class="force-full-width">
        <tr>
            <td>
                <img src="{{ \App\Handlers\ToolsHandler::getBaseUrl() }}img/emails/applicationUser_receipt/sipperUser_thanks_receipt.svg"
                     style="max-width:100%; display:block;">
            </td>
        </tr>
    </table>
@endsection