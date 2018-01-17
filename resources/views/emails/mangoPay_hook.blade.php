{!! Html::ListInfo('Identifiant de l\'évènement', $request['resourceId']) !!}
{!! Html::ListInfo('Date de l\'évènement', \Carbon\Carbon::createFromTimestamp($request['date'])) !!}
{!! Html::ListInfo('Type d\'évènement', $request['eventType']) !!}

<p><a href="https://dashboard.mangopay.com">https://dashboard.mangopay.com</a></p>

