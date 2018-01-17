<h1>Partenaire : </h1>
<p>Prénom du responsable : {{ $partner->inChargeFirstName }}</p>
<p>Nom du responsable : {{ $partner->inChargeLastName }}</p>
<p>Email : {{ $partner->email }}</p>
<p>Créé le : {{ $partner->created_at }}</p>
<hr/>
<h1> Sujet : {{ $subject }}</h1>
<p> Message : {{ $body }}</p>
<br/>
<br/>
<br/>
<hr/>
<i>Ne pas mettre ce mail en copie lors de la réponse. Toutes les modifications a éffectuées doivent être validées, puis insèrées dans la console "Admin".</i>
