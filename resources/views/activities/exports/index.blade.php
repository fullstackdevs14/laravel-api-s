@extends('templates.template_panel')


@section('panel-title')
    Liste des exports
@endsection

@section('panel-body')
    <table class="table">
        <thead>
        <tr>
            <th>Export</th>
            <th>Description</th>
            <th>Type</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <span class="glyphicon glyphicon-download-alt"> </span>
                {{ link_to_route('export.menus', 'Export menus') }}
            </td>
            <td>Export de l'ensemble des items présents dans tous les menus des partenaires.</td>
            <td>.xls</td>
        </tr>
        <tr>
            <td>
                <span class="glyphicon glyphicon-download-alt"> </span>
                {{ link_to_route('export.orders', 'Export orders') }}
            </td>
            <td>Export de l'ensemble des commandes, item par item.</td>
            <td>.xls</td>
        </tr>
        <tr>
            <td>
                <span class="glyphicon glyphicon-download-alt"> </span>
                {{ link_to_route('export.applicationUsers', 'Export application_users') }}
            </td>
            <td>Export de l'ensemble des utilisateurs de l'application.</td>
            <td>.xls</td>
        </tr>
        <tr>
            <td>
                <span class="glyphicon glyphicon-download-alt"> </span>
                {{ link_to_route('export.partners', 'Export partners') }}
            </td>
            <td>Export de l'ensemble des partenaires de l'application.</td>
            <td>.xls</td>
        </tr>
        </tbody>
    </table>
@endsection