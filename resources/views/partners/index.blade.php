@extends('templates.template_panel')

@section('search-bar')
    {!! Form::SearchBar('search', 'partner.index', 'Chercher un partenaire') !!}
@endsection

@section('panel-title')
    Liste des partenaires
@endsection

@section('panel-body')
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Ville</th>
            <th>Code postal</th>
            <th>Catégorie</th>
            <th>Actif</th>
            <th>Fiche partenaire</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($partners as $partner)
            <tr>
                <td style="vertical-align: middle">{{ $partner->id }}</td>
                <td style="vertical-align: middle" class="text-default">{{ $partner->name }}</td>
                <td style="vertical-align: middle" class="text-default">{{ $partner->city }}</td>
                <td style="vertical-align: middle" class="text-default">{{ $partner->postalCode }}</td>
                <td style="vertical-align: middle" class="text-default">{{ $partner->category }}</td>
                <td style="vertical-align: middle" class="text-default">
                    @if($partner->activated)
                        Actif
                    @else
                        Inactif
                    @endif
                </td>
                <td style="vertical-align: middle">{{ link_to_route('partner.edit', 'Modifier / Voir', [$partner->id], ['class' => 'btn btn-default']) }}</td>
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