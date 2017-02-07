Un ticket a été supprimé @if (isset($author_user))par {{ $author_user->firstName }} {{ $author_user->lastName }}@endif :<br/><br/>

<strong>{{ $ticket->title }}</strong><br/><br/>

@if (isset($project) && isset($project->client))<strong>Projet :</strong> [{{ $project->client->name }}] {{ $project->name }}@endif<br/><br/>

<br/>
L'équipe Projectsquare