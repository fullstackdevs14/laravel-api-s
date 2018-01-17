@extends('templates.template_panel')

@section('panel-title')
    Liste des documents
@endsection

@section('panel-body')
    {!! Html::RouteWithIconBlank('kyc.create', 'Créer un document (besoin d\'un accès à Mangopay)', $partner->id, 'btn-default', 'file') !!}
    {!! Html::RouteWithIconBlank('kyc.downloadShareholderDeclaration', 'Télécharger la déclaration d\'actionnaire', null, 'btn-default', 'file') !!}
    <br>
    <br>
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Date de création</th>
            <th>Type</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
       @foreach($kycDocs as $doc)
            <tr>
                <td style="vertical-align: middle">{{ $doc->Id }}</td>
                <td style="vertical-align: middle">{{ Carbon\Carbon::createFromTimestamp($doc->CreationDate) }}</td>
                <td style="vertical-align: middle">{{ $doc->Type }}</td>
                <td style="vertical-align: middle">{{ $doc->Status }}</td>
                <td style="vertical-align: middle">{{ link_to_route('kyc.show', 'Voir', [$partner->id, $doc->Id], ['class' => 'btn btn-default']) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@section('button-back')
    {!! Html::RouteWithIcon('partner.edit', 'Partenaire', $partner->id, 'btn-default', 'circle-arrow-left') !!}
@endsection