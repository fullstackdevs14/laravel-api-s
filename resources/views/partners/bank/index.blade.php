@extends('templates.template_panel')

@section('panel-title')
    Liste des comptes enregistrés
@endsection

@section('panel-body')
    {!! Html::RouteWithIcon('bank_account.create', 'Créer un compte', $partner->id, 'btn-default', 'euro') !!}
    <br />
    <br />
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Création</th>
            <th>Type</th>
            <th>IBAN</th>
            <th>Actif</th>
            <th>Utilisé</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($bankAccounts as $bankAccount)
            <tr>
                <td style="vertical-align: middle">{{ $bankAccount->Id }}</td>
                <td style="vertical-align: middle">{{ $bankAccount->CreationDate }}</td>
                <td style="vertical-align: middle">{{ $bankAccount->Type }}</td>
                <td style="vertical-align: middle">{{ $bankAccount->Details->IBAN }}</td>
                <td style="vertical-align: middle">
                    @if($bankAccount->Active == 1)
                        <span style="color: green" class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    @else
                        <span style="color: red" class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                    @endif
                </td>
                <td style="vertical-align: middle">
                    @if($bankAccount->Id == $partner->mango_bank_id AND $bankAccount->Active == true)
                        <span style="color: green" class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    @else
                        <span style="color: red" class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                    @endif
                </td>
                <td style="vertical-align: middle">{{ link_to_route('bank_account.show', 'Modifier / voir', [$partner->id, $bankAccount->Id], ['class' => 'btn btn-default']) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection

@section('button-back')
    {!! Html::RouteWithIcon('partner.edit', 'Partenaire', $partner->id, 'btn-default', 'circle-arrow-left') !!}
@endsection

@section('script')

@endsection