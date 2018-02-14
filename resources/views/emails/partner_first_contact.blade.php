<h1>Une demande de partenariat a été faite. Voici le détail de cette demande :</h1><br/>

<h2>Type d'inscription : </h2>
<strong>Demande permanente : </strong>@if($request->input('permanent') == 0)Non @else Oui @endif<br/>
<strong>Date de début : </strong>{{ $request->input('start_date') }}<br/>
<strong>Date de fin : </strong>{{ $request->input('end_date') }}<br/>

<h2>Informations représentant légal : </h2>
<strong>Nom du propriétaire : </strong>{{ $request->input('ownerFirstName') }}<br/>
<strong>Prénom du propriétaire : </strong>{{ $request->input('ownerLastName') }}<br/>
<strong>Nationalité : </strong>{{ $request->input('LegalRepresentativeNationality') }}<br/>
<strong>Pays de résidence : </strong>{{ $request->input('LegalRepresentativeCountryOfResidence') }}<br/>
<strong>Email : </strong>{{ $request->input('LegalRepresentativeEmail') }}<br/>
<strong>Date de naissance : </strong>{{ $request->input('birthday') }}<br/>
<strong>Adresse du responsable : </strong>{{ $request->input('gaddress_representative') }}<br/>

<h2>Adresse du siège : </h2>
<strong>Adresse du siège : </strong>{{ $request->input('gaddress_hq') }}<br/>

<h2>Informations du bar / lieu : </h2>
<strong>Nom du bar, de l'entreprise ou de l'association : </strong>{{ $request->input('name') }}<br/>
<strong>Catégorie du bar : </strong>{{ ucfirst($request->input('category')) }}<br/>
<strong>Téléphone : </strong>{{ $request->input('tel') }}<br/>
<strong>Email : </strong>{{ $request->input('email') }}<br/>
<strong>Adresse : </strong>{{ $request->input('gaddress') }}<br/>

<h2>Communications : </h2>
<strong>Site internet : </strong>{{ $request->input('website') }}<br/>
<strong>Présence d'une photo : </strong>{{ $request->input('picture') }}<br/>

<h2>Documents nécéssaires pour le process kyc : </h2>
<strong>Présence d'une preuve d'identité : </strong>{{ $request->input('identity_proof') }}<br/>
<strong>Présence des status : </strong>{{ $request->input('articles_of_association') }}<br/>
<strong>Présence d'un extrait de K-bis : </strong>{{ $request->input('registration_proof') }}<br/>
<strong>Présence d'une preuve d'adresse : </strong>{{ $request->input('address_proof') }}<br/>
<strong>Présence de d'une déclaration d'actionnaire : </strong>{{ $request->input('shareholder_declaration') }}

<br/>
<br/>
<br/>