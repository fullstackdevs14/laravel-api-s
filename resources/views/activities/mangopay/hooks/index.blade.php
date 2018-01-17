@extends('templates.template_panel')

@section('panel-title')
    Liste des hooks mis en place
@endsection


@section('panel-body')
    {!! Html::RouteWithIcon('mangoPay.hooks.create', 'Création', null, 'btn-default', 'bell') !!}

    <table class="table">
        <thead>
        <tr>
            <th>Identifiant du hook</th>
            <th>Date de création</th>
            <th>Url</th>
            <th>Statut</th>
            <th>Type de d'évènement</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($hooks as $hook)
            <tr>
                <td style="vertical-align: middle">{{ $hook->Id }}</td>
                <td style="vertical-align: middle">{{ \Carbon\Carbon::createFromTimestamp($hook->CreationDate) }}</td>
                <td style="vertical-align: middle">{{ $hook->Url }}</td>
                <td style="vertical-align: middle">{{ $hook->Status }}</td>
                <td style="vertical-align: middle">{{ $hook->EventType }}</td>
                <td style="vertical-align: middle">
                    @if($hook->Status == "ENABLED")
                        {{ link_to_route('mangoPay.hooks.disable', 'Désactiver', [$hook->Id], ['class' => 'btn btn-warning']) }}
                    @else
                        {{ link_to_route('mangoPay.hooks.enable', 'Activer', [$hook->Id], ['class' => 'btn btn-primary']) }}
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection

@section('button-back')
    {!! Html::BackButton() !!}
@endsection
