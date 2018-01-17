<h1>Utilisateur : </h1>
<p>Prénom : {{ $applicationUser->firstName }}</p>
<p>Nom : {{ $applicationUser->lastName }}</p>
<p>Email : {{ $applicationUser->email }}</p>
<p>Créé le : {{ $applicationUser->created_at }}</p>
<hr/>
<h1> Sujet : {{ $subject }}</h1>
<p> Message : {{ $body }}</p>
<br/>
<br/>
<br/>
<hr/>
<i>Ne pas mettre ce mail en copie lors de la réponse. Attention à bien suivre la procédure (ouverture d'un ticket).</i>
