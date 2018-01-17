@extends('templates.template_panel')

@section('panel-title')
    Liste des 100 deniers évènements
@endsection


@section('panel-body')

    <table class="table">
        <thead>
        <tr>
            <th>Identifiant</th>
            <th>Date de creation</th>
            <th>Type d'évènement</th>
        </tr>
        </thead>
        <tbody>
        @foreach($events as $event)
            <tr>
                <td style="vertical-align: middle">{{ $event->ResourceId }}</td>
                <td style="vertical-align: middle">{{ \Carbon\Carbon::createFromTimestamp($event->Date) }}</td>
                <td style="vertical-align: middle">{{ $event->EventType }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection

@section('button-back')
    {!! Html::BackButton() !!}
@endsection

@section('links')
    {{ $links }}
@endsection