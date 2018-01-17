@extends('templates.template_panel')

@section('search-bar')
    {!! Form::SearchBar('search', 'order.index', 'Chercher une commande (numéro de commande uniquement)') !!}
@endsection

@section('panel-title')
    Liste des commandes
@endsection

@section('panel-body')
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>N° de commande</th>
            <th>Date</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($orders as $order)
            <tr>
                <td style="vertical-align: middle">{{ $order->id }}</td>
                <td style="vertical-align: middle">{{ $order->orderId }}</td>
                <td style="vertical-align: middle">{{ $order->created_at }}</td>
                <td style="vertical-align: middle">{!! Html::OrderStatus($order) !!}</td>
                <td style="vertical-align: middle">{{ link_to_route('order.show', 'Voir', [$order->id], ['class' => 'btn btn-default']) }}</td>
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

