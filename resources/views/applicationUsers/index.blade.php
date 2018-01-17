@extends('templates.template_panel')

@section('search-bar')
    {!!  Form::SearchBar('search', 'applicationUser.index', 'Chercher un utilisateur') !!}
@endsection

@section('panel-title')
    Liste des utilisateurs :
@endsection

@section('panel-body')

    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Avatar</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Actif</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($applicationUsers as $user)
            <tr>
                <td style="vertical-align: middle">{{ $user->id }}</td>
                <td style="vertical-align: middle">
                    @if($user->photoPath != null)
                        <div class="avatar">
                            <img src="{{ $user->photoPath }}" />
                        </div>
                    @else
                        <span class="glyphicon glyphicon-user"></span>
                    @endif
                </td>
                <td style="vertical-align: middle" class="text-default">{{ $user->firstName }}</td>
                <td style="vertical-align: middle" class="text-default">{{ $user->lastName }}</td>
                <td style="vertical-align: middle" class="text-default">{{ $user->email }}</td>
                <td style="vertical-align: middle" class="text-default">
                    @if($user->activated)
                        Actif
                    @else
                        Inactif
                    @endif
                </td>
                <td style="vertical-align: middle">{{ link_to_route('applicationUser.show', 'Voir', [$user->id], ['class' => 'btn btn-default']) }}</td>
                <td style="vertical-align: middle">{{ link_to_route('applicationUser.edit', 'Modifier', [$user->id], ['class' => 'btn btn-default']) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection

@section('links')
    {{ $links }}
@endsection