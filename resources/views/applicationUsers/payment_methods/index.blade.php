@extends('templates.template_panel')

@section('panel-title')
    Liste des moyens de paiement
@endsection

@section('panel-body')
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Numéro</th>
            <th>Exp</th>
            <th>Type</th>
            <th>Fournisseur</th>
            <th>Validité</th>
            <th>Active</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($cards as $card)
            <tr>
                <td style="vertical-align: middle">{{ $card->Id }}</td>
                <td style="vertical-align: middle">{{ $card->Alias }}</td>
                <td style="vertical-align: middle">{{ $card->ExpirationDate }}</td>
                <td style="vertical-align: middle">{{ $card->CardType }}</td>
                <td style="vertical-align: middle">{{ $card->CardProvider }}</td>
                <td style="vertical-align: middle">{{ $card->Validity }}</td>
                <td style="vertical-align: middle">{{ $card->Active }}</td>
                <td style="vertical-align: middle">{{ link_to_route('cards.show', 'Voir', [$card->Id, $applicationUser_id], ['class' => 'btn btn-default']) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@section('button-back')
    {!!  Html::BackButton() !!}
@endsection