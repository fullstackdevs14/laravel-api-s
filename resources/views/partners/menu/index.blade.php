@extends('templates.template_panel')

@section('panel-title')
    Modification du menu
@endsection

@section('panel-body')

    {!! Html::RouteWithIcon('item.create', 'Ajouter une boisson', $partner->id, 'btn-default', 'glass') !!}
    <br/>
    <br/>
    @foreach($menu as $category => $items)

        @component('templates.template_panel_inside')
            @slot('title')
                {{ $category }}
            @endslot
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Prix HH</th>
                    <th>Disponibilité</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td style="vertical-align: middle">{{ $item->id }}</td>
                        <td style="vertical-align: middle">{{ $item->name }}</td>
                        <td style="vertical-align: middle">{{ $item->price }} €</td>
                        <td style="vertical-align: middle">{{ $item->HHPrice }} €</td>
                        <td style="vertical-align: middle">{{ $item->availability }}</td>
                        <td style="vertical-align: middle">
                            {{ Form::open(['method' => 'DELETE', 'route' => ['item.destroy', $partner->id, $item->id]]) }}
                            {{ Form::submit('Supprimer', ['class' => 'btn btn-danger', 'onclick' => 'return confirm(\'Voulez-vous vraiment supprimer cette boisson ?\')']) }}
                            {{ Form::close() }}
                        </td>
                        <td style="vertical-align: middle">{{ link_to_route('item.edit', 'Modifier', [$partner->id, $item->id], ['class' => 'btn btn-default']) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endcomponent
    @endforeach

@endsection
@section('button-back')
    {!! Html::RouteWithIcon('partner.edit', 'Partenaire',  $partner->id, 'btn-default', 'circle-arrow-left') !!}
@endsection

