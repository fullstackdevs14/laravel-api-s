@extends('emails.layout')
@section('title')
                            <table cellspacing="0" cellpadding="0" class="force-full-width" style="background-color:#088A9B;">
                                <tr>
                                    <td style="background-color:#088A9B;">

                                        <table cellspacing="0" cellpadding="0" class="force-full-width">
                                            <tr>
                                                <td style="font-size:40px; font-weight: 600; color: #ffffff; text-align:center;" class="mobile-spacing">
                                                    <div class="mobile-br">&nbsp;</div>
                                                    Hello {{ $applicationUser->firstName }} !<br>
                                                    Voici ton reçu.
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

                            <table cellspacing="0" cellpadding="0" class="force-full-width" bgcolor="#ffffff" >
                                <tr>
                                    <td style="background-color:#ffffff; padding: 10px">

                                        <span class="bold">Identifiant de commande : </span>{{ $orderInfo->orderId }}

                                        <br><br>

                                        <table class="force-full-width">

                                            <tr>
                                                <th style="text-align: center; border: #088A9B solid 2px; color: white; background: #088A9B; padding: 5px ">Nom</th>
                                                <th style="text-align: center; border: #088A9B solid 2px; color: white; background: #088A9B; padding: 5px ">Quantité</th>
                                                <th style="text-align: center; border: #088A9B solid 2px; color: white; background: #088A9B; padding: 5px ">Prix</th>
                                                <th style="text-align: center; border: #088A9B solid 2px; color: white; background: #088A9B; padding: 5px ">Tax</th>
                                            </tr>
                                            @foreach($orders as $order)
                                                <tr>
                                                    <td style="text-align: center; border: #088A9B solid 2px; padding: 5px">{{ $order->itemName }}</td>
                                                    <td style="text-align: center; border: #088A9B solid 2px; padding: 5px">{{ $order->quantity }}</td>
                                                    @if($orderInfo->HHStatus == 1)
                                                        <td style="text-align: center; border: #088A9B solid 2px; padding: 5px">{{ $order->itemHHPrice }} €</td>
                                                    @else
                                                        <td style="text-align: center; border: #088A9B solid 2px; padding: 5px">{{ $order->itemPrice }} €</td>
                                                    @endif
                                                    <td style="text-align: center; border: #088A9B solid 2px; padding: 5px">{{ $order->tax }} %</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th style="text-align: center; border: #088A9B solid 2px; color: white; background: #088A9B; padding: 5px ">Total</th>
                                                <th style="text-align: center; border: #088A9B solid 2px; color: white; background: #088A9B; padding: 5px "></th>
                                                <th style="text-align: center; border: #088A9B solid 2px; color: white; background: #088A9B; padding: 5px "></th>
                                                <th style="text-align: center; border: #088A9B solid 2px; color: white; background: #088A9B; padding: 5px "> {{ $billAmount }} €</th>
                                            </tr>
                                        </table>

                                        <p style="font-size: medium;font-style: italic; color: blue">Les notes partagées ont un surcoût de 20 centimes.</p>

                                        <table>
                                            <hr>
                                            <span class="bold">Commande en Happy Hour : </span>
                                            @if($orderInfo->HHStatus == 0)
                                                Non
                                            @else
                                                Oui
                                            @endif
                                            <br>
                                            <hr>
                                            <span class="bold">Statut de la commande : </span>
                                                @if($orderInfo->incident == 1)
                                                <span style="color: red;">Incident</span>
                                                @elseif($orderInfo->delivered == 1)
                                                    <span style="color: green;">Délivrée</span>
                                                @elseif($orderInfo->accepted == 1)
                                                    <span style="color: green">Acceptée</span>
                                                @elseif($orderInfo->accepted == 0)
                                                    <span style="color: red">Déclinée (non facturée)</span>
                                                @else
                                                    <span style="color: blue">En attente</span>
                                                @endif
                                                <br>
                                            <hr>
                                            <span class="bold">Date de la commande : </span>{{ $orderInfo->created_at }}
                                                <br>
                                            <hr>
                                            <span class="bold">Nom du bar : </span>{{ $partner->name }}
                                                <br><br>
                                            <span class="bold">Adresse : </span>{{ $partner->address }} - {{ $partner->city }} - {{ $partner->postalCode }}

                                                <br><br><br>
                                        </table>

                                    </td>
                                </tr>
                            </table>

                            <table cellspacing="0" cellpadding="0" width="600" class="force-full-width">
                                <tr>
                                    <td>
                                        <img src="{{$base_url}}img/emails/applicationUser_receipt/sipperUser_thanks_receipt.svg" style="max-width:100%; display:block;">
                                    </td>
                                </tr>
                            </table>
@endsection