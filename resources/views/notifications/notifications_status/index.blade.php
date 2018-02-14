@extends('templates.template_panel')

@section('search-bar')
    {!!  Form::SearchBar('search', 'list_notifications_status.index', 'Chercher...') !!}
@endsection

@section('panel-title')
    Liste des utilisateurs :
@endsection

@section('panel-body')

    <table class="table">
        <thead>
        <tr>
            <th class="text-left">Création</th>
            <th class="text-center">Prenom</th>
            <th class="text-center">Nom</th>
            <th class="text-center">Partenaire</th>
            <th class="text-center">Numéro de commande</th>
            <th class="text-center">Statut</th>
            <th class="text-right">Type</th>
        </tr>
        </thead>
        <tbody>
        @foreach($notifications as $notification)
            <?php $applicationUser = \App\ApplicationUser::findOrFail($notification->applicationUser_id) ?>
            <?php $partner = \App\Partner::findOrFail($notification->partner_id) ?>
            <?php $orderInfo = \App\OrderInfo::findOrFail($notification->order_id) ?>

            <tr>
                <td class="text-left">{{ $notification->created_at }}</td>
                <td class="text-center">{{ $applicationUser->firstName }}</td>
                <td class="text-center">{{ $applicationUser->lastName }}</td>
                <td class="text-center">{{ $partner->name }}</td>
                <td class="text-center">{{ $orderInfo->orderId }}</td>
                @if($notification->notification_status == true)
                    <td class="text-center" style="color: green">SUCCESS</td>
                @else
                    <td class="text-center" style="color: red">ERROR</td>
                @endif
                <td class="text-right">{{ $notification->type }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection

@section('links')
    {{ $links }}
@endsection