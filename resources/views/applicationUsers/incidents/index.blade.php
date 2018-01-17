@extends('templates.template_panel')

@section('search-bar')
    {!! Form::SearchBar('search', ['applicationUser_incidents.index', $applicationUser_id], 'Chercher un incident (par numéro de commande...)') !!}
@endsection

@section('panel-title')
    Liste des incidents
@endsection

@section('panel-body')
    @component('templates.template_panel_inside')
        @slot('title')
            Liste des incidents
        @endslot
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Création</th>
                <th>Modification</th>
                <th>N° de commande</th>
                <th>Message</th>
                <th>Traité</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($incidents as $orderId => $incident)
                <tr>
                    <td style="vertical-align: middle">{{ $incident->id }}</td>
                    <td style="vertical-align: middle">{{ $incident->created_at }}</td>
                    <td style="vertical-align: middle">{{ $incident->updated_at }}</td>
                    <td style="vertical-align: middle">{{ $orderId }}</td>
                    <td style="vertical-align: middle">{{ $incident->excuse }}</td>
                    @if($incident->status === '0')
                        <td style="vertical-align: middle; color: blue;">Non</td>
                    @elseif($incident->status === '1')
                        <td style="vertical-align: middle; color: green">Oui</td>
                    @else
                        <td style="vertical-align: middle; color: red">Urgent</td>
                    @endif
                    <td style="vertical-align: middle">{{ link_to_route('applicationUser_incident.show', 'Voir', [$incident->id], ['class' => 'btn btn-default']) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
@endsection

@section('links')
    {{ $links }}
@endsection

@section('button-back')
    {!! Html::RouteWithIcon('applicationUser.show', 'Fiche d\'utilisateur', $applicationUser_id, 'btn-default', 'circle-arrow-left') !!}
@endsection
