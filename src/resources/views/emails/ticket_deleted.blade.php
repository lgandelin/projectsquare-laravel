Un ticket a été supprimé :<br/><br/><br/>

<strong>Titre :</strong> {{ $ticket->title }}<br/><br/>
@if (isset($ticket->project) && isset($ticket->project->client))<strong>Projet :</strong> [{{ $ticket->project->client->name }}] {{ $ticket->project->name }}@endif<br/><br/>

<br/>
L'équipe Projectsquare