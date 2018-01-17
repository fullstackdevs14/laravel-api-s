@extends('templates.template_panel')

@section('panel-title')
    Liste des contestations
@endsection


@section('panel-body')

    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Date de création</th>
            <th>Echeance</th>
            <th>Type</th>
            <th>Montant</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($disputes as $dispute)
            <tr>
                <td style="vertical-align: middle">{{ $dispute->Id }}</td>
                <td style="vertical-align: middle">{{ \Carbon\Carbon::createFromTimestamp($dispute->CreationDate) }}</td>
                <td style="vertical-align: middle">{{ \Carbon\Carbon::createFromTimestamp($dispute->ContestDeadlineDate) }}</td>
                <td style="vertical-align: middle">{{ $dispute->DisputeType }}</td>
                <td style="vertical-align: middle">{{ $dispute->ContestedFunds->Amount / 100 }} €</td>
                <td style="vertical-align: middle">{{ link_to_route('mangoPay.disputes.show', 'Traiter', [$dispute->Id], ['class' => 'btn btn-default']) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection



@section('button-back')
    {!! Html::BackButton() !!}
@endsection