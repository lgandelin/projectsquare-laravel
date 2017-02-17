Un ticket a été supprimé @if (isset($ticket->last_state->author_user))par {{ $ticket->last_state->author_user->firstName }} {{ $ticket->last_state->author_user->lastName }}@endif :<br/><br/>

<strong>{{ $ticket->title }}</strong><br/><br/>

@if (isset($ticket->project) && isset($ticket->project->client))<strong>Projet :</strong> [{{ $ticket->project->client->name }}] {{ $ticket->project->name }}@endif<br/><br/>

<br/>
L'équipe Projectsquare