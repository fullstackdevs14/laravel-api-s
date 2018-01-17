@extends('templates.template_panel')

@section('search-bar')
    {!! Form::SearchBar('search', ['applicationUser.orders_list', $applicationUser_id], 'Chercher une commande (numéro de commande uniquement)') !!}
@endsection

@section('panel-title')
    Liste des commandes de l'utilisateur
@endsection

@section('panel-body')
    <table class="table">
        <thead>
        <tr>
            <th>N° commande</th>
            <th>Date</th>
            <th>Happy Hour</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders_info as $order)
            <tr>
                <td>{{ $order->orderId }}</td>
                <td>{{ $order->created_at }}</td>
                @if($order->HHStatus === '0')
                    <td>Hors HH</td>
                @else
                    <td>En HH</td>
                @endif
                <td>
                    {!! Html::OrderStatus($order) !!}
                </td>
                <td>
                    {{ link_to_route('order.show', 'Voir', [$order->id], ['class' => 'btn btn-default']) }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@section('links')
    {{ $links }}
@endsection

@section('button-back')
    {!! Html::BackButton() !!}
@endsection
